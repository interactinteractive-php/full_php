<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdpos Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Point of sales
 * @author	B.Och-Erdene <ocherdene@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdpos
 */

class Mdpos extends Controller {

    private static $viewPath = 'middleware/views/pos/';
    public static $eVatNumber = '';
    public static $eStoreCode = '';
    public static $eCashRegisterCode = '';
    public static $posHeaderName = '';
    public static $posLogo = '';
    public static $posVatPayerNo = '';
    public static $posVatPayerName = '';    
    public static $storeId = '';    
    public static $cashRegisterId = '';    

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();    
    }   
    
    public static function getPosApiServiceAddr() {
        return CONFIG_POSAPI_SERVICE_ADDRESS;
    }

    public function index() {        

        if (Config::getFromCache('IS_USE_POSAPI_V3')) {
            Message::add('s', '', AUTH_URL.'mdpos/v3');
        }
        
        $this->view->title = 'POS';
        $this->view->uniqId = getUID();        

        $this->view->css = array_unique(array_merge(AssetNew::metaCss(), array('custom/css/pos/style.css')));
        
        $getPOSSession = $this->model->setPOSSessionModel();
        
        if ($getPOSSession['status'] == 'chooseCashier') {
            
            $this->view->cashierList = $getPOSSession['data'];
            
            if (!is_ajax_request()) {
                
                $this->view->render('header', self::$viewPath);
                $this->view->render('chooseCashier', self::$viewPath);
                $this->view->render('footer');
                exit;
                
            } else {
                $response = array('html' => $this->view->renderPrint('chooseCashierAjax', self::$viewPath), 'uniqId' => $this->view->uniqId, 'chooseCashier' => '');
                echo json_encode($response); exit;        
            }
            
        } elseif ($getPOSSession['status'] != 'success') {
            Message::add('i', ($getPOSSession['message'] ? $getPOSSession['message'] : $this->lang->line('POS_0057')), URL . 'mdpos/message');
        }
        
        $this->view->js = array_unique(
            array_merge(
                array(
                    'custom/addon/plugins/scannerdetection/jquery.scannerdetection.js', 
                    'custom/addon/plugins/jquery-fixedheadertable/jquery.fixedheadertable.min.js' 
                ), 
                AssetNew::metaOtherJs()
            )
        );
        $this->view->fullUrlJs = array('middleware/assets/js/pos/pos.js');
        $this->view->isAjaxLoad = false;
        $this->view->dataViewId = Input::post('dataViewId');
        $this->view->windowSessionId = getUID();
        
        self::posConfigLoad();
        
        $this->view->billNum = $this->model->getBillNumModel();        
        $this->view->getItems = '';
        $this->view->basketInvoiceId = '';
        $this->view->basketCount = 0;
        $response = array();        
        
        if (Input::isEmpty('selectedRow') === false) {
            $this->view->getLocker = Input::post('selectedRow');
            
            if (Input::isEmpty('objectParam') === false) {
                $objectParam = json_decode(html_entity_decode(Input::post('objectParam'), ENT_QUOTES, 'UTF-8'), true);
                if ($objectParam) {
                    $this->view->selectedCustomerId = $objectParam['customerId'];
                    $this->view->selectedItemId = $objectParam['itemId'];
                }
            }
            
            if (array_key_exists('resultData', $this->view->getLocker) && array_key_exists('fitmultilockercheckin_dv', $this->view->getLocker['resultData'])) {

                $this->view->multipleLockers = $this->view->getLocker['resultData']['fitmultilockercheckin_dv'];
                $this->view->getLocker = null;

            } else {

                $this->view->getLocker['typeid'] = '5';
                $this->view->vipLockerId = Str::lower(Input::post('vipLockerId'));            
                $this->view->lockerCustomerId = Str::lower(Input::post('customerId'));            
                $this->view->specialLocker = '';            
                $lockerKeyCode = $this->view->getLocker['keycode'];
                
                $result = $this->model->getInvoiceByIdModel($this->view->getLocker);
                $this->view->vipLockerId = $this->view->vipLockerId === 'isspecialuse' ? $this->view->specialLocker = '1' : $this->view->vipLockerId;                

                if ($result['status'] == 'success' && $result['data']) {

                    $this->view->getLocker = $result['data'];
                    $this->view->getLocker['keycode'] = $lockerKeyCode;
                    $this->view->basketCount = $this->model->getBasketLockerOrderBookCountModel($this->view->getLocker['id']);
                    
                    if (issetParam($this->view->getLocker['alertmsg'])) {
                        $response['message'] = $this->view->getLocker['alertmsg'];
                    }
                    
                    $this->view->storeId  = Session::get(SESSION_PREFIX.'storeId');
                    $this->view->itemList = issetParam($result['data']['pos_item_list_get']);
                    $this->view->basketInvoiceId = $this->view->getLocker['salesorderid'];
                    $this->view->getItems = $this->view->renderPrint('items', self::$viewPath);
                }
            }            
        } else {
            $this->view->basketCount = $this->model->getBasketOrderBookCountModel();
        }
                
        /**
         * postypecode = 2 restaurant
         */
        $posTypeCode = Session::get(SESSION_PREFIX.'posTypeCode');
        $layoutCode = $posTypeCode == '3' ? 'card' : 'bottom';

        $existMetaId = (new Mdmetadata())->getMetaData('16710768284569');        
        if ($existMetaId) {
            $this->view->quickItemList = $this->model->quickItemModel($existMetaId['META_DATA_ID']);        
        }
        
        if ($posTypeCode == '5' || $layoutCode == 'card') {
            
            $this->view->leftSidebar = $this->view->renderPrint('layout/card/leftSidebar', self::$viewPath);
            $this->view->rightSidebar = $this->view->renderPrint('layout/card/rightSidebar', self::$viewPath);
            $this->view->centerSidebar = $this->view->renderPrint('layout/card/centerSidebar', self::$viewPath);
            $this->view->layout = $this->view->renderPrint('layout/card/index', self::$viewPath);   

        } elseif ($layoutCode == 'right') {
            
            $this->view->leftSidebar = $this->view->renderPrint('layout/right/leftSidebar', self::$viewPath);
            $this->view->rightSidebar = $this->view->renderPrint('layout/right/rightSidebar', self::$viewPath);
            $this->view->centerSidebar = $this->view->renderPrint('layout/right/centerSidebar', self::$viewPath);
            $this->view->layout = $this->view->renderPrint('layout/right/index', self::$viewPath);   
            
        } elseif ($layoutCode == 'bottom') {
            
            $this->view->leftSidebar = $this->view->renderPrint('layout/bottom/leftSidebar', self::$viewPath);
            $this->view->rightSidebar = $this->view->renderPrint('layout/bottom/rightSidebar', self::$viewPath);
            $this->view->centerSidebar = $this->view->renderPrint('layout/bottom/centerSidebar', self::$viewPath);
            $this->view->layout = $this->view->renderPrint('layout/bottom/index', self::$viewPath);   
            
        }
        
        if (!is_ajax_request()) {
            $this->view->render('header', self::$viewPath);
            $this->view->render('index', self::$viewPath);
            $this->view->render('footer');
            Session::set(SESSION_PREFIX.'posActiveLogin', '1');
        } else {
            $this->view->isAjaxLoad = true;
            $response['html'] = $this->view->renderPrint('index', self::$viewPath);
            $response['uniqId'] = $this->view->uniqId;
            Session::set(SESSION_PREFIX.'posActiveLogin', '1');
            echo json_encode($response); exit;        
        }
    }

    public function v3() {        
        
        $this->view->title = 'POS V3';
        $this->view->uniqId = getUID();        

        $this->view->css = array_unique(array_merge(AssetNew::metaCss(), array('custom/css/pos/style.css')));
        
        $getPOSSession = $this->model->setPOSSessionModel(true);
        
        if ($getPOSSession['status'] == 'chooseCashier') {
            
            $this->view->cashierList = $getPOSSession['data'];
            
            if (!is_ajax_request()) {
                
                $this->view->render('header', self::$viewPath);
                $this->view->render('chooseCashier', self::$viewPath);
                $this->view->render('footer');
                exit;
                
            } else {
                $response = array('html' => $this->view->renderPrint('chooseCashierAjax', self::$viewPath), 'uniqId' => $this->view->uniqId, 'chooseCashier' => '');
                echo json_encode($response); exit;        
            }
            
        } elseif ($getPOSSession['status'] != 'success') {
            Message::add('i', ($getPOSSession['message'] ? $getPOSSession['message'] : $this->lang->line('POS_0057')), URL . 'mdpos/message');
        }
        
        $this->view->js = array_unique(
            array_merge(
                array(
                    'custom/addon/plugins/scannerdetection/jquery.scannerdetection.js', 
                    'custom/addon/plugins/jquery-fixedheadertable/jquery.fixedheadertable.min.js' 
                ), 
                AssetNew::metaOtherJs()
            )
        );
        $this->view->fullUrlJs = array('middleware/assets/js/pos/pos.js');
        $this->view->isAjaxLoad = false;
        $this->view->dataViewId = Input::post('dataViewId');
        $this->view->windowSessionId = getUID();
        
        self::posConfigLoad(true);
        
        $this->view->billNum = $this->model->getBillNumModel();        
        $this->view->getItems = '';
        $this->view->basketInvoiceId = '';
        $this->view->basketCount = 0;
        $response = array();        
        
        if (Input::isEmpty('selectedRow') === false) {
            $this->view->getLocker = Input::post('selectedRow');
            
            if (Input::isEmpty('objectParam') === false) {
                $objectParam = json_decode(html_entity_decode(Input::post('objectParam'), ENT_QUOTES, 'UTF-8'), true);
                if ($objectParam) {
                    $this->view->selectedCustomerId = $objectParam['customerId'];
                    $this->view->selectedItemId = $objectParam['itemId'];
                }
            }
            
            if (array_key_exists('resultData', $this->view->getLocker) && array_key_exists('fitmultilockercheckin_dv', $this->view->getLocker['resultData'])) {

                $this->view->multipleLockers = $this->view->getLocker['resultData']['fitmultilockercheckin_dv'];
                $this->view->getLocker = null;

            } else {

                $this->view->getLocker['typeid'] = '5';
                $this->view->vipLockerId = Str::lower(Input::post('vipLockerId'));            
                $this->view->lockerCustomerId = Str::lower(Input::post('customerId'));            
                $this->view->specialLocker = '';            
                $lockerKeyCode = $this->view->getLocker['keycode'];
                
                $result = $this->model->getInvoiceByIdModel($this->view->getLocker);
                $this->view->vipLockerId = $this->view->vipLockerId === 'isspecialuse' ? $this->view->specialLocker = '1' : $this->view->vipLockerId;                

                if ($result['status'] == 'success' && $result['data']) {

                    $this->view->getLocker = $result['data'];
                    $this->view->getLocker['keycode'] = $lockerKeyCode;
                    $this->view->basketCount = $this->model->getBasketLockerOrderBookCountModel($this->view->getLocker['id']);
                    
                    if (issetParam($this->view->getLocker['alertmsg'])) {
                        $response['message'] = $this->view->getLocker['alertmsg'];
                    }
                    
                    $this->view->storeId  = Session::get(SESSION_PREFIX.'storeId');
                    $this->view->itemList = issetParam($result['data']['pos_item_list_get']);
                    $this->view->basketInvoiceId = $this->view->getLocker['salesorderid'];
                    $this->view->getItems = $this->view->renderPrint('items', self::$viewPath);
                }
            }            
        } else {
            $this->view->basketCount = $this->model->getBasketOrderBookCountModel();
        }
                
        /**
         * postypecode = 2 restaurant
         */
        $posTypeCode = Session::get(SESSION_PREFIX.'posTypeCode');
        $layoutCode = $posTypeCode == '3' ? 'card' : 'bottom';

        $existMetaId = (new Mdmetadata())->getMetaData('16710768284569');        
        if ($existMetaId) {
            $this->view->quickItemList = $this->model->quickItemModel($existMetaId['META_DATA_ID']);        
        }
        
        if ($posTypeCode == '5' || $layoutCode == 'card') {
            
            $this->view->leftSidebar = $this->view->renderPrint('layout/card/leftSidebar', self::$viewPath);
            $this->view->rightSidebar = $this->view->renderPrint('layout/card/rightSidebar', self::$viewPath);
            $this->view->centerSidebar = $this->view->renderPrint('layout/card/centerSidebar', self::$viewPath);
            $this->view->layout = $this->view->renderPrint('layout/card/index', self::$viewPath);   

        } elseif ($layoutCode == 'right') {
            
            $this->view->leftSidebar = $this->view->renderPrint('layout/right/leftSidebar', self::$viewPath);
            $this->view->rightSidebar = $this->view->renderPrint('layout/right/rightSidebar', self::$viewPath);
            $this->view->centerSidebar = $this->view->renderPrint('layout/right/centerSidebar', self::$viewPath);
            $this->view->layout = $this->view->renderPrint('layout/right/index', self::$viewPath);   
            
        } elseif ($layoutCode == 'bottom') {
            
            $this->view->leftSidebar = $this->view->renderPrint('layout/bottom/leftSidebar', self::$viewPath);
            $this->view->rightSidebar = $this->view->renderPrint('layout/bottom/rightSidebar', self::$viewPath);
            $this->view->centerSidebar = $this->view->renderPrint('layout/bottom/centerSidebar', self::$viewPath);
            $this->view->layout = $this->view->renderPrint('layout/bottom/index', self::$viewPath);   
            
        }
        
        if (!is_ajax_request()) {
            $this->view->render('header', self::$viewPath);
            $this->view->render('index', self::$viewPath);
            $this->view->render('footer');
            Session::set(SESSION_PREFIX.'posActiveLogin', '1');
        } else {
            $this->view->isAjaxLoad = true;
            $response['html'] = $this->view->renderPrint('index', self::$viewPath);
            $response['uniqId'] = $this->view->uniqId;
            Session::set(SESSION_PREFIX.'posActiveLogin', '1');
            echo json_encode($response); exit;        
        }
    }
    
    public function message($storeId = '', $posId = '', $cashierId = '') {
        
        $this->view->title = 'POS';
        $this->view->uniqId = getUID();
        
        $this->view->css = array_unique(array_merge(AssetNew::metaCss(), array('global/css/pos/style.css')));
        $this->view->js = AssetNew::metaOtherJs();
        
        if ($storeId != '' && $posId != '' && $cashierId != '') {
            
            $storeId = Input::param($storeId);
            $posId = Input::param($posId);
            $cashierId = Input::param($cashierId);
        
            $cashierInfo = $this->model->getPosInfoModel($storeId, $posId, $cashierId);
            $getPOSSession = $this->model->setSessionPosByRow($cashierInfo);
            
        } else {
            $getPOSSession = $this->model->setPOSSessionModel();
        }
        
        if ($storeId === 'pos') {
            Session::delete(SESSION_PREFIX.'cashierId');
        } elseif ($getPOSSession['status'] == 'success') {
            if (!is_ajax_request()) {
                Message::add('s', '', URL . 'mdpos');
            } else {
                echo json_encode($getPOSSession); exit;
            }
        } else {
            if (!is_ajax_request()) {
                Session::delete('flash_messages');
                $_SESSION['flash_messages']['info'][] = $getPOSSession['message'];
            } else {
                $response = array('html' => '<div class="alert alert-info col">'.$getPOSSession['message'].'</div>', 'uniqId' => $this->view->uniqId, 'chooseCashier' => '');
                echo json_encode($response); exit;                
            }
        }

        $this->view->render('header', self::$viewPath);
        $this->view->render('message', self::$viewPath);
        $this->view->render('footer');
    }
    
    public function chooseCashier($storeId = '', $posId = '', $cashierId = '') {
        
        if ($storeId == '' || $posId == '' || $cashierId == '') {
            Message::add('s', '', URL . 'mdpos');
        }
        
        $storeId   = Input::param($storeId);
        $posId     = Input::param($posId);
        $cashierId = Input::param($cashierId);
        
        $cashierInfo = $this->model->getPosInfoModel($storeId, $posId, $cashierId);
        
        $result = $this->model->setSessionPosByRow($cashierInfo, Config::getFromCache('IS_USE_POSAPI_V3'));
        
        if (issetParam($cashierInfo['isclosed']) === '1') {
            Message::add('i', $this->lang->line('isClosedPos'), URL . 'mdpos/message/pos');
        } elseif ($result == 'status') {
            Message::add('s', '', URL . 'mdpos');
        } else {
            Message::add('i', issetParam($result['message']), URL . 'mdpos/message/'.$storeId.'/'.$posId.'/'.$cashierId);
        }
    }
    
    public function posConfigLoad($posv3 = false) {
        
        $this->view->isConfigSalesPerson  = Config::getFromCacheDefault('CONFIG_POS_SALESPERSON', null, 1);
        $this->view->isConfigDelivery     = Config::getFromCacheDefault('CONFIG_POS_DELIVERY', null, 1);
        $this->view->isConfigServiceJob   = Config::getFromCacheDefault('CONFIG_POS_SERVICEJOB', null, 1);
        $this->view->isConfigEmpCustomer  = Config::getFromCacheDefault('CONFIG_POS_EMPLOYEE_CUSTOMER', null, 0);
        $this->view->isConfigSerialNumber = Config::getFromCacheDefault('CONFIG_POS_SERIALNUMBER', null, 0);
        
        $this->view->isConfigItemCheckDuplicate     = Config::getFromCacheDefault('CONFIG_POS_ITEM_CHECK_DUPLICATE', null, 0);
        $this->view->isConfigItemCheckDiscountQty   = Config::getFromCacheDefault('CONFIG_POS_ITEM_CHECK_DISCOUNTQTY', null, 0);
        $this->view->isConfigIsShowItemCheckEndQty  = Config::getFromCacheDefault('CONFIG_POS_IS_SHOW_ITEM_ENDQTY_LIST', null, 0);
        $this->view->isConfigItemCheckEndQty        = Config::getFromCacheDefault('CONFIG_POS_ITEM_CHECK_ENDQTY', null, 0);
        $this->view->isConfigItemCheckEndQtyMsg     = Config::getFromCacheDefault('CONFIG_POS_ITEM_CHECK_ENDQTY_MSG', null, 0);
        $this->view->isConfigRowDiscount            = Config::getFromCacheDefault('CONFIG_POS_ROW_DISCOUNT', null, 0);
        $this->view->isConfigRowOrderDiscount       = Config::getFromCacheDefault('CONFIG_POS_ROW_ORDER_DISCOUNT', null, 0);
        $this->view->isConfigDescriptionRequired    = Config::getFromCacheDefault('CONFIG_POS_DESCRIPTION_REQUIRED', null, 0);
        $this->view->isConfigBankBilling            = Config::getFromCacheDefault('CONFIG_POS_IS_REQUIRED_BANK_BILLING_ID', null, 0);
        $this->view->isConfigOnlyInvDescrRequired   = Config::getFromCacheDefault('CONFIG_POS_ONLY_INV_DESCR_REQUIRED', null, 0);
        $this->view->isConfigUseCandy               = (Session::get(SESSION_PREFIX.'posIsUseCandy') == '1') || defined('CONFIG_POS_PAYMENT_REDPOINT') && CONFIG_POS_PAYMENT_REDPOINT ? 1 : 0;
        $this->view->isConfigRedPointItems          = (Session::get(SESSION_PREFIX.'posRedPointIsItems') == '1') ? 1 : 0;
        $this->view->isNotSendVatsp                 = (Session::get(SESSION_PREFIX.'isNotSendVatsp') == '1') ? 1 : 0;
        $this->view->isBasketOnly                   = (Session::get(SESSION_PREFIX.'isBasketOnly') == '1') ? 1 : 0;
        $this->view->isConfigInvoiceList            = Config::getFromCacheDefault('CONFIG_POS_INVOICE_LIST', null, 0);
        $this->view->isConfigContractList           = Config::getFromCacheDefault('CONFIG_POS_CONTRACT_LIST', null, 0);
        $this->view->isConfigTestPrint              = Config::getFromCacheDefault('CONFIG_POS_TEST_PRINT', null, 1);
        $this->view->isCreateDeposit                = Config::getFromCacheDefault('POS_CREATE_DEPOSIT', null, 0);
        $this->view->isReturnCustomerInfoRequired   = Config::getFromCacheDefault('CONFIG_POS_RETURN_INFO_REQUIRED', null, 1);
        $this->view->isTalonListProtect             = Config::getFromCacheDefault('CONFIG_POS_TALONLIST_PROTECT', null, 1);
        
        $this->view->isConfigPaymentCoupon          = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_COUPON', null, 0);
        $this->view->isConfigPrePayment             = Config::getFromCacheDefault('CONFIG_POS_PREPAYMENT', null, 0);
        $this->view->isConfigPaymentBonuscard       = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_BONUSCARD', null, 0);
        $this->view->isConfigPaymentDiscountActivity= Config::getFromCacheDefault('POS_PAYMENT_IS_SHOW_DISCOUNT_ACTIVITY', null, 0);
        $this->view->isConfigPaymentInsurance       = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_INSURANCE', null, 0);
        $this->view->isConfigPaymentAccountTransfer = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_ACCOUNTTRANSFER', null, 0);
        $this->view->isConfigPaymentMobilenet       = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_MOBILENET', null, 0);
        $this->view->isConfigPaymentOther           = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_OTHER', null, 0);
        $this->view->isConfigPaymentTcard           = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_T_CARD', null, 0);
        $this->view->isConfigPaymentShoppy          = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_SHOPPY', null, 0);
        $this->view->isConfigPaymentGlmtreward      = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_GOLOMT_REWARD', null, 0);
        $this->view->isConfigPaymentSocialpayreward = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_SOCIAL_PAY_REWARD', null, 0);
        $this->view->isConfigPaymentTaxInvoice      = Config::getFromCacheDefault('POS_INVOICE_PAYMENT_TYPE', null, 0);
        $this->view->isConfigPaymentBarter          = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_BARTER', null, 0);
        $this->view->isConfigPaymentLeasing         = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_LEASING', null, 0);
        $this->view->isConfigPaymentEmpLoan         = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_EMPLOAN', null, 0);
        $this->view->isConfigPaymentLocalExpense    = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_LOCALEXPENSE', null, 0);
        $this->view->isConfigPaymentUnitReceivable  = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_UNITRECEIVABLE', null, 0);
        $this->view->isConfigPaymentCandy           = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_CANDY', null, 0);
        $this->view->isConfigPaymentUpoint          = Config::get('UPOINT_API') ? 1 : 0;
        $this->view->isConfigPaymentCandyCoupon     = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_CANDY_COUPON', null, 0);
        $this->view->isConfigPaymentDelivery        = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_DELIVERY', null, 0);
        $this->view->isConfigPaymentLendMn          = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_LENDMN', null, 0);
        $this->view->isConfigClearSidebarData       = Config::getFromCacheDefault('CONFIG_POS_IS_CLEAR_SIDEBAR_DATA', null, 0);
        $this->view->isConfigServiceJobAccompany    = Config::getFromCacheDefault('CONFIG_POS_SERVICEJOB_ACCOMPANY', null, 0);
        $this->view->isConfigShowQrcode             = Config::getFromCacheDefault('CONFIG_POS_IS_SHOW_QRCODE', null, 0);
        $this->view->isConfigAccompanyItem          = Config::getFromCacheDefault('CONFIG_POS_ACCOMPANY_ITEM', null, 0);
        $this->view->posServiceRowPriceEdit         = Config::getFromCache('CONFIG_POS_SERVICE_EDIT_PRICE') ? 1 : 0;
        $this->view->posOrderTimer                  = Config::getFromCache('CONFIG_POS_SDM_ORDER_TIME') ? Config::getFromCache('CONFIG_POS_SDM_ORDER_TIME') : 0;
        $this->view->cashierInsertC1                = Config::getFromCache('CONFIG_POS_IS_CASHIER_INSERT_C1') ? 1 : 0;
        $this->view->remainderCoupon                = Config::getFromCache('CONFIG_POS_IS_USE_REMAINDER_COUPON') ? 1 : 0;
        $this->view->matrixHideSale                 = Config::getFromCache('POS_CONFIG_MATRIX_HIDE_SALE') ? 1 : 0;
        $this->view->candyCashback                  = Config::getFromCache('candyCashback') ? 1 : 0;
        $this->view->pos_candy_user_pass            = Config::getFromCache('POS_CANDY_USER_PASS') ? 1 : 0;
        $this->view->posChooseReturn                = Config::getFromCache('POS_IS_CHOOSE_INVOICE_RETURN_TYPE') ? 1 : 0;
        $this->view->isConfigPaymentReceivable      = Config::getFromCacheDefault('CONFIG_POS_PAYMENT_RECEIVABLE', null, 0);
        $this->view->isReturnValueZero              = Config::getFromCacheDefault('CONFIG_POS_IS_RETURN_VALUE_IS_ZERO', null, 0);
        $this->view->isRequiredJobDelivery          = Config::getFromCacheDefault('POS_IS_REQUIRED_MES_JOB_FOR_IS_DELIVERY', null, 0);
        $this->view->isEditCustomerInfoBook         = Config::getFromCacheDefault('POS_IS_EDIT_CUSTOMER_INFO_FROM_ORDER_BOOK', null, 0);
        $this->view->isConfigAddCustomerSidebar     = Config::getFromCacheDefault('CONFIG_POS_ADD_CUSTOMER_SIDEBAR', null, 0);
        $this->view->limitBonusAmount               = self::getLimitBonusAmount();
        $this->view->isIpad                         = self::checkIpad();
        
        $this->view->tempInvoiceDvId                = Config::get('CONFIG_POS_TEMP_INVOICE_DVID', 'postype='.Session::get(SESSION_PREFIX.'posTypeCode'));
        $this->view->tempInvoiceDvId                = $this->view->tempInvoiceDvId ? $this->view->tempInvoiceDvId : '1529014380513';
        $this->view->getDateCashier                 = $this->getDateCashier();

        if (!$posv3) {
            $this->view->getApiInfo                     = json_decode($this->getInformation(true), true);
            $_POST['regNumber']                         = issetParam($this->view->getApiInfo['registerNo']);
            $this->view->getApiNameInfo                 = json_decode($this->model->getOrganizationInfoModel(), true);
            Session::set(SESSION_PREFIX.'posVatPayerName', issetParam($this->view->getApiNameInfo['name']));
            Session::set(SESSION_PREFIX.'posVatPayerNo', issetParam($this->view->getApiInfo['registerNo']));
        } else {
            $this->view->getApiInfo                     = [];
            $this->view->getApiNameInfo                 = [];
            $_POST['regNumber']                         = '';
            Session::set(SESSION_PREFIX.'posVatPayerName', '');
            Session::set(SESSION_PREFIX.'posVatPayerNo', '');            
        }
        
        if (Config::getFromCache('CONFIG_POS_HEALTHRECIPE')) {
            
            $emdClientId     = Session::get(SESSION_PREFIX.'posEmdClientId');
            $emdClientSecret = Session::get(SESSION_PREFIX.'posEmdClientSecret');
                    
            if ($emdClientId != '' && $emdClientSecret != '') {
                $this->view->isConfigHealthRecipe = 1;
            } else {
                $this->view->isConfigHealthRecipe = 0;
            }
            
        } else {
            $this->view->isConfigHealthRecipe = 0;
        }
        
        if (Session::get(SESSION_PREFIX.'posUseIpTerminal') === '1') {
            $this->view->bankTerminalId = $this->model->getBankListModel();
            if (is_array($this->view->bankTerminalId)) {
                $this->view->bankTerminalId = $this->view->bankTerminalId[0]['terminalid'];
            }
        }
        
        $this->view->sidebarShowList = $this->model->getLeftSidebarListModel();        
        if (Config::getFromCacheDefault('POS_IS_SHOW_INVOICE_TYPE_LIST', null, 0)) {
            $this->view->getInvoiceTypeList = $this->model->getInvoiceTypeList2Model();        
        }
        $this->view->sidebarShowList = Arr::groupByArray($this->view->sidebarShowList, 'parentordernumber');
        
        $this->view->crmChoosePosition = self::sidebarCrmChoosePosition($this->view->isConfigEmpCustomer);
    }
    
    public function sidebarCrmChoosePosition($isConfigEmpCustomer) {
        
        if ($isConfigEmpCustomer) {
            
            if (Config::getFromCache('posCrmChoosePosition') == 'top') {
                
                $result = array(
                    'top' => '<tr>
                            <td colspan="2" class="text-left pb0">'.$this->lang->line('POS_0167').' <span class="infoShortcut">(Shift+C)</span>:</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-section-path="empCustomerId">
                                    <div class="input-group double-between-input">
                                        <input type="hidden" name="empCustomerId" id="empCustomerId_valueField" data-path="empCustomerId" class="popupInit">
                                        <input type="text" name="empCustomerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="empCustomerId" id="empCustomerId_displayField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="'.$this->lang->line('code_search').'" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid(\'empCustomerId\', \'1454315883636\', \'1536742182010\', \'single\', \'empCustomerId\', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                        </span>  
                                        <span class="input-group-btn">
                                            <input type="text" name="empCustomerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="empCustomerId" id="empCustomerId_nameField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="'.$this->lang->line('name_search').'" tabindex="-1" autocomplete="off">
                                        </span>   
                                    </div>
                                </div>
                            </td>
                        </tr>', 
                    'bottom' => ''
                );
                
            } else {
                
                $result = array(
                    'top' => '', 
                    'bottom' => '<tr>
                            <td colspan="2" class="text-left pb0" style="padding-top: 30px">'.$this->lang->line('POS_0167').':</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-section-path="empCustomerId">
                                    <div class="input-group double-between-input">
                                        <input type="hidden" name="empCustomerId" id="empCustomerId_valueField" data-path="empCustomerId" class="popupInit">
                                        <input type="text" name="empCustomerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="empCustomerId" id="empCustomerId_displayField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="'.$this->lang->line('code_search').'" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid(\'empCustomerId\', \'1454315883636\', \'1536742182010\', \'single\', \'empCustomerId\', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                        </span>  
                                        <span class="input-group-btn">
                                            <input type="text" name="empCustomerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="empCustomerId" id="empCustomerId_nameField" data-processid="1454315883636" data-lookupid="1536742182010" placeholder="'.$this->lang->line('name_search').'" tabindex="-1" autocomplete="off">
                                        </span>   
                                    </div>
                                </div>
                            </td>
                        </tr>'
                );
            }
            
        } else {
            $result = array('top' => '', 'bottom' => '');
        }
        
        return $result;
    }

    public function getItemByCode() {
        
        $this->view->rowData = $this->model->getItemByCodeModel();
        
        if ($this->view->rowData) {
            
            if (isset($this->view->rowData['status']) && $this->view->rowData['status'] == 'noendqty') {
                
                $response = array(
                    'status' => 'noendqty', 
                    'message' => $this->view->rowData['message'], 
                    'itemId' => $this->view->rowData['itemId']
                );
                
            } else {
                
                $gift = $this->view->renderPrint('gift', self::$viewPath);
            
                /*if ($gift) {
                    unset($this->view->rowData['rulelist']);
                    unset($this->view->rowData['policylist']);
                }*/

                $getProcessCode = $this->view->rowData['getProcessCode'];
                unset($this->view->rowData['getProcessCode']);
                $response = array(
                    'status' => 'success', 
                    'row' => $this->view->rowData, 
                    'getProcessCode' => $getProcessCode,
                    'gift' => $gift
                );
            }
            
        } else {
            $response = array(
                'status' => 'error', 
                'message' => $this->lang->line('POS_0058')
            );
        }
        
        echo json_encode($response); exit;
    }
    
    public function payment() {
        
        $this->view->billType   = 'person';
        $this->view->orgNumber  = '';
        $this->view->orgName    = '';
        
        $this->view->payAmount    = Input::post('amount');
        $this->view->vat          = Input::post('vat');
        $this->view->cashAmount   = '';
        $this->view->socialAmount = '';
        
        $this->view->bankAmountList   = array();
        $this->view->couponAmountList = array();
        $this->view->coupon2AmountList = array();
        $this->view->prePaymentAmountList = array();
        $this->view->accountTransferAmountList = array();
        
        $this->view->invInfoCustomerLastName  = '';
        $this->view->invInfoCustomerName      = '';
        $this->view->invInfoCustomerRegNumber = '';
        $this->view->invInfoPhoneNumber       = '';
        $this->view->invInfoPhoneNumber       = '';
        $this->view->invInfoTransactionValue  = '';
        $this->view->reasonReturnHtml         = '';        
        $bankList = $this->model->getBankListModel();
        $bankCardList = $this->model->getBankListCardModel();
        $bankTransferList = $this->model->getBankListTransferModel();
        
        $this->view->isDelivery = Input::post('isDelivery');
        
        if (Session::get(SESSION_PREFIX.'posUseIpTerminal') === '1') {
            $this->view->bankCombo = Form::select(
                array(
                    'name' => 'posBankIdDtl[]',
                    'data' => $bankList,
                    'op_value' => 'bankid',
                    'op_id' => 'terminalid',
                    'op_text' => 'bankname',
                    'op_custom_attr' => array(array(
                        'attr' => 'data-bankcode',
                        'key' => 'bankcode'
                    )),
                    'text' => '- '.$this->lang->line('POS_0059').' -',
                    'class' => 'form-control form-control-sm select2'
                )
            );
        } else {
            $this->view->bankCombo = Form::select(
                array(
                    'name' => 'posBankIdDtl[]',
                    'data' => $bankList,
                    'op_value' => 'bankid',
                    'op_text' => 'bankname',
                    'op_custom_attr' => array(array(
                        'attr' => 'data-bankcode',
                        'key' => 'bankcode'
                    )),                    
                    'class' => 'form-control form-control-sm select2', 
                    'text' => '- '.$this->lang->line('POS_0059').' -'
                )
            );
        }
        
        if (Session::get(SESSION_PREFIX.'posUseIpTerminal') === '1') {
            $this->view->bankCardCombo = Form::select(
                array(
                    'name' => 'posBankIdDtl[]',
                    'data' => $bankCardList,
                    'op_value' => 'bankid',
                    'op_id' => 'terminalid',
                    'op_text' => 'bankname',
                    'op_custom_attr' => array(array(
                        'attr' => 'data-bankcode',
                        'key' => 'bankcode'
                    )),
                    'text' => '- '.$this->lang->line('POS_0059').' -',
                    'class' => 'form-control form-control-sm select2'
                )
            );
        } else {
            $this->view->bankCardCombo = Form::select(
                array(
                    'name' => 'posBankIdDtl[]',
                    'data' => $bankCardList,
                    'op_value' => 'bankid',
                    'op_text' => 'bankname',
                    'op_custom_attr' => array(array(
                        'attr' => 'data-bankcode',
                        'key' => 'bankcode'
                    )),                    
                    'class' => 'form-control form-control-sm select2', 
                    'text' => '- '.$this->lang->line('POS_0059').' -'
                )
            );
        }
        
        if (Session::get(SESSION_PREFIX.'posUseIpTerminal') === '1') {
            $this->view->bankTransferCombo = Form::select(
                array(
                    'name' => 'posBankIdDtl[]',
                    'data' => $bankTransferList,
                    'op_value' => 'bankid',
                    'op_id' => 'terminalid',
                    'op_text' => 'bankname',
                    'op_custom_attr' => array(array(
                        'attr' => 'data-bankcode',
                        'key' => 'bankcode'
                    )),
                    'text' => '- '.$this->lang->line('POS_0059').' -',
                    'class' => 'form-control form-control-sm select2'
                )
            );
        } else {
            $this->view->bankTransferCombo = Form::select(
                array(
                    'name' => 'posBankIdDtl[]',
                    'data' => $bankTransferList,
                    'op_value' => 'bankid',
                    'op_text' => 'bankname',
                    'op_custom_attr' => array(array(
                        'attr' => 'data-bankcode',
                        'key' => 'bankcode'
                    )),                    
                    'class' => 'form-control form-control-sm select2', 
                    'text' => '- '.$this->lang->line('POS_0059').' -'
                )
            );
        }
        
        if (Input::postCheck('emdAmount')) {
            $this->view->emdAmount        = Input::post('emdAmount');
            $this->view->emdInsuredAmount = Input::post('emdInsuredAmount');
        } else {
            $this->view->emdAmount        = '';
            $this->view->emdInsuredAmount = '';
        }
        
        if (Input::post('isCashVoucher') != '0') {
            $this->view->isCusBankInfo = true;
        }
        
        if (Input::isEmpty('invoiceId') == false) {
            
            $invoiceId = Input::post('invoiceId');
            
            $invoiceInfo = $this->model->getInvoiceInfoByInvoiceIdModel($invoiceId);
            
            if ($invoiceInfo) {
                $this->view->invInfoCustomerLastName  = $invoiceInfo['DELIVERY_CONTACT_LASTNAME'];
                $this->view->invInfoCustomerName      = $invoiceInfo['DELIVERY_CONTACT_NAME'];
                $this->view->invInfoCustomerRegNumber = $invoiceInfo['DELIVERY_REGISTER_NUM'];
                $this->view->invInfoPhoneNumber       = $invoiceInfo['DELIVERY_CONTACT_PHONE'];
                $this->view->invInfoCustomerId        = $invoiceInfo['CUSTOMER_ID'];
                $this->view->invInfoCustomerCode      = $invoiceInfo['CUSTOMER_CODE'];
                $this->view->invInfoCustomerName      = $invoiceInfo['CUSTOMER_NAME'];
                $this->view->invInfoTransactionValue  = $this->view->invInfoCustomerLastName.' '.$this->view->invInfoCustomerName.' '.$this->view->invInfoCustomerRegNumber.' '.$this->view->invInfoPhoneNumber;
            }
            
            $this->view->addressInfo = $this->model->getAddressInfoByInvoiceIdModel($invoiceId);
            
            if (Input::isEmpty('invoiceBasketTypeId') == false) {
                
                $paymentTypeId = $this->model->getPaymentTypeIdByInvoiceTypeIdModel(Input::post('invoiceBasketTypeId'));
                
                if ($paymentTypeId == '1') {
                    
                    $this->view->cashAmount = $this->view->payAmount;
                    $this->view->paidAmount = $this->view->cashAmount;
                    
                } elseif ($paymentTypeId == '2') {
                    
                    $this->view->bankAmountList = array(array('bankid' => '', 'amount' => $this->view->payAmount));
                    $this->view->paidAmount = $this->view->payAmount;
                    
                } elseif ($paymentTypeId == '3') {
                    
                    $this->view->mobileNetAmount['amount'] = $this->view->payAmount;
                    $this->view->paidAmount = $this->view->payAmount;
                    
                } elseif ($paymentTypeId == '4') {
                    
                    //$this->view->accountTransferAmount['amount'] = $this->view->payAmount;
                    
                    $this->view->accountTransferAmountList = array(array('bankid' => '', 'amount' => $this->view->payAmount));
                    $this->view->paidAmount = $this->view->payAmount;
                    
                } elseif ($paymentTypeId == '5') {
                    
                    $this->view->barterAmount = $this->view->payAmount;
                    $this->view->paidAmount = $this->view->payAmount;
                    
                } elseif ($paymentTypeId == '6') {
                    
                    $this->view->leasingAmount['amount'] = $this->view->payAmount;
                    $this->view->paidAmount = $this->view->payAmount;
                    
                } elseif ($paymentTypeId == '7') {
                    
                    $this->view->empLoanAmount = $this->view->payAmount;
                    $this->view->paidAmount = $this->view->payAmount;
                    
                    
                } elseif ($paymentTypeId == '9') {
                    
                    $this->view->empLoanAmount = $this->view->payAmount;
                    $this->view->paidAmount = $this->view->payAmount;
                    
                } elseif ($paymentTypeId == '13') {
                    
                    $this->view->localExpenseAmount = $this->view->payAmount;
                    $this->view->paidAmount = $this->view->payAmount;
                    
                } elseif ($paymentTypeId == '20') {
                    
                    $this->view->prePaymentAmount['amount'] = $this->view->payAmount;
                    $this->view->paidAmount = $this->view->payAmount;
                    
                } elseif ($paymentTypeId == '33') {
                    
                    $this->view->liciengExpenseAmount = $this->view->payAmount;
                    $this->view->paidAmount = $this->view->payAmount;
                }
            }        
            
            if (Input::isEmpty('invoiceRow') == false) {
                $row = json_decode(html_entity_decode(Input::post('invoiceRow')), true);
                $roww = $row;
                $result = $this->model->getInvoiceByIdModel($row);            
                if ($result['status'] == 'success' && issetParam($result['data']['pos_sdm_sales_order_payment_dtl'])) {
                    $this->view->empLoanAmount = 0;

                    foreach ($result['data']['pos_sdm_sales_order_payment_dtl'] as $row) {
                        if ($roww["id"] == "test") {
                            $this->view->empLoanAmount += (float) $row['amt'];
                            $this->view->empLoanDisable = true;
                            $this->view->cashAmountDisable = true;
                            $this->view->bankAmountDisable = true;
                            $this->view->socialAmountDisable = true;
                            $this->view->discountAmountDisable = true;
                            $this->view->accountTransferAmountDisable = true;
                            $this->view->mobileTransferAmountDisable = true;
                            $this->view->couponAmountDisable = true;
                            $this->view->barterAmountDisable = true;
                            $this->view->localExpenseAmountDisable = true;
                            $this->view->certAmountDisable = true;
                            $this->view->recAmountDisable = true;
                            $this->view->paidAmount = $this->view->empLoanAmount;                                          
                        } else {
                            if ($row['paymenttypeid'] == 1) {
                                $this->view->cashAmount = $row['amt'];
                                $this->view->paidAmount = $row['amt'];                        
                            }
                            if ($row['paymenttypeid'] == 2) {
                                $this->view->bankAmountList = array(array('bankid' => '', 'amount' => $row['amt']));
                                $this->view->paidAmount = $row['amt'];                    
                            }
                            if ($row['paymenttypeid'] == 13) {
                                $this->view->localExpenseAmount = $row['amt'];
                                $this->view->paidAmount = $row['amt'];                    
                            }
                            if ($row['paymenttypeid'] == 5) {
                                $this->view->barterAmount = $row['amt'];
                                $this->view->barterDisable = $row['isdisable'];
                                $this->view->paidAmount = $row['amt'];                    
                            }
                            if ($row['paymenttypeid'] == 7) {
                                $this->view->empLoanAmount = $row['amt'];
                                $this->view->empLoanDisable = $row['isdisable'];
                                $this->view->paidAmount = $row['amt'];                    
                            }
                            if ($row['paymenttypeid'] == 22) {
                                $this->view->recievableAmount = $row['amt'];
                                $this->view->recievableDisable = $row['isdisable'];
                                $this->view->paidAmount = $row['amt'];                    
                            }
                        }
                    }
                }
            }
        }
    
        $this->view->printCopies = $this->model->defaultPrintCopies();
        
        $response = array(
            'title' => $this->lang->line('POS_0060'), 
            'html'  => $this->view->renderPrint('payment', self::$viewPath)
        );
        
        echo json_encode($response); exit;
    }
    
    public function getOrganizationInfo() {
        
        $response = $this->model->getOrganizationInfoModel();
        echo $response; exit;
    }

    public function billPrint() {

        self::$eVatNumber = Session::get(SESSION_PREFIX.'vatNumber');
        self::$eStoreCode = Session::get(SESSION_PREFIX.'storeCode');
        self::$eCashRegisterCode = Session::get(SESSION_PREFIX.'cashRegisterCode');
        self::$posHeaderName = Session::get(SESSION_PREFIX.'posHeaderName');
        self::$posLogo = Session::get(SESSION_PREFIX.'posLogo');
        self::$posVatPayerNo = Session::get(SESSION_PREFIX.'posVatPayerNo');
        self::$posVatPayerName = Session::get(SESSION_PREFIX.'posVatPayerName');            
        self::$storeId = Session::get(SESSION_PREFIX.'storeId');            
        self::$cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');           
        
        $returnInvoiceId   = Input::post('returnInvoiceId');
        $returnTypeInvoice = Input::post('returnTypeInvoice');
                
        if ($returnInvoiceId != '' && $returnTypeInvoice != '') {
            
            if ($returnTypeInvoice == 'typeCancel') {
                
                $response = $this->model->billTypeCancelPrintModel($returnInvoiceId);

            } elseif ($returnTypeInvoice == 'typeCancel2') {
                
                $response = $this->model->billTypeCancelPrintModel2($returnInvoiceId);

            } elseif ($returnTypeInvoice == 'typeCancel3') {
                
                $response = $this->model->billTypeCancelPrintModel3($returnInvoiceId);
                
            } elseif ($returnTypeInvoice == 'typeChange') {
                
                $response = $this->model->billTypeChangePrintModel2($returnInvoiceId);
                
            } elseif ($returnTypeInvoice == 'typeReduce') {
                
                $response = $this->model->billTypeReduce3PrintModel($returnInvoiceId);
                
            } elseif ($returnTypeInvoice == 'typeSalesPayment') {
                
                $_POST['id'] = $returnInvoiceId;
                $invoiceData = $this->model->getInvoiceDataModel($returnInvoiceId);
                $invoiceData['sm_invoice_dtl_dv'] = $invoiceData['pos_sm_sales_invoice_detail'];
                $_POST['responseData'] = $invoiceData;                
                $_POST['noLotteryNumber'] = 0;                

                $response = $this->model->printInvoiceResponseModel();
            }
            
        } else {
            if (Config::get('POS_IS_USE_MULTI_BILL_NOT_PACKAGE_POLICY', 'storecode='.self::$eStoreCode) === '1') {
                $response = $this->model->spliteCustomerSalesModel();
            } else {
                $response = $this->model->billPrintModel();
            }
        }
        
        echo json_encode($response); exit;
    }
    
    public function sendDataPos() {
        
        set_time_limit(0);
        
        $sPrefix = SESSION_PREFIX;
        $vatNumber = Session::get($sPrefix.'vatNumber').'\\'.Session::get($sPrefix.'storeCode').'\\'.Session::get($sPrefix.'cashRegisterCode');
        
        $data = array('function' => 'senddata', 'vatNumber' => $vatNumber);
        $response = $this->ws->redirectPost(self::getPosApiServiceAddr(), $data);
        
        var_dump($response);
        die;
    }
    
    public function getInformation($view = false) {
        
        set_time_limit(0);
        
        $sPrefix = SESSION_PREFIX;
        $vatNumber = Session::get($sPrefix.'vatNumber').'\\'.Session::get($sPrefix.'storeCode').'\\'.Session::get($sPrefix.'cashRegisterCode');
        
        $data = array('function' => 'getinformation', 'vatNumber' => $vatNumber);
        $response = $this->ws->redirectPost(self::getPosApiServiceAddr(), $data);       
        
        if (!$view) {
            pa($response);
        } else {
            return $response;
        }
    }
    
    public function getVoucherBySerialNumber() {
        
        $response = $this->model->getVoucherBySerialNumberModel();
        echo json_encode($response); exit;
    }
    
    public function getCardNumber() {
        
        $response = $this->model->getCardNumberModel();
        echo json_encode($response); exit;
    }
    
    public function getCardNumberByPhoneNumber() {
        
        $response = $this->model->getCardNumberByPhoneNumberModel();
        echo json_encode($response); exit;
    }
    
    public function testBillPrint() {
        
        $response = $this->model->getBillPromotionModel();
        $langCode = $this->lang->getCode();
        $dirPath  = '';
        
        if ($langCode != 'mn') {
            $dirPath = '/en';
        }
        
        $sessionPosLogo = Session::get(SESSION_PREFIX.'posLogo');
        $posLogo        = ($sessionPosLogo ? $sessionPosLogo : 'pos-logo.png');
        
        $template = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.Config::getFromCache('CONFIG_POS_PRINT_TYPE').$dirPath.'/testpage/single.html');
        $template = str_replace('{promotion}', $response, $template);
        
        $template = str_replace('{poslogo}', $posLogo, $template);
        $template = str_replace('{companyName}', Session::get(SESSION_PREFIX.'posHeaderName'), $template);
        $template = str_replace('{storeName}', Session::get(SESSION_PREFIX.'storeName'), $template);
        $template = str_replace('{vatNumber}', Session::get(SESSION_PREFIX.'vatNumber'), $template);
        $template = str_replace('{contactInfo}', Session::get(SESSION_PREFIX.'posContactInfo'), $template);
        
        $response = array(
            'css' => self::getPrintCss(), 
            'html' => $template
        );
        
        echo json_encode($response); exit;
    }
    
    public static function getPrintCss() {
        
        $css = '
        *{transition:none !important} 
        @page {
            margin: '.Config::getFromCache('CONFIG_POS_BILL_MARGIN').';
            width: 100%;
        } 
        body { 
            margin: 0px;  
            width: 100%;
            font-family: Tahoma;
        } 
        table {
            border-collapse: collapse;
        }
        hr {
            border-bottom: 1px #000 solid;
        }
        ul {
            padding-left: 17px; 
        }
        .pos-preview-print.display-none {
            display: block !important;
        }
        .page-break { 
            display: block; 
            page-break-before: always; 
        }';
        
        return $css;
    }
    
    public function hotkeys() {
        $response = array(
            'html'      => $this->view->renderPrint('hotkey/hotkeys', self::$viewPath), 
            'title'     => 'Hot keys', 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function fillItemsByInvoiceId() {
        
        $row = Input::post('row');
        if (issetParam($row['event']) == 'splitCalculate') {
            $result = $row['data'];
        } elseif (issetParam($row['event']) == 'multiCustomer') {
            $result = $row['data'];
        } elseif (issetParam($row['event']) == 'qrcode') {
            $row['typeid'] = 12;
            $result = $this->model->getInvoiceByIdModel($row);
        } else {
            $result = $this->model->getInvoiceByIdModel($row);
        }

        if ($result['status'] == 'success' && isset($result['data']['pos_item_list_get'])) {
            
            $this->view->storeId  = Session::get(SESSION_PREFIX.'storeId');
            $this->view->itemList = $result['data']['pos_item_list_get'];

            $renderTypeHtml = $this->view->renderPrint('items', self::$viewPath);
            $posType = Session::get(SESSION_PREFIX.'posTypeCode');

            if ($posType == '3') {
                $renderTypeHtml = $this->view->renderPrint('itemsCustomerGroup', self::$viewPath);
            } else if ($posType == '5') {
                $renderTypeHtml = $this->view->renderPrint('itemsNoGroup', self::$viewPath);
            }

            $response = array(
                'status'    => 'success', 
                'message'   => $this->lang->line('POS_0061'), 
                'orderData' => $result,
                'html'      => $renderTypeHtml
            );
            
            if (isset($result['data']['description'])) {
                $response['description'] = $result['data']['description'];
            }
            
        } else {
            $response = $result;
        }
        
        echo json_encode($response); exit;
    }
    
    public function fillItemsByContractId() {
        
        $row = Input::post('row');
        $result = $this->model->getContractByIdModel($row);

        if ($result['status'] == 'success' && isset($result['data']['fitposcontractitemlistget'])) {
            
            $this->view->storeId  = Session::get(SESSION_PREFIX.'storeId');
            $this->view->itemList = $result['data']['fitposcontractitemlistget'];

            $response = array(
                'status'  => 'success', 
                'message' => $this->lang->line('POS_0061'), 
                'html'    => $this->view->renderPrint('items', self::$viewPath)
            );
            
            if (isset($result['data']['description'])) {
                $response['description'] = $result['data']['description'];
            }
            
        } else {
            $response = $result;
        }
        
        echo json_encode($response); exit;
    }
    
    public function giftByItemRowRender($row) {
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->rowJson = '';
        $this->view->rowData = $row;
        
        if (issetParam($this->view->rowData['sdm_sales_order_item_package'])) {
            
            $this->view->packageSelected = array();
            $itemPackageList = $this->view->rowData['sdm_sales_order_item_package'];
            
            if ($itemPackageList) {
                foreach ($itemPackageList as $packageRow) {
                    $this->view->packageSelected[$packageRow['packagedtlid'].'_'.$packageRow['discountpolicyid']] = 1;
                }
            }
        }
        
        $gift = $this->view->renderPrint('gift', self::$viewPath);
        
        return array('gift' => $gift, 'rowJson' => $this->view->rowJson);
    }
    
    public function giftSelectedByItemRowRender($row) {
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->view->row = $row;
        
        $gift = $this->view->renderPrint('giftSelected', self::$viewPath);
        
        return $gift;
    }
    
    public function getItemList() {
        $response = $this->model->getItemListModel();
        echo json_encode($response); exit;
    }
    
    public function getServiceList() {
        $response = $this->model->getServiceListModel();
        echo json_encode($response); exit;
    }
    
    public function getInvoice($bookId) {
        $response = $this->model->getInvoiceDataModel($bookId);
        var_dump($response); exit;
    }
    
    public function returnCancel($bookId) {
        $response = $this->model->getInvoiceDataModel($bookId);
        var_dump($response); exit;
    }
    
    public function returnReduce($bookId) {
        $response = $this->model->getInvoiceDataModel($bookId);
        var_dump($response); exit;
    }
    
    public function returnChangeType($bookId) {
        $response = $this->model->getInvoiceDataModel($bookId);
        var_dump($response); exit;
    }
    
    public function getInvoiceRender() {
        
        $invoiceId = Input::post('invoiceId');
        $type = Input::post('type');
        $invoiceData = $this->model->getInvoiceDataModel($invoiceId);

        if ($invoiceData) {
            
            $this->view->headerData = $this->model->getHeaderDataFromInvoiceData($invoiceId, $invoiceData);
            $this->view->itemList   = $this->model->getItemDetailFromInvoiceData($invoiceData);
            
            $this->view->billType   = $this->view->headerData['billType'];
            
            $this->view->orgNumber  = $this->view->headerData['orgNumber'];
            $this->view->orgName    = $this->view->headerData['orgName'];
            
            $this->view->payAmount  = $this->view->headerData['payAmount'];

            if ($type != 'typeSalesPayment') {
                $this->view->paidAmount = $this->view->payAmount - $this->view->headerData['discountAmount'] + ($this->view->headerData['couponAmount'] ? $this->view->headerData['couponAmount'] : 0);
                
                if ($this->view->headerData['couponAmount'] > 0 && $this->view->headerData['couponAmount'] == $this->view->headerData['discountAmount']) {
                    
                    $this->view->paidAmount = $this->view->payAmount - $this->view->headerData['couponAmount'];
                    
                } elseif ($this->view->headerData['couponAmount'] > 0 && $this->view->headerData['couponAmount'] > $this->view->payAmount) {
                    
                    $this->view->paidAmount = $this->view->payAmount;
                }
                
                $this->view->vat        = $this->view->headerData['vat'];
                $this->view->cashAmount = $this->view->headerData['cashAmount'];
                
                $this->view->bonusCardAmount       = $this->view->headerData['bonusCardAmount'];
                $this->view->discountActivityAmount = $this->view->headerData['discountActivityAmount'];
                $this->view->socialAmount          = $this->view->headerData['socialAmount'];
                $this->view->upointAmount          = $this->view->headerData['upointAmount'];
                $this->view->bankAmountList        = $this->view->headerData['bankList'];
                $this->view->couponAmountList      = $this->view->headerData['couponList'];
                $this->view->coupon2AmountList     = $this->view->headerData['coupon2List'];
                
                $this->view->accountTransferAmountList = $this->view->headerData['accountTransferList'];
                $this->view->recievableAmountList = $this->view->headerData['recievableAmountList'];
                //$this->view->accountTransferAmount = $this->view->headerData['accountTransferAmount'];
                
                $this->view->mobileNetAmount       = $this->view->headerData['mobileNetAmount'];
                $this->view->recievableAmount      = $this->view->headerData['recievableAmount'];
                $this->view->barterAmount          = $this->view->headerData['barterAmount'];
                $this->view->leasingAmount         = $this->view->headerData['leasingAmount'];
                $this->view->empLoanAmount         = $this->view->headerData['empLoanAmount'];
                $this->view->emdAmount             = $this->view->headerData['emdAmount'];
                $this->view->prePaymentAmount      = $this->view->headerData['prePaymentAmount'];
                $this->view->tcardAmount           = $this->view->headerData['tcardAmount'];
                $this->view->shoppyAmount          = $this->view->headerData['shoppyAmount'];
                $this->view->glmtRewardAmount      = $this->view->headerData['glmtRewardAmount'];
                $this->view->socialPayRewardAmount = $this->view->headerData['socialPayRewardAmount'];
                $this->view->posTaxInvoiceAmt      = $this->view->headerData['posTaxInvoiceAmount'];
                $this->view->emdInsuredAmount      = $this->view->payAmount - $this->view->emdAmount;
            }
            
            $this->view->invInfoCustomerLastName  = '';
            $this->view->invInfoCustomerName      = '';
            $this->view->invInfoCustomerRegNumber = '';
            $this->view->invInfoPhoneNumber       = '';
            $this->view->invInfoTransactionValue  = '';
            $this->view->reasonReturnHtml         = '';

            $this->view->isDelivery = 0;

            $bankList = $this->model->getBankListModel();            

            $this->view->bankCombo = Form::select(
                array(
                    'name' => 'posBankIdDtl[]',
                    'data' => $bankList,
                    'op_value' => 'bankid',
                    'op_text' => 'bankname',
                    'class' => 'form-control form-control-sm select2', 
                    'op_custom_attr' => array(array(
                        'attr' => 'data-bankcode',
                        'key' => 'bankcode'
                    )),                    
                    'text' => '- '.$this->lang->line('POS_0059').' -',
                    'data-isdevice' => is_array($bankList) ? (isset($bankList[0]['is_device']) ? '1' : '0') : '0'
                )
            );
            
            $this->view->printCopies = $this->model->defaultPrintCopies();
            
            $vatDate = $this->view->headerData['vatdate'];
            
            if (Date::formatter($vatDate, 'Y-m-d') == Date::currentDate('Y-m-d')) {
                $isTodayReturn = 1;
            } else {
                $isTodayReturn = 0;
            }

            if (Config::getFromCache('POS_IS_CHOOSE_INVOICE_RETURN_TYPE')) {
                $_POST['isSystemMeta'] = 'false';
                $_POST['isDialog'] = false;
                $_POST['fillJsonParam'] = json_encode(array('salesInvoiceId' => $invoiceId));                
                $content = (new Mdwebservice())->callMethodByMeta('1594091838794');
                
                $this->view->reasonReturnHtml = $content;            
            }            

            $response = array(
                'status'        => 'success', 
                'billid'        => $this->view->headerData['billid'], 
                'invoiceNumber' => $this->view->headerData['invoicenumber'], 
                'refNumber'     => $this->view->headerData['refnumber'], 
                'billType'      => $this->view->billType, 
                'payAmount'     => $this->view->payAmount, 
                'vatdate'       => $vatDate, 
                'isTodayReturn' => $isTodayReturn, 
                'html'          => $this->view->renderPrint('items', self::$viewPath), 
                'payment'       => $this->view->renderPrint('payment', self::$viewPath)
            );

        } else {
            $response = array('status' => 'warning', 'message' => 'Warning');
        }
        
        jsonResponse($response); exit;
    }
    
    public function posApiSendDataByStore() {
        $response = $this->model->posApiSendDataByStoreModel();
        echo json_encode($response); exit;
    }
    
    public function getReceiptNumber() {
        
        $response = $this->model->getReceiptNumberModel();
        
        if ($response['status'] == 'success') {
            
            $this->view->receiptData = $response['data'];
     
            $currentDate       = new DateTime(Date::currentDate('Y-m-d H:i'));
            $receiptExpireDate = new DateTime(date('Y-m-d H:i', substr($this->view->receiptData['receiptExpireDate'], 0, 10)));
            
            if ($currentDate < $receiptExpireDate && ($this->view->receiptData['status'] == 1 || count($this->view->receiptData['receiptDetails']))) { 
                
                if ($this->view->receiptData['receiptType'] == 1) {
                    
                    $saveJson = $this->view->receiptData;
                    //unset($saveJson['receiptDetails']);
                
                    $response = array(
                        'status' => 'success', 
                        'active' => 'active', 
                        'title'  => $this->lang->line('POS_0062'), 
                        'html'   => $this->view->renderPrint('receiptForm', self::$viewPath), 
                        'saveJson'     => $saveJson, 
                        'regNumber'    => $this->view->receiptData['patientRegNo'], 
                        'tbltCount'    => $this->view->receiptData['tbltCount'], 
                        'receiptNumber'=> $this->view->receiptData['receiptNumber'], 
                        'history_btn'  => $this->lang->line('POS_0064'), 
                        'close_btn'    => $this->lang->line('close_btn')
                    );
                    
                } else {
                    
                    $title = '';
                    if ($this->view->receiptData['receiptType'] == 2) {
                        $title = $this->lang->line('POS_0065');
                    } elseif ($this->view->receiptData['receiptType'] == 3) {
                        $title = '13А';
                    } elseif ($this->view->receiptData['receiptType'] == 4) {
                        $title = $this->lang->line('POS_0066');
                    } elseif ($this->view->receiptData['receiptType'] == 5) {
                        $title = $this->lang->line('POS_0067');
                    }
                    
                    $response = array(
                        'status' => 'success', 
                        'active' => 'inactive', 
                        'title'  => $title, 
                        'html'   => $this->view->renderPrint('receiptExpiredForm', self::$viewPath), 

                        'regNumber'   => $this->view->receiptData['patientRegNo'], 
                        'history_btn' => $this->lang->line('POS_0064'), 
                        'close_btn'   => $this->lang->line('close_btn') 
                    );
                }
                
            } else {
                $response = array(
                    'status' => 'success', 
                    'active' => 'expired', 
                    'title'  => $this->lang->line('POS_0063'), 
                    'html'   => $this->view->renderPrint('receiptExpiredForm', self::$viewPath), 
                    
                    'regNumber'   => $this->view->receiptData['patientRegNo'], 
                    'history_btn' => $this->lang->line('POS_0064'), 
                    'close_btn'   => $this->lang->line('close_btn')
                );
            }
        } 
        
        echo json_encode($response); exit;
    }
    
    public function itemSerialNumberList() {
        
        $this->view->rowData = $_POST;
        $this->view->keyList = $this->view->rowData['posimitemkeylist'];
        
        unset($this->view->rowData['posimitemkeylist']);
        
        $response = array(
            'status' => 'success', 
            'title'  => $this->lang->line('POS_0068'), 
            'html'   => $this->view->renderPrint('itemSerialNumberList', self::$viewPath), 
            'close_btn' => $this->lang->line('close_btn'), 
        );
        
        echo json_encode($response); exit;
    }
    
    public function sectionSerialNumberList() {
        
        $this->view->rowData = $_POST;
        $this->view->keyList = $this->view->rowData['posimitemsectionlist'];
        
        unset($this->view->rowData['posimitemkeylist']);
        unset($this->view->rowData['posimitemsectionlist']);
        
        $response = array(
            'status' => 'success', 
            'title'  => $this->lang->line('POS_0500'), 
            'html'   => $this->view->renderPrint('sectionSerialNumberList', self::$viewPath), 
            'close_btn' => $this->lang->line('close_btn'), 
        );
        
        echo json_encode($response); exit;
    }
    
    public function posDiscountDrugImport() {
        $response = $this->model->posDiscountDrugImportModel();
        echo json_encode($response); exit;
    }
    
    public function posDiscountDrugImportView() {
        $response = $this->model->posDiscountDrugImportViewModel();
        echo json_encode($response); exit;
    }
    
    public function calcItemDiscount() {
        
        $this->view->storeId = Session::get(SESSION_PREFIX.'storeId');
        $this->view->typeList = $this->model->getDiscountTypeListModel();
        
        $response = array(
            'status' => 'success', 
            'title'  => $this->lang->line('POS_0069'), 
            'html'   => $this->view->renderPrint('calcItemDiscount', self::$viewPath), 
            'calc_btn' => $this->lang->line('POS_0070'), 
            'close_btn' => $this->lang->line('close_btn'), 
        );
        
        echo json_encode($response); exit;
    }
    
    public function emdReturnUrl($returnBillId) {
        $response = $this->model->emdReturnUrlModel($returnBillId);
        var_dump($response);die;
    }
    
    public function emdCheckPosRnoUrl($returnBillId) {
        $response = $this->model->emdCheckPosRnoUrlModel($returnBillId);
        var_dump($response);die;
    }
    
    public function notVatCustomerList() {
        
        $this->view->crmList = $this->model->getNotVatCustomerListModel();
        
        $response = array(
            'html' => $this->view->renderPrint('notVatCustomerList', self::$viewPath), 
            'title' => $this->lang->line('POS_0071'), 
            'insert_btn' => $this->lang->line('insert_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function order() {
        
        $this->view->title = $this->lang->line('POS_0072');
        $this->view->uniqId = getUID();
        $this->view->isAjaxLoad = false;
        
        $this->view->css = array_unique(array_merge(AssetNew::metaCss(), array('custom/css/pos/style.css')));
        $this->view->js = array_unique(
            array_merge(
                array(
                    'custom/addon/plugins/scannerdetection/jquery.scannerdetection.js', 
                    'custom/addon/plugins/jquery-fixedheadertable/jquery.fixedheadertable.min.js' 
                ), 
                AssetNew::metaOtherJs()
            )
        );
        $this->view->fullUrlJs = array('middleware/assets/js/pos/pos.js');
        
        $getPOSOrderSession = $this->model->setPOSOrderSessionModel();
        
        if ($getPOSOrderSession['status'] != 'success') {
            Message::add('i', $this->lang->line('POS_0073'), URL . 'mdpos/orderMessage');
        }
        
        $this->view->isAjaxLoad = false;
        
        self::posConfigLoad();
        
        $this->view->leftSidebar = $this->view->renderPrint('layout/bottom/order/leftSidebar', self::$viewPath);
        $this->view->rightSidebar = $this->view->renderPrint('layout/bottom/order/rightSidebar', self::$viewPath);
        $this->view->centerSidebar = $this->view->renderPrint('layout/bottom/order/centerSidebar', self::$viewPath);

        $this->view->layout = $this->view->renderPrint('layout/bottom/index', self::$viewPath);   
        
        $this->view->render('header', self::$viewPath);
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer');
    }

    public function orderMessage() {
        
        $this->view->title = $this->lang->line('POS_0072');
        $this->view->uniqId = getUID();
        
        $this->view->css = array_unique(array_merge(AssetNew::metaCss(), array('global/css/pos/style.css')));
        $this->view->js = AssetNew::metaOtherJs();
        
        $getPOSOrderSession = $this->model->setPOSOrderSessionModel();
        
        if ($getPOSOrderSession['status'] == 'success') {
            Message::add('s', '', URL . 'mdpos/order');
        } else {
            Session::delete('flash_messages');
            $_SESSION['flash_messages']['info'][] = $getPOSOrderSession['message'];
        }

        $this->view->render('header', self::$viewPath);
        $this->view->render('message', self::$viewPath);
        $this->view->render('footer');
    }
    
    public function orderForm() {
        
        $this->view->billNum = 1;
        $this->view->isDelivery = Input::post('isDelivery');
        $this->view->payAmount = Input::post('amount');
        
        $this->view->invoiceTypeList = $this->model->getInvoiceTypeListModel();
        
        if (Input::isEmpty('invoiceId') == false) {
            $this->view->row = $this->model->getInvoiceHeaderInfoByIdModel(Input::post('invoiceId'));
        } else {
            $this->view->row = array();
        }
        
        $response = array(
            'title' => $this->lang->line('POS_0074'), 
            'html'  => $this->view->renderPrint('orderForm', self::$viewPath), 
            'save_btn' => $this->lang->line('save_btn'), 
            'save_print_btn' => 'Хадгалаад хэвлэх', 
            'close_btn'  => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function orderSave() {
        
        $sessionUserKeyDepartmentId = Ue::sessionUserKeyDepartmentId();
        $departmentId = $sessionUserKeyDepartmentId ? $sessionUserKeyDepartmentId : Ue::sessionDepartmentId();        
        $templateId = Config::get('posOrderTemplateId', 'departmentId='.$departmentId.';');
        $templateId = $templateId ? $templateId : '1516603694039';
        
        $response = $this->model->orderSaveModel();
        
        if ($response['status'] == 'success' && $response['orderTypeId'] == 91) {
            
            $this->view->isIgnoreTemplates = true;
            $this->view->templatesCount = 2;
            $this->view->rowClass = '';
            $this->view->options = $this->view->renderPrint('options', 'middleware/views/template/options/');
            
            $response['html'] = $this->view->renderPrint('printDropdownSettings', 'middleware/views/template/');
        }
        
        $response['templateId'] = $templateId;
        $response['key'] = Config::getFromCache('CONFIG_POS_TEMP_INVOICE_KEY_FIELD') ? Config::getFromCache('CONFIG_POS_TEMP_INVOICE_KEY_FIELD') : Session::get(SESSION_PREFIX.'posTypeCode');
        
        echo json_encode($response); exit;
    }
    
    public function getAddressInfoByPhone() {
        $phoneNumber = Input::post('phoneNumber');
        $response = $this->model->getAddressInfoByPhoneModel($phoneNumber);
        echo json_encode($response); exit;
    }
    
    public function returnBillById($billId) {
        
        $returnBillId = Input::param($billId);
        
        $vatRow = $this->db->GetRow("
            SELECT 
                SB.VAT_DATE, ST.CODE AS STORE_CODE, CR.CODE AS CASH_REGISTER_CODE, ST.DEPARTMENT_ID 
            FROM SM_BILL_RESULT_DATA SB 
                INNER JOIN SM_SALES_INVOICE_HEADER H ON H.SALES_INVOICE_ID = SB.SALES_INVOICE_ID 
                INNER JOIN SM_STORE ST ON ST.STORE_ID = H.STORE_ID 
                INNER JOIN SM_CASH_REGISTER CR ON CR.CASH_REGISTER_ID = H.CASH_REGISTER_ID 
            WHERE SB.BILL_ID = '$returnBillId'");
        
        if ($vatRow) {
            
            $jsonParam = "{
                'returnBillId': '" . $returnBillId . "',
                'date': '" . str_replace(':', '=', $vatRow['VAT_DATE']) . "'
            }";
            
            $ttd       = Session::get(SESSION_PREFIX.'vatNumber');
            $vatNumber = $ttd.'\\'.$vatRow['STORE_CODE'].'\\'.$vatRow['CASH_REGISTER_CODE'];

            $data = array(
                'function'  => 'returnBill', 
                'vatNumber' => $vatNumber, 
                'jsonParam' => $jsonParam
            );
            $response = $this->ws->redirectPost(Mdpos::getPosApiServiceAddr(), $data);

            var_dump($response); die;
            
        } else {
            echo 'no vatDate';
        }
    }
    
    public function basketForm() {
        
        $this->view->billNum   = 1;
        $this->view->payAmount = Input::post('amount');
        $this->view->keyField  = Config::getFromCache('CONFIG_POS_TEMP_INVOICE_KEY_FIELD') ? Config::getFromCache('CONFIG_POS_TEMP_INVOICE_KEY_FIELD') : Session::get(SESSION_PREFIX.'posTypeCode');
        
        if ($this->view->keyField == 'customer' || $this->view->keyField == '4') {
            
            $this->view->customerId   = Input::post('customerId');
            $this->view->customerCode = Input::post('customerCardNumber');
            $this->view->customerName = Input::post('customerName');
            
            $createdUserId = Input::post('createdUserId');
            
            if ($createdUserId && $createdUserId != Ue::sessionUserKeyId()) {
                
                $response = array('status' => 'error', 'message' => $this->lang->line('POS_0075'));
                
                echo json_encode($response); exit;
            }
        }
        
        $response = array(
            'status' => 'success', 
            'type'   => $this->view->keyField,
            'title'  => $this->lang->line('POS_0076'), 
            'html'   => $this->view->renderPrint('basketForm', self::$viewPath), 
            'save_btn'  => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function printInvoice() {
        
        $printData = $this->model->printInvoiceModel();
        
        if ($printData['status'] == 'success') {
            
            $response = array(
                'status'    => 'success',
                'printData' => $printData['printData'],
                'css'       => Mdpos::getPrintCss()
            );
            
        } else {
            $response = $printData;
        }
        
        echo json_encode($response); exit;
    }
    
    public function printInvoiceResponse() {
        
        if (Input::post('reportTemplateId')) {
            $printData = $this->model->printReportTemplateInvoiceResponseModel();
        } elseif (Input::post('multi')) {
            $printData = $this->model->mailInvoiceResponseModel();
            echo json_encode($printData); exit;
        } else {
            $printData = $this->model->printInvoiceResponseModel();
        }
        
        if ($printData['status'] == 'success') {
            
            $response = array(
                'status'    => 'success',
                'printData' => $printData['printData'],
                'css'       => Mdpos::getPrintCss()
            );
            
        } else {
            $response = $printData;
        }
        
        echo json_encode($response); exit;
    }
    
    public function printInvoiceResponseTemplate() {
        /**
         * BETA
         */
        $response = array(
            'status'    => 'success',
            'data' => Input::post('responseData')
        );            

        if (!Input::post('templateId')) {
            $this->view->isIgnoreTemplates = true;
            $this->view->templatesCount = 2;
            $this->view->rowClass = '';
            $this->view->options = $this->view->renderPrint('options', 'middleware/views/template/options/');
            $response['html'] = $this->view->renderPrint('printDropdownSettings', 'middleware/views/template/');
            
            $response['templateId'] = '1595212933594491';          
            echo json_encode($response); exit;  

        } else {
        
            $printData = $this->model->printInvoiceResponseModel();
            
            if ($printData['status'] == 'success') {
               
                $response = array(
                    'status'    => 'success',
                    'data' => array_merge(Input::post('responseData'), $printData)
                );            
                $this->view->isIgnoreTemplates = true;
                $this->view->templatesCount = 2;
                $this->view->rowClass = '';
                $this->view->options = $this->view->renderPrint('options', 'middleware/views/template/options/');
                $response['html'] = $this->view->renderPrint('printDropdownSettings', 'middleware/views/template/');
                
                $response['templateId'] = Input::post('templateId');                    
                
            } else {
                $response = $printData;
            }
        }
        
        echo json_encode($response); exit;
    }
    
    public function testDrug() {
        
        $getToken = $this->model->emdGetToken('nakhia_08', 'S9G8Ue9z3JAtLZgx');
        
        if (isset($getToken['access_token'])) {
            
            $access_token = $getToken['access_token'];
            $strArr = $this->model->emdFindAll($access_token, 1, 50);
            
            var_dump($strArr);die;
        }
    }
    
    public function updateEmdJson() {
        
        $data = $this->db->GetAll("
            SELECT 
                HDR.SALES_INVOICE_ID, 
                BR.BILL_ID, 
                BR.VAT_DATE, 
                HDR.TOTAL, 
                HDR.VAT, 
                SP.AMOUNT AS EMD_AMOUNT, 
                HDR.PRESCRIPTION_NUMBER, 
                SS.CLIENT_ID, 
                SS.CLIENT_SECRET 
            FROM SM_SALES_INVOICE_HEADER HDR 
                INNER JOIN SM_SALES_INVOICE_PRESCRIPTION PRE ON PRE.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID AND PRE.IS_SENT = 0 
                INNER JOIN SM_BILL_RESULT_DATA BR ON BR.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID 
                INNER JOIN SM_SALES_PAYMENT SP ON SP.SALES_INVOICE_ID = HDR.SALES_INVOICE_ID AND SP.PAYMENT_TYPE_ID = 14 
                INNER JOIN SM_STORE SS ON SS.STORE_ID = HDR.STORE_ID 
            WHERE HDR.STORE_ID IN (1522206140674, 1522206140678) 
                AND (HDR.IS_REMOVED = 0 OR HDR.IS_REMOVED IS NULL) 
                AND HDR.PRESCRIPTION_NUMBER IS NOT NULL 
                AND HDR.CREATED_DATE BETWEEN TO_DATE('2018-09-01', 'YYYY-MM-DD') AND TO_DATE('2018-09-30', 'YYYY-MM-DD')");
        
        $r = '';
        
        if ($data) {
            
            foreach ($data as $row) {
            
                $detail = $this->db->GetAll("
                    SELECT 
                        DTL.*, 
                        IM.ITEM_NAME, 
                        DD.TBLTPACKINGCNT 
                    FROM SM_SALES_INVOICE_DETAIL DTL 
                        INNER JOIN IM_ITEM IM ON IM.ITEM_ID = DTL.PRODUCT_ID   
                        INNER JOIN IM_DISCOUNT_DRUG DD ON DD.ID = IM.OLD_ITEM_CODE 
                    WHERE DTL.SALES_INVOICE_ID = " . $row['SALES_INVOICE_ID']);

                if ($detail) {
                    
                    $dataParams = array(
                        'posRno'        => $row['BILL_ID'], 
                        'salesDate'     => strtotime($row['VAT_DATE']).'000', 
                        'status'        => 1, 
                        'totalAmt'      => sprintf("%.2f", $row['TOTAL']), 
                        'vatAmt'        => sprintf("%.2f", $row['VAT']), 
                        'insAmt'        => sprintf("%.2f", $row['EMD_AMOUNT']), 
                        'netAmt'        => sprintf("%.2f", ($row['TOTAL'] - $row['EMD_AMOUNT'])), 
                        'receiptNumber' => $row['PRESCRIPTION_NUMBER']
                    );

                    $getToken = $this->model->emdGetToken($row['CLIENT_ID'], $row['CLIENT_SECRET']);
                    $ebarimtDetails = array();

                    foreach ($detail as $item) {

                        $ebarimtDetails[] = array(
                            'barCode'     => $item['BARCODE'], 
                            'productName' => $item['ITEM_NAME'], 
                            'quantity'    => $item['INVOICE_QTY'] * $item['TBLTPACKINGCNT'],  
                            'insAmt'      => $item['UNIT_RECEIVABLE'], 
                            'totalAmt'    => $item['LINE_TOTAL_AMOUNT'], 
                            'price'       => $item['UNIT_AMOUNT']
                        );
                    }

                    $dataParams['ebarimtDetails'] = $ebarimtDetails;

                    if (isset($getToken['access_token'])) {

                        $accessToken = $getToken['access_token'];
                        $getSendData = $this->model->emdSendData($accessToken, $dataParams);

                        if (isset($getSendData['msg']) && isset($getSendData['code']) && $getSendData['code'] == '200') {

                            $isEmdSent = 1;  

                        } else {
                            
                            $isEmdSent = 0;

                            if (isset($getSendData['error_description'])) {
                                $errorMessage = 'Send: '.(isset($getSendData['error']) ? $getSendData['error'] : 'null').' - '.$getSendData['error_description'];
                            } else {
                                $errorMessage = 'Send: Response Null';
                            }
                        }

                    } else {

                        $isEmdSent = 0;  

                        if (isset($getToken['error_description'])) {
                            $errorMessage = 'Get Token: '.(isset($getToken['error']) ? $getToken['error'] : 'null').' - '.$getToken['error_description'];
                        } else {
                            $errorMessage = 'Get Token: Response Null';
                        }
                    }

                    $updateData = array(
                        'IS_SENT' => $isEmdSent, 
                        'ERROR_MSG' => $errorMessage
                    );

                    $this->db->AutoExecute('SM_SALES_INVOICE_PRESCRIPTION', $updateData, 'UPDATE', 'SALES_INVOICE_ID = '.$row['SALES_INVOICE_ID']);
                    $this->db->UpdateClob('SM_SALES_INVOICE_PRESCRIPTION', 'SEND_JSON', json_encode($dataParams), 'SALES_INVOICE_ID = '.$row['SALES_INVOICE_ID']);

                    $r .= $isEmdSent . '<br />';
                    
                }
            }
        }
        
        echo $r; exit;
    }
    
    // EMD Resend data 

    public function resendemd() {
        $data = $this->model->getEmdInvoiceHeaderData();
        $response = '';
        $isEmdSent = 0;
        if ($data) {
            foreach ($data as $row) {
                $resData = $this->model->getEmdData($row['SALES_INVOICE_ID']);
                if($resData){

                    $dataParams = array(
                        'posRno'        => $row['BILL_ID'], 
                        'salesDate'     => strtotime($row['VAT_DATE']).'000', 
                        'status'        => 1, 
                        'totalAmt'      => sprintf("%.2f", $row['TOTAL']), 
                        'vatAmt'        => sprintf("%.2f", $row['VAT']), 
                        'insAmt'        => sprintf("%.2f", $row['EMD_AMOUNT']), 
                        'netAmt'        => sprintf("%.2f", ($row['TOTAL'] - $row['EMD_AMOUNT'])), 
                        'receiptNumber' => $row['PRESCRIPTION_NUMBER']
                    );

                    $ebarimtDetails = array();
                    $getToken =  $this->model->emdGetToken($row['CLIENT_ID'], $row['CLIENT_SECRET']);
                    
                    foreach($resData as $item){
                        $ebarimtDetails[] = array(
                            'barCode'     => $item['BARCODE'], 
                            'productName' => $item['ITEM_NAME'], 
                            'quantity'    => $item['INVOICE_QTY'] * $item['TBLTPACKINGCNT'],  
                            'insAmt'      => $item['UNIT_RECEIVABLE'], 
                            'totalAmt'    => $item['LINE_TOTAL_AMOUNT'], 
                            'price'       => $item['UNIT_AMOUNT']
                        );
                    }

                    if(isset($getToken['access_token'])){
                        $accessToken = $getToken['access_token'];
                        $getSendData = $this->model->emdSendData($accessToken, $dataParams);
                        if(isset($getSendData['msg']) && isset($getSendData['code']) && $getSendData['code'] == '200'){
                            $isEmdSent = 1;
                            $response = '{message: Success}';
                        } else { 
                            $isEmdSent = 0;
                            $response = $getSendData['msg'];
                        }
                        
                    } else {
                        $isEmdSent = 0;  
                        if (isset($getToken['error_description'])) {
                            $errorMessage = 'Get Token: '.(isset($getToken['error']) ? $getToken['error'] : 'null').' - '.$getToken['error_description'];
                        } else {
                            $errorMessage = 'Get Token: Response Null';
                        }
                        $response = $errorMessage;
                    }

                    $updateData = array(
                        'IS_SENT' => $isEmdSent, 
                        'ERROR_MSG' => $errorMessage
                    );

                    $this->db->AutoExecute('SM_SALES_INVOICE_PRESCRIPTION', $updateData, 'UPDATE', 'SALES_INVOICE_ID = '.$row['SALES_INVOICE_ID']);
                    $this->db->UpdateClob('SM_SALES_INVOICE_PRESCRIPTION', 'SEND_JSON', json_encode($dataParams), 'SALES_INVOICE_ID = '.$row['SALES_INVOICE_ID']);
               }else{
                    $response = 'Хэргэлэчийн invoice мэдээлэл олдсонгүй.';
               }
            }
        }else{
            $response = 'Хэргэлэчийн мэдээлэл олдсонгүй.';
        }   

        if($isEmdSent == 1) $isEmdSent = true; else $isEmdSent = false;
        $response = array('message'=> $response, 'success'=> $isEmdSent);
        echo json_encode($response);
        exit;
    }


    public function getCustomerInfoByRegNumber() {
        
        $response = $this->model->getCustomerInfoByRegNumberModel();
        echo json_encode($response); exit;
    }
    
    public function searchAccountStatementForm() {
        
        $this->view->bankId   = Input::post('bankId');
        $this->view->bankName = Input::post('bankName');
        
        $response = array( 
            'title'  => 'Хуулга хайх', 
            'html'   => $this->view->renderPrint('searchAccountStatement', self::$viewPath), 
            'insert_btn' => $this->lang->line('insert_btn'), 
            'close_btn'  => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function searchAccountStatement() {
        
        $response = $this->model->searchAccountStatementModel();
        echo json_encode($response); exit;
    }
    
    public function filterAccountStatement() {
        
        $response = $this->model->filterAccountStatementModel();
        echo json_encode($response); exit;
    }
    
    public function candyGetToken() {
        
        $params = array(
            'code' => '0292022737b58de702f0e76284851394', 
            'grant_type' => 'authorization_code', 
            'redirect_uri' => 'localhost', 
            'client_id' => 'eIzfDXHPvHYSBIo7',
            'client_secret' => 'QnsdV3LkaEnHcxjN4WQU2ZPgBOB1Z1LnQjOO3pwHJLBMAaF9zVByzjMKMYduw8erg1qaaTA8XlcY7lMvPNL1pZRi5MEUktb8ydmMvlYmVvgYVuPBmqbHO03CqHIPq7iU',
            'branch' => 'Test_MF'
        );
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.candy.mn/oauth/authorization/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true, 
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            )
        ));     

        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            var_dump('Error: ' . $err);
        } else {
            var_dump('Dahin refresh hiivel ug token irehgui gedgiig anhaarna uu: ' . $response);
        }
        
        die;
    }
    
    public function candyGetTransaction() {
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.candy.mn/resource/partner/v1/transaction?limit=3&offset=3',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HEADER => false, 
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer 2b71af573da51d253b770ba775a34fdd1f21772282d69582a265b23f39ecd71f2ba8f593f3a7eb983e7e156b3dd4361ab9bd8736aa3e2145205e0091ae23ed63',
                'Content-Type: application/json'
            )
        ));  
        
        $response = curl_exec($curl);       
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            var_dump('Error: ' . $err);
        } else {
            var_dump($response);
        }
        
        die;
    }
    
    public function candyback() {
        
    
        $phoneresponse = $this->model->candycashbackModel();

        if ($phoneresponse['status'] == 'error') {
            echo json_encode($phoneresponse); exit;
        }

        $this->view->phone = $phoneresponse['message']['rewardPhone'];

        $response = array( 
            'status' => 'success',
            'title'  => 'Monpay Cash back', 
            'html'   => $this->view->renderPrint('candy/candycashback', self::$viewPath), 
            'close_btn'  => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }

    public function candyCashBackAction() {
        
        $response = $this->model->candyCashBackActionModel();
        echo json_encode($response); exit;
    }

    public function candyCoupen() {
        
        $response = array( 
            'title'  => 'Монпэй купон', 
            'html'   => $this->view->renderPrint('candy/candyCoupenForm', self::$viewPath), 
            'close_btn'  => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }

    public function candyCoupenCode() {
        $qrcode = Input::post('qrCode');
        $response = $this->model->candySendCoupenModel($qrcode);
        echo json_encode($response); exit;
    }

    public function searchCandy() {
        
        $this->view->amount = Input::post('amount');
        
        $response = array( 
            'title'  => 'Монпэй', 
            'html'   => $this->view->renderPrint('candy/candyForm', self::$viewPath), 
            'close_btn'  => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function candySendTanCode() {
        
        $response = $this->model->candySendTanCodeModel();
        echo json_encode($response); exit;
    }
    
    public function candyConfirmTanCode() {
        
        $response = $this->model->candyConfirmTanCodeModel();
        echo json_encode($response); exit;
    }
    
    public function candyConfirmPinCode() {
        
        $response = $this->model->candyConfirmPinCodeModel();
        echo json_encode($response); exit;
    }
    
    public function candyGenerateQrCode() {
        
        $response = $this->model->candyGenerateQrCodeModel();
        
        if ($response['status'] == 'success') {
            
            $this->view->base64QrCodeImg = $this->model->getQrCodeImg($response['qrcode'], '250px');
            
            $response = array( 
                'status' => 'success', 
                'html'   => $this->view->renderPrint('candy/candyQrCode', self::$viewPath), 
                'uuid'   => $response['uuid'], 
                'title'  => 'Monpay QR Code', 
                'close_btn' => $this->lang->line('close_btn')
            );
        } 
        
        echo json_encode($response); exit;
    }
    
    public function candyCheckQrCode() {
        
        $response = $this->model->candyCheckQrCodeModel();
        echo json_encode($response); exit;
    }
    
    public function candyChargeQrCode() {
        
        $response = $this->model->candyChargeQrCodeModel();
        echo json_encode($response); exit;
    }
    
    public function printAskLoyaltyPoint() {
        
        $this->view->pointData = $this->model->printAskLoyaltyPointModel();
        
        if ($this->view->pointData['status'] == 'success') {
            if (Session::get(SESSION_PREFIX.'posRedPointIsAward') != '1' && Session::get(SESSION_PREFIX.'posIsUseCandy') != '1') {
                $this->view->pointData['status'] = 'directprint';
                echo json_encode($this->view->pointData); exit;
            }
            
            $this->view->isRedpoint = (Session::get(SESSION_PREFIX.'posRedPointIsAward') == '1') ? true : false;
            
            $response = array( 
                'status' => 'success', 
                'html'   => $this->view->renderPrint('loyalty/form', self::$viewPath), 
                'title'  => 'Урамшуулал олгох', 
                'print_btn' => $this->lang->line('print_btn'), 
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = $this->view->pointData;
        }
        
        echo json_encode($response); exit;
    }
    
    public function posDiscountDrugImportTest() {
        
        $getToken = $this->model->emdGetToken();
        
        if (isset($getToken['access_token'])) {
            
            $access_token = $getToken['access_token'];
            $strArr = $this->model->emdFindAll($access_token, 1, 2000);
            
            if (isset($strArr['data'])) {
                
                $itemList = $strArr['data'];
                
                $userId      = Ue::sessionUserKeyId();
                $currentDate = Date::currentDate();

                foreach ($itemList as $row) {
                    
                    if ($row['status'] == 3) {
                        $insertParam = array(
                            'ID'              => $row['id'], 
                            'TBLTNAMEMON'     => $row['tbltNameMon'], 
                            'TBLTNAMEINTER'   => $row['tbltNameInter'], 
                            'TBLTNAMESALES'   => $row['tbltNameSales'], 
                            'TBLTTYPE'        => $row['tbltType'], 
                            'TBLTSIZEUNIT'    => $row['tbltSizeUnit'], 
                            'TBLTSIZEMIXTURE' => $row['tbltSizeMixture'], 
                            'TBLTMANUFACTURE' => $row['tbltManufacture'], 
                            'TBLTCOUNTRYID'   => $row['tbltCountryId'], 
                            'TBLTBARCODE'     => $row['tbltBarCode'], 
                            'TBLTLIFEDATE'    => $row['tbltLifeDate'], 
                            'TBLTISDISCOUNT'  => $row['tbltIsDiscount'], 
                            'STATUS'          => $row['status'], 
                            'TBLTDISCOUNTPERC'=> $row['tbltDiscountPerc'], 
                            'TBLTPACKINGCNT'  => $row['tbltPackingCnt'], 
                            'TBLTMAXPRICE'    => $row['tbltMaxPrice'], 
                            'TBLTDISCOUNTAMT' => $row['tbltDiscountAmt'], 
                            'TBLTTYPENAME'    => $row['tbltTypeName'], 
                            'TBLTGROUP'       => $row['tbltGroup'], 
                            'TBLTDIAGNOSIS'   => $row['tbltDiagnosis'], 
                            'TBLTMAXDAY'      => $row['tbltMaxDay'], 
                            'DESCRIPTION'     => $row['description'], 
                            'GROUPCODE'       => $row['groupCode'], 
                            'COSTVALUE'       => $row['costValue'], 
                            'TBLTREGCODE'     => $row['tbltRegCode'],
                            'CREATED_USER_ID' => $userId, 
                            'CREATED_DATE'    => $currentDate  
                        );

                        $this->db->AutoExecute('IM_DISCOUNT_DRUG_B', $insertParam);
                    }
                }

                $response = array('status' => 'success', 'message' => $this->lang->line('POS_0123'));

            } else {
                $response = array('status' => 'error', 'message' => $strArr['error_description']);
            }
            
        } else {
            $response = array('status' => 'error', 'message' => $getToken['error_description']);
        }
        
        var_dump($response);
    }
    
    public function getRedPointBalance() {
        $response = $this->model->getRedPointBalanceModel();
        echo json_encode($response); exit;
    }
    
    public function redPointItems() {
        
        $this->view->items = $this->model->redPointItemsModel();
        
        if ($this->view->items['status'] == 'success') {
            
            $this->view->rate = $this->view->items['rate'];
            $this->view->items = $this->view->items['items'];
            
            $response = array( 
                'status' => 'success', 
                'html'   => $this->view->renderPrint('loyalty/redPointItems', self::$viewPath), 
                'title'  => 'RedPoint бараанууд', 
                'insert_btn' => $this->lang->line('insert_btn'), 
                'close_btn' => $this->lang->line('close_btn')
            );
            
        } else {
            $response = $this->view->items;
        }
        
        echo json_encode($response); exit;
    }
    
    public function fillRedPointItems() {
        
        $result = $this->model->getItemsByCodeModel();

        if ($result['status'] == 'success' && isset($result['data'])) {
            
            $this->view->storeId  = Session::get(SESSION_PREFIX.'storeId');
            $this->view->itemList = $result['data'];

            $response = array(
                'status' => 'success', 
                'html'   => $this->view->renderPrint('items', self::$viewPath)
            );
            
        } else {
            $response = $result;
        }
        
        echo json_encode($response); exit;
    }
    
    public function saveBankNotes() {
        
        $bankNotes = Input::post('bankNotes');
        
        $response = $this->model->saveBankNotesModel($bankNotes);
        echo json_encode($response); exit;
    }
    
    public function checkTalonListPass() {
        $response = $this->model->checkTalonListPassModel();
        echo json_encode($response); exit;
    }
    
    public function bankNotesPrint() {
        
        $langCode = $this->lang->getCode();
        $data = $this->model->getPosBankNotesModel();
        $bankNoteList = '';
        
        $template = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/banknotes/banknotes.html');
        
        $template = str_replace('{storeName}', Session::get(SESSION_PREFIX.'storeName'), $template);
        $template = str_replace('{posName}', Session::get(SESSION_PREFIX.'cashRegisterName'), $template);
        $template = str_replace('{cashierName}', Session::get(SESSION_PREFIX.'cashierName'), $template);
        $template = str_replace('{cashierCode}', Session::get(SESSION_PREFIX.'cashierCode'), $template);
        $template = str_replace('{date}', Date::currentDate('Y/m/d'), $template);
        $template = str_replace('{printDate}', Date::currentDate('Y/m/d H:i:s'), $template);
        
        $totalAmount = 0;
        $bankNotes = array('20000', '10000', '5000', '1000', '500', '100', '50', '20', '10', '5', '1');
        
        foreach ($bankNotes as $bankNote) {
            
            $qty = $amount = 0;
            
            if ($data && isset($data[$bankNote])) {
                $qty = $data[$bankNote];
                $amount = $bankNote * $qty;
                $totalAmount += $amount;
            }
            
            $bankNoteList .= '<tr>
                <td style="text-align: left;">
                    '.$this->model->posAmount($bankNote).'-н дэвсгэрт
                </td>
                <td style="text-align: right;">
                    '.$this->model->posAmount($qty).'
                </td>
                <td style="text-align: right;">
                    '.$this->model->posAmount($amount).'
                </td>
            </tr>';
        }
        
        $template = str_replace('{bankNoteList}', $bankNoteList, $template);
        $template = str_replace('{totalAmount}', $this->model->posAmount($totalAmount), $template);
        $template = str_replace('{totalAmount}', $this->model->posAmount($totalAmount), $template);
        if ($data) {
            $template = str_replace('{localCost}', (isset($data['localCost']) ? $this->model->posAmount($data['localCost']) : 0), $template);
        } else {
            $template = str_replace('{localCost}', 0, $template);
        }
        
        $response = array(
            'css' => self::getPrintCss(), 
            'html' => $template
        );
        
        echo json_encode($response); exit;
    }
    
    public function getInfoLocker() {
        $resultArr = array();
        $criteria = array(
            'keycode' => array(
                array(
                    'operator' => '=',
                    'operand' => Input::post('keycode')
                )
            )
        );

        $paramGroup = array(
            'systemMetaGroupId' => '1564466625130',
            'showQuery' => '0',
            'ignorePermission' => 1,
        );
        $paramGroup['criteria'] = $criteria;

        $dataGroup = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $paramGroup);

        if (isset($dataGroup['result']) && $dataGroup['result']) {
            $resultArr = $dataGroup['result'][0];
        }
        jsonResponse($resultArr);
    }
    
    public function orderSaveNotSendVat() {
        
        $response = $this->model->orderSaveNotSendVatModel();
        echo json_encode($response); exit;
    }    
    
    public function checkLoadLocker() {
        $response = array();
        
        if (Input::isEmpty('selectedRow') === false) {
            $this->view->getLocker = Input::post('selectedRow');
            $this->view->getLocker['typeid'] = '5';
            $result = $this->model->getInvoiceByIdModel($this->view->getLocker);
            
            if ($result['status'] == 'success' && $result['data']) {
                $this->view->getLocker = $result['data'];
                if (isset($this->view->getLocker['alertmsg']) && $this->view->getLocker['alertmsg']) {
                    $response['message'] = $this->view->getLocker['alertmsg'];
                }
            } else {
                $response['message'] = 'Локерийн дугаар олдсонгүй!';
            }            
        }
        echo json_encode($response); exit;        
    }    
    
    public function getPosTerminalId() {
        $bankTerminalId = 'empty';
        $bankType = Input::post('bankType');
        
        if (Session::get(SESSION_PREFIX.'posUseIpTerminal') === '1') {
            $getBankTerminalId = $this->model->getBankListModel();

            if (is_array($getBankTerminalId)) {
                if ($bankType) {
                    foreach ($getBankTerminalId as $row) {
                        if ($row['bankcode'] === '150000' && $bankType === 'glmt') {
                            $bankTerminalId = $row['terminalid'];
                        } elseif ($row['bankcode'] === '400000' && $bankType === 'tdb') {
                            $bankTerminalId = $row['terminalid'];
                        } elseif ($row['bankcode'] === '500000' && $bankType === 'khaan') {
                            $bankTerminalId = $row['terminalid'];
                        }
                    }                
                } else {
                    $bankTerminalId = $getBankTerminalId[0]['terminalid'];
                }                
            }
        }        
        echo $bankTerminalId;
    }
    
    public function socialPaySendInvoice() {
        $params = array(
            'phoneNumber' => Input::post('phone'),
            'amount' => Input::post('amount'),
            'terminalId' => Session::get(SESSION_PREFIX.'posSocialPayTerminal')
        );
        jsonResponse($this->model->socialPaySendInvoiceModel($params));
    }
    
    public function socialPayCheckInvoice() {
        $params = array(
            'amount' => Input::post('amount'),
            'id' => Input::post('id'),
            'terminalId' => Session::get(SESSION_PREFIX.'posSocialPayTerminal')
        );
        jsonResponse($this->model->socialPayCheckInvoiceModel($params));
    }
    
    public function socialPayGetInvoiceQr() {
        $params = array(
            'amount' => Input::post('amount'),
            'terminalId' => Session::get(SESSION_PREFIX.'posSocialPayTerminal')
        );
        jsonResponse($this->model->socialPayGetInvoiceQrModel($params));
    }    
    
    public function socialPaySetlement() {
        $params = array(
            'terminalId' => Session::get(SESSION_PREFIX.'posSocialPayTerminal')
        );
        jsonResponse($this->model->socialPaySetlementModel($params));
    }    
    
    public function socialPayCancel() {
        $params = array(
            'amount' => Input::post('amount'),
            'id' => Input::post('id'),
            'terminalId' => Session::get(SESSION_PREFIX.'posSocialPayTerminal')
        );        
        jsonResponse($this->model->socialPayCancelInvoiceModel($params));
    }  
    
    public function upointCancel() {
        parse_str($_POST['paymentData'], $paymentData);
        $totalAmount = Number::decimal($paymentData['payAmount']);
        
//        $params = array(
//            'device_id' => Session::get(SESSION_PREFIX.'cashRegisterCode'),
//            'receipt_id' => Input::post('transactionId'),
//            'refund_spend_amount' => Input::post('amount2') <= Input::post('amount') ? Input::post('amount2') : Input::post('amount'),
//            'refund_bonus_amount' => Input::post('returnBillType') == 'typeReduce' ? (Input::post('amount2') > Input::post('amount') ? Input::post('amount2') - Input::post('amount') : (Input::post('amount') > Input::post('amount2') ? Input::post('amount') - Input::post('amount2') : 0)) : Input::post('amount2'),
//            'refund_cash_amount' => 0,
//            "bank" => "",
//            "manufacturer" => "",
//            "items" => ""            
//        );     
        
        $spendAmt = (Input::post('amount') * Input::post('amount2')) / Input::post('totalAmount');
        $params = array(
            'device_id' => Session::get(SESSION_PREFIX.'cashRegisterCode'),
            'receipt_id' => Input::post('transactionId'),
            'refund_spend_amount' => round($spendAmt),
            // 'refund_bonus_amount' => round(Input::post('amount2') - $spendAmt),
            'refund_bonus_amount' => Input::post('upointIntAmt'),
            'refund_cash_amount' => 0,
            "bank" => "",
            "manufacturer" => "",
            "items" => ""            
        );
                
        $bankAmount = Number::decimal($paymentData['bankAmount']);
        if ($bankAmount > 0) {
            $bankAmountDtl = $paymentData['bankAmountDtl'];
            $paramsUBank = array();
            
            foreach ($bankAmountDtl as $b => $bankDtlAmount) {
                
                $bankId         = $paymentData['posBankIdDtl'][$b];
                $bankCode = '';
                $bankDtlAmount  = Number::decimal($bankDtlAmount);
                $param = array(
                    'systemMetaGroupId' => $this->model->bankInfoByStoreIdDvId,
                    'ignorePermission' => 1, 
                    'showQuery' => 0,
                    'criteria' => array(
                        'bankid' => array(
                            array(
                                'operator' => '=',
                                'operand' => $bankId
                            )
                        )
                    )
                );

                $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

                if (isset($result['result']) && isset($result['result'][0])) {
                    unset($result['result']['aggregatecolumns']);
                    unset($result['result']['paging']);
                    $bankCode = $result['result'][0]['upointbankcode'];
                }       
                        
                if ($bankId != '' && $bankDtlAmount > 0) {
                    
                    $paramsUBank[] = array(
                        'bank_code'        => $bankCode,
                        'non_cash_amount'  => $bankDtlAmount
                    );
                }
            }
            $params['bank'] = $paramsUBank;
        }        
        
        jsonResponse($this->model->upointCancelInvoiceModel($params));
    }  
    
    public function moneyBill() {
        $storeId        = Session::get(SESSION_PREFIX.'storeId');
        $cashRegisterId = Session::get(SESSION_PREFIX.'cashRegisterId');
        $cashierId      = Session::get(SESSION_PREFIX.'cashierId');
        $createdUserId  = Ue::sessionUserKeyId();
        $createdDate    = Date::currentDate('Y-m-d');
        $isExist = $cashierId ? 0 : 1;
        $getInvoiceDate = $this->model->getDateCashierModel();
        
        if (is_array($getInvoiceDate['result']) && $getInvoiceDate['result']['bookdate']) {
            $createdDate = $getInvoiceDate['result']['bookdate'];
        }          

        if (Input::post('open') && $cashierId) {
            $isExist = $this->db->GetOne("SELECT ID FROM SM_BANKNOTES WHERE LOCAL_COST IS NULL AND TYPE_ID = 1 AND STORE_ID = $storeId AND CASH_REGISTER_ID = $cashRegisterId AND CREATED_USER_ID = $createdUserId AND CREATED_CASHIER_ID = $cashierId AND TRUNC(CREATED_DATE) = '$createdDate'");
            $isExist = $isExist ? 1 : 0;
        }              
        
        $response = array(
            'title' => 'Мөнгөн дэвсгэрт', 
            'html' => $this->view->renderPrint('moneybill/moneybill', 'middleware/views/common/'), 
            'insert_btn' => 'Оруулах (F8)', 
            'isExist' => $isExist,
            'close_btn' => $this->lang->line('close_btn')
        );
        
        echo json_encode($response); exit;
    }
    
    public function posCloseIpTerminal() {
        Session::set(SESSION_PREFIX.'posUseIpTerminal', '0');
        return;
    }
    
    public function posOpenIpTerminal() {
        Session::set(SESSION_PREFIX.'posUseIpTerminal', '1');
        return;
    }
    
    public function saveDateCashier() {
        Session::set(SESSION_PREFIX.'selectedDateCashier', Input::post('askDateInput'));
        
        $checkCashier = $this->getDateCashier();
        
        $params = array(
            'rotationDate' => Input::post('askDateInput'),
            'createdDate' => Date::currentDate(),
            'startDate' => Date::currentDate(),
            'createdUserId' => Ue::sessionUserKeyId(),
            'storeId' => Session::get(SESSION_PREFIX.'storeId'),
            'cashRegisterId' => Session::get(SESSION_PREFIX.'cashRegisterId'),
            'cashierId' => Session::get(SESSION_PREFIX.'cashierId')
        );        
        if ($checkCashier) {
            $params['id'] = $checkCashier['id'];
            jsonResponse(array('status' => 'success'));
        }
        
        jsonResponse($this->model->saveDateCashierModel($params));
    }      
    
    public function saveCloseDateCashier() {
        $checkCashier = $this->getDateCashier();
        
        $params = array(
            'createdDate' => Date::currentDate(),
            'endDate' => Input::post('closeEndDate'),            
            'isClosed' => '1',
            'createdUserId' => Ue::sessionUserKeyId(),
            'storeId' => Session::get(SESSION_PREFIX.'storeId'),
            'cashRegisterId' => Session::get(SESSION_PREFIX.'cashRegisterId'),
            'cashierId' => Session::get(SESSION_PREFIX.'cashierId')
        );        
        if ($checkCashier) {
            $params['id'] = $checkCashier['id'];
            $params['rotationDate'] = issetParam($checkCashier['bookdate']);
            $params['startDate'] = issetParam($checkCashier['startdate']);
        }
        
        $response = $this->model->saveDateCashierModel($params, issetParam($checkCashier['bookdate']));
        
        if ($response['status'] === 'success') {
            Session::delete(SESSION_PREFIX.'cashierId');
        }
        
        jsonResponse($response);
    }      

    public function checkCloseDateCashier() {
        $countOrder = $this->model->getBasketOrderBookCountModel();
        echo $countOrder;
    }
    
    private function getDateCashier() {
        $result = $this->model->getDateCashierModel();
        
        if ($result['status'] === 'success') {
            return $result['result'];
        }
        return null;
    }      
    
    public function getDates() {
        $checkCashier = $this->getDateCashier();
        
        if ($checkCashier) {
            jsonResponse(array_merge(array(
                'date1' => date('Y-m-d', strtotime(Date::currentDate('Y-m-d') . ' - 1 days')), 
                'date2' => Date::currentDate('Y-m-d'),
                'isExist' => '1',
                'datetime' => Date::currentDate()
            ), $checkCashier));
            
        } else {
            
            jsonResponse(array(
                'date1' => date('Y-m-d', strtotime(Date::currentDate('Y-m-d') . ' - 1 days')), 
                'date2' => Date::currentDate('Y-m-d'),
                'isExist' => '0',
                'datetime' => Date::currentDate()
            ));            
        }
    }
    
    public function billRePrint() {
        $sPrefix = SESSION_PREFIX;
        $getRowsData = Input::post('selectedRows');
        $districtCode   = Session::get($sPrefix.'posDistrictCode');
        $billType = $taxType = '1';
        $reportMonth = $customerNo = '';
        $storeId = Session::get($sPrefix.'storeId');
        $topTitle = Session::get($sPrefix.'posHeaderName');
        $vatNumber = Session::get($sPrefix.'vatNumber');
        $printType = Config::getFromCache('CONFIG_POS_PRINT_TYPE');
        $dirPath = '';
        $sessionPosLogo = Session::get(SESSION_PREFIX.'posLogo');
        $contactInfo = Session::get($sPrefix.'posContactInfo');
        $posLogo = ($sessionPosLogo ? $sessionPosLogo : 'pos-logo.png');
        $cashRegisterId = Session::get($sPrefix.'cashRegisterId');
        $refNumber      = $this->model->getPosInvoiceRefNumber($storeId, $cashRegisterId);
        $invoiceNumber  = $this->model->getBillNumModel();        
        $storeName       = Session::get($sPrefix.'storeName');
        $cashCode        = Session::get($sPrefix.'cashRegisterCode');
        $cashierName     = Session::get($sPrefix.'cashierName');        
        $messageText     = Session::get($sPrefix.'messageText') ? Session::get($sPrefix.'messageText') : '';
        $templateContentJoin = '';
        $row = $getRowsData[0];
        
        if ($row['issentvatsp'] === '1') {
            jsonResponse([
                'status' => 'error',
                'message' => 'Татварт илгээгдсэн баримт байна.'
            ]);
        }

        $invoiceId = $row['id'];
        $itemPrintList = '';

        $items = $this->model->getItemsForLottery($invoiceId);

        $stocks = '';
        $isPackageItem = false; $merchantHeader = array();
        $merchantItems = $merchantRegisters = array();            
        $vatAmount = $totalAmount = $totalItemCount = 0;

        if ($items['pos_item_list_get']) {
                $putDate = $items['invoicedate'];
                
                foreach ($items['pos_item_list_get'] as $ikey =>  $itemData) {
                    $lineTotalAmount = $itemData['linetotalamount'];
                            
                    if ($lineTotalAmount > 0 && $itemData['saleprice'] > 0) {

                        $printItemName = $itemData['itemname'];
                        $itemName    = $this->model->apiStringReplace($printItemName);
                        $measureCode = $this->model->convertCyrillicMongolia($itemData['measurecode']);
                        $lineTotalVat = $itemData['vat'];
                        $itemCode = $itemData['itemcode'];
                        $itemQty = $itemData['invoiceqty'];
                        $unitAmount = $itemData['saleprice'];
                        $lineTotalCityTaxAmount = $itemData['citytax'];
                        $barCode = $itemData['barcode'];
                        $cityTax = $itemData['citytax'];
                        $vatAmount += $lineTotalVat;
                        $totalAmount += $lineTotalAmount;
                        $totalItemCount += $itemQty;

                        $stocks .= "{
                            'code': '" . $itemCode . "',
                            'name': '" . $itemName . "',
                            'measureUnit': '" . $measureCode . "',
                            'qty': '" . sprintf("%.2f", $itemQty) . "',
                            'unitPrice': '" . sprintf("%.2f", $unitAmount) . "',
                            'totalAmount': '" . sprintf("%.2f", $lineTotalAmount) . "',
                            'cityTax': '" . sprintf("%.2f", $lineTotalCityTaxAmount) . "',
                            'vat': '" . sprintf("%.2f", $lineTotalVat) . "',
                            'barCode': '" . $barCode . "'
                        }, ";  

                        $merchantId = $itemData['merchantid'] ? $itemData['merchantid'] : '_1';
                        $stateRegNumber = Str::upper($itemData['stateregnumber']);
                        $stateRegNumberReal = $stateRegNumber;
                        preg_match("/[ФЦУЖЭНГШҮЗКЪЙЫБӨАХРОЛДПЯЧЁСМИТЬВЮЕЩ]{2}[0-9]{8}$/", $stateRegNumber, $validRegNo);                                              

                        if ($validRegNo) {
                            $stateRegNumber = $this->model->posApiCallFunction('toReg::'.$this->model->apiStringReplace($stateRegNumber));
                            $merchantRegisters[$stateRegNumber] = $stateRegNumberReal;
                        }

                        if ($merchantId !== '_1') {
                            $isPackageItem = true;
                        }

                        if (!isset(${"stocks".$merchantId})) {
                            ${"stocks".$merchantId} = '';
                            ${"merchantInternalId".$merchantId} = $itemData['internalid'];
                            ${"merchantRegisterNo".$merchantId} = $stateRegNumber;
                            ${"merchantTotalAmount".$merchantId} = 0;
                            ${"merchantTotalCityTax".$merchantId} = 0;
                        }

                        ${"merchantTotalAmount".$merchantId} += $lineTotalAmount;
                        ${"merchantTotalCityTax".$merchantId} += $lineTotalCityTaxAmount;

                        if (!in_array($merchantId, $merchantHeader)) {
                            array_push($merchantHeader, $merchantId);
                        }

                        ${"stocks".$merchantId} .= "{
                            'code': '" . $itemCode . "',
                            'name': '" . $itemName . "',
                            'measureUnit': '" . $measureCode . "',
                            'qty': '" . sprintf("%.2f", $itemQty) . "',
                            'unitPrice': '" . sprintf("%.2f", $unitAmount) . "',
                            'totalAmount': '" . sprintf("%.2f", $lineTotalAmount) . "',
                            'cityTax': '" . sprintf("%.2f", $lineTotalCityTaxAmount) . "',
                            'vat': '" . sprintf("%.2f", $lineTotalVat) . "',
                            'barCode': '" . $barCode . "'
                        }, ";  

                        array_push($merchantItems, array(
                            'cityTax'        => $cityTax, 
                            'itemName'       => $printItemName, 
                            'salePrice'      => $unitAmount, 
                            'itemQty'        => $itemQty, 
                            'totalPrice'     => $lineTotalAmount, 
                            'unitReceivable' => '', 
                            'maxPrice'       => '', 
                            'isDelivery'     => '',
                            'merchantId'     => $merchantId,
                            'registerNo'     => $stateRegNumber
                        ));
                        
                        $rowPrint = array(
                            'cityTax'        => $cityTax, 
                            'itemName'       => $printItemName, 
                            'salePrice'      => $unitAmount, 
                            'itemQty'        => $itemQty, 
                            'totalPrice'     => $lineTotalAmount, 
                            'unitReceivable' => '', 
                            'maxPrice'       => '', 
                            'isDelivery'     => ''
                        );
                        $itemPrintList .= $this->model->generateItemRow($rowPrint);
            
                    }                
                }
                
                if ($isPackageItem) {
                    $merchantPackage = '';
                    
                    $timeMin = Date::currentDate('H');
                    if ($merchantHeader) {
                        
                        foreach ($merchantHeader as $merchant) {
                            $merchantPackage .= "{
                                'amount': '" . sprintf("%.2f", ${"merchantTotalAmount".$merchant}) . "',
                                'vat': '" . sprintf("%.2f", ${"merchantTotalAmount".$merchant} - number_format(${"merchantTotalAmount".$merchant} / 1.1, 2, '.', '')) . "',
                                'cashAmount': '" . sprintf("%.2f", ${"merchantTotalAmount".$merchant}) . "',
                                'nonCashAmount': '0.00',
                                'cityTax': '" . sprintf("%.2f", ${"merchantTotalCityTax".$merchant}) . "',
                                'districtCode': '" . $districtCode . "',
                                'posNo': '',
                                'reportMonth': '" . $reportMonth . "',
                                'customerNo': '" . $customerNo . "',
                                'billType': '" . $billType . "',
                                'taxType': '" . $taxType . "',
                                'billIdSuffix': '',
                                'returnBillId': '',
                                'internalId': '" . ${"merchantInternalId".$merchant} . "',
                                'registerNo': '" . ${"merchantRegisterNo".$merchant} . "',
                                'stocks': [
                                    " . rtrim(${"stocks".$merchant}, ', ') . "
                                ]
                            }, ";
                        }
                    }
                    
                    $jsonParam = "{
                        'group': true,
                        'vat': '" . sprintf("%.2f", $vatAmount) . "',
                        'amount': '" . sprintf("%.2f", $totalAmount) . "',
                        'billType': '" . $billType . "',
                        'billIdSuffix': '" . $timeMin . str_shuffle(str_shuffle(substr((time() * rand()), 0, 4))) . "',
                        'posNo': '" . $cashCode . "',
                        'bills': [
                            " . rtrim($merchantPackage, ', ') . "
                        ]
                    }";
                } else {
                    $jsonParam = "{
                        'amount': '" . sprintf("%.2f", $totalAmount) . "',
                        'vat': '" . sprintf("%.2f", $vatAmount) . "',
                        'cashAmount': '" . sprintf("%.2f", $totalAmount) . "',
                        'nonCashAmount': '0.00',
                        'cityTax': '" . sprintf("%.2f", $cityTax) . "',
                        'districtCode': '" . $districtCode . "',
                        'posNo': '" . $cashCode . "',
                        'reportMonth': '" . $reportMonth . "',
                        'customerNo': '" . $customerNo . "',
                        'billType': '" . $billType . "',
                        'taxType': '" . $taxType . "',
                        'billIdSuffix': '',
                        'returnBillId': '',
                        'stocks': [
                            " . rtrim($stocks, ', ') . "
                        ]
                    }";
                }

                $jsonParam = Str::remove_doublewhitespace(Str::removeNL($jsonParam));

                $posApiArray = $this->model->posApiFunction($jsonParam);
                $billId      = isset($posApiArray['billId']) ? $posApiArray['billId'] : null;                   
                
                if ($billId) {
                    
                    if ($isPackageItem) {
                        $merchantItems = Arr::groupByArray($merchantItems, 'registerNo');

                        if ($posApiArray['bills']) {
                            $itemPrintList = '';
                            $firstPackage = true;

                            foreach ($posApiArray['bills'] as $bkey => $bill) {                                                                

                                $ebillNo = $bill['registerNo'];
                                if (isset($merchantItems[$ebillNo])) {
                                    $itemPrintList .= $this->model->generatePackageItemRow(array(), $bill['billId'], $firstPackage);
                                    $firstPackage = false;
                                    foreach ($merchantItems[$ebillNo]['rows'] as $merRow) {
                                        $itemPrintList .= $this->model->generatePackageItemRow($merRow);
                                    }

                                    $billResultParams = array(
                                        'BILL_ID'          => $bill['billId'], 
                                        'SALES_INVOICE_ID' => $invoiceId, 
                                        'MERCHANT_ID'      => isset($merchantRegisters[$ebillNo]) ? $merchantRegisters[$ebillNo] : $ebillNo, 
                                        'VAT_DATE'         => $putDate, 
                                        'SUCCESS'          => '', 
                                        'WARNING_MSG'      => '',  
                                        'SEND_JSON'        => '', 
                                        'STORE_ID'         => $storeId, 
                                        'CUSTOMER_NUMBER'  => '', 
                                        'CUSTOMER_NAME'    => ''
                                    );
                                    $this->model->createBillResultData($billResultParams); 

                                }
                            }
                        }
                    }                  

                    $billResultParams = array(
                        'BILL_ID'          => $billId, 
                        'SALES_INVOICE_ID' => $invoiceId, 
                        'MERCHANT_ID'      => $posApiArray['merchantId'], 
                        'VAT_DATE'         => $putDate, 
                        'SUCCESS'          => $posApiArray['success'], 
                        'WARNING_MSG'      => $posApiArray['warningMsg'],  
                        'SEND_JSON'        => $jsonParam, 
                        'STORE_ID'         => $storeId, 
                        'CUSTOMER_NUMBER'  => '', 
                        'CUSTOMER_NAME'    => ''
                    );
                    if ($isPackageItem) {
                        $billResultParams['IS_ROOT_PACKAGE'] = '1';
                    }

                    $this->model->createBillResultData($billResultParams);                    
                    
                    $templateContent   = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/person/single.html');
                    $qrLotteryTemplate = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/person/qrcode-lottery.html');

                    //$promotionContent = self::getBillPromotionModel();

                    $templateContent = str_replace('{promotion}', '', $templateContent);
                    $templateContent = str_replace('{qrCodeLottery}', $qrLotteryTemplate, $templateContent);

                    //$lotteryContent = self::printLotteryPriceInterval($invoiceId, $printType, issetParam($params['phoneNumber']));

                    $templateContent = str_replace('{lotterypart}', '', $templateContent);
                    $templateContent = str_replace('{loyaltyPart}', '', $templateContent);

                    $lottery         = $posApiArray['lottery'];
                    $qrData          = $posApiArray['qrData'];

                    $replacing = array(
                        '{poslogo}'         => $posLogo,
                        '{companyName}'     => $topTitle,
                        '{title}'           => '', 
                        '{vatNumber}'       => $vatNumber,
                        '{contactInfo}'     => $contactInfo,
                        '{ddtd}'            => $billId,
                        '{date}'            => Date::formatter($putDate, 'Y/m/d'),
                        '{time}'            => Date::formatter($putDate, 'H:i:s'),
                        '{refNumber}'       => $refNumber,
                        '{invoiceNumber}'   => $invoiceNumber,
                        '{storeName}'       => $storeName,
                        '{cashierName}'     => $cashierName,
                        '{cashCode}'        => $cashCode, 
                        '{salesPersonCode}' => '', 
                        '{itemList}'        => $itemPrintList,
                        '{totalAmount}'     => $this->model->posAmount($totalAmount),
                        '{payAmount}'       => $this->model->posAmount($totalAmount),
                        '{vatAmount}'       => $this->model->posAmount($vatAmount),
                        '{discountPart}'    => '',
                        '{lottery}'         => $lottery,
                        '{qrCode}'          => $this->model->getQrCodeImg($qrData),
                        '{giftList}'        => '', 
                        '{totalItemCount}'  => $this->model->posAmount($totalItemCount), 

                        '{bonusCardNumber}'         => '', 
                        '{bonusCardDiscountPercent}'=> '',
                        '{bonusCardBeginAmount}'    => '', 
                        '{bonusCardDiffAmount}'     => '', 
                        '{bonusCardPlusAmount}'     => '', 
                        '{bonusCardEndAmount}'      => '', 
                        '{payment-detail}'          => '',
                        '{lockerCode}'              => '',
                        '{serialText}'              => '',
                        '{info-ipterminal}'         => '',
                        '{messageText}'             => $messageText
                    );

                    /*if ($itemPrintCopiesLast) {

                        $printCopies = $itemPrintCopiesLast;

                    } elseif (defined('CONFIG_POS_BANKCARD_COPIES_COUNT') && CONFIG_POS_BANKCARD_COPIES_COUNT 
                        && isset($isBankCardPaid) && $printCopies < CONFIG_POS_BANKCARD_COPIES_COUNT) {

                        $printCopies = CONFIG_POS_BANKCARD_COPIES_COUNT;
                    }

                    if ($printCopies) {

                        $internalContent = file_get_contents(BASEPATH . 'middleware/views/pos/print_template/'.$printType.$dirPath.'/person/internal.html');

                        $internalContent = str_replace('{title}', Lang::line('POS_0103'), $internalContent);
                        $internalContent = str_replace('{lotterypart}', '', $internalContent);
                        $internalContent = str_replace('{info-ipterminal}', $infoIpTerminal, $internalContent);

                        $internalContent = strtr($internalContent, $replacing);

                        if ($printCopies > 1) {
                            $internalContent = str_repeat($internalContent, $printCopies);
                        }

                        $templateContent .= $internalContent;
                    }*/                    
                    
                    $templateContentJoin .= strtr($templateContent, $replacing);          
                    $response = array('status' => 'success');
                    
                } else {
                    $templateContentJoin = $this->model->convertCyrillicMongolia($posApiArray['message'], true);
                    $response = array('status' => 'error', 'message' => $templateContentJoin);
                }
                    
            }
                
        $response['css']        = Mdpos::getPrintCss();
        $response['printData']  = $templateContentJoin;    

        echo json_encode($response); exit;        
        
    }
    
    public function insertSalesFromFile() {
        $ids = '';
        die('finish');
        $data = file_get_contents(UPLOADPATH.'1211.json');
//        $data = json_encode($data);
        $data = json_decode($data, true);
        
        $headerSql = 'INSERT INTO SM_SALES_INVOICE_HEADER (SALES_INVOICE_ID, STORE_ID, CASH_REGISTER_ID, INVOICE_NUMBER, INVOICE_DATE, SUB_TOTAL, VAT, TOTAL,
         CREATED_DATE_TIME, WFM_STATUS_ID, CREATED_DATE, TOTAL_CITY_TAX_AMOUNT, BOOK_TYPE_ID)
        SELECT
         TD.SALES_INVOICE_ID,
         SS.STORE_ID,
         SS.CASH_REGISTER_ID,
         ROWNUM AS INVOICE_NUMBER,
         TO_CHAR(TO_DATE(:date, \'YYYY-MM-DD HH24:MI:SS\'), \'YYYY-MM-DD\') AS INVOICE_DATE,
         :amount AS SUB_TOTAL,
         :vat AS VAT,
         :amount AS TOTAL,
         :date AS CREATED_DATE_TIME,
         1505964291977811 AS WFM_STATUS_ID,
         :date AS CREATED_DATE_TIME,
         :cityTax AS TOTAL_CITY_TAX_AMOUNT,
         9 AS BOOK_TYPE_ID
        FROM
         SM_SALES_INVOICE_ID_TMP_DATA TD
         LEFT JOIN (SELECT SSCR.CASH_REGISTER_ID, MAX(SSCR.STORE_ID) AS STORE_ID FROM SM_STORE_CASH_REGISTER SSCR
         INNER JOIN SM_CASH_REGISTER SCR ON SSCR.CASH_REGISTER_ID = SCR.CASH_REGISTER_ID WHERE SCR.CODE = :posNo GROUP BY SSCR.CASH_REGISTER_ID) SS ON 1 = 1';

        $headerSql2 = 'INSERT INTO SM_SALES_PAYMENT (SALES_PAYMENT_ID, SALES_INVOICE_ID, AMOUNT, CREATED_DATE, PAYMENT_TYPE_ID)
        SELECT
         CURRENT_MILLISECS () + ROWNUM AS SALES_PAYMENT_ID,
         TD.SALES_INVOICE_ID,
         :amount AS AMOUNT,
         :date AS CREATED_DATE,
         1 AS PAYMENT_TYPE_ID
        FROM
         SM_SALES_INVOICE_ID_TMP_DATA TD';        

         $dtlSql = 'INSERT INTO SM_SALES_INVOICE_DETAIL (SALES_INVOICE_DETAIL_ID, SALES_INVOICE_ID, PRODUCT_ID, SECTION_ID, INVOICE_QTY, UNIT_PRICE, LINE_TOTAL_PRICE, PERCENT_VAT,
         UNIT_VAT, LINE_TOTAL_VAT, UNIT_AMOUNT, LINE_TOTAL_AMOUNT, IS_REMOVED, CREATED_DATE, LINE_TOTAL_CITY_TAX_AMOUNT, BARCODE, WFM_STATUS_ID)
        SELECT
         DD.SALES_INVOICE_DETAIL_ID + ROWNUM,
         TD.SALES_INVOICE_ID,
         II.PRODUCT_ID,
         NULL AS SECTION_ID,
         :qty AS INVOICE_QTY,
         :unitPrice AS UNIT_PRICE,
         :totalAmount AS LINE_TOTAL_PRICE,
         10 AS PERCENT_VAT,
         ROUND(:vat / :qty, 2) AS UNIT_VAT,
         :vat AS LINE_TOTAL_VAT,
         :unitPrice AS UNIT_AMOUNT,
         :totalAmount AS LINE_TOTAL_AMOUNT,
         0 AS IS_REMOVED,
         :date AS CREATED_DATE,
         :cityTax AS LINE_TOTAL_CITY_TAX_AMOUNT,
         6 AS BARCODE,
         1505443958153017 AS WFM_STATUS_ID
        FROM
         SM_SALES_INVOICE_ID_TMP_DATA TD
         LEFT JOIN (SELECT MAX(SALES_INVOICE_DETAIL_ID) AS SALES_INVOICE_DETAIL_ID FROM SM_SALES_INVOICE_DETAIL) DD ON 1 = 1
         LEFT JOIN (SELECT ITEM_ID AS PRODUCT_ID FROM IM_ITEM WHERE ITEM_CODE = :code) II ON 1 = 1';
         
        
        foreach ($data as $row) {
            $this->db->StartTrans(); 
            
            $headerSqlReplace = $headerSql;
            $headerSqlReplace2 = $headerSql2;
            $dtlReplace = $dtlSql;
            
            $this->db->Execute('INSERT INTO SM_SALES_INVOICE_ID_TMP_DATA SELECT SALES_INVOICE_ID + ROWNUM FROM (SELECT MAX(SALES_INVOICE_ID) + 1000 AS SALES_INVOICE_ID FROM SM_SALES_INVOICE_HEADER)');
            $this->db->Execute('INSERT INTO SM_SALES_INVOICE_ID_TMP_DATAS SELECT SALES_INVOICE_ID FROM SM_SALES_INVOICE_ID_TMP_DATA');
            
            if (isset($row['group'])) {
                
                foreach ($row as $key => $subRow) {
                    if ($key !== 'bills' && $key !== 'date') {
                        $headerSqlReplace = str_replace(':'.$key, $subRow, $headerSqlReplace);
                        $headerSqlReplace2 = str_replace(':'.$key, $subRow, $headerSqlReplace2);
                    }
                }
                
                $headerSqlReplace = str_replace(':date', '\''.$row['date'].'\'', $headerSqlReplace);
                $headerSqlReplace = str_replace(':cityTax', '0', $headerSqlReplace);       

                $headerSqlReplace2 = str_replace(':date', '\''.$row['date'].'\'', $headerSqlReplace2);
                $headerSqlReplace2 = str_replace(':cityTax', '0', $headerSqlReplace2);                      

                $this->db->Execute($headerSqlReplace);
                $this->db->Execute($headerSqlReplace2);     
                
                $ids .= $row['billId'] . ',<br>';
                
                $this->db->Execute('INSERT INTO SM_BILL_RESULT_DATA (BILL_ID, SALES_INVOICE_ID, MERCHANT_ID, VAT_DATE, SEND_JSON, ID, STORE_ID, IS_REMOVED, IS_ROOT_PACKAGE)
                    SELECT
                     \''.$row['billId'].'\' AS BILL_ID,
                     TD.SALES_INVOICE_ID,
                     \''.$row['merchantId'].'\' AS MERCHANT_ID,
                     \''.$row['date'].'\' AS VAT_DATE,
                     \'\' AS SEND_JSON,
                     CURRENT_MILLISECS() + ROWNUM,
                     SS.STORE_ID,
                     0 AS IS_REMOVED,
                     1 AS IS_ROOT_PACKAGE
                    FROM
                     SM_SALES_INVOICE_ID_TMP_DATA TD
                     LEFT JOIN (SELECT SSCR.CASH_REGISTER_ID, MAX(SSCR.STORE_ID) AS STORE_ID FROM SM_STORE_CASH_REGISTER SSCR
                     INNER JOIN SM_CASH_REGISTER SCR ON SSCR.CASH_REGISTER_ID = SCR.CASH_REGISTER_ID WHERE SCR.CODE = \''.$row['posNo'].'\' GROUP BY SSCR.CASH_REGISTER_ID) SS ON 1 = 1');
                
                foreach ($row['bills'] as $subDtl) {
                    
                    //if ($subDtl['registerNo'] == '5996821') {
                    //}                    
                    $ids .= $subDtl['billId'] . ',<br>';
                    
                    $this->db->Execute('INSERT INTO SM_BILL_RESULT_DATA (BILL_ID, SALES_INVOICE_ID, MERCHANT_ID, VAT_DATE, SEND_JSON, ID, STORE_ID, IS_REMOVED, IS_ROOT_PACKAGE)
                        SELECT
                         \''.$subDtl['billId'].'\' AS BILL_ID,
                         TD.SALES_INVOICE_ID,
                         \''.$subDtl['registerNo'].'\' AS MERCHANT_ID,
                         \''.$subDtl['date'].'\' AS VAT_DATE,
                         \'\' AS SEND_JSON,
                         CURRENT_MILLISECS() + ROWNUM,
                         SS.STORE_ID,
                         0 AS IS_REMOVED,
                         0 AS IS_ROOT_PACKAGE
                        FROM
                         SM_SALES_INVOICE_ID_TMP_DATA TD
                         LEFT JOIN (SELECT SSCR.CASH_REGISTER_ID, MAX(SSCR.STORE_ID) AS STORE_ID FROM SM_STORE_CASH_REGISTER SSCR
                         INNER JOIN SM_CASH_REGISTER SCR ON SSCR.CASH_REGISTER_ID = SCR.CASH_REGISTER_ID WHERE SCR.CODE = \''.$subDtl['posNo'].'\' GROUP BY SSCR.CASH_REGISTER_ID) SS ON 1 = 1');

                    foreach ($subDtl['stocks'] as $subGroupDtl) {

                        $dtlReplace = $dtlSql;
                        foreach ($subGroupDtl as $subKey => $subDtlRow) {
                            $dtlReplace = str_replace(':'.$subKey, $subDtlRow, $dtlReplace);
                        }
                        
                        $dtlReplace = str_replace(':date', '\''.$row['date'].'\'', $dtlReplace);
                        $dtlReplace = str_replace(':cityTax', '0', $dtlReplace);                                 
                        
                        $this->db->Execute($dtlReplace);

                    }

                }                
                
            } else {
                
                foreach ($row as $key => $subRow) {
                    if ($key !== 'stocks' && $key !== 'date') {
                        $headerSqlReplace = str_replace(':'.$key, $subRow, $headerSqlReplace);
                        $headerSqlReplace2 = str_replace(':'.$key, $subRow, $headerSqlReplace2);
                    }
                }

                $headerSqlReplace = str_replace(':date', '\''.$row['date'].'\'', $headerSqlReplace);
                $headerSqlReplace = str_replace(':cityTax', '0', $headerSqlReplace);       

                $headerSqlReplace2 = str_replace(':date', '\''.$row['date'].'\'', $headerSqlReplace2);
                $headerSqlReplace2 = str_replace(':cityTax', '0', $headerSqlReplace2);       
                
                $this->db->Execute($headerSqlReplace);
                $this->db->Execute($headerSqlReplace2);                
                
                $ids .= $row['billId'] . ',<br>';
                
                $this->db->Execute('INSERT INTO SM_BILL_RESULT_DATA (BILL_ID, SALES_INVOICE_ID, MERCHANT_ID, VAT_DATE, SEND_JSON, ID, STORE_ID, IS_REMOVED, IS_ROOT_PACKAGE)
                    SELECT
                     \''.$row['billId'].'\' AS BILL_ID,
                     TD.SALES_INVOICE_ID,
                     \''.$row['merchantId'].'\' AS MERCHANT_ID,
                     \''.$row['date'].'\' AS VAT_DATE,
                     \'\' AS SEND_JSON,
                     CURRENT_MILLISECS() + ROWNUM,
                     SS.STORE_ID,
                     0 AS IS_REMOVED,
                     1 AS IS_ROOT_PACKAGE
                    FROM
                     SM_SALES_INVOICE_ID_TMP_DATA TD
                     LEFT JOIN (SELECT SSCR.CASH_REGISTER_ID, MAX(SSCR.STORE_ID) AS STORE_ID FROM SM_STORE_CASH_REGISTER SSCR
                     INNER JOIN SM_CASH_REGISTER SCR ON SSCR.CASH_REGISTER_ID = SCR.CASH_REGISTER_ID WHERE SCR.CODE = \''.$row['posNo'].'\' GROUP BY SSCR.CASH_REGISTER_ID) SS ON 1 = 1');                                    
            
                foreach ($row['stocks'] as $subDtl) {

                    $dtlReplace = $dtlSql;
                    foreach ($subDtl as $subKey => $subDtlRow) {
                        $dtlReplace = str_replace(':'.$subKey, $subDtlRow, $dtlReplace);
                    }
                    
                    $dtlReplace = str_replace(':date', '\''.$row['date'].'\'', $dtlReplace);
                    $dtlReplace = str_replace(':cityTax', '0', $dtlReplace);         
                    
                    $this->db->Execute($dtlReplace);

                }
                
            }       
            
            $this->db->Execute('DELETE FROM SM_SALES_INVOICE_ID_TMP_DATA');
            
            $this->db->CompleteTrans();

        }                
        
        echo $ids;
        die('Success');
    }
    
    public function giftByItemPaymentRender($param) {
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->load->model('mdpos', 'middleware/models/');
        
        $row = $this->model->giftByItemPaymentModel($param);
        
        if ($row) {
            $this->view->rowData = $row;
        } else {
            $this->view->rowData = array();
        }
        
        $gift = $this->view->renderPrint('gift', self::$viewPath);
        
        return array('gift' => $gift);
    }    
    
    public function getMatrixDiscound($_ = null) {
        
        $this->load->model('mdpos', 'middleware/models/');
        
        $param = array(
            'filterItemId1' => Input::post('filterItemId1'),
            'filterItemId2' => Input::post('filterItemId2'),
            'storeid' => Session::get(SESSION_PREFIX.'storeId')
        );

        $row = $this->model->getMatrixDiscoundModel($param);

        if ($row) {
            $giftArray = $this->giftByItemRowRender($row);
            
            $giftArray['discountpercent'] = $row['discountpercent'];
            
            if ($_) {
                return $giftArray;
            } else {
                jsonResponse($giftArray);
            }
        } else {
            return null;
        }
    }    

    public function reloadFingerServer() {

        $ch = curl_init(Config::getFromCache('findFingerPrintServer'));

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $str = curl_exec($ch);
        curl_close($ch);         
    }
    
    public function getInfoLocationName() {
        $getNamesOrig = Input::post('suggestText');
        
        $resultArr = array('cityId' => '', 'districtId' => '', 'streetId' => '', 'moreAddress' => issetParam($getNamesOrig['formatted_address']));
        
        if (!$getNamesOrig && !is_array($getNamesOrig)) {
            jsonResponse($resultArr);
        }

        if (!issetParamArray($getNamesOrig['address_components'])) {
            jsonResponse($resultArr);
        }

        $getNames = $getNamesOrig['address_components'];
        
        $values = array();
        
        if ($getNames && is_array($getNames)) {
            foreach ($getNames as $row) {
                array_push($values, array(
                    'operator' => 'like',
                    'operand' => '%'.trim(Str::replace('Дүүрэг', '', $row['long_name'])).'%'
                ));
            }
        }
        
        $criteria = array(
            'name' => $values
        );

        $paramGroup = array(
            'systemMetaGroupId' => '1446632274202',
            'showQuery' => '0',
            'ignorePermission' => 1,
        );
        $paramGroup['criteria'] = $criteria;
        $dataGroup = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $paramGroup);

        if (isset($dataGroup['result']) && array_key_exists('0', $dataGroup['result'])) {
            $resultArr['cityId'] = $dataGroup['result'][0]['id'];
        }

        if ($resultArr['cityId']) {
            $paramGroup = array(
                'systemMetaGroupId' => '144436175673444',
                'showQuery' => '0',
                'ignorePermission' => 1,
            );
            $criteria['cityId'] = array(array('operator' => '=', 'operand' => $dataGroup['result'][0]['id']));
            $paramGroup['criteria'] = $criteria;
            $dataGroup2 = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $paramGroup);

            if (isset($dataGroup2['result']) && array_key_exists('0', $dataGroup2['result'])) {
                $resultArr['districtId'] = $dataGroup2['result'][0]['id'];
            }

            if ($resultArr['districtId']) {
                $paramGroup = array(
                    'systemMetaGroupId' => '144436196690182',
                    'showQuery' => '0',
                    'ignorePermission' => 1,
                );
                $criteria['cityId'] = array(array('operator' => '=', 'operand' => $dataGroup['result'][0]['id']));
                $criteria['districtId'] = array(array('operator' => '=', 'operand' => $dataGroup2['result'][0]['id']));
                $paramGroup['criteria'] = $criteria;
                foreach ($paramGroup['criteria']['name'] as $crikey => $cri) {
                    if (strpos($cri['operand'], 'Хороо') === false) {
                        unset($paramGroup['criteria']['name'][$crikey]);
                    }
                }
                
                $dataGroup = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $paramGroup);

                if (isset($dataGroup['result']) && array_key_exists('0', $dataGroup['result'])) {
                    $resultArr['streetId'] = $dataGroup['result'][0]['id'];
                }
            }
        }
        
        jsonResponse($resultArr);
    }    
    
    public function upointCheckInfo() {
        
        $params = array(
            'device_id' => Session::get(SESSION_PREFIX.'storeId'),
            'card_number' => Input::post('cardNumber'),
            'mobile' => Input::post('mobile'),
            'pin_code' => Input::post('pinCode'),
        );
        $response = $this->model->upointCheckInfoModel($params);
        echo json_encode($response); exit;
    }        
    
    function bla() {
        $_POST['dataViewId'] = '1511323408476';
        $_POST['statementId'] = '1511323409147';
        $_POST['param']['startdate'] = '2021-01-18';
        $_POST['param']['endDate'] = '2021-01-18';
        $reportHTml = (new Mdstatement())->renderDataModelByFilter(true);
        print($reportHTml);
    }
    
    public function itemGroup() {
        $param = array(
            'systemMetaGroupId' => '16116464420601',
            'ignorePermission' => 1, 
            'treeGrid' => 1,
            'showQuery' => 0,
            'criteria' => array(
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Session::get(SESSION_PREFIX.'cashRegisterId')
                    )
                ),
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Session::get(SESSION_PREFIX.'storeId')
                    )
                )                
            )            
        );
        
        if (Input::post('parentId')) {
            $param['criteria'] = array(
                'parentId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('parentId')
                    )
                ),
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Session::get(SESSION_PREFIX.'cashRegisterId')
                    )
                ),
                'storeId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Session::get(SESSION_PREFIX.'storeId')
                    )
                )                  
            );            
        }

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);
        $resultArr = '';

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $resultArr = $result['result'];
        }               
        
        jsonResponse($resultArr);
    }
    
    public function saveLocationImage() {
        jsonResponse($this->model->saveLocationImageModel());
    }
    
    public function deleteTableLocation() {
        jsonResponse($this->model->deleteTableLocationModel());
    }
    
    public function getTables($return = null) {
        $this->view->sidebarDVresult = array();
        
        $param = array(
            'systemMetaGroupId' => Input::post('dataViewId'),
            'ignorePermission' => 1, 
            'showQuery' => 0,
            'criteria' => array(
                'position' => array(
                    array(
                        'operator' => Input::post('stype') ? 'IS NOT NULL' : 'IS NULL',
                        'operand' => ''
                    )
                ),
                'cashRegisterId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Session::get(SESSION_PREFIX.'cashRegisterId')
                    )
                )
            )
        );

        $result = $this->ws->runSerializeResponse(GF_SERVICE_ADDRESS, Mddatamodel::$getDataViewCommand, $param);

        if (isset($result['result']) && isset($result['result'][0])) {
            unset($result['result']['aggregatecolumns']);
            unset($result['result']['paging']);
            $this->view->sidebarDVresult = $result['result'];
        }   

        if ($return)
            return $this->view->sidebarDVresult;
        
        jsonResponse($this->view->sidebarDVresult);
    }
    
    public function restTables() {
        
        $p = Input::post('id');
        $location = Input::post('location');
        $this->view->postParams = Input::postData();
        $this->view->isWorkspace = Input::post('isworkspace');        
        
        $this->view->locationId = $p;
        $this->view->uniqId = getUID();
        $this->view->dataViewId = '16116476587841';
        $this->view->isIpad = self::checkIpad();
        $dataViewCode = '';
        
        $this->load->model('mdmetadata', 'middleware/models/');
        $getMeta = $this->model->getMetaDataModel($this->view->dataViewId);        
        $dataViewCode = $getMeta['META_DATA_CODE'];
        
        $this->load->model('mdobject', 'middleware/models/');
        $this->view->dataViewProcessCommand = $this->model->dataViewProcessCommandModel($this->view->dataViewId, $dataViewCode, false, true, $this->view->dataViewId);

        echo $this->view->renderPrint('restauran/imageMarkerViewReferenceDvControl', self::$viewPath);
    }      
    
    public function fillItemsByEshop() {
        
        $qr = Input::post('qr');
        $result = $this->model->getInvoiceByEshopModel($qr);

        if ($result['status'] == 'success' && isset($result['data']['pos_item_list_get'])) {
            
            $this->view->storeId  = Session::get(SESSION_PREFIX.'storeId');
            $this->view->itemList = $result['data']['pos_item_list_get'];

            $response = array(
                'status'  => 'success', 
                'message' => $this->lang->line('POS_0061'), 
                'html'    => $this->view->renderPrint('items', self::$viewPath)
            );
            
        } else {
            $response = $result;
        }
        
        echo json_encode($response); exit;
    }

    public function changeTableRest() {
        jsonResponse($this->model->changeTableRestModel());
    }

    public function mergeTableRest() {
        jsonResponse($this->model->mergeTableRestModel());
    }

    public function returnTableRest() {
        jsonResponse($this->model->returnTableRestModel());
    }

    public function nextBillTableRest() {
        jsonResponse($this->model->nextBillTableRestModel());
    }
    
    public function splitCalculateRest() {        
        jsonResponse($this->model->splitCalculateRestModel());
    }
    
    public function pieceCalculateRest() {        
        jsonResponse($this->model->pieceCalculateRestModel());
    }
    
    public function pieceCalculateSaveRest() {        
        jsonResponse($this->model->pieceCalculateSaveRestModel());
    }
    
    public function splitCalculateSaveRest() {        
        jsonResponse($this->model->splitCalculateSaveRestModel());
    }
    
    public function customerDiscount() {        
        jsonResponse($this->model->customerDiscountModel());
    }
    
    public function getBasketOrderBookCount() {        
        echo $this->model->getBasketOrderBookCountModel();
    }
    
    public function kitchenIsPrint() {        
        jsonResponse($this->model->kitchenIsPrintModel());
    }
    
    public function closurePrint() {   
        if ($posDayClosePrintReportMetaId = Config::getFromCache('posDayClosePrintReportMetaId')) {
            $response = array();
            $_POST['dataViewId']  = '1620917831911061';
            $_POST['statementId'] = $posDayClosePrintReportMetaId;
            $_POST['param']['bookDate'] = Input::post('bookdate');
            $_POST['param']['cashierId'] = Input::post('cashierid');
            $response['report'] = (new Mdstatement())->renderDataModelByFilter(true);

            $response['css'] = file_get_contents('assets/custom/css/print/reportPrint.css');
            jsonResponse($response);
        } else {
            echo '';
        }             
    }

    public function getLimitBonusAmount() {
        $result = array();
        $limitBonusAmount = $this->model->getLimitBonusAmountModel();

        foreach ($limitBonusAmount as $row) {
            $values = explode(',', $row['CRITERIA']);
            foreach ($values as $rrow) {
                if ($rrow)
                    $result[$rrow] = $row['CONFIG_VALUE'];
            }
        }

        return $result;
    }

    public function paymentTypeLocalExp() {
        $this->load->model('mdpos', 'middleware/models/');
        $getResult = $this->model->paymentTypeLocalExpModel();
        if ($getResult) {
            $getResult = str_replace("storecode", "'".Session::get(SESSION_PREFIX.'storeCode')."'", $getResult["CRITERIA"]);
            
            return @eval('return ('.$getResult.');');
        }
        return false;
    }

    public function decodeTaxMsg($msg = "") {
        echo $this->model->apiStringReplace($msg, true);
    }

    public function checkIpad() {
        includeLib('Detect/Browser');
        $browser = new Browser();
        return strpos(Str::lower($browser->getUserAgent()), 'android') !== false || strpos(Str::lower($browser->getUserAgent()), 'ipad') !== false ? 1 : 0;
    }

    public function deleteDetailOrderItem() {
        jsonResponse($this->model->deleteDetailOrderItemModel());
    }     

    function viewLog() {
       pa(unserialize(Str::cleanOut('a:1:{s:8:"response";a:2:{s:6:"status";s:7:"success";s:3:"wfm";a:2:{s:3:"log";a:4:{s:1:"0";a:18:{s:11:"wfmstatusid";s:16:"1676002441250781";s:13:"wfmstatusname";s:8:"Шинэ";s:14:"wfmstatuscolor";s:7:"#1c7e2d";s:14:"wfmdescription";N;s:11:"createddate";s:19:"2024-01-05 09:54:15";s:15:"isuserdefassign";s:1:"0";s:8:"username";s:28:"0012-П.Отгонцэцэг";s:7:"picture";N;s:12:"positionname";s:40:"Бичиг хэргийн ажилтан";s:14:"departmentname";s:50:"Захиргаа удирдлагын хэлтэс";s:8:"wfmlogid";s:14:"17031346824874";s:13:"aliasusername";N;s:8:"rulecode";N;s:16:"timespentpercent";N;s:9:"timespent";s:1:"0";s:13:"createduserid";s:13:"1670405117642";s:13:"attachedfiles";N;s:11:"assignments";N;}s:1:"1";a:18:{s:11:"wfmstatusid";s:16:"1675137324098133";s:13:"wfmstatusname";s:27:"СЕО шилжүүлсэн";s:14:"wfmstatuscolor";s:6:"purple";s:14:"wfmdescription";N;s:11:"createddate";s:19:"2024-01-05 09:54:26";s:15:"isuserdefassign";s:1:"0";s:8:"username";s:28:"0012-П.Отгонцэцэг";s:7:"picture";N;s:12:"positionname";s:40:"Бичиг хэргийн ажилтан";s:14:"departmentname";s:50:"Захиргаа удирдлагын хэлтэс";s:8:"wfmlogid";s:14:"17031346827404";s:13:"aliasusername";N;s:8:"rulecode";s:4:"100%";s:16:"timespentpercent";N;s:9:"timespent";s:1:"0";s:13:"createduserid";s:13:"1670405117642";s:13:"attachedfiles";N;s:11:"assignments";N;}s:1:"2";a:18:{s:11:"wfmstatusid";s:16:"1553573874840384";s:13:"wfmstatusname";s:38:"Хэлтсийн дарга хянах";s:14:"wfmstatuscolor";s:7:"#4f1fff";s:14:"wfmdescription";s:18:"шилжүүлэв";s:11:"createddate";s:19:"2024-01-05 13:29:37";s:15:"isuserdefassign";s:1:"0";s:8:"username";s:26:"0001-Ц.Амарсанаа";s:7:"picture";N;s:12:"positionname";s:14:"Захирал";s:14:"departmentname";s:14:"Захирал";s:8:"wfmlogid";s:14:"17031349812744";s:13:"aliasusername";N;s:8:"rulecode";s:4:"100%";s:16:"timespentpercent";N;s:9:"timespent";s:3:"215";s:13:"createduserid";s:13:"1670405117824";s:13:"attachedfiles";N;s:11:"assignments";N;}s:1:"3";a:18:{s:11:"wfmstatusid";s:16:"1553573746999575";s:13:"wfmstatusname";s:35:"Ажилтан шийдвэрлэх";s:14:"wfmstatuscolor";s:7:"#3498DB";s:14:"wfmdescription";N;s:11:"createddate";s:19:"2024-01-08 08:44:04";s:15:"isuserdefassign";s:1:"0";s:8:"username";s:29:"0018-Я.Мөнх-Эрдэнэ";s:7:"picture";N;s:12:"positionname";s:100:"Хяналт-шинжилгээ, үнэлгээ, дотоод аудитын албаны дарга";s:14:"departmentname";s:136:"Захиргаа удирдлагын хэлтэс,Хяналт-шинжилгээ, үнэлгээ, дотоод аудитын алба";s:8:"wfmlogid";s:14:"17031363614464";s:13:"aliasusername";N;s:8:"rulecode";s:4:"100%";s:16:"timespentpercent";N;s:9:"timespent";s:4:"4034";s:13:"createduserid";s:13:"1670405117648";s:13:"attachedfiles";N;s:11:"assignments";a:1:{s:1:"0";a:34:{s:2:"id";s:14:"17031363614474";s:6:"userid";s:13:"1670405117636";s:12:"employeename";s:16:"0007-С.Дина";s:13:"wfmstatusname";N;s:14:"wfmstatuscolor";N;s:10:"isneedsign";N;s:7:"picture";s:64:"storage/uploads/process/file_1672906694619539_15281129808551.jpg";s:7:"duedate";s:10:"2024/01/08";s:7:"duetime";s:8:"00:00:00";s:6:"dueday";N;s:12:"assigneddate";s:10:"2024/01/08";s:12:"assignedtime";s:9:" 00:00:00";s:11:"assignedday";s:9:"Monday   ";s:12:"positionname";s:112:"Гүйцэтгэлийн төлөвлөгөө, тайлан мэдээ хариуцсан мэргэжилтэн";s:14:"departmentname";s:50:"Захиргаа удирдлагын хэлтэс";s:14:"wfmstatusnames";s:35:"Ажилтан шийдвэрлэх";s:15:"wfmstatuscolors";s:7:"#3498DB";s:14:"assigneduserid";s:13:"1670405117648";s:20:"assignedemployeename";s:29:"0018-Я.Мөнх-Эрдэнэ";s:18:"assignpositionname";s:100:"Хяналт-шинжилгээ, үнэлгээ, дотоод аудитын албаны дарга";s:20:"assigndepartmentname";s:85:"Хяналт-шинжилгээ, үнэлгээ, дотоод аудитын алба";s:13:"assignpicture";N;s:8:"ordernum";N;s:13:"istransferred";s:1:"0";s:14:"userstatusdate";N;s:14:"userstatustime";N;s:13:"userstatusday";N;s:11:"description";s:165:"Зөвшилцсөний дагуу ББЗХХ-ийн ахлах инженер Мөнх-Отгон, Амардалай нарын нэрсийг хүргүүлэх.";s:8:"isactive";s:1:"1";s:13:"aliasusername";N;s:6:"weight";s:1:"0";s:8:"rulecode";s:4:"100%";s:17:"userwfmstatusname";N;s:18:"userwfmstatuscolor";N;}}}}s:4:"next";a:1:{s:13:"1670405117636";a:27:{s:6:"userid";s:13:"1670405117636";s:8:"username";s:16:"0007-С.Дина";s:9:"firstname";s:8:"Дина";s:8:"lastname";s:16:"Серекбол";s:7:"picture";s:64:"storage/uploads/process/file_1672906694619539_15281129808551.jpg";s:14:"departmentname";s:50:"Захиргаа удирдлагын хэлтэс";s:14:"departmentcode";s:2:"24";s:12:"positionname";s:112:"Гүйцэтгэлийн төлөвлөгөө, тайлан мэдээ хариуцсан мэргэжилтэн";s:11:"createddate";s:10:"2024-01-08";s:11:"wfmstatusid";s:13:"1587109043324";s:13:"wfmstatusname";s:20:"Шийдвэрлэх";s:14:"wfmstatuscolor";s:7:"#28B463";s:13:"wfmstatusicon";s:15:"fa-check-circle";s:18:"wfmstatusprocessid";N;s:13:"wfmisneedsign";s:5:"false";s:17:"wfmisdescrequired";s:5:"false";s:11:"processname";s:20:"Шийдвэрлэх";s:12:"processcolor";s:7:"#28B463";s:6:"isedit";s:1:"2";s:19:"wfmuseprocesswindow";s:4:"true";s:15:"isformnotsubmit";N;s:10:"metatypeid";N;s:23:"mobileprocessmetadataid";N;s:20:"usedescriptionwindow";s:4:"true";s:12:"ismailaction";s:4:"true";s:13:"aliasusername";N;s:4:"rows";a:3:{s:1:"0";a:3:{s:13:"wfmstatusname";s:24:"Шийдвэрлэсэн";s:14:"wfmstatuscolor";s:7:"#28B463";s:13:"wfmstatusicon";s:15:"fa-check-circle";}s:1:"1";a:3:{s:13:"wfmstatusname";s:16:"Буцаасан";s:14:"wfmstatuscolor";s:7:"#F2635F";s:13:"wfmstatusicon";s:10:"fa-history";}s:1:"2";a:3:{s:13:"wfmstatusname";s:42:"Сунгах зөвшөөрөл хүсэх";s:14:"wfmstatuscolor";s:7:"#80923F";s:13:"wfmstatusicon";s:10:"fa-recycle";}}}}}}}]]')));
    }
     
    public function resendEmdSendDataExcel() {
        echo $this->model->resendCreatePrescriptionExcelModel();
    }     
     
    public function resendEmdSendData($id) {
        echo $this->model->resendCreatePrescriptionModel($id);
    }     
    
    public function qpayGenerateQrCode() {
        
        $sPrefix        = SESSION_PREFIX;
        $storeId        = Session::get($sPrefix.'storeId');
        $cashRegisterId = Session::get($sPrefix.'cashRegisterId');        
        $cashRegisterCode = Session::get($sPrefix.'cashRegisterCode');        
        $bill_no = getUID();
        $params = [
            'clientId' => Config::getFromCache('QPAY_V2_CLIENTID'),
            'clientSecret' => Config::getFromCache('QPAY_V2_CLIENTSECRET'),
            'amount' => Input::post('amount'),
            'storeId' => $storeId,
            'invoice_code' => Config::getFromCache('QPAY_V2_INVOICECODE'),
            'sender_invoice_no' => $bill_no,
            'invoice_receiver_code' => $bill_no,
            'invoice_description' => 'Veritech erp ' . $cashRegisterCode,
            'callback_url' => 'https://dev.veritech.mn/mdintegration/qpaywebhook2'
        ];        
        $response = $this->model->qPayGetInvoiceQrModel($params);
        
        if ($response['status'] == 'success') {
            
            $this->view->base64QrCodeImg = $response['qrcode'];
            
            $response = array( 
                'status' => 'success', 
                'html'   => $this->view->renderPrint('candy/qpayQrCode', self::$viewPath), 
                'traceNo'=> $response['traceNo'], 
                'bill_no' => $bill_no,
                'title'  => 'QPAY QR', 
                'close_btn' => $this->lang->line('close_btn')
            );
        } 
        
        echo json_encode($response); exit;
    }    
    
    public function tokipayGenerateQrCode() {
        
        $sPrefix        = SESSION_PREFIX;
        $storeId        = Session::get($sPrefix.'storeId');
        $cashRegisterId = Session::get($sPrefix.'cashRegisterId');        
        $cashRegisterCode = Session::get($sPrefix.'cashRegisterCode');        
        $bill_no = getUID();
        $params = [
            'amount' => Input::post('amount'),
            'storeId' => $storeId,
            'orderId' => $bill_no,
            'notes' => 'Veritech erp ' . $cashRegisterCode
        ];        
        $response = $this->model->tokiPayGetInvoiceQrModel($params);
        
        if ($response['status'] == 'success') {
            
            $this->view->base64QrCodeImg = $response['qrcode'];
            
            $response = array( 
                'status' => 'success', 
                'html'   => $this->view->renderPrint('candy/tokipayQrCode', self::$viewPath), 
                'traceNo'=> $response['traceNo'], 
                'bill_no' => $bill_no,
                'title'  => 'TOKIPAY QR', 
                'close_btn' => $this->lang->line('close_btn')
            );
        } 
        
        echo json_encode($response); exit;
    }    
    
    public function qpayCheckQrCode() {
        $sPrefix        = SESSION_PREFIX;
        $storeId        = Session::get($sPrefix.'storeId');        
        
        $params = [
            'clientId' => Config::getFromCache('QPAY_V2_CLIENTID'),
            'clientSecret' => Config::getFromCache('QPAY_V2_CLIENTSECRET'),
            'storeId' => $storeId,
            'object_type' => 'INVOICE',
            'object_id' => Input::post('uuid')
        ];             
        $response = $this->model->qpayCheckQrCodeModel($params);
        echo json_encode($response); exit;
    }    
    
    public function tokipayCheckQrCode() {
        $sPrefix        = SESSION_PREFIX;
        $storeId        = Session::get($sPrefix.'storeId');        
        
        $params = [
            'storeId' => $storeId,
            'requestId' => Input::post('uuid')
        ];             
        $response = $this->model->tokipayCheckQrCodeModel($params);
        echo json_encode($response); exit;
    }    

}
