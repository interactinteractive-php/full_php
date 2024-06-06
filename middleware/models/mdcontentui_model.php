<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdcontentui_model extends Model {

    private static $GfServiceAddress = GF_SERVICE_ADDRESS;
    public static $uploadedPath = 'ecm_content/';
    public static $excelImportUploadedPath = 'file_svn/excel_template/';
    public static $contentHtmlFilePathSvn = 'storage/uploads/file_svn/content_default/';

    public function __construct() {
        parent::__construct();
    }

    // <editor-fold defaultstate="collapsed" desc="CONTENT">
    /**
     * Content төрөлтэй Meta data - нууд
     * @return type
     */
    public function findMetaData() {
        $result = $this->db->GetAll("SELECT META_DATA_ID, META_DATA_NAME FROM META_DATA WHERE META_TYPE_ID = 200101010000023");

        return $result;
    }

    /**
     * Layout хадгалах
     * @param type $code
     * @param type $name
     * @param type $rowCount
     * @param type $colCount
     * @param type $cellArray
     * @return boolean
     */
    public function createLayout($code, $name, $rowCount, $colCount, $bgColor, $borderWidth, $bgImage, $cellArray) {

        //Layout code давхцаж байгаа эсэхийг шалгах
        if ($this->checkLayoutCode($code, 0)) {
            return array('errorMessage' => 'Код давхцаж байна.');
        }

        try {
            $id = getUniqId();
            $data = array(
                'LAYOUT_ID' => $id,
                'LAYOUT_CODE' => $code,
                'LAYOUT_NAME' => $name,
                'ROW_COUNT' => $rowCount,
                'COL_COUNT' => $colCount,
                'BG_COLOR' => $bgColor,
                'BORDER_WIDTH' => $borderWidth,
                'BG_IMAGE' => $bgImage
            );
            // insert to layout
            $this->db->AutoExecute('META_CONTENT_LAYOUT', $data, 'INSERT');
            // insert to layout cell
            self::createCell($id, $cellArray);
            $qry = "SELECT CELL_ID FROM META_CONTENT_LAYOUT_CELL WHERE LAYOUT_ID = $id";
            $result = $this->db->GetOne($qry);
            if ($result) {
                return array('status' => 'success', 'id' => $id, 'message' => 'Амжилттай хадгаллаа');
            } else {
                $qry = "DELETE FROM META_CONTENT_LAYOUT WHERE LAYOUT_ID = $id";
                $this->db->Execute($qry);
                return array('status' => 'error', 'message' => 'Амжилтгүй боллоо');
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Content layout update
     * @param type $layoutId
     * @param type $code
     * @param type $name
     * @param type $bgColor
     * @param type $borderWidth
     * @param type $bgImage
     * @param type $cellArray
     * @return boolean
     */
    public function updateLayout($layoutId, $code, $name, $bgColor, $borderWidth, $bgImage, $cellArray) {
        //Layout code давхцаж байгаа эсэхийг шалгах
        if ($this->checkLayoutCode($code, $layoutId)) {
            return array('errorMessage' => 'Код давхцаж байна.');
        }

        try {
            $data = array(
                'LAYOUT_CODE' => $code,
                'LAYOUT_NAME' => $name,
                'BG_COLOR' => $bgColor,
                'BORDER_WIDTH' => $borderWidth,
                'BG_IMAGE' => $bgImage
            );
            // update layout
            $this->db->AutoExecute('META_CONTENT_LAYOUT', $data, 'UPDATE', 'LAYOUT_ID = ' . $layoutId);
            // upadte layout cell
            $this->updateCell($layoutId, $cellArray);
            $qry = "SELECT CELL_ID FROM META_CONTENT_LAYOUT_CELL WHERE LAYOUT_ID = $layoutId";
            $result = $this->db->GetOne($qry);
            if ($result) {
                return array('status' => 'success', 'id' => $layoutId, 'message' => 'Амжилттай хадгаллаа');
            } else {
                return array('status' => 'error', 'message' => 'Амжилтгүй боллоо');
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Нүднүүдийг хадгалах
     * @param type $layoutId
     * @param type $cellArray
     */
    public function createCell($layoutId, $cellArray) {
        $counter = 1;
        $mergeArray = array();
        $lastMergeId = null;
        try {
            // Мөр болгоноор давтах
            foreach ($cellArray AS $rowIndex => $row) {

                // get row id
                $tmpRowId = substr($rowIndex, 1, strlen($rowIndex));
                $rowIdPlus = $tmpRowId + 1; // Javascript дээр array index 0 ээс эхэлж байгаа
                // save row
                $height = null;
                if (isset($row['height'])) {
                    $height = number_format($row['height'][0], 4);
                }
                $rowId = $this->saveRow($rowIdPlus, $layoutId, $height);

                // Нүд болгоноор давтах
                foreach ($row AS $cellIndex => $cell) {

                    // get cell id
                    $tmpColId = substr($cellIndex, 1, strlen($cellIndex));
                    $colId = $tmpColId + 1; // Javascript дээр array index 0 ээс эхэлж байгаа
                    $is_merge = isset($cell['is_merge']) ? $cell['is_merge'] : 0;
                    $isUse = isset($cell['is_use']) ? $cell['is_use'] : 0;
                    $width = isset($cell['width']) ? number_format($cell['width'], 4) : 0;
                    $heightCell = isset($cell['height']) ? number_format($cell['height'], 4) : 0;
                    $borderTop = isset($cell['border-top']) ? $cell['border-top'] : 0;
                    $borderLeft = isset($cell['border-left']) ? $cell['border-left'] : 0;
                    $borderBottom = isset($cell['border-bottom']) ? $cell['border-bottom'] : 0;
                    $borderRight = isset($cell['border-right']) ? $cell['border-right'] : 0;
                    $borderColor = isset($cell['border-color']) ? $cell['border-color'] : null;
                    $bgColor = isset($cell['background-color']) ? $cell['background-color'] : null;
                    $caption = isset($cell['caption']) ? $cell['caption'] : null;

                    if (substr($cellIndex, 0, 1) != 'h') { // Өндөрөөс бусад үед хадгална
                        // save col
                        if ($rowId == 1) { // Эхний мөрийн хувьд 
//            if (!is_null($width)) {
                            $colId = $this->saveCol($colId, $layoutId, $width, $rowId);
                        }

                        $cellId = self::saveLayoutCell($layoutId, $rowId, $colId, $is_merge, $isUse, $borderTop, $borderLeft, $borderBottom,
                                        $borderRight, $caption, $width, $heightCell, $bgColor, $borderColor);

                        $cellArray[$rowIndex][$cellIndex]['cellId'] = $cellId;

                        if (isset($cell['is_merge']) && $cell['is_merge'] == 1) {
                            if (isset($cell['startMerge'])) {
                                // insert to layout
                                $mergeId = self::saveStartMerge($layoutId, $cellId);
                                $cellArray[$rowIndex][$cellIndex]['mergeId'] = $mergeId;
                                $mergeArray[$cell['tmpMergeId']] = $mergeId;
                            } else {
                                if (isset($mergeArray[$cell['tmpMergeId']])) {
                                    $cellArray[$rowIndex][$cellIndex]['mergeId'] = $mergeArray[$cell['tmpMergeId']];
                                }
                            }
                        }

                        $counter++;
                    }
                }
            }

            // Бүх merge хийгдсэн cell үүдийг цуглуулах
            return self::collectMergeCells($cellArray, $layoutId, $mergeArray);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Нүднүүдийг засварлана
     * @param type $layoutId
     * @param type $cellArray
     * @param type $meta_data_id
     * @return boolean
     */
    public function updateCell($layoutId, $cellArray, $meta_data_id = null) {
        $this->clearContentDatas($layoutId, $meta_data_id);
        try {
            $mergeArray = array();
            // Мөр болгоноор давтах
            foreach ($cellArray AS $rowIndex => $row) {

                // get row id
                $tmpRowId = substr($rowIndex, 1, strlen($rowIndex));
                $rowIdPlus = $tmpRowId + 1; // Javascript дээр array index 0 ээс эхэлж байгаа
                // save row
                $height = null;
                if (isset($row['height'])) {
                    $height = number_format($row['height'][0], 4);
                }

                $rowId = $this->saveRow($rowIdPlus, $layoutId, $height);

                // Нүд болгоноор давтах
                foreach ($row AS $cellIndex => $cell) {
                    // get cell id
                    $tmpColId = substr($cellIndex, 1, strlen($cellIndex));
                    $colId = $tmpColId + 1; // Javascript дээр array index 0 ээс эхэлж байгаа

                    $isUse = isset($cell['is_use']) ? $cell['is_use'] : 0;
//                    if ($isUse == '1') {
                    $is_merge = isset($cell['is_merge']) ? $cell['is_merge'] : 0;
                    $width = isset($cell['width']) ? number_format(str_replace("%", "", $cell['width']), 4) : null;
                    $heightCell = isset($cell['height']) ? number_format(str_replace("px", "", $cell['height']), 4) : null;
                    $borderTop = isset($cell['border-top']) ? $cell['border-top'] : 0;
                    $borderLeft = isset($cell['border-left']) ? $cell['border-left'] : 0;
                    $borderBottom = isset($cell['border-bottom']) ? $cell['border-bottom'] : 0;
                    $borderRight = isset($cell['border-right']) ? $cell['border-right'] : 0;
                    $borderColor = isset($cell['border-color']) ? $cell['border-color'] : null;
                    $bgColor = isset($cell['background-color']) ? $cell['background-color'] : null;
                    $caption = isset($cell['caption']) ? $cell['caption'] : null;

                    if (substr($cellIndex, 0, 1) != 'h') { // Өндөрөөс бусад үед хадгална
                        $cellId = $cell['cell_id'];

                        if ($rowId == 1) { // Эхний мөрийн хувьд 
                            $colId = $this->saveCol($colId, $layoutId, $width, $rowId);
                        }

                        if (substr($width, 0, 1) != '%') {
                            $this->updateLayoutCell($cellId, $is_merge, $isUse, $width, $heightCell, $borderTop, $borderLeft, $borderBottom,
                                    $borderRight, $borderColor, $bgColor, $caption);
                        }

                        $cellArray[$rowIndex][$cellIndex]['cellId'] = $cellId;

                        if ($is_merge == '1') {
                            if (isset($cell['startMerge'])) {
                                $mergeId = $this->saveStartMerge($layoutId, $cellId);
                                $cellArray[$rowIndex][$cellIndex]['mergeId'] = $mergeId;
                                $mergeArray[$cell['tmpMergeId']] = $mergeId;
                            } else {
                                if (isset($mergeArray[isset($cell['tmpMergeId'])])) {
                                    $cellArray[$rowIndex][$cellIndex]['mergeId'] = $mergeArray[$cell['tmpMergeId']];
                                }
                            }
                        }

                        if (!is_null($meta_data_id) && isset($cell['meta_data_id'])) {
                            $this->saveContentMap($cell['meta_data_id'], $cellId, $layoutId, $meta_data_id);
                        }
                    }
//                    }
                }
            }

            // Бүх merge хийгдсэн cell үүдийг цуглуулах
            return $this->collectMergeCells($cellArray, $layoutId);
        } catch (Exception $e) {
            return false;
        }
    }

    private function saveContentMap($metaDataId, $cellId, $layoutId, $srcMetaDataId) {
        $dataContentMap = array(
            'MAP_ID' => getUniqId(),
            'META_DATA_ID' => $metaDataId,
            'CELL_ID' => $cellId,
            'LAYOUT_ID' => $layoutId,
            'SRC_META_DATA_ID' => $srcMetaDataId
        );
        $this->db->AutoExecute('META_CONTENT_MAP', $dataContentMap);
    }

    /**
     * Layout code давхцаж байгаа эсэхийг шалгах
     * @param type $code
     * @param type $id 
     * @return boolean
     */
    private function checkLayoutCode($code, $id) {
        $result = $this->db->GetOne("SELECT "
                . "LAYOUT_CODE "
                . "FROM META_CONTENT_LAYOUT "
                . "WHERE LAYOUT_CODE = '$code' AND LAYOUT_ID != '$id'");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * save layout cell
     * @param type $layoutId
     * @param type $rowId
     * @param type $colId
     * @param type $is_merge
     * @param type $isUse
     */
    private function saveLayoutCell($layoutId, $rowId, $colId, $is_merge, $isUse, $borderTop, $borderLeft, $borderBottom, $borderRight,
            $caption, $width, $heightCell, $bgColor, $borderColor) {
        // insert to layout
        $cell_id = getUniqId();
        $id = getUniqId();
        $widthTmp = substr($width, 0, 5);
        $data = array(
            'CELL_ID' => $cell_id,
            'LAYOUT_ID' => $layoutId,
            'ROW_ID' => $rowId,
            'COL_ID' => $colId,
            'IS_MERGE' => $is_merge,
            'IS_USE' => $isUse,
            'BORDER_TOP' => $borderTop,
            'BORDER_LEFT' => $borderLeft,
            'BORDER_BOTTOM' => $borderBottom,
            'BORDER_RIGHT' => $borderRight,
            'BORDER_COLOR' => $borderColor,
            'BG_COLOR' => $bgColor,
            'CAPTION' => $caption,
            'WIDTH' => $widthTmp . "%",
            'HEIGHT' => $heightCell . "px",
            'ID' => $id,
            'ALIGN' => 'center',
            'VALIGN' => 'middle',
        );
        $result = $this->db->AutoExecute('META_CONTENT_LAYOUT_CELL', $data);

        return $cell_id;
    }

    private function updateLayoutCell($cellId, $is_merge, $isUse, $width, $heightCell, $borderTop, $borderLeft, $borderBottom, $borderRight,
            $borderColor, $bgColor, $caption) {
        $widthTmp = substr($width, 0, 5);
        $data = array(
            'IS_MERGE' => $is_merge,
            'IS_USE' => $isUse,
            'BORDER_TOP' => $borderTop,
            'BORDER_LEFT' => $borderLeft,
            'BORDER_BOTTOM' => $borderBottom,
            'BORDER_RIGHT' => $borderRight,
            'BORDER_COLOR' => $borderColor,
            'BG_COLOR' => $bgColor,
            'CAPTION' => $caption,
            'WIDTH' => $widthTmp . "%",
            'HEIGHT' => $heightCell . "px",
        );
        $this->db->AutoExecute('META_CONTENT_LAYOUT_CELL', $data, 'UPDATE', "CELL_ID = " . $cellId);
    }

    /**
     * save start merge
     * @param type $layoutId
     * @param type $startCellId
     */
    private function saveStartMerge($layoutId, $startCellId) {
        // insert to layout
        $mergeId = getUniqId();
        $data = array(
            'MERGE_ID' => $mergeId,
            'LAYOUT_ID' => $layoutId,
            'START_CELL_ID' => $startCellId
        );
        $this->db->AutoExecute('META_CONTENT_LAYOUT_MERGE', $data, 'INSERT');
        return $mergeId;
    }

    private function saveRow($rowId, $layoutId, $height) {
        $id = getUniqId();
        $data = array(
            'ROW_ID' => $rowId,
            'LAYOUT_ID' => $layoutId,
            'HEIGHT' => $height . "px",
            'ID' => $id
        );

        $this->db->AutoExecute('META_CONTENT_LAYOUT_ROW', $data, 'INSERT');
        return $rowId;
    }

    private function saveCol($colId, $layoutId, $width, $rowId) {
        $id = getUniqId();
        $width = substr($width, 0, 5);
        $data = array(
            'COL_ID' => $colId,
            'LAYOUT_ID' => $layoutId,
            'WIDTH' => $width . "%",
            'ID' => $id
        );
        $this->db->AutoExecute('META_CONTENT_LAYOUT_COL', $data, 'INSERT');
        return $colId;
    }

    private function saveMergeCell($mergeId, $cellId, $layoutId) {
        try {
            $mergeCellId = getUniqId();
            $data = array(
                'MERGE_CELL_ID' => $mergeCellId,
                'MERGE_ID' => $mergeId,
                'CELL_ID' => $cellId,
                'LAYOUT_ID' => $layoutId
            );
            $this->db->AutoExecute('META_CONTENT_LT_MERGE_CELL', $data, 'INSERT');
            return $mergeCellId;
        } catch (Exception $exc) {
            return false;
        }
    }

    private function collectMergeCells($cellArray, $layoutId) {
        foreach ($cellArray AS $row) {
            $row = (array) $row;
            foreach ($row AS $cell) {
                $cell = (array) $cell;
                if (isset($cell['is_merge'])) {
                    if ($cell['is_merge'] == 1) {
                        if (isset($cell['mergeId'])) {
                            if (!self::saveMergeCell($cell['mergeId'], $cell['cellId'], $layoutId)) {
                                return false;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    public function getMetaDataListByName($name) {
        $data = $this->db->GetAll("SELECT "
                . "META_DATA_ID, "
                . "META_DATA_CODE,  "
                . "META_DATA_NAME "
                . "FROM META_DATA "
                . "WHERE LOWER(META_DATA_NAME) LIKE  '%" . Str::lower($name) . "%'");
        return $data;
    }

    public function getMetaDataList($id) {
        $metaDataId = Security::sanitize($id);
        $qr = "SELECT MCM.META_DATA_ID, MD.META_DATA_NAME FROM META_CONTENT_MAP MCM INNER JOIN META_DATA MD ON MCM.META_DATA_ID = MD.META_DATA_ID WHERE MCM.SRC_META_DATA_ID = $metaDataId";
        $data = $this->db->GetAll($qr);
        return $data;
    }

    private function clearContentDatas($layoutId, $metaDataId = null) {
        if (!is_null($metaDataId)) {
            $this->db->Execute("DELETE FROM META_CONTENT_MAP WHERE LAYOUT_ID = $layoutId");
        }
//        $this->db->Execute("DELETE FROM META_CONTENT_LT_MERGE_CELL WHERE LAYOUT_ID = $layoutId");
//        $this->db->Execute("DELETE FROM META_CONTENT_LAYOUT_MERGE WHERE LAYOUT_ID = $layoutId");
        $this->db->Execute("DELETE FROM META_CONTENT_LAYOUT_COL WHERE LAYOUT_ID = $layoutId");
        $this->db->Execute("DELETE FROM META_CONTENT_LAYOUT_ROW WHERE LAYOUT_ID = $layoutId");
//    $this->db->Execute("DELETE FROM META_CONTENT_LAYOUT_CELL WHERE LAYOUT_ID = $layoutId");
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="CONTENT HTML">
    public function saveContentHtml() {
        $contentId = getUID();
        $fileName = str_replace(' - Контент нэмэх', '', Input::post('name'));
        $fileExtension = 'html';
        $physicalPath = $this->writeContentToFile($contentId, Input::post('defaultPath'));
        $response = array(
            'message' => Lang::line('msg_save_success'),
            'status' => 'success'
        );

        if ($physicalPath != null) {
            try {
                $fileSize = @filesize($physicalPath);
            } catch (Exception $e) {
                $fileSize = '';
            }
            $ecmContent = array(
                'CONTENT_ID' => $contentId,
                'FILE_NAME' => $fileName,
                'PHYSICAL_PATH' => $physicalPath,
                'FILE_SIZE' => $fileSize,
                'FILE_EXTENSION' => $fileExtension,
                'DESCRIPTION' => Input::post('description'),
                'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                'CREATED_DATE' => Date::currentDate(),
                'TYPE_ID' => Input::post('typeId')
            );

            $this->db->AutoExecute('ECM_CONTENT', $ecmContent);

            self::updateContentMenu($contentId);

            $dataViewId = Input::post('srcDataViewId');

            if ($dataViewId != null && $dataViewId != '') {
                $sql = "SELECT REF_STRUCTURE_ID FROM META_GROUP_LINK WHERE META_DATA_ID = " . $dataViewId;
                $row = $this->db->GetRow($sql);
                if ($row && !is_null($row['REF_STRUCTURE_ID'])) {
                    $sqlTableName = "SELECT TABLE_NAME FROM META_GROUP_LINK WHERE META_DATA_ID = " . $row['REF_STRUCTURE_ID'];
                    $rowTableName = $this->db->GetRow($sqlTableName);
                    $metaDmRecordMapData = array(
                        'ID' => getUID(),
                        'SRC_TABLE_NAME' => $rowTableName['TABLE_NAME'],
                        'SRC_RECORD_ID' => Input::post('srcRecordId'),
                        'TRG_TABLE_NAME' => 'ECM_CONTENT',
                        'TRG_RECORD_ID' => $contentId
                    );

                    $this->db->AutoExecute('META_DM_RECORD_MAP', $metaDmRecordMapData);

                    $response['dataViewId'] = $dataViewId;
                } else {
                    $response = array(
                        'message' => 'Ref structure тохируулаагүй байна.',
                        'status' => 'error'
                    );
                }
            }
        } else {
            $response = array(
                'message' => Lang::line('msg_error'),
                'status' => 'error'
            );
        }

        return $response;
    }

    public function updateContentHtml() {
        // self::clearContentHtml(Input::post('id'), Input::post('defaultPath'));
        $contentId = Input::post('id');
        $fileName = Input::post('name');
        $fileExtension = 'html';
        $physicalPath = $this->writeContentToFile($contentId, Input::post('defaultPath'));

        if ($physicalPath == null) {
            return false;
        }
        try {
            $fileSize = @filesize($physicalPath);
        } catch (Exception $e) {
            $fileSize = '';
        }

        $ecmContent = array(
            'FILE_NAME' => $fileName,
            'PHYSICAL_PATH' => $physicalPath,
            'FILE_SIZE' => $fileSize,
            'FILE_EXTENSION' => $fileExtension,
            'DESCRIPTION' => Input::post('description'),
            'CREATED_USER_ID' => Ue::sessionUserKeyId(),
            'CREATED_DATE' => Date::currentDate(),
            'TYPE_ID' => Input::post('typeId')
        );

        $this->db->AutoExecute('ECM_CONTENT', $ecmContent, 'UPDATE', "CONTENT_ID = " . $contentId);

        self::createContentLog($contentId);

        return true;
    }

    private function createContentLog($contentId) {
        $ecmContent = array(
            'ID' => getUID(),
            'CONTENT_ID' => $contentId,
            'CREATED_USER_ID' => Ue::sessionUserKeyId(),
            'CREATED_DATE' => Date::currentDate(),
        );

        $this->db->AutoExecute('ECM_CONTENT_LOG', $ecmContent);
    }

    public function writeContentToFile($fileName, $defaultPath) {
        if (Input::postCheck('isDefault')) {
            $defaultPath = self::$contentHtmlFilePathSvn;
        }

        $contentHtml = Input::postCheck('tempEditor') ? Input::postNonTags('tempEditor') : Input::postNonTags('tempEditor-' . Input::post('contentUniqId'));

        if (!$contentHtml) {
            return null;
        }

        if (!$defaultPath) {
            return null;
        }
        try {
            $htmlFileName = $fileName . ".html";
            $path = $defaultPath . $htmlFileName;
            $handle = fopen($path, 'w') or die('Cannot open file:  ' . $path);
            fwrite($handle, $contentHtml);
            fclose($handle);

            return $path;
        } catch (Exception $e) {
            return null;
        }
    }

    public function clearContentHtml($contentId, $defaultPath) {

        // Энэ нь файл үүсгэсэн байвал
        $sql2 = "SELECT PHYSICAL_PATH, FILE_NAME FROM ECM_CONTENT WHERE CONTENT_ID = " . $contentId;
        $row2 = $this->db->GetRow($sql2);
        if ($row2) {

            // file шууд устгахаас зайлсхийж rename хийлээ.
            // FILE RENAME
            if ($row2['PHYSICAL_PATH'] != null && (new Mdcontentui())->contentFileExists($row2['PHYSICAL_PATH'])) {
                $newName = 'delete_' . date('Y_m_d_H_m_s') . '_' . $contentId . '.html';
                $newName = $defaultPath . $newName;
                try {
                    rename($row2['PHYSICAL_PATH'], $newName);
                } catch (Exception $e) {
                    
                }
            }

            return true;
        }
    }

    public function getContentHtmlForRender($contentId) {
        
        $data = $this->db->GetRow("
            SELECT 
                C.*, 
                CT.NAME TYPE_NAME 
            FROM ECM_CONTENT C 
                LEFT JOIN ECM_CONTENT_TYPE CT ON C.TYPE_ID = CT.ID 
            WHERE C.CONTENT_ID = ".$this->db->Param(0), array($contentId));
        
        if ($data) {
            return $data;
        } else {
            return null;
        }
    }

    public function getContentHtmlListModel() {
        $page = Input::postCheck('page') ? Input::post('page') : 1;
        $rows = Input::postCheck('rows') ? Input::post('rows') : 20;
        $offset = ($page - 1) * $rows;
        $subCondition = " ";
        $result = array();
        $sortField = 'EC.CREATED_DATE';
        $sortOrder = 'DESC';
        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        $selectQr = 'SELECT DISTINCT EC.CONTENT_ID,
                EC.FILE_NAME,
                EC.PHYSICAL_PATH,
                EC.CREATED_DATE,
                (SUBSTR(BP.LAST_NAME,0, 2) || \'.\' || BP.FIRST_NAME) AS PERSON_NAME ';

        $fromQr = ' FROM ECM_CONTENT EC 
              LEFT JOIN UM_SYSTEM_USER USU ON EC.CREATED_USER_ID = USU.USER_ID 
              LEFT JOIN BASE_PERSON BP ON USU.PERSON_ID = BP.PERSON_ID 
              WHERE EC.TYPE_ID = ' . Mdcontentui::$contentHtmlTypeId;

        if (!is_null(Input::post('contentName'))) {
            $fromQr .= " AND LOWER(EC.FILE_NAME) LIKE '%" . Str::lower(Input::post('contentName')) . "%'";
        }

        $selectCount = 'SELECT COUNT(EC.CONTENT_ID) AS ROW_COUNT ' . $fromQr . $subCondition;
        $selectList = $selectQr . $fromQr . $subCondition . 'ORDER BY ' . $sortField . ' ' . $sortOrder;

        $data = $this->db->SelectLimit($selectList, $rows, $offset);
        $rowCount = $this->db->GetRow($selectCount);
        $result["total"] = $rowCount['ROW_COUNT'];
        $result["rows"] = $data;

        return $result;
    }

    private function updateContentMenu($contentId) {
        $menuMetaDataId = Input::post('menuId');

        if (!is_null($menuMetaDataId)) {
            $dataMenuLink = array(
                'META_DATA_ID' => $menuMetaDataId,
                'WEB_URL' => 'mdcontentui/contentHtmlRender/' . $contentId,
                'MODIFIED_USER_ID' => Ue::sessionUserKeyId(),
                'MODIFIED_DATE' => Date::currentDate(),
            );

            /* mdm */$this->db->AutoExecute('META_MENU_LINK', $dataMenuLink, 'UPDATE', 'META_DATA_ID = ' . $menuMetaDataId);

            // clear menu cache
            $tmp_dir = Mdcommon::getCacheDirectory();
            $files = glob($tmp_dir . "/*/le/leftmenu_" . Ue::sessionUserKeyId() . '_' . Input::post('moduleId') . ".txt");
            foreach ($files as $file) {
                @unlink($file);
            }
        }
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="ECM CONTENT">
    public function createEcmContentModel() {
        $fileDataTmp = Input::fileData();
        $fileData = $fileDataTmp['file'];

        if (is_uploaded_file($fileData['tmp_name'])) {
            $newFileName = "file_" . getUID();
            $fileName = $fileData['name'];
            $fileSize = $fileData['size'];
            $fileExtension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
            $newFileName = $newFileName . '.' . $fileExtension;
            $filePath = UPLOADPATH . self::$uploadedPath;
            FileUpload::SetFileName($newFileName);
            FileUpload::SetTempName($fileData['tmp_name']);
            FileUpload::SetUploadDirectory($filePath);
            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
            $uploadResult = FileUpload::UploadFile();

            if ($uploadResult) {
                $contentId = getUID();
                $ecmContentData = array(
                    'CONTENT_ID' => $contentId,
                    'FILE_NAME' => $fileName,
                    'PHYSICAL_PATH' => $filePath . $newFileName,
                    'FILE_SIZE' => $fileSize,
                    'FILE_EXTENSION' => $fileExtension,
                    'DESCRIPTION' => Input::post('description'),
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'CREATED_DATE' => Date::currentDate(),
                );

                $ecmContent = $this->db->AutoExecute('ECM_CONTENT', $ecmContentData);

                if ($ecmContent && Input::postCheck('folderId')) {
                    $ecmContentDirectoryData = array(
                        'ID' => getUID(),
                        'CONTENT_ID' => $contentId,
                        'DIRECTORY_ID' => Input::post('folderId'),
                    );

                    $this->db->AutoExecute('ECM_CONTENT_DIRECTORY', $ecmContentDirectoryData);
                }

                return $fileExtension;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function moveToFolderModel() {
        $contentId = Input::post('id');
        $directoryId = Input::post('directoryId');
        $content = $this->db->GetOne("SELECT * FROM ECM_CONTENT_DIRECTORY WHERE CONTENT_ID = $contentId");

        if ($content) {
            $ecmContentDirectoryData = array(
                'DIRECTORY_ID' => $directoryId,
            );

            $this->db->AutoExecute('ECM_CONTENT_DIRECTORY', $ecmContentDirectoryData, 'UPDATE', 'CONTENT_ID = ' . $contentId);
        } else {
            $ecmContentDirectoryData = array(
                'ID' => getUID(),
                'CONTENT_ID' => $contentId,
                'DIRECTORY_ID' => $directoryId,
            );

            $this->db->AutoExecute('ECM_CONTENT_DIRECTORY', $ecmContentDirectoryData);
        }

        return true;
    }

    public function copyToFolderModel() {
        $contentId = Input::post('id');
        $content = $this->db->GetRow("SELECT * FROM ECM_CONTENT WHERE CONTENT_ID = $contentId");

        if ($content && Input::postCheck('directoryId')) {
            $physicalPathOld = $content['PHYSICAL_PATH'];
            if ((new Mdcontentui())->contentFileExists($physicalPathOld)) {
                $physicalPathOldExploded = explode('/', $physicalPathOld);
                $lengthPhysicalPath = (count($physicalPathOldExploded) - 1);
                $physicalFileName = $physicalPathOldExploded[$lengthPhysicalPath];
                unset($physicalPathOldExploded[$lengthPhysicalPath]);
                $physicalPathNew = implode('/', $physicalPathOldExploded);

                if (copy($physicalPathOld, $physicalPathNew . '/Copy of ' . $physicalFileName)) {
                    $directoryId = Input::post('directoryId');
                    $contentId = getUID();

                    $ecmContentData = array(
                        'CONTENT_ID' => $contentId,
                        'FILE_NAME' => 'Copy of ' . $content['FILE_NAME'],
                        'PHYSICAL_PATH' => $content['PHYSICAL_PATH'],
                        'THUMB_PHYSICAL_PATH' => $content['THUMB_PHYSICAL_PATH'],
                        'FILE_SIZE' => $content['FILE_SIZE'],
                        'FILE_EXTENSION' => $content['FILE_EXTENSION'],
                        'CREATED_DATE' => $content['CREATED_DATE'],
                        'CREATED_USER_ID' => $content['CREATED_USER_ID'],
                        'MODIFIED_DATE' => $content['MODIFIED_DATE'],
                        'MODIFIED_USER_ID' => $content['MODIFIED_USER_ID'],
                        'WFM_STATUS_ID' => $content['WFM_STATUS_ID'],
                        'WFM_DESCRIPTION' => $content['WFM_DESCRIPTION'],
                        'IS_LOCKED' => $content['IS_LOCKED'],
                        'LOCKED_USER_ID' => $content['LOCKED_USER_ID'],
                        'LOCKED_DATE' => $content['LOCKED_DATE'],
                        'LOCKED_IP_ADDRESS' => $content['LOCKED_IP_ADDRESS'],
                        'IS_VERSION' => $content['IS_VERSION'],
                        'TYPE_ID' => $content['TYPE_ID'],
                        'DESCRIPTION' => $content['DESCRIPTION'],
                    );

                    $ecmContent = $this->db->AutoExecute('ECM_CONTENT', $ecmContentData);

                    $ecmContentDirectoryData = array(
                        'ID' => getUID(),
                        'CONTENT_ID' => $contentId,
                        'DIRECTORY_ID' => $directoryId,
                    );

                    $this->db->AutoExecute('ECM_CONTENT_DIRECTORY', $ecmContentDirectoryData);

                    $response = array(
                        'message' => Lang::line('msg_save_success'),
                        'status' => 'success',
                    );
                } else {
                    $response = array(
                        'message' => Lang::line('Файл хуулахад алдлаа гарлаа'),
                        'status' => 'error'
                    );
                }
            } else {
                $response = array(
                    'message' => Lang::line('Файл олдсонгүй'),
                    'status' => 'error'
                );
            }
        } else {
            $response = array(
                'message' => Lang::line('msg_error'),
                'status' => 'error'
            );
        }

        return $response;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="excel template">
    public function saveExcelTemplateModel() {
        $fileDataTmp = Input::fileData();
        $fileData = $fileDataTmp['excel_template_file'];

        if (is_uploaded_file($fileData['tmp_name'][0])) {
            $newFileName = "file_" . getUID();
            $fileName = $fileData['name'][0];
            $fileSize = $fileData['size'][0];
            $fileExtension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
            $newFileName = $newFileName . '.' . $fileExtension;
            $filePath = UPLOADPATH . self::$excelImportUploadedPath;
            FileUpload::SetFileName($newFileName);
            FileUpload::SetTempName($fileData['tmp_name'][0]);
            FileUpload::SetUploadDirectory($filePath);
            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
            $uploadResult = FileUpload::UploadFile();

            if ($uploadResult) {
                $fileName = Input::post('excel_template_file_name');
                $contentId = getUID();
                $ecmContentData = array(
                    'CONTENT_ID' => $contentId,
                    'FILE_NAME' => $fileName[0],
                    'PHYSICAL_PATH' => $filePath . $newFileName,
                    'FILE_SIZE' => $fileSize,
                    'FILE_EXTENSION' => $fileExtension,
                    'DESCRIPTION' => Input::post('description'),
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'CREATED_DATE' => Date::currentDate(),
                    'TYPE_ID' => Config::getFromCache('ECM_CONTENT_TYPE_EXCEL')
                );

                $ecmContent = $this->db->AutoExecute('ECM_CONTENT', $ecmContentData);

                if ($ecmContent && Input::postCheck('processMetaDataId')) {
                    $metaDataId = Input::post('processMetaDataId');
                    $row = $this->db->GetOne("SELECT ID FROM ECM_CONTENT_META WHERE META_DATA_ID = $metaDataId");
                    $ecmContentMetaData = array(
                        'ID' => getUID(),
                        'CONTENT_ID' => $contentId,
                        'META_DATA_ID' => Input::post('processMetaDataId'),
                    );

                    if ($row) {
                        unset($ecmContentMetaData['ID']);
                        $this->db->AutoExecute('ECM_CONTENT_META', $ecmContentMetaData, 'UPDATE', 'META_DATA_ID = ' . $metaDataId);
                    } else {
                        $this->db->AutoExecute('ECM_CONTENT_META', $ecmContentMetaData);
                    }
                }

                return $fileExtension;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getExcelTemplateDetail() {
        $metaDataId = Input::post('processMetaDataId');

        $row = $this->db->GetRow("SELECT 
                                    FILE_NAME, PHYSICAL_PATH
                                FROM ECM_CONTENT EC
                                INNER JOIN ECM_CONTENT_META ECM
                                ON EC.CONTENT_ID = ECM.CONTENT_ID
                                WHERE ECM.META_DATA_ID = $metaDataId");

        return $row;
    }

    // </editor-fold>

    public function getContentUIModel($metaDataId) {
        return $this->db->GetOne("SELECT IS_CONTENT_UI FROM META_MENU_LINK WHERE META_DATA_ID = $metaDataId");
    }
    
    public function getContentDataModel($metaDataId) {
        $data = $this->db->GetAll("SELECT 
                                        kn.KNOWLEDGE_ID,
                                        kn.KNOWLEDGE_CODE,
                                        kn.KNOWLEDGE_NAME,
                                        kn.KNOWLEDGE_DESCRIPTION,
                                        kn.CREATED_DATE,
                                        kn.CREATED_USER_ID,
                                        uu.USERNAME,
                                        '' AS CONTENTS,
                                        tem.*
                                    FROM KNOWLEDGE kn
                                    INNER JOIN (
                                        SELECT 
                                            K.KNOWLEDGE_ID AS id,
                                            K.KNOWLEDGE_CODE AS knowledgecode,
                                            K.KNOWLEDGE_NAME AS knowledgename,
                                            K.KNOWLEDGE_DESCRIPTION AS knowledgedescription,
                                            C.CONTENT_ID AS contentid,
                                            K.CREATED_DATE AS createddate,
                                            U.USERNAME AS itfirstname,
                                            K.MODIFIED_DATE AS modifieddate,
                                            K.PARENT_ID AS parentid,
                                            KC.ID AS categoryid
                                        FROM KNOWLEDGE K
                                        LEFT JOIN UM_USER U ON K.CREATED_USER_ID = U.USER_ID
                                        LEFT JOIN META_DM_RECORD_MAP M ON K.KNOWLEDGE_ID = M.SRC_RECORD_ID
                                        LEFT JOIN ECM_CONTENT C ON M.TRG_RECORD_ID = C.CONTENT_ID
                                        LEFT JOIN KM_CATEGORY KC ON K.CATEGORY_ID = KC.ID
                                    ) tem ON kn.KNOWLEDGE_ID = tem.id
                                    INNER JOIN UM_USER uu on kn.CREATED_USER_ID = uu.USER_ID
                                    WHERE kn.KNOWLEDGE_ID IN (SELECT TRG_RECORD_ID FROM META_DM_RECORD_MAP WHERE SRC_TABLE_NAME = 'META_DATA' AND SRC_RECORD_ID = '$metaDataId' AND TRG_TABLE_NAME = 'KNOWLEDGE')");
        
        (Array) $item = array();
        foreach ($data as $row) {
            $rows = $this->db->GetAll("SELECT 
                                            CO.CONTENT_ID AS ATTACH_ID, 
                                            CO.FILE_NAME AS ATTACH_NAME, 
                                            CO.PHYSICAL_PATH AS ATTACH, 
                                            CO.FILE_EXTENSION, 
                                            CO.FILE_SIZE, 
                                            '' AS SYSTEM_URL 
                                        FROM ECM_CONTENT CO 
                                            INNER JOIN ECM_CONTENT_MAP MP ON MP.CONTENT_ID = CO.CONTENT_ID 
                                            INNER JOIN META_DATA MD ON MP.REF_STRUCTURE_ID = MD.META_DATA_ID
                                        WHERE MD.META_DATA_CODE = 'KNOWLEDGE_STRUCTURE'
                                            AND MP.RECORD_ID = '". $row['KNOWLEDGE_ID'] ."' AND IS_PHOTO = 0");
            $row['CONTENTS'] = $rows;
            array_push($item, $row);
        }
        return $item;
    }
    
    public function getLastHtmlContentByRecordId($recordId) {
            
        $htmlPath = $this->db->GetRow("
            SELECT 
                EC.THUMB_PHYSICAL_PATH 
            FROM ECM_CONTENT_MAP CM 
                INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
            WHERE CM.RECORD_ID = $recordId 
                AND EC.THUMB_PHYSICAL_PATH IS NOT NULL 
            ORDER BY CM.CREATED_DATE DESC");

        if (isset($htmlPath['THUMB_PHYSICAL_PATH']) && file_exists($htmlPath['THUMB_PHYSICAL_PATH'])) {
            return file_get_contents($htmlPath['THUMB_PHYSICAL_PATH']);
        }
        
        return null;
    }
    
    public function saveHtmlEditorModel() {
        
        try {
            
            includeLib('PDF/Pdf');
            set_time_limit(0);
            ini_set('memory_limit', '-1');
        
            $recordId = Input::post('recordId');
            $newHtml = Input::postNonTags('ecmContentBody');
            
            $newHtml = preg_replace('/(;| )\/([_\-.A-Za-zА-Яа-яӨҮөүх0-9]+)\//u', '$1<nobr>/$2/</nobr>', $newHtml);
            $newHtml = preg_replace('/(;| )\/([_\-.A-Za-zА-Яа-яӨҮөүх0-9]+)(&nb| )/u', '$1<nobr>/$2</nobr>$3', $newHtml);
            $newHtml = preg_replace('/(;| )([_\-.A-Za-zА-Яа-яӨҮөүх0-9]+)\/(&nb| )/u', '$1<nobr>$2/</nobr>$3', $newHtml);
            $newHtml = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $newHtml);
            $newHtml = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $newHtml); 
            $newHtml = str_replace('  ', '&emsp;&emsp;', $newHtml);
        
            $row = $this->db->GetRow("
                SELECT 
                    EC.CONTENT_ID, 
                    EC.PHYSICAL_PATH, 
                    EC.THUMB_PHYSICAL_PATH, 
                    EC.PAGE_SETTINGS, 
                    CM.MAIN_RECORD_ID 
                FROM ECM_CONTENT_MAP CM 
                    INNER JOIN ECM_CONTENT EC ON EC.CONTENT_ID = CM.CONTENT_ID 
                WHERE CM.RECORD_ID = ".$this->db->Param(0)." 
                    AND EC.THUMB_PHYSICAL_PATH IS NOT NULL 
                ORDER BY CM.CREATED_DATE DESC", 
                array($recordId)
            );

            $contentId    = $row['CONTENT_ID'];
            $htmlPath     = $row['THUMB_PHYSICAL_PATH'];
            $pdfPath      = $row['PHYSICAL_PATH'];
            $pageSettings = ($row['PAGE_SETTINGS'] ? json_decode($row['PAGE_SETTINGS'], true) : array());
            
            if (file_exists($pdfPath)) {
                @unlink($pdfPath);
            }
            
            $oldHtml = file_get_contents($htmlPath);

            file_put_contents($htmlPath, $newHtml);
            
            $site_url = defined('LOCAL_URL') ? LOCAL_URL : URL;
            $htmlContent = preg_replace('/(<img.*?src=")(?!http|data:image\/)(.*">)/', "$1$site_url/$2", $newHtml);
            $htmlContent = str_replace('&emsp;&emsp;', '<span style="display: inline-block; width: 30px;"></span>', $htmlContent);
            
            $fileToSave = UPLOADPATH.Mdwebservice::$uploadedPath.'file_'.getUID();
            $orientation = 'portrait';
            
            if (isset($pageSettings['marginTop'])) { 
                
                $orientation = $pageSettings['orientation'];
                
                $_POST['orientation'] = $orientation;
                $_POST['size']        = $pageSettings['size'];
                $_POST['top']         = $pageSettings['marginTop'];
                $_POST['left']        = $pageSettings['marginLeft'];
                $_POST['bottom']      = $pageSettings['marginBottom'];
                $_POST['right']       = $pageSettings['marginRight'];
                
                $headerHtmlPath = issetParam($pageSettings['headerHtmlPath']);
                $footerHtmlPath = issetParam($pageSettings['footerHtmlPath']);
                        
                if ($headerHtmlPath && file_exists($headerHtmlPath)) {
                    $_POST['headerHtml'] = file_get_contents($headerHtmlPath);
                }
                
                if ($footerHtmlPath && file_exists($footerHtmlPath)) {
                    $_POST['footerHtml'] = file_get_contents($footerHtmlPath);
                }
                
            } else {
                $_POST['left'] = $_POST['right'] = 1;
            }
            
            $css = '<style type="text/css">';
            $css .= Mdtemplate::printCss('return');
            $css .= '</style>';    
            
            $_POST['isIgnoreFooter'] = 1;
            $_POST['isSmartShrinking'] = '1';
            
            $htmlContent = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'.$css.'</head><body>' . $htmlContent . '</body></html>';
            
            $pdf = Pdf::createSnappyPdf(($orientation == 'portrait' ? 'Portrait' : 'Landscape'), 'A4');

            Pdf::generateFromHtml($pdf, $htmlContent, $fileToSave, array(), true);

            $updateData = array(
                'PHYSICAL_PATH'    => $fileToSave.'.pdf', 
                'MODIFIED_DATE'    => Date::currentDate(),
                'MODIFIED_USER_ID' => Ue::sessionUserKeyId()
            );

            $this->db->AutoExecute('ECM_CONTENT', $updateData, 'UPDATE', 'CONTENT_ID = '.$contentId);
            
            /**
             * This library 7.4 version - s doosh ajillahgui yum bna
             */
            if (Config::getFromCache('is_html_diff_ecm_content') && $oldHtml && $newHtml && (float) phpversion() >= 7.4) {
                
                includeLib('DOM/HtmlDiff/autoload');   
                
                $htmlDiff = new Caxy\HtmlDiff\HtmlDiff($oldHtml, $newHtml);
                $diffHtmlContent = $htmlDiff->build();
                
                if ($diffHtmlContent) {
                    
                    $filePath = Mdwebservice::bpUploadGetPath();
                    $oldHtmlPath = $filePath . getUIDAdd(1) . '.html';
                    $newHtmlPath = $filePath . getUIDAdd(2) . '.html';
                                                
                    file_put_contents($oldHtmlPath, $oldHtml);
                    file_put_contents($newHtmlPath, $diffHtmlContent);
                    
                    $historyData = array(
                        'ID'                 => getUID(), 
                        'CONTENT_ID'         => $contentId, 
                        'PHYSICAL_PATH'      => $newHtmlPath, 
                        'PREV_PHYSICAL_PATH' => $oldHtmlPath, 
                        'CREATED_USER_ID'    => Ue::sessionUserKeyId(), 
                        'CREATED_DATE'       => Date::currentDate(), 
                        'IP_ADDRESS'         => get_client_ip()
                    );

                    $this->db->AutoExecute('ECM_CONTENT_FILE_VERSION', $historyData);
                }
            }
            
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
            
        } catch (Exception $ex) {
            return array('status' => 'error', 'message' => $ex->getMessage());
        }
    }
    
    public function ecmContentHtmlDiffViewerModel() {
        
        try {
            
            $postData = Arr::changeKeyLower(Input::postData());
            $versionId = issetVar($postData['versionid']);
            
            $row = $this->db->GetRow("SELECT PREV_PHYSICAL_PATH, PHYSICAL_PATH FROM ECM_CONTENT_FILE_VERSION WHERE ID = ".$this->db->Param(0), array($versionId));
            
            if ($row && $row['PREV_PHYSICAL_PATH'] && $row['PHYSICAL_PATH'] 
                && file_exists($row['PREV_PHYSICAL_PATH']) && file_exists($row['PHYSICAL_PATH'])) {
                
                $prevHtmlFile = file_get_contents($row['PREV_PHYSICAL_PATH']);
                $nextHtmlFile = file_get_contents($row['PHYSICAL_PATH']);
                
                $response = array('status' => 'success', 'prevHtmlFile' => $prevHtmlFile, 'nextHtmlFile' => $nextHtmlFile);
                
            } else {
                throw new Exception('Тохиргоо олдсонгүй!'); 
            }
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
        
        return $response;
    }
    
    public function contentVisitorLogModel() {
        try {
            $mergeCellId = getUID();
            $recordId = Input::post('recordId');
            $duration = Input::post('duration');
            $userId = Ue::sessionUserKeyId();
            
            $isExist = $this->db->GetOne("SELECT DURATION FROM ECM_CONTENT_VISITOR WHERE RECORD_ID = $recordId AND USER_ID = $userId");
            
            if ($isExist) {
                
                $updateData = array(
                    'DURATION'    => $duration, 
                    'MODIFIED_DATE'    => Date::currentDate(),
                    'MODIFIED_USER_ID' => $userId
                );

                $this->db->AutoExecute('ECM_CONTENT_VISITOR', $updateData, 'UPDATE', 'RECORD_ID = '.$recordId.' AND USER_ID = '.$userId);                
                
            } else {
                    
                $data = array(
                    'ID' => $mergeCellId,
                    'TABLE_NAME' => 'TEST_TABLE',
                    'REF_STRUCTURE_ID' => null,
                    'RECORD_ID' => $recordId,
                    'USER_ID' => $userId,
                    'CREATED_DATE' => Date::currentDate(),
                    'DURATION' => $duration
                );
                $this->db->AutoExecute('ECM_CONTENT_VISITOR', $data, 'INSERT');
            }
            
            return $isExist ? 1 : 0;
        } catch (Exception $exc) {
            return false;
        }
    }    
    
    public function contentDurationVisitorLogModel() {
        try {
            
            $userId = Ue::sessionUserKeyId();
            $recordId = Input::post('recordId');
            
            return $this->db->GetOne("SELECT DURATION FROM ECM_CONTENT_VISITOR WHERE RECORD_ID = $recordId AND USER_ID = $userId");
            
        } catch (Exception $exc) {
            return false;
        }
    }    
    
}
