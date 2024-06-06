<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdgl Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	General Ledger
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/GeneralLedger
 */
class Mdgl extends Controller {
    
    public static $taxPayable = 40;
    public static $taxReceivable = 17;
    public static $glBookDtlGroupProcessId = 1457334502452;
    public static $glBookDtlGroupMetaDataId = 1453257384209;
    public static $expenseCenterMetaDataCode = 'expensecenterid'; //1460711400656511
    public static $cashRateAccountWithKeeperDataView = 'RATE_REVALUATE_LIST_KPR';
    public static $accountListDataViewCode = 'fin_account_list';
    public static $accountListDataViewId = 1454379109682;
    public static $customerListDataViewCode = 'fin_customer_dvlist';
    public static $glBookDtlGroup = 'generalLedgerBookDtls';
    public static $glMainDvId = '1448888606134';
    public static $oppAccountMeta = array();
    public static $loadAccount = array();
    public static $getDefaultValues = array();
    public static $customerApArListDataViewCode = 'AR_AP_BALANCE_VW';
    private $viewPath = 'middleware/views/generalledger/';
    
    public static $glRowStaticKeys = array(
        'id'=>0, 'subid'=>0, 'booktypeid'=>0, 'balancytypeid'=>0, 'objectid'=>0, 'isusedetail'=>0,  
        'accounttypeid'=>0, 'accounttypecode'=>0, 'processid'=>0, 'refcustomerid'=>0, 'generalledgerbookid'=>0, 
        'islockamount'=>0, 'islock'=>0, 'isdebit'=>0, 'accountid'=>0, 'accountcode'=>0, 'accountname'=>0, 
        'customerid'=>0, 'customercode'=>0, 'customername'=>0, 'rate'=>0, 'description'=>0,
        'debitamount'=>0, 'creditamount'=>0, 'debitamountbase'=>0, 'creditamountbase'=>0, 
        'currencyname'=>0, 'generalledgermaps'=>0, 'accountfilter'=>0,   
        'detailvalues'=>0, 'srcinvoicebook'=>0, 'invoicebook'=>0, 'createddate'=>0, 'createduserid'=>0, 
        'modifieduserid'=>0, 'modifieddate'=>0 
    );
    
