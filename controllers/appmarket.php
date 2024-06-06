<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Appmarket extends Controller {
    
    private static $standartColors = [
        '#FF7E79',
        '#6373ED',
        '#39E0CF',
        '#FEC345',
        '#48C7F4',
        '#FF8E50',
        '#36C09A',
        '#7A93A0',
        '#A74FB6',
        '#9C7D72',
        '#01BCD4',
        '#EB6FA5',
        '#FEB69C',
        '#9C7D72',
        '#FFEB6D',
        '#FFCC99'
    ];

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index($id = '') 
    {   
        $this->view->title = 'App market'; 

        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();        
        $this->view->leftMenuData = $this->model->getDataviewResultModel(1700449463973796, 100);
        $this->view->leftIndustryMenuData = $this->model->getDataviewResultModel(1700453026051099, 100);
        $this->view->standartColors = self::$standartColors;
        $this->view->moduleId = $id;
        $this->view->uniqId = getUID();
        
        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('appmarket/appmarket');
            $this->view->render('footer');
        } else {
            $response['html'] = $this->view->renderPrint('appmarket/appmarket');
            $response['uniqId'] = $this->view->uniqId;
            echo json_encode($response); exit;        
        }        
    }        

    public function detail($id) 
    {   
        $this->view->title = 'App market detail'; 
        $this->view->uniqId = getUID();        
        
        $this->view->getModuleInfo = $this->model->getModuleInfoModel($id);
        $this->view->sessionUserKeyId = Ue::sessionUserKeyId();

        echo $this->view->renderPrint('appmarket/appmarket-detail');
    }    
    
    
    public function basket() 
    {   
        $this->view->title = 'App market basket'; 
        $this->view->uniqId = getUID();
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();   
        
        $this->view->leftMenuData = $this->model->getDataviewResultModel(1700449463973796, 100);
        $this->view->leftIndustryMenuData = $this->model->getDataviewResultModel(1700453026051099, 100);        
        $this->view->getBasket = $this->model->getBasketModel();    
        $this->view->standartColors = self::$standartColors;

        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('appmarket/appmarket-basket');
            $this->view->render('footer');
        } else {
            echo $this->view->renderPrint('appmarket/appmarket-basket');
        }           
    }

    public function basketOther($menuCode = null) 
    {   
        $this->view->title = 'App market basket'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/appmarket.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->render('header');
        $this->view->render('appmarket/appmarket-basket1');
        $this->view->render('footer');
    }
    
    public function getAjaxTree() {
        $dataViewId = Input::param($_REQUEST['dataViewId']);
        $structureMetaDataId = Input::param($_REQUEST['structureMetaDataId']);
        $parent = Input::param($_REQUEST['parent']);
        $criteria = Input::param(issetParam($_REQUEST['criteria']));
        
        $folderList = $this->model->getTreeDataByValue($dataViewId, $structureMetaDataId, $parent, $criteria);
        
        jsonResponse($folderList);
    }        
    
    public function saveToBasket() {
        $id = Input::post('id');
        $itemId = Input::post('itemId');
        $basketTotalAmount = Input::post('basketTotalAmount');
        $price = Input::post('price');
        
        jsonResponse($this->model->saveToBasketModel($id, $itemId, $basketTotalAmount, $price));
    }        
    
    public function qpayGenerateQrCode() {
        
        $bill_no = getUID();
        $params = [
            'clientId' => 'COZY_MN',
            'clientSecret' => '7IOkZyjk',
            'amount' => Input::post('amount'),
            'invoice_code' => 'COZY_MN_INVOICE',
            'sender_invoice_no' => $bill_no,
            'invoice_receiver_code' => $bill_no,
            'invoice_description' => 'Veritech App Market',
            'callback_url' => 'https://dev.veritech.mn/mdintegration/qpaywebhook2'
        ];
        $response = $this->model->qPayGetInvoiceQrModel($params);
        
        if ($response['status'] == 'success') {
            
            $this->view->base64QrCodeImg = $response['qrcode'];
            
            $response = array( 
                'status' => 'success', 
                'html'   => $this->view->renderPrint('candy/qpayQrCode', 'middleware/views/pos/'), 
                'traceNo'=> $response['traceNo'], 
                'bill_no' => $bill_no,
                'title'  => 'QPAY QR', 
                'close_btn' => $this->lang->line('close_btn')
            );
        } 
        
        echo json_encode($response); exit;
    }    
    
    public function qpayCheckQrCode() {
        
        $params = [
            'clientId' => 'COZY_MN',
            'clientSecret' => '7IOkZyjk',
            'object_type' => 'INVOICE',
            'object_id' => Input::post('uuid')
        ];        
        $response = $this->model->qpayCheckQrCodeModel($params);
        echo json_encode($response); exit;
    }        
    
}