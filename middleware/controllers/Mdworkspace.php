<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdworkspace Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	WorkSpace
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdworkspace
 */

class Mdworkspace extends Controller {
    
    private static $viewPath = 'middleware/views/workspace/';
    private static $viewMetaPath = 'middleware/views/metadata/';
    
    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index($workSpaceId = '', $isJson = '1') {
        
        $this->load->model('mdworkspace', 'middleware/models/');
        if (!isset($this->view)) {
            $this->view = new View();
        }

        $this->view->title = '';
        
        if (Input::postCheck('param')) {
            $postData = Input::post('param');
            if (isset($postData['title'])) {
                $this->view->title = $postData['title'];
            }
        }
        
        $content = self::workSpaceContent($workSpaceId);
        
        if (!$content) {
            Message::add('e', '', 'back');
        }
        
        $this->view->isAjax = is_ajax_request();
        
        if (!$this->view->isAjax) {
            
            $this->view->css = AssetNew::metaCss();
            $this->view->fullUrlCss = array('middleware/assets/theme/'.$this->view->wsRow['THEME_CODE'].'/css/main.css');
            $this->view->js = AssetNew::metaOtherJs();
            $this->view->fullUrlJs = array(
                'middleware/assets/js/mdtaskflow.js',
                'assets/custom/addon/plugins/jquery-easypiechart/jquery.easypiechart.min.js',
                'assets/custom/addon/plugins/jquery.sparkline.min.js'
            );
            
            if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) { 
                $this->view->replacedWorkSpaceHtml = $content['html'];
            } else {
                if ($this->view->wsRow['THEME_CODE'] == 'theme15' || $this->view->wsRow['THEME_CODE'] == 'theme14' || $this->view->wsRow['THEME_CODE'] == 'theme18' || $this->view->wsRow['THEME_CODE'] == 'theme19' || $this->view->wsRow['THEME_CODE'] == 'theme20' || $this->view->wsRow['THEME_CODE'] == 'theme24' || $this->view->wsRow['THEME_CODE'] == 'theme21' || $this->view->wsRow['THEME_CODE'] == 'theme27' || $this->view->wsRow['THEME_CODE'] == 'theme28' || $this->view->wsRow['THEME_CODE'] == 'theme29' || $this->view->wsRow['THEME_CODE'] == 'theme30') {
                    $this->view->replacedWorkSpaceHtml = '<div class="col-md-12">'. $content['html'] .'</div>';
                } else {
                    $this->view->replacedWorkSpaceHtml = '<div class="col-md-12"><div class="card light shadow">'. $content['html'] .'</div></div>';
                }
            }
            
            $this->view->render('header');
            
        } else {
            $this->view->replacedWorkSpaceHtml = '<link href="'.autoVersion('middleware/assets/theme/'.$this->view->wsRow['THEME_CODE'].'/css/main.css').'" rel="stylesheet" type="text/css"/>';
            $this->view->replacedWorkSpaceHtml .= $content['html'];
        }
        