    public static $segmentAccountPath = array('accountid', 'filteraccountid', 'trgaccountid', 'oppaccountid', 'accountidsegmentcode');

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function entry() {
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = 'Гүйлгээ';
        self::glExtensions();

        $this->view->accountCode = 'vw_account';
        $this->view->messageCode = 'fin_message';
        $this->view->defaultAccounts = '';
        $this->view->inputMetaDataId = self::$glBookDtlGroupMetaDataId;
        $this->view->paramList = array();
        $this->view->uniqId = getUID();
        $this->view->glBpMainWindowId = 'glTemplateSectionStatic_'.$this->view->uniqId;
        $this->view->isFieldSet = (Input::postCheck('bpTabLength') ? Input::post('bpTabLength') : 1);
        $this->view->isShowGlBookNumber = true;
        
        $this->view->currencyList = $this->model->currencyListModel();
        
        $this->view->isDataView = false;
        $this->view->dataViewId = null;
        
        if (Input::isEmpty('dataViewId') == false) {
            $this->view->isDataView = true;
            $this->view->dataViewId = Input::post('dataViewId');
        }        
        
        $runSource = Input::post('runsource');
        $isIgnoreUseDetail = Input::post('isignoreusedetail');
        
        if ($runSource == 'frombudget') {
            $this->view->runSourceInputName  = 'isFromBudget';
            $this->view->runSourceInputValue = '1';
        } 
        
        if ($isIgnoreUseDetail == '1') {
            $this->view->isIgnoreUseDetail = true;
        }
        
        if (Config::getFromCache('IS_GL_BOOK_DATE_CURRENT_FP_SD')) {
            $this->view->glBookDate = Ue::currentFiscalPeriodStartDate();
        } else {
            $this->view->glBookDate = Date::currentDate('Y-m-d');
        }
        
        if (Config::getFromCache('IS_GL_BOOK_DATE_DISABLED')) {
            $this->view->isGlBookDateDisabled = true;
        }
        
        if (Config::getFromCache('IS_RUNTIME_GL_BOOK_NUMBER')) {
            $this->view->isGlBookNumberDisabled = true;
        }
        
        if ($glStructureId = Config::getFromCache('GENERAL_LEDGER_BOOK_STRUCTURE_ID')) {
            $this->view->isFileAttachTab = true;
            $this->view->fileAttachTab = $this->view->renderPrint('addon/renderAddModeBpFileTab', 'middleware/views/webservice/');
        }
        
        $this->view->gridDtl = self::glGridRender();      
        
        $this->view->isAjax = is_ajax_request();
        $this->view->isPopup = false;

        if (!$this->view->isAjax) {
            $this->view->render('header');
        }

        $this->view->addForm = $this->view->renderPrint('main/sub/addGl', $this->viewPath);
        
        $this->load->model('mdobject', 'middleware/models/');
        $dvConfigRow = $this->model->getDataViewConfigRowModel(self::$glMainDvId);
        
        if ($dvConfigRow['COUNT_REPORT_TEMPLATE'] != '0') {
            $this->view->isSavePrint = true;
        } else {
            $this->view->isSavePrint = false;
        }
        
        if (Input::postCheck('dialogMode')) {
            
            $this->view->isPopup = true;
            
            $response = [
                'html' => $this->view->renderPrint('main/entry', $this->viewPath),
                'title' => $this->view->title, 
                'metaType' => 'add_gl', 
                'uniqId' => $this->view->uniqId, 
                'glMainDvId' => self::$glMainDvId, 
                'helpContentId' => Config::getFromCache('FIN_GL_ENTRY_HELP_CONTENT')
            ];
            
            jsonResponse($response);
            
        } else {
            $this->view->render('main/entry', $this->viewPath);
        }

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function edit_entry($id = '') {
        $this->load->model('mdgl', 'middleware/models/');
        
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = 'Журнал бичилт засах';
        $this->view->isglcopy = Input::post('isglcopy');
        
        if ($this->view->isglcopy) {
            $this->view->title = 'Журнал бичилт хуулах';
        }
        
        self::glExtensions();

        $this->view->accountCode = 'vw_account';
        $this->view->messageCode = 'fin_message';
        $this->view->isEditMode = true;
        $this->view->inputMetaDataId = self::$glBookDtlGroupMetaDataId;
        $this->view->isFieldSet = (Input::postCheck('bpTabLength') ? Input::post('bpTabLength') : 1);
        $this->view->isShowGlBookNumber = true;

        if (Input::isEmpty('id') === false) {
            $id = Input::post('id');
        }

        $this->view->paramList = array();
        $this->view->uniqId = getUID();
        $this->view->errorGLMessage = '';
        $this->view->glBpMainWindowId = 'glTemplateSectionStatic_'.$this->view->uniqId;
        
        $entry = $this->model->getGlEntryModel($id);

        if ($entry['status'] == 'success') {
            
            $glBook = $entry['result'];

            $this->view->paramList = $glBook;
            
            if (isset($glBook['description'])) {
                $this->view->descriptionCode = $this->model->getDescriptionModel($glBook['description']); 
            } else {
                $this->view->descriptionCode = '';
            }
            
            if (Config::getFromCache('IS_RL_PL_EDIT_MODE_INPUT_ENABLE')) {
                $this->view->glRlPlEditModeInputsEnable = true;
            }
            
            $glStructureId = Config::getFromCache('GENERAL_LEDGER_BOOK_STRUCTURE_ID');
            
            if (!$this->view->isglcopy && $glStructureId) {
                
                $this->view->isFileAttachTab = true;
                $this->view->fileAttachTab = self::glFileAttach($this->view->uniqId, $glStructureId, $id);
            }
            
        } else {
            $this->view->errorGLMessage = $this->ws->getResponseMessage($entry);
        }
        
        $this->view->currencyList = $this->model->currencyListModel();
        
        $this->view->isDataView = false;
        $this->view->dataViewId = null;
        $this->view->drillDownParams = array();
        
        if (Input::isEmpty('dataViewId') == false) {
            $this->view->isDataView = true;
            $this->view->dataViewId = Input::post('dataViewId');
        }        
        if (Input::isEmpty('drillDownParams') == false) {
            $this->view->drillDownParams = Input::post('drillDownParams');
        }
        
        $this->load->model('mdobject', 'middleware/models/', '1');

        $dvConfigRow = $this->model->getDataViewConfigRowModel(self::$glMainDvId);
        
        $this->load->model('mdgl', 'middleware/models/', '1');
        
        $this->view->gridDtl = self::glGridRender();
        
        $this->view->isAjax = is_ajax_request();
        $this->view->isPopup = false;

        if (!$this->view->isAjax) {
            $this->view->render('header');
        }

        if ($dvConfigRow['COUNT_REPORT_TEMPLATE'] != '0' && isset($this->view->paramList['generalledgerbookdtls'])) {

            $this->view->isPrint = true;

            $this->load->model('mdgl', 'middleware/models/');
            $printRowJson = $this->model->printTemplateRowGL($id, $this->view->paramList['generalledgerbookdtls']);
            $this->view->printRowJson = $printRowJson['mainGlRow'];

        } else {
            $this->view->isPrint = false;
        }

        if ($this->view->isglcopy) {
            
            $param = [
                'objectId' => '40002',
                'bookTypeId' => '2', 
                'bookDate' => Date::formatter($this->view->paramList['bookdate'], 'Y-m-d')
            ];
            $ins = &getInstance();
            $ins->load->model('mdcommon', 'middleware/models/');
            $getAutoNum = $ins->model->getAutoNumberModel($param);
            $this->view->paramList['booknumber'] = $getAutoNum['result'];
        }        
        
        if (Input::postCheck('dialogMode')) {
            
            $this->view->isPopup = true;
            $this->view->editForm = $this->view->renderPrint('main/sub/editGl', $this->viewPath);
            
            $response = array(
                'html' => $this->view->renderPrint('main/editEntry', $this->viewPath),
                'title' => $this->view->title, 
                'metaType' => 'edit_gl', 
                'uniqId' => $this->view->uniqId, 
                'isPrint' => $this->view->isPrint, 
                'glMainDvId' => self::$glMainDvId, 
                'helpContentId' => Config::getFromCache('FIN_GL_ENTRY_HELP_CONTENT'), 
                'save_btn' => $this->lang->line('save_btn'), 
                'close_btn' => $this->lang->line('close_btn'), 
                'print_btn' => $this->lang->line('print_btn')
            );

            echo json_encode($response, JSON_UNESCAPED_UNICODE); exit;
            
        } else {
            
            $this->view->editForm = $this->view->renderPrint('main/sub/editGl', $this->viewPath);
            $this->view->render('main/editEntry', $this->viewPath);
        }

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function view_entry($id = '') {
        $this->load->model('mdgl', 'middleware/models/');
        
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = 'Журнал бичилт харах';
        self::glExtensions();

        $this->view->accountCode = 'vw_account';
        $this->view->messageCode = 'fin_message';
        $this->view->isEditMode = true;
        $this->view->inputMetaDataId = self::$glBookDtlGroupMetaDataId;
        $this->view->isFieldSet = (Input::postCheck('bpTabLength') ? Input::post('bpTabLength') : 1);
        $this->view->isShowGlBookNumber = true;

        if (Input::isEmpty('id') === false) {
            $id = Input::post('id');
        }

        $this->view->paramList = array();
        $this->view->uniqId = getUID();
        $this->view->errorGLMessage = '';
        $this->view->glBpMainWindowId = 'glTemplateSectionStatic_'.$this->view->uniqId;
        
        $entry = $this->model->getGlEntryModel($id);

        if ($entry['status'] == 'success') {
            
            $glBook = $entry['result'];

            $this->view->paramList = $glBook;
            
            if (isset($glBook['description'])) {
                $this->view->descriptionCode = $this->model->getDescriptionModel($glBook['description']); 
            } else {
                $this->view->descriptionCode = '';
            }
            
        } else {
            $this->view->errorGLMessage = $this->ws->getResponseMessage($entry);
        }
        
        $this->view->currencyList = $this->model->currencyListModel();
        
        $this->view->isNotButton = false;
        $this->view->isNotAddAccount = true;
        $this->view->isDataView = false;
        $this->view->dataViewId = null;
        $this->view->drillDownParams = array();
        
        if (Input::isEmpty('dataViewId') == false) {
            $this->view->isDataView = true;
            $this->view->dataViewId = Input::post('dataViewId');
        }        
        if (Input::isEmpty('drillDownParams') == false) {
            $this->view->drillDownParams = Input::post('drillDownParams');
        }
        
        $this->view->gridDtl = self::glGridRender();
        
        $this->view->isAjax = is_ajax_request();
        $this->view->isPopup = false;

        if (!$this->view->isAjax) {
            $this->view->render('header');
        }

        $this->view->isPrint = false;
        
        if (Input::postCheck('dialogMode')) {
            
            $this->view->isPopup = true;
            $this->view->editForm = $this->view->renderPrint('main/sub/editGl', $this->viewPath);
            
            $response = array(
                'html' => $this->view->renderPrint('main/editEntry', $this->viewPath),
                'title' => 'Журнал бичилт', 
                'metaType' => 'view_gl',  
                'uniqId' => $this->view->uniqId,  
                'isPrint' => $this->view->isPrint, 
                'uniqId' => $this->view->uniqId, 
                'glMainDvId' => self::$glMainDvId, 
                'save_btn' => $this->lang->line('save_btn'), 
                'close_btn' => $this->lang->line('close_btn'), 
                'print_btn' => $this->lang->line('print_btn')
            );

            echo json_encode($response); exit;
            
        } else {
            
            $this->view->editForm = $this->view->renderPrint('main/sub/editGl', $this->viewPath);
            $this->view->render('main/editEntry', $this->viewPath);
        }

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function createGlEntry() {
        $result = $this->model->createGlEntryModel();
        echo json_encode($result); exit();
    }

    public function updateGlEntry() {
        $result = $this->model->updateGlEntryModel();
        echo json_encode($result); exit();
    }
    
    public function deleteGlEntry() {
        $data = $this->model->deleteGlEntryModel();
        echo json_encode($data); exit();
    }

    public function deleteGlEntryWithBook() {
        $data = $this->model->deleteGlEntryWithBookModel();
        echo json_encode($data); exit();
    }
    
    public function glFileAttach($uniqId, $refStructureId, $sourceId) {
        
        $this->load->model('mdmetadata', 'middleware/models/');
        
        $this->view->callbackFnc = '';
        $this->view->uniqId      = $uniqId;
        $this->view->metaDataId  = $refStructureId;
        $this->view->metaValueId = $sourceId;
        $this->view->actionType  = 'update';
        
        $this->view->metaValueFileRows = $this->model->getMetaDataValueFilesModel($this->view->metaDataId, $this->view->metaValueId);
        $this->view->metaValueFileCount = count($this->view->metaValueFileRows);
        
        $fileContent = $this->view->renderPrint('addon/viewFile', 'middleware/views/webservice/');
        
        $this->load->model('mdgl', 'middleware/models/');
        
        return $fileContent;
    }
    
    public function saveBillRate() {
        $result = $this->model->saveBillRateModel();
        echo json_encode($result); exit();
    }
    
    public function glGridRender() {
        
        if (Config::getFromCache('IS_GL_RATE_DISABLED')) {
            $this->view->isGlRateDisabled = true;
        }
        
        $this->view->amountScale  = Mdgl::getAmountScale();
        $this->view->incomeTaxDeduction = Config::getFromCache('FIN_INCOMETAX_DEDUCTION');
        
        $this->view->header1      = $this->view->renderPrint('main/glGridHeader1', $this->viewPath);
        $this->view->header2      = $this->view->renderPrint('main/glGridHeader2', $this->viewPath);
        $this->view->gridBodyData = $this->view->renderPrint('main/gridBodyData', $this->viewPath);
        $gridDtl                  = $this->view->renderPrint('main/glGridForProcess', $this->viewPath); 
        
        return $gridDtl;
    }

    public static function visibleReplacerForGl($selectedRow, $groupConfig, $editMode, $k = 0) {
        
        $accountId = $selectedRow['accountid'];
        $controlName = 'accountMeta['.$k.']['.$accountId.']['.strtolower($groupConfig['PARAM_REAL_PATH']).']'; 
        
        $metaControllers = Mdwebservice::renderParamControl(Mdgl::$glBookDtlGroupProcessId, $groupConfig, $controlName, '', $editMode);
        
        if (isset($groupConfig['VALUE_CRITERIA'])) {
            $valueCriteria = str_replace(',', '&id[]=', Mdmetadata::setDefaultValue($groupConfig['VALUE_CRITERIA']));
            $metaControllers = str_replace(array('class="popupInit"', '<select'), array('class="popupInit" data-criteria="id[]='.$valueCriteria.'"', '<select data-criteria="id[]='.$valueCriteria.'"'), $metaControllers); 
        } elseif (isset($groupConfig['LOOKUP_CRITERIA'])) {
            $metaControllers = str_replace(array('class="popupInit"', '<select'), array('class="popupInit" data-criteria="'.$groupConfig['LOOKUP_CRITERIA'].'"', '<select data-criteria="'.$groupConfig['LOOKUP_CRITERIA'].'"'), $metaControllers); 
        }
        
        $metaHtmlArr = array(
            'path' => $groupConfig['PARAM_REAL_PATH'],
            'label' => Lang::line($groupConfig['LABEL_NAME']),
            'isRequired' => $groupConfig['IS_REQUIRED'],
            'input' => $metaControllers, 
            'isHidden' => $groupConfig['IS_SHOW'], 
            'segmentId' => $groupConfig['SEGMENT_ID'], 
            'separatorChar' => $groupConfig['SEPRATOR_CHAR'], 
            'replaceValue' => $groupConfig['REPLACE_VALUE'], 
            'defaultValue' => $groupConfig['DEFAULT_VALUE'], 
            'accountFilter' => issetParam($groupConfig['ACCOUNT_FILTER']), 
            'rowIndex' => $k, 
            'accountId' => $accountId
        );

        return $metaHtmlArr;
    }

// <editor-fold defaultstate="collapsed" desc="hanshiin tegshitgel">
    public function cashrate() {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->title = 'Ханшийн тэгшитгэл';
        self::glExtensions();

        $this->view->depInfo = $this->model->getDepartmentInfoByIdModel();
        $this->view->currencyList = $this->model->currencyListModel();
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->keeperData = $this->model->getMetaDataIdByCodeModel(self::$cashRateAccountWithKeeperDataView);
        
        $this->view->isAjax = is_ajax_request();
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('currencyrate/cashrate', $this->viewPath);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function saveCashRate() {
        
        $check = $this->model->checkCalculateRateModel();
        
        if ($check['status'] != 'success') {
            echo json_encode($check); exit;
        }
        
        $result = $this->model->createGlEntryModel();
        echo json_encode($result); exit;
    }
    
    public function billRate() {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        self::glExtensions();
        
        $this->view->title = 'Харилцагчийн тооцооны тэгшитгэл';
        $this->view->depInfo = $this->model->getDepartmentInfoByIdModel();
        $this->view->currencyList = $this->model->currencyListModel();
        
        $this->view->isAjax = true;
                
        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('header');
        }
        
        $this->view->currencyId = null;
        $this->view->customerId = null;
        $this->view->customerCode = null;
        $this->view->customerName = null;
        $this->view->accountId = null;
        $this->view->accountCode = null;
        $this->view->accountName = null;
        $this->view->startDate = Ue::sessionFiscalPeriodStartDate();
        $this->view->endDate = Ue::sessionFiscalPeriodEndDate();
        $this->view->isDataView = false;
        $this->view->dialogMode = Input::post('dialogMode');
        $this->view->isDefaultCalc = Config::getFromCache('FIN_REVAL_DEFAULT');
        $this->view->metaDataId = Input::postCheck('dataViewId') ? Input::post('dataViewId') : '';
        
        if (Input::isEmpty('filterstartdate') === false) {
            $this->view->startDate = Input::post('filterstartdate');
        }
        
        if (Input::isEmpty('filterenddate') === false) {
            $this->view->endDate = Input::post('filterenddate');
        }
        
        if (Input::postCheck('dataViewId')) {
            
            if (Input::isEmpty('currencycode') == false) {
                $this->view->currencyId = $this->model->getCurrencyIdByCodeModel(Input::post('currencycode'));
            }
            
            if (Input::isEmpty('customerid') == false) {
                $customerId = Input::post('customerid');
                $customerRow = (new Mdobject())->getDataViewValueRowByMetaCode(Mdgl::$customerListDataViewCode, 'id', $customerId);
                
                if ($customerRow) {
                    $this->view->customerId = $customerRow['META_VALUE_ID'];
                    $this->view->customerCode = $customerRow['META_VALUE_CODE'];
                    $this->view->customerName = $customerRow['META_VALUE_NAME'];
                }
            }
            
            if (Input::isEmpty('accountid') == false) {
                $accountId = Input::post('accountid');
                $accountRow = (new Mdobject())->getDataViewValueRowByMetaCode('fin_account_list', 'id', $accountId);
                
                if ($accountRow) {
                    $this->view->accountId = $accountRow['META_VALUE_ID'];
                    $this->view->accountCode = $accountRow['META_VALUE_CODE'];
                    $this->view->accountName = $accountRow['META_VALUE_NAME'];
                }
            }
            
            if (Input::isEmpty('filterStartDate') == false) {
                $this->view->startDate = Input::post('filterStartDate');
                $this->view->endDate = Input::post('filterEndDate');
            }
            
            $this->view->isDataView = true;
            $this->view->booknumber = Input::post('booknumber');
        }                
        
        if ($this->view->dialogMode === 'popup') {
            $response = array(
                'dialogFormName' => '#saveBillRate-form',
                'dialogWindowId' => '#billRate-'.$this->view->metaDataId,
                'submitUrl' => 'mdgl/saveBillRate',
                'dialogWidth' => '1024',
                'mainId' => '',
                'Html' => $this->view->renderPrint('currencyrate/billRate', $this->viewPath),
                'Title' => $this->view->title,
                'save_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response); exit;
        } else {       
            $this->view->render('currencyrate/billRate', $this->viewPath);
        }
        
        if (!is_ajax_request()) {
            $this->view->render('footer');
        }
    }	
    
    public function billRate2(){
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        self::glExtensions();
        $this->view->currencyList = $this->model->currencyListModel();
        $this->view->title = 'Харилцагчийн тооцооны тэгшитгэл';
        
        $this->view->isAjax = true;
                
        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('header');
        }
        
        $this->view->currencyId = null;
        $this->view->customerId = null;
        $this->view->customerCode = null;
        $this->view->customerName = null;
        $this->view->accountId = null;
        $this->view->accountCode = null;
        $this->view->accountName = null;
        $this->view->startDate = Ue::sessionFiscalPeriodStartDate();
        $this->view->endDate = Ue::sessionFiscalPeriodEndDate();
        $this->view->isDataView = false;
        $this->view->dialogMode = Input::post('dialogMode');
        $this->view->isDefaultCalc = Config::getFromCache('FIN_REVAL_DEFAULT');
        $this->view->metaDataId = Input::postCheck('dataViewId') ? Input::post('dataViewId') : '';
        
        if (Input::isEmpty('filterstartdate') === false)
            $this->view->startDate = Input::post('filterstartdate');
        if (Input::isEmpty('filterenddate') === false)
            $this->view->endDate = Input::post('filterenddate');
        
        if (Input::postCheck('dataViewId')) {
            
            if (Input::isEmpty('currencycode') == false) {
                $this->view->currencyId = $this->model->getCurrencyIdByCodeModel(Input::post('currencycode'));
            }
            
            if (Input::isEmpty('customerid') == false) {
                $customerId = Input::post('customerid');
                $customerRow = (new Mdobject())->getDataViewValueRowByMetaCode(Mdgl::$customerListDataViewCode, 'id', $customerId);
                
                if ($customerRow) {
                    $this->view->customerId = $customerRow['META_VALUE_ID'];
                    $this->view->customerCode = $customerRow['META_VALUE_CODE'];
                    $this->view->customerName = $customerRow['META_VALUE_NAME'];
                }
            }
            
            if (Input::isEmpty('accountid') == false) {
                $accountId = Input::post('accountid');
                $accountRow = (new Mdobject())->getDataViewValueRowByMetaCode('fin_account_list', 'id', $accountId);
                
                if ($accountRow) {
                    $this->view->accountId = $accountRow['META_VALUE_ID'];
                    $this->view->accountCode = $accountRow['META_VALUE_CODE'];
                    $this->view->accountName = $accountRow['META_VALUE_NAME'];
                }
            }
            
            if (Input::isEmpty('filterStartDate') == false) {
                $this->view->startDate = Input::post('filterStartDate');
                $this->view->endDate = Input::post('filterEndDate');
            }
            
            $this->view->isDataView = true;
            $this->view->booknumber = Input::post('booknumber');
        }                
        
        if ($this->view->dialogMode === 'popup') {
            $response = array(
                'dialogFormName' => '#saveBillRate-form',
                'dialogWindowId' => '#billRate-'.$this->view->metaDataId,
                'submitUrl' => 'mdgl/saveBillRate',
                'dialogWidth' => '1024',
                'mainId' => '',
                'Html' => $this->view->renderPrint('currencyrate/billRate2', $this->viewPath),
                'Title' => $this->view->title,
                'save_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response); exit;
        } else        
            $this->view->render('currencyrate/billRate2', $this->viewPath);
        
        if (!is_ajax_request()) {
            $this->view->render('footer');
        }
    }	
    
    public function getOneCurrency() {
        $result = $this->model->getOneCurrencyRateModel(Input::post('currencyId'), Input::post('rateDate'));
        echo json_encode($result); exit;
    }

    public function getCurrencyRatedRow() {
        
        $selectedRow = $_POST['ratedRow'];
        $requestType = Input::post('type');
        $bookDate = Input::postCheck('bookdate') ? Input::post('bookdate') : Date::currentDate('Y-m-d');
        
        $param = array(
            'bookDate' => $bookDate,
            'bookTypeId' => $requestType === 'billRate' ? '22' : '5',
            'description' => 'Ханшийн тэгшитгэл',
            'objectId' => '20000',
            'exchangeDtls' => $selectedRow
        );

        $result = $this->model->getTemplateModel($param);

        if ($result['status'] == 'success') {

            $this->view->isFieldSet = (Input::postCheck('bpTabLength') ? Input::post('bpTabLength') : 1);
            $this->view->paramList = $result['data'];
            $this->view->glBpMainWindowId = 'glTemplateSectionStatic';
            $this->view->uniqId = getUID();
            $this->view->isPopup = false;
            
            $this->view->currencyList = $this->model->currencyListModel();
            
            $this->view->isDataView = false;
            $this->view->dataViewId = null;

            if (Input::isEmpty('dataViewId') == false) {
                $this->view->isDataView = true;
                $this->view->dataViewId = Input::post('dataViewId');
            }    
            
            if (Input::postCheck('isNotAddAccount')) {
                $this->view->isNotAddAccount = true;
            }
            
            if (Input::postCheck('isCashFlowSubCategoryId')) {
                $this->view->isCashFlowSubCategoryId = true;
            }
            
            $glhtml = self::glGridRender();
            
            $response = array(
                'Html' => $glhtml,
                'status' => $result['status']
            );
        } else {
            $response = array(
                'status' => $result['status'],
                'message' => $result['message']
            );
        }
        echo json_encode($response); exit;
    }
    
    public function cashSorting(){
        $this->load->model('mdobject', 'middleware/models/');
        $result = $this->model->dataViewDataGridModel(false);
        /*if (isset($result['rows'])) {
            usort($result['rows'], self::build_sorter('accountcode'));
        }*/
        echo json_encode($result); exit;
    }
    
    public function build_sorter($key) {
        return function ($a, $b) use ($key) {
            return strnatcmp($a[$key], $b[$key]);
        };
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="haaltiin guilgee">
    public function filterAccountCode() {
        $accountCode = Input::post('accountCode');
        $response = $this->model->filterAccountCodeModel($accountCode);
        
        header('Content-Type: application/json');
        echo json_encode($response); exit;
    }
    
    public function filterDepartmentCode() {
        $department = Input::post('department');
        $response = $this->model->filterDepartmentCodeModel($department);
        
        header('Content-Type: application/json');
        echo json_encode($response); exit;
    }

    public function clearingtrans() {

        $this->view->title = 'Хаалтын гүйлгээ';

        $this->view->css = array(
            'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css',
            'custom/addon/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css',
            'custom/addon/plugins/jquery-easyui/themes/metro/easyui.css',
            'custom/addon/plugins/jstree/dist/themes/default/style.min.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/jquery-easyui/jquery.easyui.min.js',
            'custom/addon/plugins/jquery-easyui/locale/easyui-lang-' . $this->lang->getCode() . '.js',
            'custom/addon/plugins/datatables/media/js/jquery.dataTables.min.js',
            'custom/addon/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js',
            'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js'
        );
        $this->view->fullUrlJs = array(
            'middleware/assets/js/mdgl.js',
            'middleware/assets/js/mdmetadata.js', 
            'middleware/assets/js/mdbp.js',
            'middleware/assets/js/mdexpression.js', 
        );

        $this->view->fillPath = $this->model->defaultFillPathModel();

        $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
        $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();  
        
        $accountId1 = Config::get('incomeOutcomeAccountDefaultId', 'departmentId='.$departmentId.';');
        $accountId2 = Config::get('extAccountDefaultId', 'departmentId='.$departmentId.';');
        
        $this->view->defaultAccountId1 = array();
        
        $param = array(
            'systemMetaGroupId' => '1459138813444931',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $accountId1
                    )
                )
            )             
        );
        
        $defaultAccount1 = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        
        if ($defaultAccount1['status'] === 'success' && isset($defaultAccount1['result'][0])) {
            if ($this->view->fillPath && array_key_exists(1, $defaultAccount1['result'])) {
                $param = array(
                    'systemMetaGroupId' => '1459138813444931',
                    'showQuery' => 0, 
                    'ignorePermission' => 1,
                    'criteria' => array(
                        'departmentid' => array(
                            array(
                                'operator' => '=',
                                'operand' => $this->view->fillPath['filterdepartmentid']
                            )
                        )
                    )             
                );
                
                $defaultAccount11 = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);                
                
                if ($defaultAccount11['result'] && array_key_exists(0, $defaultAccount11['result'])) {
                    $this->view->defaultAccountId1['id'] = $defaultAccount11['result'][0]['id'];
                    $this->view->defaultAccountId1['code'] = $defaultAccount11['result'][0]['accountcode'];
                    $this->view->defaultAccountId1['name'] = $defaultAccount11['result'][0]['accountname'];
                } else {
                    $this->view->defaultAccountId1['id'] = $defaultAccount1['result'][0]['id'];
                    $this->view->defaultAccountId1['code'] = $defaultAccount1['result'][0]['accountcode'];
                    $this->view->defaultAccountId1['name'] = $defaultAccount1['result'][0]['accountname'];                    
                }
            } else {
                $this->view->defaultAccountId1['id'] = $defaultAccount1['result'][0]['id'];
                $this->view->defaultAccountId1['code'] = $defaultAccount1['result'][0]['accountcode'];
                $this->view->defaultAccountId1['name'] = $defaultAccount1['result'][0]['accountname'];
            }
        }        
        
        $this->view->defaultAccountId2 = array();
        
        $param = array(
            'systemMetaGroupId' => '1479113500351',
            'showQuery' => 0, 
            'ignorePermission' => 1,
            'criteria' => array(
                'id' => array(
                    array(
                        'operator' => '=',
                        'operand' => $accountId2
                    )
                )
            )             
        );
        
        $defaultAccount1 = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        
        if ($defaultAccount1['status'] === 'success' && $defaultAccount1['result']) {
            
            if ($this->view->fillPath && array_key_exists(1, $defaultAccount1['result'])) {
                $param = array(
                    'systemMetaGroupId' => '1479113500351',
                    'showQuery' => 0, 
                    'ignorePermission' => 1,
                    'criteria' => array(
                        'departmentid' => array(
                            array(
                                'operator' => '=',
                                'operand' => $this->view->fillPath['filterdepartmentid']
                            )
                        )
                    )             
                );
                
                $defaultAccount11 = $this->ws->runResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);                
                
                if ($defaultAccount11['result'] && array_key_exists(0, $defaultAccount11['result'])) {
                    $this->view->defaultAccountId2['id'] = $defaultAccount11['result'][0]['id'];
                    $this->view->defaultAccountId2['code'] = $defaultAccount11['result'][0]['accountcode'];
                    $this->view->defaultAccountId2['name'] = $defaultAccount11['result'][0]['accountname'];
                } else {
                    $this->view->defaultAccountId2['id'] = $defaultAccount1['result'][0]['id'];
                    $this->view->defaultAccountId2['code'] = $defaultAccount1['result'][0]['accountcode'];
                    $this->view->defaultAccountId2['name'] = $defaultAccount1['result'][0]['accountname'];                    
                }
            } else {            
                $this->view->defaultAccountId2['id'] = $defaultAccount1['result'][0]['id'];
                $this->view->defaultAccountId2['code'] = $defaultAccount1['result'][0]['accountcode'];
                $this->view->defaultAccountId2['name'] = $defaultAccount1['result'][0]['accountname'];
            }
        }
        
        $this->view->isAjax = is_ajax_request();
                
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('clearingtrans/index', $this->viewPath);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }

