<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdlanguage Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Language
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdlanguage
 */
class Mdlanguage extends Controller {

    private static $viewPath = 'middleware/views/language/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function renderGenerateLanguageFile() {
        
        $this->view->globeCount = $this->model->getGlobeCountModel();
        
        $response = array(
            'html' => $this->view->renderPrint('renderGenerateLanguageFile', self::$viewPath),
            'title' => 'Орчуулгын файл үүсгэх',
            'create_btn' => $this->lang->line('create_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function generateLanguageFile() {
        $result = $this->model->generateLanguageFileModel();
        convJson($result);
    }
    
    public function index() {
        
        $this->view->title = 'Орчуулга';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        
        $this->view->isAjax = is_ajax_request();
        $this->view->code = '';
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }

        $this->view->render('index', self::$viewPath);

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function languageDataGrid() {
        $data = $this->model->getLanguageMainDataGridModel();
        echo json_encode($data); exit;
    }
    
    public function renderGenerateGlobeList() {
        
        $this->view->isAjax = true;
        $this->view->code = Input::post('code');
        
        $response = array(
            'html' => $this->view->renderPrint('index', self::$viewPath),
            'title' => 'Орчуулгын жагсаалт',
            'choose_btn' => $this->lang->line('choose_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function getMetaDictionary() {
        $response = $this->model->getMetaDictionaryModel();
        echo json_encode($response); exit;
    }
    
    public function saveMetaTranslation() {
        $response = $this->model->saveMetaTranslationModel();
        echo json_encode($response); exit;
    }
    
    public function getMenuMetaDictionary() {
        $response = $this->model->getMenuMetaDictionaryModel();
        echo json_encode($response); exit;
    }
    
    public function saveMenuMetaTranslation() {
        $response = $this->model->saveMenuMetaTranslationModel();
        echo json_encode($response); exit;
    }
    
    public static function isTranslateOptionByConfig() {
        
        if (Config::getFromCache('META_TRANSLATE_BUTTON') == '1' && self::isAccessMetaTranslateButton()) {
            return true;
        }
        return false;
    }
    
    public static function translateBtnByMetaId($metaId) {
        
        $button = '';
        
        if (self::isTranslateOptionByConfig()) {
            $button = '<button type="button" class="btn btn-sm btn-primary bp-btn-translate float-right" onclick="metaTranslator(this, \''.$metaId.'\');"><i class="fa fa-language"></i> Translate</button>';
        }
        
        return $button;
    }
    
    public static function isAccessMetaTranslateButton() {
        
        if (Session::get(SESSION_PREFIX . 'isTranslateUser')) {
            return true;
        }
        
        global $db;
        
        $metaDataIdPh = $db->Param(0);
        $userIdPh     = $db->Param(1);
        
        $row = $db->GetRow("
            SELECT 
                UMP.PERMISSION_ID 
            FROM UM_META_PERMISSION UMP 
                LEFT JOIN (SELECT ROLE_ID FROM UM_USER_ROLE WHERE USER_ID = $userIdPh) UR ON UMP.ROLE_ID = UR.ROLE_ID 
            WHERE UMP.META_DATA_ID = $metaDataIdPh 
                AND (UMP.USER_ID = $userIdPh OR UR.ROLE_ID IS NOT NULL)", 
                
            array('1570672892681511', Ue::sessionUserKeyId())
        );
        
        if (isset($row['PERMISSION_ID'])) {
            return true;
        }
        
        return false;
    }
    
    public function getLanguagePackage() {
        
        $response = array(
            'list'            => Lang::getLanguageList(), 
            'langCode'        => Lang::getCode(), 
            'defaultLangCode' => Lang::getDefaultLangCode()
        );
        jsonResponse($response);
    }

}