        if ($isJson == '1') {
            $this->view->render('index', self::$viewPath);
        } else {
            return $this->view->renderPrint('index', self::$viewPath);
        }
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
    }
    
    public function workSpaceContent($workSpaceId = '') {
        
        if (!isset($this->view)) {
            $this->view = new View();
        }
        
        $this->load->model('mdworkspace', 'middleware/models/');
        $this->view->metaDataId = ($workSpaceId != '' ? $workSpaceId : Input::numeric('metaDataId'));
        $this->view->wsRow = $this->model->getWorkSpaceByMetaIdModel($this->view->metaDataId);

        if (!$this->view->wsRow) {
            return false;
        }
        
        if ($this->view->wsRow['THEME_CODE'] == 'ntrSocialView') {
            return self::workSpaceSocialViewContent($this->view->metaDataId, $this->view->wsRow);
        }
        
        $response = $subMenu = $tabHtml = $layoutRender = $cartMenuHtml = '';
        $selectedRow = Input::post('selectedRow');
        $this->view->iscontract = (isset($selectedRow['iscontract'])) ? $selectedRow['iscontract'] : '0';
        
        if ($selectedRow == null && $this->view->wsRow['GROUP_META_DATA_ID'] != '' && $this->view->wsRow['ACTION_TYPE'] != 'add') {
            
            $this->load->model('mdobject', 'middleware/models/');
            
            $selectedRow = self::getDVRowDataByDefaultCriteria($this->view->wsRow['GROUP_META_DATA_ID']);
            
            $_POST['selectedRow'] = $selectedRow;
            $_POST['dmMetaDataId'] = $this->view->wsRow['GROUP_META_DATA_ID'];
            
        } elseif ($selectedRow == null && $this->view->wsRow['GROUP_META_DATA_ID'] != '' && $this->view->wsRow['ACTION_TYPE'] == 'add') {
            
            $_POST['selectedRow'] = false;
            $_POST['dmMetaDataId'] = $this->view->wsRow['GROUP_META_DATA_ID'];
            
        } elseif ($this->view->wsRow['ROW_DATAVIEW_ID'] != '' && $this->view->wsRow['ACTION_TYPE'] != 'add' && $selectedRow) {
            
            $rowData = $this->model->getRowDataViewIdByIdModel(Input::numeric('dmMetaDataId'), $this->view->wsRow['ROW_DATAVIEW_ID'], $selectedRow);
            
            if ($rowData) {
                $selectedRow = array_merge($selectedRow, $rowData);
                $_POST['selectedRow'] = $selectedRow;
            }
        }
        
        if (Input::isEmpty('dmMetaDataId') && $this->view->wsRow['GROUP_META_DATA_ID'] != '') {
            $_POST['dmMetaDataId'] = $this->view->wsRow['GROUP_META_DATA_ID'];
        }

        if (isset($selectedRow['pfnextstatuscolumn'])) {
            unset($selectedRow['pfnextstatuscolumn']);
        }
        
        $this->view->wsOneSelectedRow = $selectedRow;
        $this->view->wsDmMetaDataId = Input::numeric('dmMetaDataId');
        
        $this->load->model('mdworkspace', 'middleware/models/');
        
        $this->view->defaultMenuId = $this->view->wsRow['DEFAULT_MENU_ID'];
        
        $isLastVisitMenu = $this->view->wsRow['IS_LAST_VISIT_MENU'];
        $windowType = $this->view->wsRow['WINDOW_TYPE'];
        $themeCode = $this->view->wsRow['THEME_CODE'];
        $rootMenuId = $this->view->wsRow['MENU_META_DATA_ID'];
        $subMenuId = $this->view->wsRow['SUBMENU_META_DATA_ID'];
        
        if ($isLastVisitMenu == '1' && Cookie::isCheck('ws_last_menu_'.$this->view->metaDataId.'_'.$this->view->wsDmMetaDataId.'_'.issetParam($this->view->wsOneSelectedRow['id']))) {
            $this->view->defaultMenuId = Input::param(Cookie::get('ws_last_menu_'.$this->view->metaDataId.'_'.$this->view->wsDmMetaDataId.'_'.issetParam($this->view->wsOneSelectedRow['id'])));
        }
        
        $menuHtml = html_tag('div', array('class' => 'alert alert-danger float-left'), 'Цэс олдсонгүй.');
        
        if ($rootMenuId) {

            $menuData = $this->model->getMetaMenuListByWorkSpaceIdModel($this->view->wsRow['ID'], $selectedRow);
            if ($menuData['status'] == 'success' && isset($menuData['menuData'][0]['child'])) {
                
                if (isset($selectedRow['defaultmenuid'])) {
                    $this->view->defaultMenuId = Input::paramNum($selectedRow['defaultmenuid']);
                }
                
                switch ($themeCode) {
                    case 'theme9':
                    case 'theme10':
                    case 'theme11':
                    case 'theme12':
                    case 'theme13':    
                    case 'theme14':    
                    case 'theme15':      
                    case 'theme19':    
                    case 'theme20':   
                    case 'theme24':   
                    case 'theme21':        
                    case 'theme22':   
                    case 'theme27':   
                    case 'theme28':   
                    case 'theme30':     
                    case 'theme32':     
                        if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) { 
                            
                            $menuData = $menuData['menuData'][0]['child'];
                            $menuHtml = $this->model->topMetaMenuModuleV2Model($this->view->metaDataId, $menuData, $rootMenuId, 0, false, 'close_all', $this->view->defaultMenuId, array_merge($this->view->wsRow, ($selectedRow != null ? $selectedRow : array())), 0, $themeCode, $this->view->wsRow, $selectedRow, $this->view->wsDmMetaDataId);
                            
                            if (!$this->view->defaultMenuId) {
                                $firstKey = key($menuData); 
                                $this->view->defaultMenuId = $menuData[$firstKey]['metadataid'];
                            }
                            
                        } else {
                            $menuHtml = $this->model->topMetaMenuModuleModel($this->view->metaDataId, $menuData['menuData'][0]['child'], $rootMenuId, 0, false, 'close_all', $this->view->defaultMenuId, array_merge($this->view->wsRow, ($selectedRow != null ? $selectedRow : array())), 0, $themeCode, $this->view->wsRow, $selectedRow, $this->view->wsDmMetaDataId);
                        }
                        break;
                    case 'theme33':
                        $menuHtml = $this->model->menuTheme33Model($this->view->metaDataId, $menuData['menuData'][0]['child'], $rootMenuId, 0, false, 'close_all', $this->view->defaultMenuId, array_merge($this->view->wsRow, ($selectedRow != null ? $selectedRow : array())), 0, $themeCode, $this->view->wsRow, $selectedRow, $this->view->wsDmMetaDataId);
                    case 'shop':
                        $tabHtml = $this->model->tabModuleModel($this->view->metaDataId, $menuData['menuData'][0]['child'], $rootMenuId, $this->view->defaultMenuId, array_merge($this->view->wsRow, ($selectedRow != null ? $selectedRow : array())));
                        break;
                    case 'theme18':
                        $cartMenuHtml = $this->model->cartModuleModel($this->view->metaDataId, $menuData['menuData'][0]['child'], $rootMenuId, 0, false, 'close_all', $this->view->defaultMenuId, array_merge($this->view->wsRow, ($selectedRow != null ? $selectedRow : array())), 0, $themeCode, $this->view->wsRow, $selectedRow, $this->view->wsDmMetaDataId);
                        break;
                    case 'wizard':
                        $menuHtml = $this->model->menuThemeWizardModel($this->view->metaDataId, $menuData['menuData'][0]['child'], $rootMenuId, 0, false, 'close_all', $this->view->defaultMenuId, array_merge($this->view->wsRow, ($selectedRow != null ? $selectedRow : array())), 0, $themeCode, $this->view->wsRow, $selectedRow, $this->view->wsDmMetaDataId);
                        break;
                    default:
                        $menuHtml = $this->model->leftMetaMenuModuleModel($this->view->metaDataId, $menuData['menuData'][0]['child'], $rootMenuId, 0, false, 'close_all', $this->view->defaultMenuId, array_merge($this->view->wsRow, ($selectedRow != null ? $selectedRow : array())), 0, $themeCode, $this->view->wsRow, $selectedRow, $this->view->wsDmMetaDataId);
                        if ($subMenuId) {
                            $subMenuData = $this->model->getMetaMenuListByModuleIdModel($subMenuId, $selectedRow);
                            
                            if ($subMenuData['status'] == 'success' && isset($subMenuData['menuData'][0]['child'])) {
                                $tabHtml = $this->model->leftMetaMenuModuleModel($this->view->metaDataId, $subMenuData['menuData'][0]['child'], $subMenuId, 0, false, 'close_all', $this->view->defaultMenuId, array_merge($this->view->wsRow, ($selectedRow != null ? $selectedRow : array())));
                            }
                        }
                        break;
                }
            } else {
                $menuHtml = html_tag('div', array('class' => 'alert alert-danger float-left'), $menuData['message']);
            }
        }
        
        $workSpaceContent = file_get_contents(BASEPATH.'middleware/views/workspace/themes/'.$themeCode.'/theme.html');
        
        $layoutMetaDataId = $this->view->wsRow['LAYOUT_META_DATA_ID'];
        
        if ($layoutMetaDataId) {
            
            $this->load->model('mdlayoutrender', 'middleware/models/');
            
            $this->view->layoutLink = $this->model->getLayoutLinkModel($layoutMetaDataId);
            
            if ($this->view->layoutLink) {
                
                $this->view->layoutParamMap = $this->model->getLayoutParamMapModel($this->view->layoutLink['ID']);

                $layoutSearchReplace = array(
                    '{layout-id}',
                    '{hidden-params}'
                );
                $layoutReplaced = array(
                    $this->view->layoutLink['ID'],
                    ''
                );
                $tmpObject = new Mddashboard();

                foreach ($this->view->layoutParamMap as $k => $row) {
                    array_push($layoutSearchReplace, '{' . $row['LAYOUT_PATH'] . '}');
                    array_push($layoutReplaced, '<div class="layout-fill" id="layout-' . $row['BP_META_DATA_ID'] . '" data-meta-type-id="' . $row['META_TYPE_ID'] . '" data-meta-id="' . $row['BP_META_DATA_ID'] . '"></div>');
                }
                
                $layoutContent = file_get_contents(BASEPATH . 'middleware/views/layoutrender/themes/' . $this->view->layoutLink['THEME_CODE'] . '/theme.html');
                $layoutRender = str_replace($layoutSearchReplace, $layoutReplaced, $layoutContent);
                
            }
        }
        
        $this->load->model('mdworkspace', 'middleware/models/');
        
        $searchReplace = array(
            '{workspace-id}', 
            '{menu}',
            '{tab}',
            '{cartMenu}',
            '{layout-render}',
            '{header-position-1}', 
            '{header-position-2}',
            '{header-position-3}', 
            '{header-position-4}', 
            '{header-position-5}', 
            '{header-position-6}', 
            '{header-position-7}', 
            '{hidden-params}',
            '{cover}',
            '{left-side}',
            '{right-side}',
            '{ws-bg}',
            '{back-btn}',
            '{img-margin}',
            '{use-picture}',
            '{dm-metadata-id}',
        );
       
        $leftSideBar = $rightSideBar = $wsBg = $backBtn = $imgMargin = '';
        $usePic = 'hidden';
        
        if ($this->view->wsRow['USE_PICTURE'] == '1') {
            $imgMargin = 'mt180';
            $usePic = '';
        }
        
        if ($this->view->wsRow['USE_COVER_PICTURE'] == '0') {
            $wsBg = 'hidden';
            $imgMargin = 'mt0';
            $leftSideBar = 'mt0';
        }
        
        if ($this->view->wsRow['USE_LEFT_SIDE'] == '0') {
            $leftSideBar = 'hidden emptyit';
            $rightSideBar = 'width-100';
        } 
       
        if (Input::postCheck('selectedRow') && Input::postCheck('dmMetaDataId')) {
            
            if (is_array($selectedRow)) {
                
                foreach ($selectedRow as $skey => $srow) {
                    if (isset($srow['children'])) {
                        unset($srow['children']);
                    }
            
                    if (isset($srow['pfnextstatuscolumn'])) {
                        unset($srow['pfnextstatuscolumn']);
                    }
                    
                    if (!is_array($srow)) {
                        $selectedRow[$skey] = strip_tags($srow);
                    }
                }
            }
            
            $dmMetaDataId = Input::numeric('dmMetaDataId');
            
            $replaced = array(
                $this->view->metaDataId, 
                $menuHtml, 
                $tabHtml,
                $cartMenuHtml,
                $layoutRender,
                $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-1', $selectedRow, $themeCode), 
                $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-2', $selectedRow), 
                $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-3', $selectedRow), 
                $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-4', $selectedRow, $themeCode),
                $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-5', $selectedRow),
                $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-6', $selectedRow),
                $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-7', $selectedRow),
                $this->model->renderHiddenParams($selectedRow) . '<input type="hidden" name="isFlow" id="isFlow" value="'.$this->view->wsRow['IS_FLOW'].'">', /* iim bsaniig oorchlow 2017.01.25 ($this->view->wsRow['ACTION_TYPE'] == 'edit' ? $this->model->renderHiddenParams($selectedRow) . '<input type="hidden" name="isFlow" id="isFlow" value="'.$this->view->wsRow['IS_FLOW'].'">' : ''), */
                $this->model->getWorkSpaceCoverPosition($this->view->metaDataId, $dmMetaDataId, $this->view->wsRow['GROUP_META_DATA_ID'], $selectedRow),
                $leftSideBar, //hidden
                $rightSideBar, //width-100
                $wsBg,
                'hidden',
                $imgMargin, //mt180
                $usePic, // ''
                $dmMetaDataId
            );
            
            switch ($themeCode) {
                
                case 'theme10':
                case 'theme22':
                    
                    $this->load->model('mdmetadata', 'middleware/models/');
                    $this->view->metaDataRow = $this->model->getMetaDataModel($dmMetaDataId, false);
                    
                    /*$this->load->model('mdobject', 'middleware/models/');
                    $selectedRow = self::getDVRowDataByDefaultCriteria($this->view->wsRow['GROUP_META_DATA_ID'], true);*/

                    $this->load->model('mdworkspace', 'middleware/models/');
                    $this->view->viewrenderRow = $this->model->getWorkSpaceHeaderPositions($this->view->metaDataId, $dmMetaDataId, $selectedRow);

                    array_push($searchReplace, '{header-001}');
                    array_push($replaced, $this->view->renderPrint('wpheader', self::$viewPath));

                    array_push($searchReplace, '{title-001}');
                    array_push($replaced, $this->view->metaDataRow['META_DATA_NAME']);
                    
                    break;
                case 'theme11':
                case 'theme12':
                case 'theme14':
                case 'theme17':
                case 'theme19':
                case 'theme18':
                case 'theme20':
                case 'theme24':   
                case 'theme21':
                case 'theme27':
                case 'theme28':
                case 'theme30':
                case 'theme32':
                case 'theme34':    
                    
                    $searchReplace = array_merge($searchReplace, array(
                        '{header-position-8}',
                        '{header-position-9}',
                        '{header-position-10}',
                        '{header-position-14}',
                        '{header-position-15}',
                        '{header-position-16}',
                        '{header-position-17}',
                        '{header-position-18}',
                        '{header-position-19}',
                        '{header-position-20}',
                        '{header-position-21}',
                        '{header-position-22}',
                        '{header-position-23}',
                        '{header-position-24}',
                        '{header-position-25}',
                        '{header-position-26}',
                        '{header-position-27}',
                        '{header-position-28}',
                        '{header-position-29}',
                        '{header-position-30}',
                        '{header-position-31}',
                        '{header-position-32}',
                        '{header-position-33}',
                        '{header-position-34}',
                        '{header-position-35}',
                        '{header-position-36}',
                        '{header-position-43}',
                        '{header-position-44}',
                        '{usemenu}',
                    ));

                    $replaced = array_merge($replaced, array(
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-8', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-9', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-10', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-14', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-15', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-16', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-17', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-18', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-19', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-20', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-21', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-22', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-23', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-24', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-25', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-26', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-27', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-28', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-29', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-30', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-31', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-32', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-33', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-34', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-35', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-36', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-43', $selectedRow),
                        $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, $dmMetaDataId, 'header-position-44', $selectedRow),
                        $this->view->wsRow['USE_MENU'] == '1' ? 'hidden' : '',
                    ));

                    break;
            }
            
        } else {
            
            $replaced = array(
                $this->view->metaDataId, 
                $menuHtml, 
                $tabHtml,
                $cartMenuHtml,
                '',
                $this->model->getWorkSpaceHeaderPosition($this->view->metaDataId, '', 'header-position-1', '', $this->view->wsRow), 
                '', 
                '', 
                '',
                '',
                ''
            );
        }
        
        $replacedWorkSpaceHtml = str_replace($searchReplace, $replaced, $workSpaceContent);
        
        if ($themeCode == 'theme19' || $themeCode == 'theme21' || $themeCode == 'theme30') {
            $replacedWorkSpaceHtml = $this->model->setMetaWorkSpaceModel($this->view->metaDataId, $selectedRow, $replacedWorkSpaceHtml);
        }
        
        $this->view->replacedWorkSpaceHtml = $this->model->setLabelNameWorkSpaceModel($this->view->metaDataId, $this->view->wsRow['GROUP_META_DATA_ID'], $replacedWorkSpaceHtml);
        
        $this->view->isAddMode = ($this->view->wsRow['ACTION_TYPE'] == 'add' ? true : false);
        $this->view->isFlow = ($this->view->wsRow['IS_FLOW'] == '1' ? true : false);
        
        $workSpaceHtml = $this->view->renderPrint('renderWorkSpace', self::$viewPath);
        
        if ($windowType == 'standart') { 
            
            $response = array(
                'html' => $workSpaceHtml, 
                'title' => $this->lang->line($this->view->wsRow['META_DATA_NAME']), 
                'close_btn' => $this->lang->line('close_btn'), 
                'mode' => 'dialog',
                'theme' => $themeCode,
                'dialogClass' => $this->view->wsRow['WINDOW_TYPE'], 
                'dialogSize' => $this->view->wsRow['WINDOW_SIZE'], 
                'dialogWidth' => (($this->view->wsRow['WINDOW_SIZE'] == 'custom' && $this->view->wsRow['WINDOW_WIDTH']) ? $this->view->wsRow['WINDOW_WIDTH'] : Mdwebservice::$defaultWindowWidth), 
                'dialogHeight' => (($this->view->wsRow['WINDOW_SIZE'] == 'custom' && $this->view->wsRow['WINDOW_HEIGHT']) ? $this->view->wsRow['WINDOW_HEIGHT'] : Mdwebservice::$defaultWindowHeight)
            );
            
        } else {

            $html = $workSpaceHtml.'<div class="clearfix w-100"></div>';
            
            $response = array(
                'html' => $html, 
                'title' => $this->lang->line($this->view->wsRow['META_DATA_NAME']), 
                'mode' => $windowType, 
                'theme' => $themeCode
            );
        }
        
        return $response;
    }
    
    public function renderWorkSpace($workSpaceId = '') {     
        $response = self::workSpaceContent($workSpaceId);
        convJson($response);
    }
    
    public function workSpaceThemePositionList() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->groupMetaDataId = Input::post('groupMetaDataId');
        $this->view->initThemePositionList = $this->model->initThemePositionListModel($this->view->metaDataId, $this->view->groupMetaDataId);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/workspace/themePositionList', self::$viewMetaPath),
            'Title' => 'Байрлал тохируулах',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function checkWindowType() {
        $result = $this->model->getWorkSpaceByMetaIdModel(Input::numeric('metaDataId'));
        
        echo json_encode(
            array(
                'WINDOW_TYPE'=>$result['WINDOW_TYPE'], 
                'THEME_CODE' => $result['THEME_CODE'],
                'GROUP_META_DATA_ID' => $result['GROUP_META_DATA_ID']
            )
        ); exit;
    }
    
    public function addThemePositionFrom() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->targetMetaId = Input::post('groupMetaDataId');
        $this->view->getDVParameterList = $this->model->getDVParameterListModel($this->view->targetMetaId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/workspace/addThemePositionForm', self::$viewMetaPath),
            'title' => 'Байрлал нэмэх',
            'add_btn' => $this->lang->line('add_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function editThemePositionFrom() {
        
        $this->view->id = Input::post('id');
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->targetMetaId = Input::post('groupMetaDataId');
        $this->view->getDVParameterList = $this->model->getDVParameterListModel($this->view->targetMetaId);
        $this->view->row = $this->model->getWorkSpacePositionByIdModel($this->view->id);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/workspace/editThemePositionForm', self::$viewMetaPath),
            'title' => 'Байрлал нэмэх',
            'add_btn' => $this->lang->line('add_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function insertThemePosition() {
        echo json_encode($this->model->insertThemePositionModel()); exit;
    }
    
    public function updateThemePosition() {
        echo json_encode($this->model->updateThemePositionModel()); exit;
    }
    
    public function deleteThemePosition() {
        echo json_encode($this->model->deleteThemePositionModel()); exit;
    }
    
    public function initThemePositionList() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->groupMetaDataId = Input::post('groupMetaDataId');
        
        echo json_encode($this->model->initThemePositionListModel($this->view->metaDataId, $this->view->groupMetaDataId)); exit;
    }
    
    public function workSpaceProcessList() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->groupMetaDataId = Input::numeric('groupMetaDataId');
        $this->view->initWorkSpaceProcessList = $this->model->initWorkSpaceProcessListModel($this->view->metaDataId);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/workspace/workSpaceProcessList', self::$viewMetaPath),
            'Title' => 'Процесс тохируулах',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function addWorkSpaceProcessFrom() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->groupMetaDataId = Input::post('groupMetaDataId');
        $this->view->getDVParameterList = $this->model->getDVParameterListModel($this->view->groupMetaDataId);
        
        $this->view->rowId = null;
        $this->view->row = array();
        $title = 'Холбоос нэмэх';
        
        if (Input::postCheck('rowId')) {
            $this->view->rowId = Input::numeric('rowId');
            $this->view->row = $this->model->getWorkSpaceProcessMapModel($this->view->rowId);
            $title = 'Холбоос засах';
        }
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/workspace/addWorkSpaceProcessFrom', self::$viewMetaPath),
            'title' => $title,
            'add_btn' => $this->lang->line('add_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function insertWorkSpaceProcess() {
        $response = $this->model->insertWorkSpaceProcessModel();
        echo json_encode($response); exit;
    }
    
    public function updateWorkSpaceProcessMap() {
        $response = $this->model->updateWorkSpaceProcessMapModel();
        echo json_encode($response); exit;
    }
    
    public function initworkSpaceProcessList() {
        $metaDataId = Input::numeric('metaDataId');
        echo json_encode($this->model->initWorkSpaceProcessListModel($metaDataId)); exit;
    }
    
    public function defaultProcess() {
        echo json_encode($this->model->defaultProcessModel(Input::post('actionMenuId'))); exit;
    }
    
    public function refreshHeaderParams() {
        
        $workSpaceId = Input::numeric('workSpaceId');
        $rowId = Input::post('rowId');
        
        $wsRow = $this->model->getWorkSpaceByMetaIdModel($workSpaceId);
        $dmMetaDataId = $wsRow['GROUP_META_DATA_ID'];
        
        $selectedRow = $this->model->getDataViewRowByRowIdModel($dmMetaDataId, $rowId);
        $response = array(
            'headerParam1' => $this->model->getWorkSpaceHeaderPosition($workSpaceId, $dmMetaDataId, 'header-position-1', $selectedRow), 
            'headerParam2' => $this->model->getWorkSpaceHeaderPosition($workSpaceId, $dmMetaDataId, 'header-position-2', $selectedRow), 
            'headerParam3' => $this->model->getWorkSpaceHeaderPosition($workSpaceId, $dmMetaDataId, 'header-position-3', $selectedRow), 
            'headerParam4' => $this->model->getWorkSpaceHeaderPosition($workSpaceId, $dmMetaDataId, 'header-position-4', $selectedRow),
            'hiddenParams' => $this->model->renderHiddenParams($selectedRow)
        );
        
        echo json_encode($response); exit;
    }
    
    public function paramsToUrl() {
        
        $workSpaceId = Input::numeric('workSpaceId');
        $metaDataId = Input::numeric('metaDataId');
        
        $response = $this->model->paramsToUrlModel($workSpaceId, $metaDataId);
        
        echo json_encode($response); exit;
    }
    
    public function shop() {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        $this->view->css = array(
            'custom/addon/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.css',
            'global/theme/shop/css/main.css'
        );
        $this->view->js = array(
            'custom/addon/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.min.js',
        );
        
        $this->view->render('header');
        $this->view->render('themes/shop/shop', self::$viewPath);
        $this->view->render('footer');
    }
    
    public function getDVRowDataByDefaultCriteria($groupMetaDataId, $all = false) {
        
        $_POST['metaDataId'] = $groupMetaDataId;
        $_POST['defaultCriteriaData'] = 'inputMetaDataId=' . $groupMetaDataId .'&';
        
        $dataViewHeaderData = $this->model->dataViewHeaderDataModel($groupMetaDataId); 
        
        foreach ($dataViewHeaderData as $key => $row) {
            if (strtolower($row['META_DATA_CODE']) == 'sessionemployeeid') {
                $_POST['defaultCriteriaData'] .= 'criteriaCondition[sessionEmployeeId]&param[sessionEmployeeId]=' . Ue::sessionEmployeeId();
                break;
            }
        }
        
        if ($all == false) {
            
            $_POST['page'] = 1;
            $_POST['rows'] = 2;
            
            $this->result = $this->model->dataViewDataGridModel(true);
            
        } else {
            
            $_POST['page'] = 1;
            $_POST['rows'] = 100;
            
            $this->result = $this->model->dataViewDataGridModel(true);
        }   
        
        if ($this->result && count($this->result['rows']) > 0) {
            if ($all)
                return $this->result['rows'];
            else
                return $this->result['rows'][0];
        }
        
        return false;
    }
    
    public function renderCoverChangeModal() {
        $this->view->uniqId = Input::numeric('workSpaceId');

        $response = array(
            'Title' => $this->lang->line('Workspace cover зураг солих'),
            'close_btn' => $this->lang->line('close_btn'),
            'crop_btn' => $this->lang->line('save_btn'),
            'html' => $this->view->renderPrint('renderCoverChange', self::$viewPath)
        );

        echo json_encode($response); exit;
    }

    public function saveCover() {
        $result = $this->model->saveCoverModel();

        if ($result) {
            echo json_encode($result);
        } else {
            $response = array('message' => $this->lang->line('msg_error'), 'status' => 'error');
            echo json_encode($response);
        }
        exit;
    }
    
    public function submenuRender() {
        $result = $this->model->submenuRenderModel();
        echo json_encode($result); exit;
    }
    
    public function workSpaceWidgetList() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->groupMetaDataId = Input::post('groupMetaDataId');
        $this->view->widgetRow = $this->model->initWorkSpaceWidgetListModel($this->view->metaDataId);
        
        $response = array(
            'Html' => $this->view->renderPrint('system/link/workspace/workSpaceWidgetList', self::$viewMetaPath),
            'Title' => 'Widget жагсаалт',
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function initWorkSpaceWidgetList() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $response = array(
            'Html' => $this->model->initWorkSpaceWidgetHtmlModel($this->view->metaDataId),
        );
        echo json_encode($response); exit;
    }
    
    public function addWorkSpaceWidgetFrom() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/workspace/addWorkSpaceWidgetFrom', self::$viewMetaPath),
            'title' => 'Widget тохируулах',
            'add_btn' => $this->lang->line('add_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function editWorkSpaceWidgetFrom() {
        
        $this->view->widgetId = Input::post('widgetId');
        $this->view->data = $this->model->getWidgetDataModel($this->view->widgetId);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/workspace/editWorkSpaceWidgetFrom', self::$viewMetaPath),
            'title' => 'Widget тохируулах',
            'add_btn' => $this->lang->line('add_btn'),
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function updateWorkSpaceWidget() {
        $result = $this->model->updateWorkSpaceWidgetModel();
        echo json_encode($result); exit;
    }
    
    public function insertWorkSpaceWidget() {
        $result = $this->model->insertWorkSpaceWidgetModel();
        echo json_encode($result); exit;
    }
    
    public function deleteWorkSpaceWidget() {
        $result = $this->model->deleteWorkSpaceWidgetModel();
        echo json_encode($result); exit;
    }
    
    public function workSpaceReload() {
        
        $rowId        = Input::post('rowId');
        $dmMetaDataId = Input::post('dmMetaDataId');
        
        $selectedRow  = $this->model->getDataViewRowByRowIdModel($dmMetaDataId, $rowId);
        
        $json = json_encode($selectedRow);
        $json = strip_tags($json);
        
        $_POST['selectedRow'] = json_decode($json, true);
        
        echo json_encode(self::workSpaceContent()); exit;
    }
    
    public function backWorkinProcess() {
        $postData = Input::postData();
        $selectedRow = Arr::decode($postData['selectedRow']);
        unset($_POST);
        
        $_POST['responseType'] = 'outputArray';
        $_POST['methodId'] = $postData['processId'];
        /* $_POST['nult'] = true; */
        /* $_POST['processSubType'] = 'internal'; */
        /* $_POST['create'] = '1'; */
        $_POST['isSystemProcess'] = 'true';

        $_POST['param']['metaMenuId'] = $postData['menuId'];
        $_POST['param']['orderNum'] = $postData['order'];
        
        $result = (new Mdwebservice())->runProcess();
        echo json_encode($result);
    }
}