    public function clearingTransList() {
        $result = $this->model->newClearingTransListModel();
        echo json_encode($result); exit;
    }

    public function saveClearingTrans() {
        $result = $this->model->saveClearingTransModel();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

// </editor-fold>

    public function getAutoNumber() {
        $data = $this->model->getAutoNumberModel(Input::post('bookTypeId'));
        echo json_encode($data); exit;
    }

    public function filterAccountInfo() {
        $result = $this->model->filterAccountInfoModel();
        jsonResponse($result);
    }
    
    public function getRowAccountInfo() {
        $result = $this->model->getRowAccountInfoModel();
        jsonResponse($result);
    }
    
    public function getDescriptionInfo() {
        $result = $this->model->getDescriptionInfoModel();
        jsonResponse($result);
    }
    
    public function getCustomerInfo() {
        $result = $this->model->getCustomerInfoModel();
        jsonResponse($result);
    }
    
    public function getExpenseCenterInfo() {
        $result = $this->model->getExpenseCenterInfoModel();
        jsonResponse($result);
    }

    public function filterDescriptionInfo() {
        $result = $this->model->filterDescriptionInfoModel();
        jsonResponse($result);
    }

    public function glExtensions() {
        $this->view->css = array_unique(array_merge(
                array(
                    'custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css', 
                    'custom/addon/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css'), 
                AssetNew::metaCss()
            )
        );
        $this->view->js = array_unique(array_merge(
                array(
                    'custom/addon/plugins/datatables/media/js/jquery.dataTables.min.js', 
                    'custom/addon/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js'), 
                AssetNew::metaOtherJs()
            )
        );
        $this->view->fullUrlJs = array('middleware/assets/js/mdgl.js');
    }

    public function getTemplate() {

        $postData = Input::postData();
        
        if (!isset($postData['methodId'])) {
            $response = array('status' => 'error', 'text' => 'Undefined index: methodId');
            echo json_encode($response); exit;
        }
        
        $dimensionPaths = $this->model->getGLAllDimensionPaths();
        
        $this->load->model('mdwebservice', 'middleware/models/');
        
        $metaDataId = Input::param($postData['methodId']);
        $param = $fileParamData = $defaultValueDimensions = array();
        
        if (isset($postData['param'])) {
            
            $paramData = $postData['param'];
            $paramList = $this->model->groupParamsDataModel($metaDataId, null, ' AND PAL.PARENT_ID IS NULL');
            
            if (isset($postData['cacheId']) && $postData['cacheId'] != '') {
                
                $cacheId = Input::param($postData['cacheId']);
                
                if ($cacheArray = (new Mdcache())->getDetailFromCache($cacheId, $metaDataId, $paramData)) {
                    $isCache = true;
                }
            }
            
            foreach ($paramList as $input) {
                
                $typeCode = strtolower($input['META_TYPE_CODE']);
                
                if ($typeCode != 'group') {

                    if ($typeCode === 'boolean') {
                        
                        if (isset($paramData[$input['META_DATA_CODE']])) {
                            $param[$input['META_DATA_CODE']] = $paramData[$input['META_DATA_CODE']];
                        } else {
                            if ($input['IS_SHOW'] != '1' && !is_null($input['DEFAULT_VALUE'])) {
                                $param[$input['META_DATA_CODE']] = $input['DEFAULT_VALUE'];
                            } else {
                                $param[$input['META_DATA_CODE']] = '0';
                            }
                        }
                    } else {
                        if (isset($paramData[$input['META_DATA_CODE']])) {
                            $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType($paramData[$input['META_DATA_CODE']], $typeCode);
                        } else {
                            $param[$input['META_DATA_CODE']] = $this->ws->convertDeParamType(Mdmetadata::setDefaultValue($input['DEFAULT_VALUE']), $typeCode);
                        }
                    }
                    
                    if (isset($dimensionPaths[$input['LOWER_PARAM_REAL_PATH']]) && $param[$input['META_DATA_CODE']] != '') {
                        $defaultValueDimensions[$input['LOWER_PARAM_REAL_PATH']] = $param[$input['META_DATA_CODE']];
                    }
                    
                } elseif ($input['IS_SHOW'] === '1') {
                    
                    if (isset($isCache) && isset($cacheArray[strtolower($input['META_DATA_CODE'])])) {

                        $param[$input['META_DATA_CODE']] = $cacheArray[strtolower($input['META_DATA_CODE'])];

                    } else {
                        $param[$input['META_DATA_CODE']] = (new Mdwebservice())->fromPostGenerateArray(
                            $metaDataId, $input['ID'], $input['META_DATA_CODE'], $input['RECORD_TYPE'], $paramData, $fileParamData, 0, 0, $postData
                        );
                    }
                }
            }
        }
        
        $ins = &getInstance();
        $ins->load->model('mdgl', 'middleware/models/');
        $result = $ins->model->getTemplateModel($param);
        
        if ($result['status'] == 'success') {
            
            $this->view->paramList = $result['data'];
            $this->view->isFieldSet = (isset($postData['bpTabLength']) ? $postData['bpTabLength'] : 1);
            $this->view->isPopup = false;
            
            $this->view->uniqId = Input::isEmpty('uniqId') == false ? Input::post('uniqId') : getUID();
            $this->view->glBpMainWindowId = Input::post('glBpMainWindowIdProcess') === null ? 'glTemplateSectionStatic_'.$this->view->uniqId : 'glTemplateSectionProcess_'.$this->view->uniqId;
            
            $this->view->currencyList = $ins->model->currencyListModel();
            $this->view->defaultValueDimensions = $defaultValueDimensions;
            
            $this->view->isDataView = false;
            $this->view->dataViewId = null;
            
            if (isset($param['oppRate']) && isset($param['accountId']) && isset($param['oppCurrencyId']) && isset($param['currencyCode'])) {
                $this->view->oppRate = $param['oppRate'];
                $this->view->oppAccountId = $param['accountId'];
                $this->view->oppCurrencyId = $param['oppCurrencyId'];
                $this->view->oppCurrencyCode = strtolower($param['currencyCode']);
            }
            
            if (isset($this->view->paramList['templateid']) && $this->view->paramList['templateid'] != '') {
                
                $this->view->glTemplateExpression = (new Mdexpression())->glTemplateExpression($this->view->paramList['templateid'], $this->view->uniqId);

                $this->load->model('mdgl', 'middleware/models/');
            }
            
            if (isset($this->view->paramList['templates']) && count($this->view->paramList['templates']) > 1) {
                
                $this->view->templates = $this->view->paramList['templates'];
                
                array_walk($this->view->templates, function(&$value, $index) { 
                    $value['name'] = ++$index . ' ' . $value['name'];
                }); 
            }
            
            $glhtml = self::glGridRender(); 
 
            $response = array('Html' => $glhtml, 'status' => $result['status']);
            
        } else {
            $response = array('status' => $result['status'], 'text' => $result['message']);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function getTemplateByDesc() {

        $param = array(
            'messageId' => Input::post('descriptionId')
        );
        
        if (Input::isEmpty('bookDate') == false) {
            $param['bookDate'] = Input::post('bookDate');
        }

        $result = $this->model->getTemplateModel($param);

        if ($result['status'] == 'success') {
            
            $this->view->paramList = $result['data'];
            
            if (isset($this->view->paramList['generalledgerbookdtls']) && $this->view->paramList['generalledgerbookdtls']) {
                
                $this->view->uniqId = Input::post('uniqId');
                $this->view->currencyList = $this->model->currencyListModel();
                $this->view->amountScale = Mdgl::getAmountScale();
                
                $expressionScript = '';
                
                if (isset($this->view->paramList['templateid']) && $this->view->paramList['templateid'] != '') {
                
                    $this->view->glTemplateExpression = (new Mdexpression())->glTemplateExpression($this->view->paramList['templateid'], $this->view->uniqId);
                    $expressionScript = $this->view->renderPrint('main/sub/expressionScript', $this->viewPath); 
                    
                    $this->load->model('mdgl', 'middleware/models/');
                }

                $response = array(
                    'status' => 'success', 
                    'html' => $this->view->renderPrint('main/gridBodyData', $this->viewPath), 
                    'expression' => $expressionScript
                );
                
            } else {
                $response = array('status' => 'info', 'text' => 'empty');
            }
            
        } else {
            $response = array('status' => $result['status'], 'text' => $result['message']);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function getTemplateByEditMode() {
        $result = $this->model->getTemplateByEditModeModel();

        if ($result['status'] == 'success') {
            $glhtml = '';

            if (isset($result['result']) && !empty($result['result'])) {

                $this->view->uniqId = getUID();
                $this->view->isFieldSet = Input::post('bpTabLength', 1);
                $this->view->paramList = $result['result'];
                $this->view->isPopup = false;
                $this->view->glBpMainWindowId = Input::post('glBpMainWindowIdProcess') === null ? 'glTemplateSectionStatic_'.$this->view->uniqId : 'glTemplateSectionProcess_'.$this->view->uniqId;
                
                $this->view->currencyList = $this->model->currencyListModel();
                
                $this->view->isDataView = false;
                $this->view->dataViewId = null;

                if (Input::isEmpty('dataViewId') == false) {
                    $this->view->isDataView = true;
                    $this->view->dataViewId = Input::post('dataViewId');
                }   
                
                $glhtml = self::glGridRender();
            }
            
            $response = array('Html' => $glhtml, 'status' => $result['status']);
            
        } else {
            
            $message = $this->ws->getResponseMessage($result);
            $response = array('status' => $result['status'], 'text' => $message);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

// <editor-fold defaultstate="collapsed" desc="autoCompleteByCode">

    public function autoCompleteByCustomerCode() {
        $result = $this->model->autoCompleteByCustomerCodeModel();
        echo json_encode($result); exit;
    }

    public function autoCompleteByExpenseCode() {
        $result = $this->model->autoCompleteByExpenseCodeModel();
        echo json_encode($result); exit;
    }
    
    public function getAccountRowById() {
        $result = $this->model->getAccountRowByIdModel();
        echo json_encode($result); exit;
    }

// </editor-fold>

    public function getRate() {
        $result = $this->model->getRateForAccountModel();
        echo json_encode($result); exit();
    }

    public function getRate2() {
        $result = $this->model->getRate2ForAccountModel();
        echo json_encode($result); exit();
    }
    
    public function getRateByCurrencyId() {
        $result = $this->model->getRateByCurrencyIdModel();
        echo json_encode($result); exit();
    }

    public function getAccountDtlMeta() {
        
        $haveMeta = false;
        $isFindMeta = true;
        $selectedRow = $_POST['selectedRow'];
        $paramData = isset($_POST['paramData']) ? $_POST['paramData'] : array();
        $isUseOppAccount = $expenseCenterControl = '';
        $isDebitCreditDefaultValue = '0';
        $data = [];
        
        if (!isset($selectedRow['checkAccountTypeId']) 
            && isset($paramData['booktypeid']) 
            && ($paramData['booktypeid'] == '42' || $paramData['booktypeid'] == '43' || $paramData['booktypeid'] == '44') 
            && ($selectedRow['objectid'] == '20003' || $selectedRow['objectid'] == '20004')) {
            $isFindMeta = false;
        }
        
        $this->view->selectedRow = $selectedRow;
        $this->view->inputMetaDataId = self::$glBookDtlGroupMetaDataId;
        $this->view->uniqId = getUID();
        
        if ($isFindMeta) {
            
            $isOpMeta = isset($this->view->selectedRow['opMeta']) ? $this->view->selectedRow['opMeta'] : null;
            
            $this->load->model('mdgl', 'middleware/models/'); 
            $data = $this->model->getMetaByAccountTypeModel($selectedRow, $isOpMeta);

            if ($data) {
                
                if ($isOpMeta == 'cashFlowSubCategoryId') {
                    $isNotOpMeta = true;
                }
                
                if ($isOpMeta && !isset($isNotOpMeta)) {
                    if ($this->view->selectedRow['isdebit'] == '1') {
                        
                        $this->view->selectedRow['isdebit'] = '0';
                        $this->view->cashFlowCreditData = self::getCashMetaValuesToGrid('0');
                        
                    } elseif ($this->view->selectedRow['isdebit'] == '') {
                        
                        $this->view->cashFlowCreditData = self::getCashMetaValuesToGrid('all');
                        
                    } else {
                        $this->view->selectedRow['isdebit'] = '1';
                        $this->view->cashFlowDebitData = self::getCashMetaValuesToGrid('1');
                    }
                    
                } else {
                    if ($this->view->selectedRow['isdebit'] == '1') {
                        $this->view->cashFlowDebitData = self::getCashMetaValuesToGrid('1');
                    } elseif ($this->view->selectedRow['isdebit'] == '') {
                        $this->view->cashFlowCreditData = self::getCashMetaValuesToGrid('all');
                    } else {
                        $this->view->cashFlowCreditData = self::getCashMetaValuesToGrid('0');
                    }
                }
                
                $this->view->detailvalues = isset($paramData['detailvalues']) ? $paramData['detailvalues'] : (isset($selectedRow['detailvalues']) ? json_decode(html_entity_decode($selectedRow['detailvalues'], ENT_QUOTES, 'UTF-8'), true) : array());
                $defaultInvoiceData = isset($paramData['defaultinvoices']) ? $paramData['defaultinvoices'] : (isset($selectedRow['defaultinvoices']) ? json_decode(html_entity_decode($selectedRow['defaultinvoices'], ENT_QUOTES, 'UTF-8'), true) : array());
                
                if ($defaultInvoiceData && array_key_exists('receivableBookDtls', $defaultInvoiceData) && array_key_exists('receivableTypeId', $defaultInvoiceData['receivableBookDtls'][0])) {
                    $this->view->detailvalues['receivabletypeid'] = $defaultInvoiceData['receivableBookDtls'][0]['receivableTypeId'];
                }

                $editValue = array();

                if (!empty($this->view->detailvalues)) {
                    $editValue = $this->view->detailvalues;
                }
            
                $metaHtmlArr = array();

                if (Config::getFromCache('CONFIG_GL_ROW_EXPENSE_CENTER')) {

                    foreach ($data as $value) {
                        
                        if ($value['IS_USE_OPP_ACCOUNT'] == '1' && !isset($isNotOpMeta)) {
                            
                            $isUseOppAccount = $this->view->selectedRow['accountid'].'|'.$value['PARAM_REAL_PATH'];
                            
                        } else {
                            
                            if ($value['DEBIT_DEFAULT_VALUE'] && $selectedRow['debitamount']) {
                                $value['DEFAULT_VALUE'] = $value['DEBIT_DEFAULT_VALUE'];
                            } elseif ($value['CREDIT_DEFAULT_VALUE'] && $selectedRow['creditamount']) {
                                $value['DEFAULT_VALUE'] = $value['DEBIT_DEFAULT_VALUE'];
                            }       
                            
                            $metaForm = self::visibleReplacerForGl($selectedRow, $value, $editValue);

                            if (strtolower($value['PARAM_REAL_PATH']) == self::$expenseCenterMetaDataCode) {
                                if ($expenseCenterControl == '') {
                                    $expenseCenterControl = $metaForm['input'];
                                }
                            } elseif (!empty($metaForm)) {
                                array_push($metaHtmlArr, $metaForm);
                                $haveMeta = true;
                                
                                if ($value['DEBIT_DEFAULT_VALUE'] || $value['CREDIT_DEFAULT_VALUE']) {
                                    $isDebitCreditDefaultValue = '1';
                                }                                
                            }
                        }
                    }

                } else {
                    
                    foreach ($data as $value) {                  
                        
                        if ($value['IS_USE_OPP_ACCOUNT'] == '1') { //&& !isset($isNotOpMeta)
                            
                            $isUseOppAccount = $this->view->selectedRow['accountid'].'|'.$value['PARAM_REAL_PATH'];
                            
                        } else {
                            
                            if ($value['DEBIT_DEFAULT_VALUE'] && $selectedRow['debitamount']) {
                                $value['DEFAULT_VALUE'] = $value['DEBIT_DEFAULT_VALUE'];
                            } elseif ($value['CREDIT_DEFAULT_VALUE'] && $selectedRow['creditamount']) {
                                $value['DEFAULT_VALUE'] = $value['CREDIT_DEFAULT_VALUE'];
                            }                            
                            
                            $metaForm = self::visibleReplacerForGl($selectedRow, $value, $editValue);

                            if (!empty($metaForm)) {
                                array_push($metaHtmlArr, $metaForm);
                                $haveMeta = true;                                                        
                                if ($value['DEBIT_DEFAULT_VALUE'] || $value['CREDIT_DEFAULT_VALUE']) {
                                    $isDebitCreditDefaultValue = '1';
                                }                                
                            }
                        }
                    }
                }

                if ($haveMeta) {
                    $this->view->metaRows = array_filter($metaHtmlArr);
                    
                    $this->load->model('mdgl', 'middleware/models/');
                    
                    if (!Input::postCheck('isIgnoreExp') && $accountFullExp = $this->model->getAccountFullExpressionModel($this->view->selectedRow['accountid'])) {
                        Mdexpression::$isFromMetaGroup = true;
                        Mdexpression::$setMainSelector = '$row_'.$this->view->uniqId;
                        $this->view->accountFullScripts = (new Mdexpression())->fullExpressionConvertEvent($accountFullExp, Mdgl::$glBookDtlGroupMetaDataId);
                    }
                }
            }
        }
        
        $isEmptymeta = false;
        
        if (!isset($this->view->metaRows)) {
            if ($selectedRow['usedetail'] == '0' || $selectedRow['usedetail'] == 'false') {
                $isEmptymeta = true;
            } else {
                if (issetParam($selectedRow['booktypeid']) == '5' && $selectedRow['defaultinvoices'] != '') {
                   $isEmptymeta = true;  
                } elseif (substr($selectedRow['invoices'], 0, 3) == 'vid') {
                   $isEmptymeta = true;  
                }
            }
        }
        
        $response = array(
            'isemptymeta' => $isEmptymeta,
            'isDebitCreditDefaultValue' => $isDebitCreditDefaultValue,
            'html' => $this->view->renderPrint('main/glExpanded', $this->viewPath),
            'title' => 'Дэлгэрэнгүй',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn'),
            'uniqId' => $this->view->uniqId, 
            'expenseCenterControl' => $expenseCenterControl, 
            'accountData' => $data
        );
        echo json_encode($response); exit;
    }
    
    public function getAccountMeta() {
        
        $haveMeta = false;
        $isFindMeta = true;
        $selectedRow = Input::post('selectedRow');
        $paramData = isset($_POST['paramData']) ? $_POST['paramData'] : array();
        $isUseOppAccount = $expenseCenterControl = '';
        
        if (!isset($selectedRow['checkAccountTypeId']) && isset($paramData['booktypeid']) && ($paramData['booktypeid'] == '42' || $paramData['booktypeid'] == '43' || $paramData['booktypeid'] == '44')) {
            $isFindMeta = false;
        }
        
        $this->view->selectedRow = $selectedRow;
        $this->view->inputMetaDataId = self::$glBookDtlGroupMetaDataId;
        $this->view->uniqId = getUID();
        
        if ($isFindMeta) {
            
            $isOpMeta = isset($this->view->selectedRow['opMeta']) ? $this->view->selectedRow['opMeta'] : null;
            
            $this->load->model('mdgl', 'middleware/models/'); 
            $data = $this->model->getMetaByAccountTypeModel($selectedRow, $isOpMeta);

            if ($data) {
                
                if ($isOpMeta == 'cashFlowSubCategoryId') {
                    $isNotOpMeta = true;
                }
                
                if ($isOpMeta && !isset($isNotOpMeta)) {
                    if ($this->view->selectedRow['isdebit'] == '1') {
                        
                        $this->view->selectedRow['isdebit'] = '0';
                        $this->view->cashFlowCreditData = self::getCashMetaValuesToGrid('0');
                        
                    } elseif ($this->view->selectedRow['isdebit'] == '') {
                        
                        $this->view->cashFlowCreditData = self::getCashMetaValuesToGrid('all');
                        
                    } else {
                        $this->view->selectedRow['isdebit'] = '1';
                        $this->view->cashFlowDebitData = self::getCashMetaValuesToGrid('1');
                    }
                    
                } else {
                    if ($this->view->selectedRow['isdebit'] == '1') {
                        $this->view->cashFlowDebitData = self::getCashMetaValuesToGrid('1');
                    } elseif ($this->view->selectedRow['isdebit'] == '') {
                        $this->view->cashFlowCreditData = self::getCashMetaValuesToGrid('all');
                    } else {
                        $this->view->cashFlowCreditData = self::getCashMetaValuesToGrid('0');
                    }
                }
                
                $this->view->detailvalues = array();
                
                if (isset($paramData['detailvalues'])) {
                    
                    $this->view->detailvalues = $paramData['detailvalues'];
                    
                } elseif (isset($selectedRow['detailvalues']) && $selectedRow['detailvalues']) {
                    
                    $this->view->detailvalues = json_decode(html_entity_decode($selectedRow['detailvalues'], ENT_QUOTES, 'UTF-8'), true);
                }
                                
                $editValue = '';

                if (!empty($this->view->detailvalues)) {
                    $editValue = $this->view->detailvalues;
                } 
            
                $metaHtmlArr = array();

                if (Config::getFromCache('CONFIG_GL_ROW_EXPENSE_CENTER')) {

                    foreach ($data as $value) {
                        
                        if ($value['IS_USE_OPP_ACCOUNT'] == '1' && !isset($isNotOpMeta)) {
                            
                            $isUseOppAccount = $this->view->selectedRow['accountid'].'|'.$value['PARAM_REAL_PATH'];
                            
                        } else {
                            $metaForm = self::visibleReplacerForGl($selectedRow, $value, $editValue);

                            if (strtolower($value['PARAM_REAL_PATH']) == self::$expenseCenterMetaDataCode) {
                                $expenseCenterControl = $metaForm['input'];
                                
                            } elseif (!empty($metaForm)) {
                                array_push($metaHtmlArr, $metaForm);
                                $haveMeta = true;
                            }
                        }
                    }

                } else {

                    foreach ($data as $value) {
                        
                        if ($value['IS_USE_OPP_ACCOUNT'] == '1') {
                            
                            $isUseOppAccount = $this->view->selectedRow['accountid'].'|'.$value['PARAM_REAL_PATH'];
                            
                        } else {
                            $metaForm = self::visibleReplacerForGl($selectedRow, $value, $editValue);

                            if (!empty($metaForm)) {
                                array_push($metaHtmlArr, $metaForm);
                                $haveMeta = true;
                            }
                        }
                    }
                }

                if ($haveMeta) {
                    $this->view->metaRows = array_filter($metaHtmlArr);
                    
                    $this->load->model('mdgl', 'middleware/models/');
                    
                    if ($accountFullExp = $this->model->getAccountFullExpressionModel($this->view->selectedRow['accountid'])) {
                        Mdexpression::$isFromMetaGroup = true;
                        Mdexpression::$setMainSelector = '$row_'.$this->view->uniqId;
                        $this->view->accountFullScripts = (new Mdexpression())->fullExpressionConvertEvent($accountFullExp, Mdgl::$glBookDtlGroupMetaDataId);
                    }
                }
            }
        }
        
        $isEmptymeta = '1';
        
        if (isset($this->view->metaRows)) {
            $isEmptymeta = '0';
        }
        
        $response = array(
            'isemptymeta' => $isEmptymeta,
            'html' => $this->view->renderPrint('main/sub/glMeta', $this->viewPath),
            'title' => 'Дэлгэрэнгүй',
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn'),
            'uniqId' => $this->view->uniqId, 
            'expenseCenterControl' => $expenseCenterControl, 
            'isUseOppAccount' => $isUseOppAccount
        );
        echo json_encode($response); exit;
    }
    
    public function checkAccountRowBpMeta($row, $k) {
          
        $bookTypeId = $row['headerBookTypeId'];
        
        if ($row['accountid'] == '' || $bookTypeId == '42' || $bookTypeId == '43' || $bookTypeId == '44') {
            return array('isMeta' => false, 'isProcess' => false, 'expenseCenterControl' => '');
        }
            
        $result = $this->model->checkAccountRowBpMetaModel($row, $k);

        if (strpos($result['expenseCenterControl'], 'accountMeta[') !== false) {  
            $result['expenseCenterControl'] = str_replace('accountMeta[0]', 'accountMeta['.$k.']', $result['expenseCenterControl']);        
        } 
        
        return $result;
    }

    public function checkAccountBpLink() {
        $postData = Input::postData();
        $response = $this->model->checkAccountBpLinkModel($postData);
        echo json_encode($response); 
    }

// <editor-fold defaultstate="collapsed" desc="noat, mongon uzuulelt">
    public function getTaxMetaValuesToGrid($type, $isDebit) {
        $this->load->model('mdgl', 'middleware/models/');
        $data = $this->model->getTaxMetaValuesModel($type, $isDebit);
        return $data;
    }
    
    public function getCashMetaValuesToGridById($id, $type) {
        $this->load->model('mdgl', 'middleware/models/');
        $data = $this->model->getCashMetaValuesByIdModel($id, $type);
        return $data;
    }
    
    public function getCashMetaValuesToGrid($type) {
        $this->load->model('mdgl', 'middleware/models/');
        $data = $this->model->getCashMetaValuesModel($type);
        return $data;
    }
// </editor-fold>

    public function glAutoCompleteById(){
        $lookupCode = Input::post('lookupCode');
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $lookupId = $this->model->getMetaDataIdByCodeModel($lookupCode);
        
        $code = Str::lower(trim(Input::post('code')));
        
        $isName = $isCode = $row = false;
        $isValueNotEmpty = true;

        if ($code == '') {
            $isValueNotEmpty = false;
        }
        
        $this->load->model('mdobject', 'middleware/models/');
        
        if ($lookupId && $lookupId != '' && $isValueNotEmpty) {

            if (Input::postCheck('isName')) {
                if (Input::post('isName') == 'true') {
                    $isName = true;
                } else {
                    $isCode = true;
                }
            }
            
            if ($isName) {
                if ($nameField = $this->model->getDataViewMetaValueName($lookupId)) {

                    $this->load->model('mdobject', 'middleware/models/');

                    $criteria[$nameField][] = array(
                        'operator' => '=',
                        'operand' => $code
                    );

                    $result = $this->model->getDataViewByCriteriaModel($lookupId, $criteria);

                    if ($result) {
                        $idField = strtolower($this->model->getDataViewMetaValueId($lookupId));
                        $codeField = strtolower($this->model->getDataViewMetaValueCode($lookupId));
                        $nameField = strtolower($nameField);

                        $row = array(
                            'META_VALUE_ID' => ($idField ? $result[$idField] : (isset($result['id']) ? $result['id'] : '')),
                            'META_VALUE_CODE' => (isset($result[$codeField]) ? $result[$codeField] : ''),
                            'META_VALUE_NAME' => (isset($result[$nameField]) ? $result[$nameField] : ''),
                            'rowData' => $result
                        );
                    }
                }
            } else {
                if ($codeField = $this->model->getDataViewMetaValueCode($lookupId)) {

                    $criteria[$codeField][] = array(
                        'operator' => '=',
                        'operand' => $code
                    );
                    
                    $result = $this->model->getDataViewByCriteriaModel($lookupId, $criteria);

                    if ($result) {
                        $idField = strtolower($this->model->getDataViewMetaValueId($lookupId));
                        $nameField = strtolower($this->model->getDataViewMetaValueName($lookupId));
                        $codeField = strtolower($codeField);

                        $row = array(
                            'META_VALUE_ID' => ($idField ? $result[$idField] : (isset($result['id']) ? $result['id'] : "")),
                            'META_VALUE_CODE' => (isset($result[$codeField]) ? $result[$codeField] : ""),
                            'META_VALUE_NAME' => (isset($result[$nameField]) ? $result[$nameField] : ""),
                            'rowData' => $result
                        );
                    }
                }
            }
        }

        if ($row) {
            echo json_encode($row);
        } else {
            $response = array('META_VALUE_ID' => '', 'META_VALUE_CODE' => '', 'META_VALUE_NAME' => '');
            echo json_encode($response);
        }  
        
        exit();
    }
    
    public function glLookupAutoComplete() {
        $type = Input::post('type');
        $lookupCode = Input::post('lookupCode');
        $where = '';
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $lookupId = $this->model->getMetaDataIdByCodeModel($lookupCode);
        
        if ($lookupId && $lookupId != '') {
            
            $this->load->model('mdobject', 'middleware/models/');
            
            $idField = $this->model->getDataViewMetaValueId($lookupId);
            $codeField = $this->model->getDataViewMetaValueCode($lookupId);
            $nameField = $this->model->getDataViewMetaValueName($lookupId);

            if ($type == 'code') {
                if ($codeField) {          
                    $q = Input::post('q');
                    $q = trim(str_replace('_', '', str_replace('_-_', '', $q)));

                    $criteria[$codeField][] = array(
                        'operator' => 'LIKE',
                        'operand' => $q.'%'
                    );   

                    $result = $this->model->getRowsDataViewByCriteriaModel($lookupId, $criteria, $idField, $codeField, $nameField, $where);       
                    echo json_encode($result);
                }
            } else {
                if ($nameField) {
                    $q = Input::post('q');             
                    $q = trim(str_replace('_', '', str_replace('_-_', '', $q)));

                    $criteria[$nameField][] = array(
                        'operator' => 'LIKE',
                        'operand' => '%'.$q.'%'
                    );
                    $result = $this->model->getRowsDataViewByCriteriaModel($lookupId, $criteria, $idField, $codeField, $nameField, $where);       
                    echo json_encode($result);
                }
            }  
        }
        exit();
    }
    
    public function customerBill() {
       $postData = Input::postData();

       $response = $this->model->customerBillModel($postData);
       echo json_encode($response); exit();
    }
    
    public function customerBill2() {
       $postData = Input::postData();

       $response = $this->model->customerBill2Model($postData);
       echo json_encode($response); exit();
    }
    
    public function bankRangeCustomerBill() {
       $postData = Input::postData();

       $response = $this->model->bankRangeCustomerBillModel($postData);
       echo json_encode($response); exit();
    }
    
    public function customerBillDetail(){
               
        $keyId = Input::post('keyId');
        $this->view->dataList = $this->model->customerBillDetailModel($keyId);
        
        $response = array(
            'html' => $this->view->renderPrint('currencyrate/customerBillDetail', $this->viewPath),
            'title' => 'Харилцагчийн тооцооны дэлгэрэнгүй',
            'close_btn' => $this->lang->line('close_btn'),
        );
        echo json_encode($response); exit;
    }
    
    public function getCustomerRow($customerId) {
        $this->load->model('mdgl', 'middleware/models/');
        return $this->model->getCustomerRowModel($customerId);
    }
    
    public function popupConnectGL() {
        
        if (Input::postCheck('isMulti')) {
            $selectedRows = $_POST['selectedRow'];
        
            if ($validateMsg = self::validateMultiConnect($selectedRows)) {
                echo json_encode($validateMsg); exit;
            }
        }

        $this->view->accountCode = 'vw_account';
        $this->view->messageCode = 'fin_message';
        $this->view->isEditMode = true;
        $this->view->inputMetaDataId = self::$glBookDtlGroupMetaDataId;
        $this->view->isFieldSet = Input::postCheck('bpTabLength') ? Input::post('bpTabLength') : 1;
        $this->view->isShowGlBookNumber = true;

        $this->view->paramList = array();
        $this->view->uniqId = Input::isEmpty('uniqId') == false ? Input::post('uniqId') : getUID();
        $this->view->errorGLMessage = '';
        $this->view->glBpMainWindowId = 'glTemplateSectionStatic_'.$this->view->uniqId;
        $connectType = Input::post('connectType');
        
        $response = array();
        
        if ($connectType == '3') {
            
            $this->view->isNotAddAccount = true;
            $this->view->isNotAccountProcess = true;
            
            $entry = $this->model->getMultiConnectGLModel($connectType);
            
            $response['bookTypeId'] = $entry['bookTypeId'];
            $response['processId']  = $entry['processId'];
            $response['objectId']   = $entry['objectId'];
            
        } else {
            $entry = $this->model->getGlEntryFromParamConfigModel($connectType);
        }
        
        if ($entry['status'] == 'success') {
            
            $glBook = $entry['result'];
            
            $this->view->paramList = $glBook;
            $this->view->descriptionCode = $this->model->getDescriptionModel($glBook['description']);
            
            if (isset($this->view->paramList['templates']) && count($this->view->paramList['templates']) > 1) {
                
                $this->view->templates = $this->view->paramList['templates'];
                
                array_walk($this->view->templates, function(&$value, $index) { 
                    $value['name'] = ++$index . ' ' . $value['name'];
                }); 
                
                $this->view->postJson = json_encode(Input::postData());
            }
            
        } else {
            $this->view->errorGLMessage = $this->ws->getResponseMessage($entry);
        }
        
        $this->view->currencyList = $this->model->currencyListModel();
        
        $this->view->isDataView = false;
        $this->view->dataViewId = null;
        
        if (Input::isEmpty('dataViewId') == false) {
            $this->view->isDataView = true;
            $this->view->dataViewId = Input::post('dataViewId');
        }
        
        $this->view->gridDtl = self::glGridRender();
        
        $this->view->isPopup = true;
        $this->view->isAjax = true;

        $this->view->editForm = $this->view->renderPrint('main/sub/editGl', $this->viewPath);
        
        $response['status']    = 'success';
        $response['html']      = $this->view->renderPrint('main/editEntry', $this->viewPath);
        $response['title']     = 'Журналд холбох';
        $response['save_btn']  = $this->lang->line('save_btn');
        $response['close_btn'] = $this->lang->line('close_btn');
        
        echo json_encode($response); exit;
    }
    
    public function confirmMultiGLConnect() {
        
        $selectedRows = $_POST['selectedRow'];
        
        if ($validateMsg = self::validateMultiConnect($selectedRows)) {
            $response = $validateMsg;
        } else {
            $this->view->connectType = issetParam($selectedRows[0]['connecttype']) ? $selectedRows[0]['connecttype'] : 1;
            $response = array(
                'status' => 'success', 
                'title' => $this->lang->line('msg_title_confirm'), 
                'html' => $this->view->renderPrint('main/sub/multiConfirm', $this->viewPath), 
                'continue_btn' => $this->lang->line('continue_btn'), 
                'close_btn' => $this->lang->line('close_btn'),
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function popupMultiConnectGL() {
        
        $selectedRows = $_POST['selectedRow'];
        
        if ($validateMsg = self::validateMultiConnect($selectedRows)) {
            echo json_encode($validateMsg); exit;
        }
        
        $postConnectType = Input::post('connectType');
        $connectType     = $postConnectType ? $postConnectType : 2;

        $entry = $this->model->getMultiConnectGLModel($connectType);
        
        if ($entry['status'] == 'success') {
            
            $selectedRow = $selectedRows[0];
            
            $this->view->uniqId = getUID();
            $this->view->paramList = $entry['result'];
            
            $this->view->bookTypeId = Arr::get($selectedRow, 'booktypeid');
            $this->view->processId = $entry['processId'];
            $this->view->objectId = $entry['objectId'];
            
            $response = array(
                'status' => 'success', 
                'html' => $this->view->renderPrint('main/multi', $this->viewPath),
                'title' => 'Журналд холбох', 
                'save_btn' => $this->lang->line('save_btn'), 
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = array(
                'status' => 'warning',
                'message' => $this->ws->getResponseMessage($entry)
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function saveMultiGlEntry() {
        $result = $this->model->saveMultiGlEntryModel();
        echo json_encode($result); exit;
    }
    
    public function validateMultiConnect($selectedRows) {
        
        if (!isset($selectedRows[0]['booktypeid'])) {
            return array('status' => 'warning', 'message' => 'Баримтын төрлийн шалгуур ажиллахад шалгах багана олдсонгүй!');
        }
        
        $typeIds = Arr::groupByArray($selectedRows, 'booktypeid');
        
        if (count($typeIds) > 1) {
            return array('status' => 'warning', 'message' => 'Ижил төрөлтэй баримтууд сонгоно уу!');
        }
        
        return false;
    }

    public function bankCharge() {
        
        self::glExtensions();
        
        $this->view->currencyList = $this->model->currencyListModel();
        $this->view->title = "Банкны шимтгэлийн гүйлгээ";
        
        $this->view->row = Input::param($_POST['selectedRow']);
        $this->view->selectedRow = json_encode($this->view->row);
        $this->view->metaDataId = Input::post('dataViewId');
        $this->view->processMetaDataId = Input::post('processMetaDataId');
        $this->view->dialogMode = Input::post('dialogMode');
        
        $this->view->isAjax = is_ajax_request();
        
        if ($this->view->dialogMode === 'popup') {
            
            $response = array(
                'dialogFormName' => '#saveBankCharge-form',
                'dialogWindowId' => '#bankCharge-'.$this->view->metaDataId,
                'submitUrl' => 'mdgl/createGlEntry',
                'dialogWidth' => '1024',
                'beforeValidation' => '1',
                'beforeValidationId' => '#isUsedGl',
                'beforeValidationValue' => '0',
                'beforeValidationText' => 'Журналтай холбоно уу?',
                'mainId' => $this->view->row['id'],
                'Html' => $this->view->renderPrint('bank/bankCharge', $this->viewPath),
                'Title' => $this->view->title,
                'save_btn' => $this->lang->line('save_btn'),
                'close_btn' => $this->lang->line('close_btn')
            );
            echo json_encode($response); exit;
            
        } else {
            
            if (!$this->view->isAjax) {
                $this->view->render('header');
            }

            $this->view->render('bank/bankCharge', $this->viewPath);

            if (!$this->view->isAjax) {
                $this->view->render('footer');
            }
        }
    }
    
    public function callBillRateForm() {
        self::glExtensions();
        
        $this->view->currencyList = $this->model->currencyListModel();
        $this->view->title = "Харилцагчийн тооцооны жагсаалт";
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->selectedRow = Input::post('selectedRow');
        $title = ($this->view->selectedRow['customername']) ? $this->view->selectedRow['customername'].'/'. $this->view->selectedRow['accountcode'] .'/ :'.'Харилцагчийн тооцооны жагсаалт' : 'Харилцагчийн тооцооны жагсаалт';
        $this->view->isAjax = true;
        
        $response = array(
            'Html' => $this->view->renderPrint('bank/billRate', $this->viewPath),
            'Title' => $title,
            'save_btn' => $this->lang->line('save_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function callBankRangeGlEntry() {
        $result = $this->model->callBankRangeGlEntryModel();
        
        if ($result['status'] == 'success') {

            $this->view->isFieldSet = (Input::postCheck('bpTabLength') ? Input::post('bpTabLength') : 1);
            $this->view->paramList = $result['data'];
            $this->view->glBpMainWindowId = "glTemplateSectionStatic";
            $this->view->uniqId = getUID();
            $this->view->isPopup = false;
            
            $this->view->currencyList = $this->model->currencyListModel();
            
            $this->view->isDataView = false;
            $this->view->dataViewId = null;

            if (Input::isEmpty('dataViewId') == false) {
                $this->view->isDataView = true;
                $this->view->dataViewId = Input::post('dataViewId');
            }   
            
            $glhtml = self::glGridRender();
            
            $response = array(
                'Html' => $glhtml,
                'status' => $result['status'],
                'message' => "Success"
            );
        } else {
            $response = array(
                'status' => $result['status'],
                'message' => $result['message']
            );
        }
        
        echo json_encode($response); exit();
    }
    
    public function runDeleteGlBp() {
        $data = $this->model->runDeleteGlBpModel(Input::post('id'), Input::post('type'));
        echo json_encode($data); exit();
    }    
    
    public function printGlDetail() {
        
        $pageProperties = array(
            'reportName' => 'Журнал бичилтийн дэлгэрэнгүй хэвлэх', 
            'pageSize' => 'a4', 
            'pageOrientation' => 'landscape', 
            'pagePrint' => true,
            'pagePdf' => true,
            'pageExcel' => false,
            'pageWord' => true,
            'pageSearch' => false,
            'pageArchive' => false, 
            'pageMarginTop' => '0.5cm', 
            'pageMarginLeft' => '0.5cm', 
            'pageMarginRight' => '0.5cm', 
            'pageMarginBottom' => '0.5cm', 
            'pageWidth' => '',
            'pageHeight' => '',
            'fontFamily' => 'arial, helvetica, sans-serif', 
            'contentHtml' => $_POST['content'].'<div class="print-width-dpi"></div>' 
        );
        
        $response = array(
            'html' => (new Mdpreview())->renderToolbar($pageProperties),
            'title' => $pageProperties['reportName'],
            'close_btn' => Lang::line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function fiscalPeriodDepartmentClose() {

        $this->view->title = 'Тайлант үе /Хэлтэсээр Шинэ/';
        $this->view->isAjax = true;
        $this->view->uniqId = getUID();
        $this->view->departmentDV = 'Department11';
        $this->view->finFiscalPeriodDV = 'finFiscalPeriod';
        $this->view->isAjax = is_ajax_request();
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }

        $this->view->render('main/fiscalPeriodClose', $this->viewPath);

        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function fiscalPeriodDepartmentCloseService() {
        $data = $this->model->fiscalPeriodDepartmentCloseServiceModel();
        echo json_encode($data); exit;
    }
    
    public function getOppMetaByAccountId($glRow) {
        
        if (isset($glRow['accountid']) && $glRow['accountid'] && !array_key_exists($glRow['accountid'], Mdgl::$oppAccountMeta)) {
            
            if (isset($glRow['accounttypeid']) && $glRow['accounttypeid']) {
                
                $row = $this->db->GetRow("
                    SELECT 
                        GC.FIELD_PATH 
                    FROM FIN_ACCOUNT_GL_CONFIG GC 
                        INNER JOIN FIN_ACCOUNT_GL_CONFIG_DTL GCL ON LOWER(GCL.FIELD_PATH) = LOWER(GC.FIELD_PATH) 
                    WHERE GC.ACCOUNT_ID = ".$this->db->Param(0)." OR GC.ACCOUNT_TYPE_ID = ".$this->db->Param(1), 
                    array($glRow['accountid'], $glRow['accounttypeid'])
                );

                if ($row) {
                    Mdgl::$oppAccountMeta[$glRow['accountid']] = $glRow['accountid'].'|'.$row['FIELD_PATH'];
                    return $glRow['accountid'].'|'.$row['FIELD_PATH'];
                }
            }
            
            Mdgl::$oppAccountMeta[$glRow['accountid']] = null;
            
            return null;
            
        } else {
            return issetParam(Mdgl::$oppAccountMeta[$glRow['accountid']]);
        }
    }
    
    public function accountSegmentCriteria() {
        
        $this->view->uniqId      = getUID();
        $this->view->path        = Input::post('path');
        $this->view->segmentList = $this->model->getAccountSegmentListModel();
        
        if (Input::isEmpty('segmentCode') == false) {
            $this->load->model('mdobject', 'middleware/models/');
            
            $segmentCode = ltrim(Input::post('segmentCode'), '-');
            $this->view->segmentSplit = explode('-', $segmentCode);
        }
        
        $response = array(
            'html' => $this->view->renderPrint('account/accountSegmentCriteria', $this->viewPath),
            'title' => 'Dimension',
            'save_btn' => 'Оруулах',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function callBpWindow() {
        
        $this->model->pushBpParamsModel();
        
        if (Input::isEmpty('metaDataId') == false) {
            (new Mdwebservice())->callMethodByMeta();
        } else {
            echo json_encode(array('errorMsg' => 'Процессийн тохиргоо хийгдээгүй байна.'));
        }
        
        exit;
    }
    
    public static function getAmountScale() {
        
        $cache = phpFastCache();
        $conf = $cache->get('sysConfigGlAmountScale');
        
        if ($conf == null) {
            global $db;
            
            if (DB_DRIVER == 'postgres9') {
                $cache->set('sysConfigGlAmountScale', 6, Mdwebservice::$expressionCacheTime);
            } else {
                $rs = $db->MetaColumns('FIN_GENERAL_LEDGER');
                if ($rs) {
                    $conf = $rs['DEBIT_AMOUNT_BASE']->scale;
                    $cache->set('sysConfigGlAmountScale', $conf, Mdwebservice::$expressionCacheTime);
                }
            }
        }

        return $conf;
    }
    
    public function accountCodePaste() {
        
        $array         = array();
        $accountCodes  = Input::post('accountCodes');
        $bookDate      = Input::post('bookDate');
        $dbAccountData = $this->model->getMultiAccountModel($accountCodes);
        
        foreach ($accountCodes as $k => $v) {
            
            if ($v) {
                
                $accountRow = array();
                
                foreach ($dbAccountData as $dbAccount) {
                    if ($dbAccount['ACCOUNTCODE'] == $v) {
                        $accountRow = $dbAccount;
                        break;
                    }
                }
                
                $arrayRow = array('accountRow' => $accountRow);
                
                if ($accountRow) {
                    
                    if ($accountRow['CURRENCYCODE'] == 'MNT') {
                        
                        $arrayRow['rate'] = 1;
                        
                    } else {
                        
                        $arrayRow['rate'] = 1;
                        
                        $_POST['accountId'] = $accountRow['ACCOUNTID'];
                        $_POST['date'] = $bookDate;
                        
                        $rateResult = $this->model->getRateForAccountModel();
                        
                        if ($rateResult['status'] == 'success') {
                            $arrayRow['rate'] = $rateResult['result']['result'];
                        }
                    }
                    
                    $glRow = array(
                        'accountid'     => $accountRow['ACCOUNTID'], 
                        'accounttypeid' => $accountRow['ACCOUNTTYPEID'], 
                        'objectid'      => $accountRow['OBJECTID']
                    );
                    
                    $isOppMetaAttr = self::getOppMetaByAccountId($glRow);
                    
                    $arrayRow['isOppMetaAttr'] = $isOppMetaAttr;
                    
                    $glRow['rowislock'] = 0;
                    $glRow['usedetail'] = $accountRow['ISUSEDETAILBOOK'];
                    $glRow['headerBookTypeId'] = 1;

                    $rowCheckRow = self::checkAccountRowBpMeta($glRow, $k);
                    
                    $arrayRow = array_merge($arrayRow, $rowCheckRow);
                }
                
                $array[] = $arrayRow;
                
            } else {
                $array[] = '';
            }
        }
        
        echo json_encode($array); exit;
    }
    
    public function glAddRows() {
        
        $this->view->uniqId = Input::post('uniqId');
        $this->view->rowCount = Input::post('rowCount');
        
        $response = array(
            'html' => $this->view->renderPrint('main/sub/addMultiRows', $this->viewPath),
            'title' => 'Олноор мөр нэмэх',
            'status' => 'success',
            'insert_btn' => 'Оруулах',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function budgetConnectGL() {

        $this->view->accountCode = 'vw_account';
        $this->view->messageCode = 'fin_message';
        $this->view->isEditMode = true;
        $this->view->inputMetaDataId = self::$glBookDtlGroupMetaDataId;
        $this->view->isFieldSet = 1;
        $this->view->isShowGlBookNumber = true;

        $this->view->paramList = array();
        $this->view->uniqId = getUID();
        $this->view->errorGLMessage = '';
        $this->view->isNotAccountProcess = true;
        $this->view->glBpMainWindowId = 'glTemplateSectionStatic_'.$this->view->uniqId;
        
        $response = array();
        
        $entry = $this->model->getGlEntryFromBudgetModel();
        
        if ($entry['status'] == 'success') {
            
            $glBook = $entry['result'];
            
            $this->view->paramList = $glBook;
            $this->view->descriptionCode = $this->model->getDescriptionModel($glBook['description']);
            
        } else {
            $this->view->errorGLMessage = $this->ws->getResponseMessage($entry);
        }
        
        $this->view->currencyList = $this->model->currencyListModel();
        
        $this->view->isDataView = false;
        $this->view->dataViewId = null;
        $this->view->importId = 1;
        
        $this->view->accountDvId = '1572946832425178';
        $this->view->accountDvCode = 'bmAccountList';
        
        $this->view->gridDtl = self::glGridRender();
        
        $this->view->isPopup = true;
        $this->view->isAjax = true;

        $this->view->editForm = $this->view->renderPrint('main/sub/editGl', $this->viewPath);
        
        $response['status']    = 'success';
        $response['title']     = 'Журналд холбох';
        $response['html']      = $this->view->renderPrint('main/editEntry', $this->viewPath);
        $response['save_btn']  = $this->lang->line('save_btn');
        $response['close_btn'] = $this->lang->line('close_btn');
        $response['isEdit']    = $entry['isEdit'];
        
        if (isset($entry['isDvReload'])) {
            $response['isDvReload'] = $entry['isDvReload'];
        }
        
        echo json_encode($response); exit;
    }
    
    public function expenseExpressionForm() {
        
        $this->view->uniqId = getUID();
        $this->view->metaCode = Input::post('rowMetaCode');
        $this->view->metaName = Input::post('rowMetaName');
        
        $this->view->expression = '';
        $this->view->metaList = '';
        
        $mdobject = &getInstance();
        $mdobject->load->model('mdobject', 'middleware/models/');
        $metas = $mdobject->model->getDataViewGridHeaderModel(Input::post('bpMetaDataId'));
        
        $expression = Input::post('expression');
        
        if ($metas) {
            
            $search = array('==', '&&', '||'); 
            $replace = array('=', 'and', 'or');
            
            $searchArr = $replaceArr = array();
            
            foreach ($metas as $k => $meta) {
                
                if (!empty($meta['FIELD_NAME'])) {
                    $metaDataName = Lang::line($meta['LABEL_NAME']);
                    
                    $this->view->metaList .= '<li data-code="'.$meta['FIELD_NAME'].'" title="'.$meta['FIELD_NAME'].'">'.issetDefaultVal($metaDataName, $meta['FIELD_PATH']).'</li>';
                    
                    $searchArr[] = 'p_' . $k . '_code';
                    $searchArr[] = 'p_' . $k . '_name';
                    
                    $replaceArr[] = $meta['FIELD_NAME'];
                    $replaceArr[] = $metaDataName;
                    
                    $expression = preg_replace('/\b'.$meta['FIELD_NAME'].'\b/u', '<span class="p-exp-meta" contenteditable="false" data-code="p_'.$k.'_code">p_'.$k.'_name<span class="p-exp-meta-remove" contenteditable="false">x</span></span>', $expression);
                }
            }
            
            $expression = str_replace($searchArr, $replaceArr, $expression);
            
            $this->view->expression = str_replace($search, $replace, $expression);
            
        }
        
        $response = array(
            'html' => $this->view->renderPrint('expenseexpression/expressionForm', $this->viewPath),
            'title' => 'Томъёо тохируулах', 
            'save_btn' => Lang::line('save_btn'),
            'check_btn' => Lang::line('Шалгах'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }    
    
    public function validateExpression() {
        
        loadPhpQuery();
        
        $expressionContent = Input::postNonTags('expressionContent');
        
        $htmlObj = phpQuery::newDocumentHTML($expressionContent);  
        $matches = $htmlObj->find('span.p-exp-meta:not(:empty)');
        
        if ($matches->length) {
            
            foreach ($matches as $tag) {
                $metaCode = pq($tag)->attr('data-code');
                pq($tag)->replaceWith($metaCode);
            }
            
            $expressionContent = $htmlObj->html();
        }
        
        $search  = array('&nbsp;', '\r\n', '\r', '\n', "\r\n", "\r", "\n");
        $replace = array(' ',       '',     '',   '',   '',     '',   ''); 
            
        $expressionContent = html_entity_decode(trim(str_replace($search, $replace, strip_tags($expressionContent))));
        $expressionContent = str_replace(array("\xC2", "\xA0", '\u00a0', '<==', '>==', '!=='), array(' ', ' ', '', '<=', '>=', '!='), $expressionContent);
        $expressionContent = Str::remove_doublewhitespace(Str::remove_whitespace_feed($expressionContent));
        $expressionContent = trim($expressionContent, "\x20,\xC2,\xA0");
        $expressionContent = preg_replace('/\bor\b/u', 'OR', $expressionContent);
        $expressionContent = preg_replace('/\bOR\b/u', 'OR', $expressionContent);
        $expressionContent = preg_replace('/\bOr\b/u', 'OR', $expressionContent);
        $expressionContent = preg_replace('/\band\b/u', 'AND', $expressionContent);
        $expressionContent = preg_replace('/\bAND\b/u', 'AND', $expressionContent);
        $expressionContent = preg_replace('/\bAnd\b/u', 'AND', $expressionContent);
        $expressionContent = preg_replace('/(\s*=\s*)([a-zA-Z0-9]+)/', '$1\'$2\'', $expressionContent);
        
        if (Input::post('isRun') == 'hide') {
            $response = array('status' => 'success', 'message' => 'Success', 'expression' => $expressionContent);
        } else {
            $response = $this->model->validateExpressionModel($expressionContent);
        }
        
        echo json_encode($response); exit;
    }    

    public function getConfigGlDeduction($accountDepartmentId) {        
        $response = array(
            'CONFIG_GL_VAT_DEDUCTION_DEBIT' => Config::get('CONFIG_GL_VAT_DEDUCTION_DEBIT', $accountDepartmentId),
            'CONFIG_GL_VAT_DEDUCTION_CREDIT' => Config::get('CONFIG_GL_VAT_DEDUCTION_CREDIT', $accountDepartmentId)
        );
        echo json_encode($response); exit;
    }    
    
}
