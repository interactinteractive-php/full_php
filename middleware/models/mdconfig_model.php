<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Mdconfig_Model extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function getConfigMainDataGridModel($pagination = true) {

        $page = Input::post('page', 1);
        $rows = Input::post('rows', 10);
        $offset = ($page - 1) * $rows;
        $subCondition = '';
        $result = array();

        if (Input::postCheck('filterRules')) {
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);
            
            foreach ($filterRules as $rule) {
                
                $field = $rule['field'];
                $value = Input::param(Str::lower($rule['value']));
                
                if (!empty($value)) {
                    if ($field === 'CODE') {
                        $subCondition .= " AND (LOWER(CODE) LIKE '%$value%')";
                    } elseif ($field === 'DESCRIPTION') {
                        $subCondition .= " AND (LOWER(DESCRIPTION) LIKE '%$value%')";
                    }
                }
            }
        }

        $sortField = 'DISPLAY_ORDER';
        $sortOrder = 'ASC';

        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        $selectCount = "SELECT 
                            COUNT(ID) AS ROW_COUNT
                        FROM CONFIG 
                        WHERE CODE IS NOT NULL $subCondition";

        $selectList = " 
                    SELECT * 
                    FROM (
                        SELECT 
                            ID, 
                            DESCRIPTION, 
                            CODE, 
                            DISPLAY_ORDER, 
                            '' AS ACTION
                        FROM CONFIG 
                    ) TEMP 
                    WHERE CODE IS NOT NULL $subCondition 
                    ORDER BY $sortField $sortOrder";

        $rowCount = $this->db->GetRow($selectCount);
        
        $result['total'] = $rowCount['ROW_COUNT'];
        $result['rows'] = array();

        $rs = $this->db->SelectLimit($selectList, $rows, $offset);

        if (isset($rs->_array)) {
            $result['rows'] = $rs->_array;
        }

        return $result;
    }

    public function getConfigValueDataGridModel($pagination = true) {

        $page = Input::post('page', 1);
        $rows = Input::post('rows', 10);
        $offset = ($page - 1) * $rows;
        $subCondition = '';
        $result = array();

        if (Input::postCheck('filterRules')) {
            $filterRules = json_decode(Str::cp1251_utf8($_POST['filterRules']), true);
            
            foreach ($filterRules as $rule) {
                
                $field = $rule['field'];
                $value = Input::param(Str::lower($rule['value']));
                
                if (!empty($value)) {
                    if ($field === 'CODE') {
                        $subCondition .= " AND (LOWER(CFG.CODE) LIKE '%$value%')";
                    } elseif ($field === 'DESCRIPTION') {
                        $subCondition .= " AND (LOWER(CV.DESCRIPTION) LIKE '%$value%')";
                    } elseif ($field === 'CONFIG_VALUE') {
                        $subCondition .= " AND (LOWER(CV.CONFIG_VALUE) LIKE '%$value%')";
                    } elseif ($field === 'CRITERIA') {
                        $subCondition .= " AND (LOWER(CV.CRITERIA) LIKE '%$value%')";
                    }
                }
            }
        }

        if (Input::postCheck('params') && Input::isEmpty('params') == false) {
            parse_str(Input::post('params'), $qryStrings);
            foreach ($qryStrings as $k => $v) {
                if (!empty($v) && $k == 'configId') {
                    $subCondition .= " AND CV.CONFIG_ID = '" . Input::param($v) . "'";
                }
            }
        }

        $sortField = 'ID';
        $sortOrder = 'DESC';

        if (Input::postCheck('sort') && Input::postCheck('order')) {
            $sortField = Input::post('sort');
            $sortOrder = Input::post('order');
        }

        $selectCount = "SELECT 
                            COUNT(CV.ID) AS ROW_COUNT
                        FROM CONFIG_VALUE CV 
                            INNER JOIN CONFIG CFG ON CFG.ID = CV.CONFIG_ID 
                        WHERE CV.ID IS NOT NULL $subCondition";

        $selectList = " 
                    SELECT * 
                    FROM (
                        SELECT 
                            CFG.ID AS CONFIG_ID, 
                            CV.ID, 
                            CFG.CODE, 
                            CV.DESCRIPTION, 
                            CASE WHEN CFG.VALUE_TYPE = 'password' THEN RPAD('*', LENGTH(CV.CONFIG_VALUE), '*') 
                            ELSE CV.CONFIG_VALUE END AS CONFIG_VALUE, 
                            CV.CRITERIA 
                        FROM CONFIG_VALUE CV 
                            INNER JOIN CONFIG CFG ON CFG.ID = CV.CONFIG_ID  
                        WHERE CV.ID IS NOT NULL $subCondition 
                    ) TEMP 
                    ORDER BY $sortField $sortOrder";

        $rowCount = $this->db->GetRow($selectCount);
        
        $result['total'] = $rowCount['ROW_COUNT'];
        $result['rows'] = array();

        $rs = $this->db->SelectLimit($selectList, $rows, $offset);

        if (isset($rs->_array)) {
            $result['rows'] = $rs->_array;
        }

        return $result;
    }

    public function getConfigValueByIdModel($id) {
        
        $row = $this->db->GetRow("
            SELECT 
                ID, 
                CONFIG_ID, 
                CONFIG_VALUE, 
                CRITERIA, 
                DESCRIPTION, 
                COMPANY_DEPARTMENT_ID 
            FROM CONFIG_VALUE 
            WHERE ID = ".$this->db->Param(0), 
            array($id)
        );
        
        return $row;
    }

    public function getConfigKeyListModel() {
        $data = $this->db->GetAll("SELECT ID, CODE, DESCRIPTION FROM CONFIG ORDER BY CODE ASC");
        return $data;
    }

    public function getConfigByIdModel($id) {
        
        $row = $this->db->GetRow("
            SELECT 
                ID, 
                DESCRIPTION, 
                VALUE_TYPE, 
                DISPLAY_ORDER, 
                CODE, 
                DEFAULT_VALUE 
            FROM CONFIG 
            WHERE ID = ".$this->db->Param(0), 
            array($id)
        );
        
        return $row;
    }

    public function createConfigValueModel() {

        $criteria = '';

        if (Input::postCheck('criteria') && $joinCriteria = implode(';', Input::post('criteria'))) {
            $criteria = $joinCriteria;
        }

        if (isset($_FILES['configValue'])) {
            
            $fileData = $_FILES['configValue'];

            $newFileName   = 'config-file_' . getUID();
            $fileExtension = strtolower(substr($fileData['name'], strrpos($fileData['name'], '.') + 1));
            $fileName      = $newFileName . '.' . $fileExtension;
            $filePath      = UPLOADPATH . 'process/';
            
            FileUpload::SetFileName($fileName);
            FileUpload::SetTempName($fileData['tmp_name']);
            FileUpload::SetUploadDirectory($filePath);
            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
            
            $uploadResult = FileUpload::UploadFile();          
            
            $_POST['configValue'] = $filePath . $fileName;
        }
        
        if (Input::post('configPasswordHash') == '1') {
            $_POST['configValue'] = Hash::createMD5reverse(Input::post('configValue'));
        }

        $data = array(
            'ID'           => getUID(), 
            'CONFIG_ID'    => Input::post('configId'), 
            'CONFIG_VALUE' => Input::post('configValue'), 
            'DESCRIPTION'  => Input::post('configDescr'), 
            'CRITERIA'     => $criteria, 
            'COMPANY_DEPARTMENT_ID' => Input::numeric('companyDepartmentId')
        );

        $result = $this->db->AutoExecute('CONFIG_VALUE', $data);

        if ($result) {
            self::clearSystemConfig();
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
        }
    }

    public function updateConfigValueModel() {

        $criteria = '';

        if (Input::postCheck('criteria') && $joinCriteria = implode(';', Input::post('criteria'))) {
            $criteria = $joinCriteria;
        }

        if (isset($_FILES['configValue'])) {
            
            $fileData = $_FILES['configValue'];

            $newFileName   = 'config-file_' . getUID();
            $fileExtension = strtolower(substr($fileData['name'], strrpos($fileData['name'], '.') + 1));
            $fileName      = $newFileName . '.' . $fileExtension;
            $filePath      = UPLOADPATH . 'process/';
            
            FileUpload::SetFileName($fileName);
            FileUpload::SetTempName($fileData['tmp_name']);
            FileUpload::SetUploadDirectory($filePath);
            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
            
            $uploadResult = FileUpload::UploadFile();          
            
            $_POST['configValue'] = $filePath . $fileName;

            if (Input::postCheck('configValueOld') && file_exists(Input::post('configValueOld'))) {
                @unlink(Input::post('configValueOld'));
            }                        
        } 
        
        if (Input::post('configPasswordHash') == '1') {
            $_POST['configValue'] = Hash::createMD5reverse(Input::post('configValue'));
        }

        $data = array(
            'CONFIG_ID'    => Input::post('configId'), 
            'CONFIG_VALUE' => Input::post('configValue'),
            'DESCRIPTION'  => Input::post('configDescr'), 
            'CRITERIA'     => $criteria, 
            'COMPANY_DEPARTMENT_ID' => Input::numeric('companyDepartmentId')
        );

        $result = $this->db->AutoExecute('CONFIG_VALUE', $data, 'UPDATE', 'ID = '.Input::post('id'));

        if ($result) {
            self::clearSystemConfig();
            return array('status' => 'success', 'message' => Lang::line('msg_save_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_save_error'));
        }
    }

    public function deleteConfigValueModel($id) {
        
        $idPh = $this->db->Param(0);
        $getConfig = $this->db->GetRow("SELECT CONFIG_VALUE FROM CONFIG_VALUE WHERE ID = $idPh", array($id));

        if ($getConfig && file_exists($getConfig['CONFIG_VALUE'])) {
            @unlink($getConfig['CONFIG_VALUE']);         
        }
        
        $result = $this->db->Execute("DELETE FROM CONFIG_VALUE WHERE ID = $idPh", array($id));

        if ($result) {
            self::clearSystemConfig();
            return array('status' => 'success', 'message' => Lang::line('msg_delete_success'));
        } else {
            return array('status' => 'error', 'message' => Lang::line('msg_delete_error'));
        }
    }
    
    public function clearSystemConfig() {
        
        $tmp_dir  = Mdcommon::getCacheDirectory();
        $sysFiles = glob($tmp_dir."/*/sy/*.txt");
        $configWsUrl = Config::getFromCache('heavyServiceAddress');
        
        $this->ws->runResponse(GF_SERVICE_ADDRESS, 'reload_config');
        
        if ($configWsUrl && @file_get_contents($configWsUrl)) {
            $this->ws->runResponse($configWsUrl, 'reload_config');
        } 

        if (count($sysFiles)) {
            foreach ($sysFiles as $sysFile) {
                @unlink($sysFile);
            }
            return true;
        }
        
        return false;
    }

    public function getConfigValueModel() {
        
        $key = Input::post('key');
        
        if ($key == 'config_file_viewer_address') {
            return defined('CONFIG_FILE_VIEWER_ADDRESS') ? CONFIG_FILE_VIEWER_ADDRESS : null;
        }
        
        if (Input::isEmpty('criteria') == false) {
            $criteria = Input::post('criteria');
        } else {
            Auth::handleLogin();
            $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
            $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();
            $criteria = 'departmentId='.$departmentId.';';
        }
        
        return Config::get($key, $criteria);
    }
    
    public function phpToDbModel() {
        
        $alreadyConfig = $this->db->GetOne("SELECT ID FROM CONFIG WHERE CODE = 'phpconfigtodb'");
        
        if ($alreadyConfig) {
            return array('PHP config ийг өмнө нь бааз руу оруулсан байна!');
        }
        
        //$this->db->Execute("DELETE FROM CONFIG_VALUE WHERE CONFIG_ID IN (SELECT ID FROM CONFIG WHERE SYSTEM_ID = 999)");
        //$this->db->Execute("DELETE FROM CONFIG WHERE SYSTEM_ID = 999");
        
        $configs = array(
            array(
                'code' => 'TITLE', 
                'descr' => 'Системийн үндсэн HTML title', 
                'valueType' => 'string'
            ), 
            array(
                'code' => 'MULTI_LANG', 
                'descr' => 'Олон хэл дэмжих эсэх', 
                'valueType' => 'boolean'
            ), 
            array(
                'code' => 'LANG_ID', 
                'descr' => 'default xэлний REF_LANGUAGE таблийн ID', 
                'valueType' => 'number'
            ), 
            array(
                'code' => 'LANG_NAME', 
                'descr' => 'default xэлний нэр', 
                'valueType' => 'string'
            ), 
            array(
                'code' => 'LANG', 
                'descr' => 'default xэлний код', 
                'valueType' => 'string'
            ), 
            array(
                'code' => 'CONFIG_IMG_EXT', 
                'descr' => 'Upload хийхэд зөвшөөрөгдөх зурагны төрлүүд', 
                'valueType' => 'string'
            ), 
            array(
                'code' => 'CONFIG_FILE_EXT', 
                'descr' => 'Upload хийхэд зөвшөөрөгдөх файлын төрлүүд', 
                'valueType' => 'string'
            ), 
            array(
                'code' => 'CONFIG_FILE_MAX_SIZE', 
                'descr' => 'Upload хийхэд зөвшөөрөгдөх файлын дээд хэмжээ (bytes)', 
                'valueType' => 'number'
            ), 
            array(
                'code' => 'CONFIG_FILE_UPLOAD_DATE_FOLDER', 
                'descr' => 'Upload хийх фолдерийн бүтцийн огнооны формат /Ymd/', 
                'valueType' => 'string'
            ), 
            array(
                'code' => 'CONFIG_START_LINK', 
                'descr' => 'Логин хийсэний дараа орох дефаулт линк хаяг /appmenu/', 
                'valueType' => 'string'
            ), 
            array(
                'code' => 'CONFIG_OBJECT_BACKLINK', 
                'descr' => 'Үндсэн /тайлан, жагсаалт, layout, widget/ орсоны дараа мета удирдах руу буцах товч харуулах эсэх', 
                'valueType' => 'boolean'
            ), 
            array(
                'code' => 'CONFIG_FISCAL_PERIOD', 
                'descr' => 'Тайлант үе ашиглах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_MULTI_TAB', 
                'descr' => 'Системийн олон табны боломж ашиглах эсэх', 
                'valueType' => 'boolean'
            ), 
            array(
                'code' => 'CONFIG_ALWAYS_NEWTAB', 
                'descr' => 'CONFIG_MULTI_TAB = 1 үед хэрэглэгдэнэ, шинэ таб нээгдэх эсэх', 
                'valueType' => 'boolean'
            ), 
            array(
                'code' => 'CONFIG_USE_BP_DTL_THEME', 
                'descr' => 'Процессийн детайлын загвар ашиглах эсэх', 
                'valueType' => 'boolean'
            ), 
            array(
                'code' => 'CONFIG_USE_ETOKEN', 
                'descr' => 'Тоон гарын үсэг ашиглах эсэх', 
                'valueType' => 'boolean'
            ), 
            array(
                'code' => 'CONFIG_USE_LDAP', 
                'descr' => 'Active Directory ашиглах эсэх', 
                'valueType' => 'boolean'
            ), 
            array(
                'code' => 'CONFIG_USE_WEBMAIL', 
                'descr' => 'Webmail ашиглах эсэх', 
                'valueType' => 'boolean'
            ), 
            array(
                'code' => 'CONFIG_ACCOUNT_CODE_MASK', 
                'descr' => 'Дансны кодны маск. 9-н тоогоор нэг тоон цифрийг илэрхийлнэ. /999-999/', 
                'valueType' => 'string'
            ), 
            array(
                'code' => 'CONFIG_ACCOUNT_SEGMENT', 
                'descr' => 'Дансны сегментийн тохиргоо ашиглах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_ALL_CACHE_CLEAR', 
                'descr' => 'Бүх кейшийг цэвэрлэх эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_IGNORE_CHECK_LOCK', 
                'descr' => 'Метаны түгжээг шалгахгүй эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_ROW_DESC', 
                'descr' => 'Журнал бичилт хийх цонхны детайл мөр дээр гүйлгээний утга харуулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_ROW_EXPENSE_CENTER', 
                'descr' => 'Журнал бичилт хийх цонхны детайл мөр дээр хариуцлагын төв сонгох эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_ALL_ACC_META', 
                'descr' => 'Журнал бичилт дээр харьцсан дансан дээр МГ үзүүлэлт сонгоно', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_IGNORE_ACC_AFTER_POPUP', 
                'descr' => 'Авлага өглөгийн данс сонгосны дараа процессын цонх дуудагдахгүй, харин хэрэглэгч өөрөө товч дээр дарсан үед дуудагдах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_META_DUPLICATE', 
                'descr' => 'Ижил төрөлтай дансны үзүүлэлт ижилсүүлэх эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_VAT_DEDUCTION_DEBIT', 
                'descr' => 'Журнал бичилтийн мөрний НӨАТ салгах үеийн дебит талын дансны код', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'CONFIG_GL_VAT_DEDUCTION_CREDIT', 
                'descr' => 'Журнал бичилтийн мөрний НӨАТ салгах үеийн кредит талын дансны код', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'CONFIG_GL_SINGLE_META_NOTOPEN', 
                'descr' => 'Журнал бичилт дээр данс сонгоход зөвхөн нэг үзүүлэлттэй мөн утга сонгогдсон үед үзүүлэлтийн цонхыг нээхгүй байх эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_ALL_META_NOTOPEN', 
                'descr' => 'Журнал бичилт дээр данс сонгоход үзүүлэлтүүд утга сонгогдсон үед үзүүлэлтийн цонхыг нээхгүй байх эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_ACCOUNT_PARENT_ID', 
                'descr' => 'Журнал бичилт дээрхи данс нь fin_account - parent_id талбарыг ашиглах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_BILLRATE_HDR_RATE', 
                'descr' => 'Тооцооны ханшийн тэгшитгэл дээр шинэ ханш дуудаж ажиллуулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_BILLRATE_IGNORE_CALC', 
                'descr' => 'Тооцооны ханшийн тэгшитгэлийн шууд бодолтыг болиулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_GL_VAT_META_VALIDATE_IGNORE', 
                'descr' => 'Журнал бичилтийн дансны нөатийн үзүүлэлтийн датаны шалгуурыг болиулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_CT_ECONOMIC_SRC', 
                'descr' => 'Ханшийн тэгшитгэл дээр economicSourceId ашиглах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_BANK_ID_GOLOMT', 
                'descr' => 'ref_bank таблийн Голомт банкны ID', 
                'valueType' => 'number'
            ),
            array(
                'code' => 'CONFIG_BANK_ID_KHAN', 
                'descr' => 'ref_bank таблийн Хаан банкны ID', 
                'valueType' => 'number'
            ),
            array(
                'code' => 'CONFIG_TOP_MEGA_MENU', 
                'descr' => 'Менюг mega төрлөөр харуулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_IS_CLOSE_ON_ESCAPE', 
                'descr' => 'Popup цонхыг дан ESC товчоор хаах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_TOP_MENU_NOBACK', 
                'changeCode' => 'CONFIG_TOP_MENU_NOICON', 
                'descr' => 'Дээд менюний дүрсийг харуулахгүй эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_STATEMENT_PDF_VIEW', 
                'descr' => 'Statement тайланг PDF ээр харуулах товчийг ашиглах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_FILTER_ONLY_ENTER_KEY', 
                'descr' => 'Жагсаалтын баганын шүүлтийг enter товч дарж шүүх эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_IS_DELETEACTION_BEFORERELOAD', 
                'descr' => 'Жагсаалтын мөр устгахаас өмнө жагсаалтыг заавал сэргээх эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'USE_CHAT', 
                'descr' => 'Дотоод чат систем ашиглах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_PNOTIFY_POSITION', 
                'descr' => 'PNotify мессеж харуулах зэрэгцүүлэлт', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'CONFIG_ALWAYS_CONFIRM_CT', 
                'descr' => 'Систем доторхи табыг хаах үед байнга баталжуулалт асуух эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_CHECK_MODIFIED_CATCH', 
                'descr' => 'Workspace доторхи процесс дээр засвар орсон үед хадгалаагүй гарах үед сануулга өгөх эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'usePushNotification', 
                'descr' => 'Менюний count дээр 0 ээс их утга ирсэн үед мэдэгдэл харуулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_OCR_SERVICE', 
                'descr' => 'OCR API сервисийн хаяг', 
                'valueType' => 'url'
            ),
            array(
                'code' => 'IS_TEST_SERVER', 
                'descr' => 'Тестийн сервер эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_IS_COLLAPSE_BUTTON', 
                'descr' => 'Жагсаалтын туслах товчны dropdown аар харуулах эсэх', 
                'valueType' => 'boolean'
            ),
            
            array(
                'code' => 'SMTP_HOST', 
                'descr' => 'Smtp host', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'SMTP_PORT', 
                'descr' => 'Smtp port', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'SMTP_HOSTNAME', 
                'descr' => 'The hostname to use in the Message-ID header and as default HELO string', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'SMTP_SECURE', 
                'descr' => 'Smtp secure', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'SMTP_AUTH', 
                'descr' => 'Smtp auth', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'SMTP_SSL_VERIFY', 
                'descr' => 'Smtp ssl verify', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'SMTP_USER', 
                'descr' => 'Smtp user', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'SMTP_PASS', 
                'descr' => 'Smtp password', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'EMAIL_FROM', 
                'descr' => 'From email', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'EMAIL_FROM_NAME', 
                'descr' => 'From email name', 
                'valueType' => 'string'
            ),
            
            /**
             * Start U
             */
            array(
                'code' => 'CONFIG_TNA_HISHIGARVIN', 
                'descr' => 'Хишиг Арвинд зориулсан Цагийн системийн хувилбар', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_TNA_SOYOL', 
                'descr' => 'SOYOL-д зориулсан Цагийн системийн хувилбар', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_TNA_HIDENOTPLAN', 
                'descr' => 'Төлөвлөгөө байхгүй ажилтныг харуулахгүй байх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_BUDGET_SERVER_ADDRESS', 
                'descr' => 'Төсөв, Төлөвлөлт ASP дуудаж буй тохиргоо', 
                'valueType' => 'url'
            ),
            array(
                'code' => 'CONFIG_POS_SALESPERSON', 
                'descr' => 'Худалдааны зөвлөх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_DELIVERY', 
                'descr' => 'Хүргэллтэй эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_HEALTHRECIPE', 
                'descr' => 'Эмийн сан эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_SERIALNUMBER', 
                'descr' => 'Барааг Сериал - аар сонгох', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PRINT_TYPE', 
                'descr' => 'Харилцагч дээр баримт хэвлэх төрөл', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'CONFIG_POS_BILL_MARGIN', 
                'descr' => 'Bill Paper Margins', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'CONFIG_POS_ITEM_CHECK_DUPLICATE', 
                'descr' => 'Бараа давхцаж бичих эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_ITEM_CHECK_ENDQTY', 
                'descr' => 'Барааны үлдэгдэл шалгах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_ITEM_CHECK_ENDQTY_MSG', 
                'descr' => 'Барааны үлдэгдлийн мессеж харуулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PRINT_COPIES_COUNT', 
                'descr' => 'Баримт хэвлэх хувь', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_ROW_DISCOUNT', 
                'descr' => 'Хөнгөлөлт тооцоолох товч харуулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_BILL_PROMOTION', 
                'descr' => 'Баримт дээр хөтөлбөрүүд харуулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_ONLY_CARDNUMBER', 
                'descr' => 'Гишүүнчлэлийн карт харуулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_DESCRIPTION_REQUIRED', 
                'descr' => 'Гүйлгээний утга заавал бөглөх эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_ITEM_GET', 
                'descr' => 'Сонгосон барааны GET Process', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'CONFIG_POS_TEMP_INVOICE_KEY_FIELD', 
                'descr' => 'Харилцагчид ялгах тохиргоо', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'CONFIG_POS_TEMP_INVOICE_DVID', 
                'descr' => 'Нэхэмжлэлийн DataviewId', 
                'valueType' => 'number'
            ),
            array(
                'code' => 'CONFIG_POS_EMPLOYEE_CUSTOMER', 
                'descr' => 'Ажилтан сонгох эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_INVOICE_LIST', 
                'descr' => 'Нэхэмжлэлийн жагсаалт харах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_CONTRACT_LIST', 
                'descr' => 'Гэрээний жагсаалт харах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_COUPON', 
                'descr' => 'Купонтай эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_BONUSCARD', 
                'descr' => 'Хөнгөлөлтийн карт харуулах эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_ACCOUNTTRANSFER', 
                'descr' => 'Төлбөрийн төрөл Дансны шилжүүлэг', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_MOBILENET', 
                'descr' => 'Төлбөрийн төрөл Мобайл интернэт', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_BARTER', 
                'descr' => 'Төлбөрийн төрөл Бартер', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_LEASING', 
                'descr' => 'Төлбөрийн төрөл Лизинг', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_EMPLOAN', 
                'descr' => 'Төлбөрийн төрөл Дараа тооцоо', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_LOCALEXPENSE', 
                'descr' => 'Төлбөрийн төрөл Дотоод зардал', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_UNITRECEIVABLE', 
                'descr' => 'Төлбөрийн төрөл Даатгал', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_TALONLIST_PROTECT', 
                'descr' => 'Бичсэн талон цоожлох эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_BANKCARD_COPIES_COUNT', 
                'descr' => 'Банкны карт хэдэн хувь хэвлэх', 
                'valueType' => 'number'
            ),
            array(
                'code' => 'CONFIG_POS_GIFT', 
                'descr' => 'Бэлэгтэй эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_VOUCHER_PATH', 
                'descr' => 'Харилцагчид тус бүрийн Voucher загварууд /folder name/', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'CONFIG_POS_RETURN_INFO_REQUIRED', 
                'descr' => 'Буцаалт хийхэд харилцагчдын мэдээлэл болох Овог, Нэр, РД, Утасны дугаар заавал эсэх', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_SERVICEJOB_ACCOMPANY', 
                'descr' => 'Дагалдах үйлчилгээ', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_SERVICEJOB', 
                'descr' => 'Дагалдах үйлчилгээ', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_ACCOMPANY_ITEM', 
                'descr' => 'Дагалдах бараа', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PREPAYMENT', 
                'descr' => 'Төлбөрийн төрөл Урьдчилгаа төлбөр', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_ITEM_CHECK_DISCOUNTQTY', 
                'descr' => 'Хөнгөлөлтийн тоо хэмжээг шалгах', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_TNA_NUMBER_DEC', 
                'descr' => 'TNA оронгийн нарийвчлал', 
                'valueType' => 'number'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_CANDY', 
                'descr' => 'MONpay', 
                'valueType' => 'boolean'
            ),
            array(
                'code' => 'CONFIG_POS_PAYMENT_CANDY_COUPON', 
                'descr' => 'MONpay', 
                'valueType' => 'boolean'
            ),
            /**
             * End U
             */

            array(
                'code' => 'MONPASS_SERVER', 
                'descr' => 'E-token тохиргоо', 
                'valueType' => 'url'
            ),
            array(
                'code' => 'CONFIG_FILE_VIEWER_ADDRESS', 
                'descr' => 'doc, docx, xsl viewer in IIS', 
                'valueType' => 'url'
            ),
            array(
                'code' => 'DOC_SERVER', 
                'descr' => 'onlyoffice - docx, xls, ppt editor', 
                'valueType' => 'url'
            ),
            array(
                'code' => 'DOC_SERVER_LOCAL', 
                'descr' => 'onlyoffice local server ip address', 
                'valueType' => 'url'
            ),
            array(
                'code' => 'XYP_TOKEN_FILE_URL', 
                'descr' => 'XYP token хадгалсан storage-н зам', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'XYP_TOKEN_ACCESS_TOKEN', 
                'descr' => 'xyp-н access token', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'XYP_TOKEN_KEY_STR', 
                'descr' => 'xyp-н access token', 
                'valueType' => 'string'
            ),
            array(
                'code' => 'XYP_WSDL_VERSION', 
                'descr' => 'xyp-н wsdl version', 
                'valueType' => 'string'
            ), 
            array(
                'code' => 'CONFIG_URL', 
                'descr' => 'scan хийх url', 
                'valueType' => 'url'
            )
        );
        
        $response = array();
        
        foreach ($configs as $row) {
            
            $code = $row['code'];
            $valueType = $row['valueType'];
            $descr = $row['descr'];
            
            $dbCode = self::getExitsConfigByCode($code);
            
            if (!$dbCode && defined($code)) {
                
                $lastConfigId = self::getConfigLastId() + 1;
                
                $dataConfig = array(
                    'ID'          => $lastConfigId, 
                    'CODE'        => isset($row['changeCode']) ? $row['changeCode'] : $code, 
                    'DESCRIPTION' => $descr, 
                    'VALUE_TYPE'  => $valueType, 
                    'SYSTEM_ID'   => 999
                );
                
                $result = $this->db->AutoExecute('CONFIG', $dataConfig);
                
                if ($result) {
                    
                    $lastConfigValueId = self::getConfigValueLastId() + 1;
                    
                    if ($valueType == 'boolean') {
                        
                        $constantVal = constant($code);
                        $configValue = is_bool($constantVal) ? (int) $constantVal : $constantVal;
                        
                    } else {
                        
                        $constantVal = constant($code);
                            
                        if (is_bool($constantVal)) {
                            $configValue = (int) $constantVal;
                        } else {
                            $configValue = $constantVal;
                        }
                    }
                    
                    $dataConfigValue = array(
                        'ID'           => $lastConfigValueId, 
                        'CONFIG_ID'    => $lastConfigId, 
                        'CONFIG_VALUE' => $configValue
                    );

                    $this->db->AutoExecute('CONFIG_VALUE', $dataConfigValue);
                    
                    $response[$code] = $configValue;
                }
            }
        }
        
        $dataConfigPhp = array(
            'ID'          => self::getConfigLastId() + 1, 
            'CODE'        => 'phpconfigtodb', 
            'DESCRIPTION' => 'PHP config баазаас уншдаг болгох', 
            'SYSTEM_ID'   => 999
        );

        $this->db->AutoExecute('CONFIG', $dataConfigPhp);
        
        self::clearSystemConfig();

        return array('PHP config ийг бааз руу амжилттай орууллаа');
    }
    
    private function getExitsConfigByCode($code) {
        
        $dbCode = $this->db->GetOne("
            SELECT 
                C.CODE  
            FROM CONFIG C 
                INNER JOIN CONFIG_VALUE CV ON CV.CONFIG_ID = C.ID 
            WHERE LOWER(C.CODE) = " . $this->db->Param(0), 
            array(strtolower($code))
        );
        
        return $dbCode;
    }
    
    private function getConfigLastId() {
        $maxId = $this->db->GetOne("SELECT MAX(ID) FROM CONFIG");
        return $maxId;
    }
    
    private function getConfigValueLastId() {
        $maxId = $this->db->GetOne("SELECT MAX(ID) FROM CONFIG_VALUE");
        return $maxId;
    }

}