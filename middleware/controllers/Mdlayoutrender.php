<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdlayout Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Meta Layout
 * @category	Layout
 * @author	B.Och-Erdene <ocherdene@veritech.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdlayout
 */
class Mdlayoutrender extends Controller {

    private static $viewPath = "middleware/views/layoutrender/";
    private static $linkPath = "middleware/views/metadata/system/link/layout/";
    private static $viewSocialPath = 'middleware/views/metadata/dataview/viewer/explorer/social/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index($metaDataId = '', $isMainPage = 0) {
        self::layout($metaDataId, $isMainPage);
    }
    
    public function layout($metaDataId = '', $isMainPage = 0) {
        
        $this->load->model('mdlayoutrender', 'middleware/models/');
        
        if (!isset($this->view)) {
            $this->view = new View();
        }        
        
        try {
            
            $this->view->metaDataId = ($metaDataId != '' ? Input::paramNum($metaDataId) : Input::numeric('metaDataId'));
            
            $this->view->uniqId = getUID();
            $this->view->workSpaceId = Input::postCheck('workSpaceId') ? Input::post('workSpaceId') : '';
            $this->view->workSpaceParams = Input::postCheck('workSpaceParams') ? Input::post('workSpaceParams') : '';     
            $this->view->executeType = Input::postCheck('executeType') ? Input::post('executeType') : '';
            $this->view->isWorkAlone = Input::numeric('isWorkAlone');
            $this->view->layoutLink = $this->model->getLayoutLinkModel($this->view->metaDataId);
            $this->view->isAjax = is_ajax_request();
            $this->view->defaultCriteria = $this->view->defaultCriteriaExtra = $this->view->advancedCriteria = '';
            $this->view->layoutLinkId = $this->view->layoutLink['ID'];            
            
            if (!empty($this->view->layoutLink['CRITERIA_DATA_VIEW_ID'])) {
                $this->view->metaDataId = $this->view->layoutLink['CRITERIA_DATA_VIEW_ID'];

                $this->load->model('mdobject', 'middleware/models/');
                $this->view->dataViewHeaderRealData = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
                $this->view->row = $this->model->getDataViewConfigRowModel($this->view->metaDataId);

                $this->view->dataViewCriteriaType = strtolower($this->view->row['SEARCH_TYPE'] == '0' ? 'BUTTON' : Info::getSearchType($this->view->row['SEARCH_TYPE']));
                $this->view->dataViewHeaderData = Mdobject::findCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);
                $this->view->dataViewMandatoryHeaderData = Mdobject::findMandatoryCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);
                $advancedCriteria = ($this->view->dataViewHeaderData) ? Arr::multidimensional_list($this->view->dataViewHeaderData['data'], array('IS_ADVANCED' => '1')) : array();    
                includeLib('Compress/Compression');
                $this->view->advancedCriteria = Compression::encode_string_array(array('fillPath' => isset($this->view->fillPath) ? $this->view->fillPath : array(), 'advancedCriteria' => $advancedCriteria));                

                if ($this->view->dataViewMandatoryHeaderData) {
                    $this->view->defaultCriteria = $this->view->renderPrint('search/defaultCriteriaMandatory', 'middleware/views/metadata/dataview/'); 
                    $this->view->defaultCriteriaExtra = $this->view->renderPrint('search/defaultCriteria', 'middleware/views/metadata/dataview/');
                } else {
                    $this->view->defaultCriteria = $this->view->renderPrint('search/defaultCriteria', 'middleware/views/metadata/dataview/');
                }
                $this->view->defaultCriteria .= $this->view->renderPrint('viewer/detail/main/criteriaScripts', 'middleware/views/metadata/dataview/');
            } 

            if ($this->view->layoutLink) {
                
                if ($this->view->isWorkAlone) {
                    $this->view->filterParams = '';
                    if (Input::postCheck('filterParams')) {
                        $this->view->filterParams = json_encode($_POST['filterParams'], JSON_UNESCAPED_UNICODE);
                    }
                    $this->view->defaultCss = $this->view->renderPrint('scripts/css', self::$viewPath);
                    $this->view->defaultJs = $this->view->renderPrint('scripts/js', self::$viewPath);
                }
                
                switch ($this->view->layoutLink['THEME_CODE']) {
                    case 'dashboardv1':
                        header("location: ".URL.'government/unitdashboard');
                        break;
                    case 'dashboardv2':
                        header("location: ".URL.'government/agentdashboard');
                        break;
                        if (!$this->view->isAjax) {

                            $this->view->isAjax = false;
                            $this->view->css = AssetNew::metaCss();
                            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
                            $this->view->fullUrlJs = AssetNew::amChartJs();
                            $this->view->js = AssetNew::metaOtherJs();
                            $this->view->title = Lang::line($this->view->layoutLink['META_DATA_NAME']);
                            $this->view->isMainPage = $isMainPage;
                            
                            $this->view->render('header');
                            $this->view->render('render', self::$viewPath);
                            $this->view->render('footer');

                        } else {

                            $this->view->isAjax = true;
                            $this->view->title = Lang::line($this->view->layoutLink['META_DATA_NAME']);
                            $this->view->isDialog = Input::post('isDialog');

                            $response = array(
                                'Html' => $this->view->renderPrint('render', self::$viewPath),
                                'Title' => $this->view->title,
                                'close_btn' => Lang::line('close_btn'),
                                'mode' => ($this->view->isDialog) ? 'dialog' : 'main'
                            );

                            echo json_encode($response);
                        }
                        
                        break;
                    default:
                        $searchReplace = array(
                            '{layout-id}"',
                            '{layout-uniqid}',
                            '{hidden-params}',
                            '{layout-criteria-params}',
                            '{layout-criteria-dv-id}',
                            '{customercode}'
                        );

                        $replaced = array(
                            $this->view->layoutLink['ID'].'" '.'data-loaded-layoutdata="false"',
                            $this->view->uniqId,
                            '',
                            $this->view->defaultCriteria,
                            $this->view->metaDataId,
                            (Config::getFromCache('tmsCustomerCode') == 'gov') ? 'hr_gov w-100' : '',
                        );
                        
                        $ml = &getInstance();
                        $ml->load->model('mdlayoutrender', 'middleware/models/');

                        $this->view->layoutParamMap = $ml->model->getLayoutParamMapModel($this->view->layoutLink['ID']);
                        foreach ($this->view->layoutParamMap as $k => $row) {
                            array_push($searchReplace, '{' . $row['LAYOUT_PATH'] . '}');
                            array_push(
                                $replaced,
                                '<div class="'. (Mdmetadata::$cardMetaTypeId === '200101010000031' ? 'layout-fill' : '') .' layout-fill-' . $this->view->metaDataId . '" id="layout-' . $row['BP_META_DATA_ID'] . '" data-meta-type-id="' . $row['META_TYPE_ID'] . '" data-meta-id="' . $row['BP_META_DATA_ID'] . '" data-meta-code="' . $row['META_DATA_CODE'] . '" data-layout-param-map-id="' . $row['ID'] . '" data-pfgotometa="1"></div>'
                            );
                        }

                        for ($indexPath = 1; $indexPath <= 24; $indexPath++) {
                            if (!in_array('{data-position-' . $indexPath . '}', $searchReplace)) {
                                array_push($searchReplace, '{data-position-' . $indexPath . '}');
                            }
                        }

                        $layoutContent = file_get_contents(BASEPATH . 'middleware/views/layoutrender/themes/' . $this->view->layoutLink['THEME_CODE'] . '/theme.html');
                        $this->view->replacedLayoutHtml = str_replace($searchReplace, $replaced, $layoutContent);

                        $this->view->metaBackLink = 'mdmetadata/system';
                        $this->view->isBackLink = Config::getFromCache('CONFIG_OBJECT_BACKLINK');
                        
                        $appmenu = &getInstance();
                        $appmenu->load->model('appmenu', 'models/');            
                        $this->view->getResetUser = Config::getFromCache('IsChangePassword') == '1' ? $appmenu->model->getResetPasswordUser() : false;                           

                        if (!$this->view->isAjax) {

                            $this->view->css = AssetNew::metaCss();
                            $this->view->fullUrlJs = AssetNew::amChartJs();
                            $this->view->js = AssetNew::metaOtherJs();
                            $this->view->title = Lang::line($this->view->layoutLink['META_DATA_NAME']);
                            
                            $moduleId = Input::get('mmid');
                            
                            if ($moduleId) {
                                $mdmeta = &getInstance();
                                $mdmeta->load->model('mdmeta', 'middleware/models/');
                                $moduleInfo = $mdmeta->model->getModuleNameModel($moduleId);
                                $this->view->moduleName = Lang::line($moduleInfo['META_DATA_NAME']);
                            } else {
                                $this->view->moduleName = '';
                            }
                            
                            $this->view->isMainPage = $isMainPage;

                            $this->view->render('header');
                            $this->view->render('layoutRender', self::$viewPath);
                            $this->view->render('footer');

                        } else {

                            $this->view->title = Lang::line($this->view->layoutLink['META_DATA_NAME']);
                            $this->view->isDialog = Input::post('isDialog');

                            $response = array(
                                'Html' => $this->view->renderPrint('layoutRender', self::$viewPath),
                                'Title' => $this->view->title,
                                'close_btn' => $this->lang->line('close_btn'),
                                'mode' => ($this->view->isDialog) ? 'dialog' : 'main'
                            );

                            echo json_encode($response); exit;
                        }
                        
                        break;
                }
            }
            
        } catch (Exception $ex) {
            
            set_status_header(404);
        
            $err = Controller::loadController('Err');
            $err->index();
            exit;
        }
    }

    public function getShowTypeModal() {
        $dir = self::$viewPath . 'themes/dvtheme';
        $showTypeThemeList = $dataViewColumnNameList = array();
        $listThemeFiles = scandir($dir);
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->metaTypeId = Input::post('metaTypeId');
        if ($listThemeFiles) {
            foreach ($listThemeFiles as $file) {
                if ($file != '.' && $file != '..' && is_file($dir . '/' . $file)) {
                    $showTypeThemeList[] = array(
                        'code' => str_replace('.html', '', $file),
                        'name' => str_replace('.html', '', $file),
                    );
                }
            }
        }

        $this->view->showTypeThemeList = $showTypeThemeList;
        if ($this->view->metaTypeId == Mdmetadata::$metaGroupMetaTypeId) {
            $responseColumnNameList = $this->model->getDataViewColumnNameList($this->view->metaDataId);
        } else if ($this->view->metaTypeId == Mdmetadata::$widgetMetaTypeId) {
            $this->view->getMetaWidgetLink = $this->model->getMetaWidgetLinkModel($this->view->metaDataId);
            $responseColumnNameList = $this->model->getDataViewColumnNameList($this->view->getMetaWidgetLink['LIST_META_DATA_ID']);
            if ($this->view->getMetaWidgetLink) {
                $this->view->getMetaWidgetParam = $this->model->getMetaWidgetParamModel($this->view->getMetaWidgetLink['ID'], $this->view->getMetaWidgetLink['LIST_META_DATA_ID']);
            }
        }

        if ($responseColumnNameList) {
            $dataViewColumnNameList = array('dataViewColumnNameList' => $responseColumnNameList);
        }
        $response = array_merge(array(
            'Html' => $this->view->renderPrint('showType', self::$linkPath),
            'Title' => 'SHOWTYPE',
            'css' => array('middleware/assets/css/layout-theme/layotShowType.css'),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
                ), $dataViewColumnNameList);
        echo json_encode($response);
    }

    public function getShowTypeParameters() {
        $dir = self::$viewPath . 'themes/dvtheme';
        $themeCode = Input::post('themeCode');
        $fileContent = file_get_contents(BASEPATH . $dir . '/' . $themeCode . '.html');
        if ($fileContent) {
            echo json_encode(array(0 => $fileContent));
        } else {
            echo json_encode(array('errorMessage' => 'Файл олдсонгүй'));
        }
    }

    public function getTemplateAndConfig() {
        $metaWidgetLinkParamList = $this->model->getMetaWidgetLinkParams();
        if ($metaWidgetLinkParamList) {

            $adHtml = file_get_contents(BASEPATH . 'middleware/views/layoutrender/themes/dvtheme/' . $metaWidgetLinkParamList[0]['SUBTYPE'] . '.html');
            if ($adHtml) {
                echo json_encode(
                        array(
                            'themeHtml' => $adHtml,
                            'metaWidgetLinkParamList' => $metaWidgetLinkParamList
                        )
                );
            }
        }
    }
    
    public function social() {
        $this->load->model('mdworkspace', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->isAjax = true;
        
        if (!is_ajax_request()) {
            $this->view->css = array_unique(array_merge(array(
                'middleware/assets/theme/theme17/css/main.css' 
                ), AssetNew::metaCss()
            ));
            $this->view->js = AssetNew::metaOtherJs();
            $this->view->fullUrlJs = array(
                'middleware/assets/js/mdtaskflow.js',
                'assets/custom/addon/plugins/jquery-easypiechart/jquery.easypiechart.min.js',
                'assets/custom/addon/plugins/jquery.sparkline.min.js'
            );
            $this->view->isAjax = false;
            $this->view->render('header');
            
        }
        
        $this->view->metaDataId = getUID();
        $this->view->socialViewStyle = $this->view->renderPrint('css', self::$viewSocialPath);
        $this->view->render('index', self::$viewSocialPath);
        
        if (!is_ajax_request()) {
            $this->view->render('footer');
        }
    }
    
    public function reloadLayout() {
        
        $response = array(
                        'Html' => '',
                        'Title' => '',
                    );
        
        $this->view->metaDataId = Input::post('metaDataId');
        $this->view->uniqId = Input::post('uniqId');
        $this->view->layoutLink = $this->model->getLayoutLinkModel($this->view->metaDataId);
        
        if ($this->view->layoutLink) {
            $currentDate = Date::currentDate('Y-m-d');
            Session::init();
            
            $paramFilter = array(
                'filterEndDate' => array(
                    array(
                        'operator' => '=',
                        'operand' => $currentDate
                    )
                ),
                'sessionDepartmentId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Ue::sessionDepartmentId()
                    )
                ),
                'filterDepartmentId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Ue::sessionDepartmentId()
                    )
                ),
                'sessionUserId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Ue::sessionUserId()
                    )
                ),
                'filterUserId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Ue::sessionUserId()
                    )
                ),
                'sessionUserKeyId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Ue::sessionUserKeyId()
                    )
                ),
                'filterNextWfmUserId' => array(
                    array(
                        'operator' => '=',
                        'operand' => Ue::sessionUserKeyId()
                    )
                ),
            );
            
            $this->view->layoutPositionArr = array();
                    
            switch ($this->view->layoutLink['THEME_CODE']) {
                case 'dashboardv1':
                    $this->view->layoutPositionArr['pos_1_dvid'] = "1568362202338";
                    $this->view->layoutPositionArr['pos_2_dvid'] = "1572351184117";
                    $this->view->layoutPositionArr['pos_3_dvid'] = "1572350681806";
                    $this->view->layoutPositionArr['pos_4_dvid'] = "1568972234402410";
                    $this->view->layoutPositionArr['pos_5_dvid'] = "1571474482320";
                    
                    $this->view->layoutPositionArr['pos_6_0_dvid'] = "1567152804488";
                    $this->view->layoutPositionArr['pos_6_1_dvid'] = "1568086502403341";
                    $this->view->layoutPositionArr['pos_6_2_dvid'] = "1568362804484";
                    $this->view->layoutPositionArr['pos_6_3_dvid'] = "1568362202687";
                    
                    $this->view->layoutPositionArr['pos_7_dvid'] = "1568889882063";
                    $this->view->layoutPositionArr['pos_8_0_dvid'] = "1568889881645";
                    $this->view->layoutPositionArr['pos_8_1_dvid'] = "1568889881884";
                    $this->view->layoutPositionArr['pos_9_0_dvid'] = "1568889882293";
                    $this->view->layoutPositionArr['pos_9_1_dvid'] = "1568889882478";
                    
                    $this->view->layoutPositionArr['pos_1'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_2'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_2_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_3'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_3_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_3'] = array(Arr::groupByArrayRowByKey($this->view->layoutPositionArr['pos_3'], 'workedtimepercent'));
                    
                    $this->view->layoutPositionArr['pos_4'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_4_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_5'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_5_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");

                    $this->view->layoutPositionArr['pos_6'] = array();
                    $this->view->layoutPositionArr['pos_6'][0] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_6_0_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -6 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_6'][1] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_6_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_6'][2] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_6_2_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_6'][3] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_6_3_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");

                    $this->view->layoutPositionArr['pos_7'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_7_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    
                    $this->view->layoutPositionArr['pos_8'] = array();
                    $this->view->layoutPositionArr['pos_8'][0] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_8_0_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_8'][1] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_8_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");

                    $this->view->layoutPositionArr['pos_9'] = array();
                    $this->view->layoutPositionArr['pos_9'][0] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_9_0_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_9'][1] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_9_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");

                    $this->view->layoutParamMap = $this->model->getLayoutParamMapModel($this->view->layoutLink['ID']);
                    $render = 'static/dashboard1';
                    
                    $response = array(
                        'Html' => $this->view->renderPrint($render, self::$viewPath),
                        'Title' => '',
                        'data' => $this->view->layoutPositionArr['pos_6'][0],
                        'close_btn' => Lang::line('close_btn'),
                    );
                    break;
                case 'dashboardv2':
                    $this->view->layoutPositionArr['pos_1_dvid'] = "1568362202338";
                    $this->view->layoutPositionArr['pos_2_dvid'] = "1572351189226";
                    $this->view->layoutPositionArr['pos_3_dvid'] = "1572350684801";
                    $this->view->layoutPositionArr['pos_4_dvid'] = "1568972234402410";
                    $this->view->layoutPositionArr['pos_5_dvid'] = "1571474482320";
                    $this->view->layoutPositionArr['pos_6_0_dvid'] = "1568018390310";
                    $this->view->layoutPositionArr['pos_6_1_dvid'] = "1568282239461311";
                    $this->view->layoutPositionArr['pos_7_dvid'] = "1568889882063";
                    $this->view->layoutPositionArr['pos_8_0_dvid'] = "1568889881645";
                    $this->view->layoutPositionArr['pos_8_1_dvid'] = "1568889881884";
                    $this->view->layoutPositionArr['pos_9_0_dvid'] = "1568889882293";
                    $this->view->layoutPositionArr['pos_9_1_dvid'] = "1568889882478";
                    
                    $this->view->layoutPositionArr['pos_1'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_2'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_2_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_3'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_3_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
//                    $this->view->layoutPositionArr['pos_3'] = array(Arr::groupByArrayRowByKey($this->view->layoutPositionArr['pos_3'], 'workedtimepercent'));
                    
                    $this->view->layoutPositionArr['pos_4'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_4_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_5'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_5_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");

                    $this->view->layoutPositionArr['pos_6'] = array();
                    $this->view->layoutPositionArr['pos_6'][0] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_6_0_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_6'][1] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_6_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");

                    $this->view->layoutPositionArr['pos_7'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_7_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");

                    $this->view->layoutPositionArr['pos_8'] = array();
                    $this->view->layoutPositionArr['pos_8'][0] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_8_0_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_8'][1] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_8_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");

                    $this->view->layoutPositionArr['pos_9'] = array();
                    $this->view->layoutPositionArr['pos_9'][0] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_9_0_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");
                    $this->view->layoutPositionArr['pos_9'][1] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_9_1_dvid'], "filterstartdate", "=", Date::weekdayAfter('Y-m-d', $currentDate, ' -30 days'), $paramFilter, "", "", "0");

                    $this->view->layoutParamMap = $this->model->getLayoutParamMapModel($this->view->layoutLink['ID']);
                    $render = 'static/dashboard2';
                    
                    $response = array(
                        'Html' => $this->view->renderPrint($render, self::$viewPath),
                        'Title' => '',
                        'close_btn' => Lang::line('close_btn'),
                    );
                    break;
            }
        }
        
        echo json_encode($response);
    }
    
}
