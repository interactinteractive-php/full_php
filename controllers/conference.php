<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Conference extends Controller {

    private $viewName = "government";
    public static $heleltsegTypeId = array('typeid' => array(array('operator' => '=', 'operand' => "1561974650875")));
    public static $taniltsahTypeId = array('typeid' => array(array('operator' => '=', 'operand' => "1561974650898")));
    private static $viewPath = "projects/views";
    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {
        
        set_status_header(404);
        
        $err = Controller::loadController('Err');
        $err->index();
        exit;
    }

    public function detail($id = '1') {
        if ($id !== '1') {
            self::conferencedetail_v1();
        } else {
            self::conferencedetail();
        }
    }
 
    public function conferencedetail($id = null) {

        $this->view->title = 'Хурлын Дэлгэрэнгүй';
        $this->view->uniqId = getUID();
        
        $selectedRow = Input::post('selectedRow');
        $this->view->id = ($id) ? $id : $selectedRow['id'];
        
        $dataRow = $this->model->fncRunDataview("1559809605021332", "id", $this->view->id, "=");
        $this->view->selectedRow = (isset($dataRow[0]) && $dataRow[0]) ? $dataRow[0] : $selectedRow;
        
        $this->view->metaDataId = '1559809605021332';
        $this->view->metaDataCode = 'CMS_MEETING_LIST';
        $this->view->refStructureId = '1553696503515026';
        
        $this->view->kheleltsekhTypeId =  self::$heleltsegTypeId['typeid'][0]['operand'];
        $this->view->taniltsakhTypeId =  self::$taniltsahTypeId['typeid'][0]['operand'];
        
        $this->view->memberRole = $this->model->memberRoleModel(array('id' => Ue::sessionUserKeyId(), 'meetingbookid' => $this->view->selectedRow['id']));
        $this->view->conferenceDataArr = $dataRow; // $this->model->getConferenceDataModel($this->view->selectedRow['id']);
        
        $this->view->conferenceData = isset($this->view->conferenceDataArr[0]) ? $this->view->conferenceDataArr[0] : array();

        $this->view->rowJson = json_encode($this->view->selectedRow);
        $this->view->id = $this->view->selectedRow['id'];

        if ($this->view->conferenceData['action']) {
            
            $this->view->selectedRow['startime'] = $this->view->conferenceData['starttime'];
            $this->view->selectedRow['endtime'] = $this->view->conferenceData['endtime'];
            
            $durationTimeInt = $this->view->conferenceData['diffsysdate'];
            $breakTimeInt = $this->view->conferenceData['diffbreaksysdate'];
            
            if ($this->view->conferenceData['action'] == '2') {
                $this->view->selectedRow['totalbreaktime'] = floor($breakTimeInt/3600) .':' . (floor(($breakTimeInt - floor($breakTimeInt/3600)*3600)/60)) . ':' . ($breakTimeInt - floor($breakTimeInt/3600)*3600)%60;
            } else {
                $this->view->selectedRow['totalbreaktime'] = $this->view->conferenceData['totalbreaktime'];
            }
            
            $this->view->selectedRow['duration'] = floor($durationTimeInt/3600) .':' . (floor(($durationTimeInt - floor($durationTimeInt/3600)*3600)/60)) . ':' . ($durationTimeInt - floor($durationTimeInt/3600)*3600)%60;
        }
        
        if ($this->view->conferenceData['action'] === '3') {
            $this->view->selectedRow['duration'] = $this->view->conferenceData['duration'];
            $this->view->selectedRow['totalbreaktime'] = $this->view->conferenceData['totalbreaktime'];
        }
       
        $this->view->member = $this->model->fncRunDataview("1562563260729012", "bookid", $this->view->selectedRow['id'], "=");
        $this->view->othermember = $this->model->fncRunDataview("1562316364962", "bookid", $this->view->selectedRow['id'], "=");
        $this->view->allmember = $this->model->fncRunDataview("1565833906233063", "bookid", $this->view->selectedRow['id'], "=");
        $this->view->issuelist = $this->model->fncRunDataview("1564710579741", "meetingbookid", $this->view->selectedRow['id'], "=", self::$heleltsegTypeId);
        $this->view->reviewgov = $this->model->fncRunDataview("1564710579741", "meetingbookid", $this->view->selectedRow['id'], "=", self::$taniltsahTypeId);
        $this->view->issuelist = Arr::sortBy('ordernum', $this->view->issuelist, 'asc');
        $this->view->reviewgov = Arr::sortBy('ordernum', $this->view->reviewgov, 'asc');
        $this->view->protocolList = $this->view->employeeList = array();
        
        if (issetParam($this->view->memberRole['readonly']) !== '1') {
            $employeeList = $this->model->getConferencingEmployeeModel();
            
            if (isset($employeeList['empList'])) {
                $this->view->employeeList = $employeeList['empList'];
            }

            if (isset($this->view->issuelist[0]['id']) && $this->view->issuelist[0]['id']) {
                $this->view->protocolList = $this->model->fncRunDataview("1565780715693831", "subjectid", $this->view->issuelist[0]['id'], "=");
            }
        }
        
        $this->view->defaultCss = $this->view->renderPrint('defaultCss', 'views/government/');
        $this->view->defaultJs = $this->view->renderPrint('defaultJs', 'views/government/');
        
        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('header');
        } 

        $this->view->render($this->viewName . '/conference');
    }

    public function conferencedetail_v1($id = null) {

        $this->view->title = 'Хурлын Дэлгэрэнгүй';
        $this->view->uniqId = getUID();
        
        $selectedRow = Input::post('selectedRow');
        $this->view->id = ($id) ? $id : $selectedRow['id'];
        $this->view->sessionUserKeyId = Ue::sessionUserKeyId();
        $dataRow = $this->model->fncRunDataview("16879201172319", "id", $this->view->id, "=");
        $this->view->selectedRow = (isset($dataRow[0]) && $dataRow[0]) ? $dataRow[0] : $selectedRow;
        $this->view->selectedRowid = $this->view->selectedRow['id'];
        $this->view->metaDataId = '16879201172319';
        $this->view->metaDataCode = 'CMS_MEETING_LIST';
        $this->view->refStructureId = '1553696503515026';
        
        $this->view->kheleltsekhTypeId =  self::$heleltsegTypeId['typeid'][0]['operand'];
        $this->view->taniltsakhTypeId =  self::$taniltsahTypeId['typeid'][0]['operand'];
        
        $this->view->memberRole = $this->model->memberRoleModel(array('id' => Ue::sessionUserKeyId(), 'meetingbookid' => $this->view->selectedRow['id']));
        $this->view->conferenceDataArr = $dataRow; // $this->model->getConferenceDataModel($this->view->selectedRow['id']);
        
        $this->view->conferenceData = isset($this->view->conferenceDataArr[0]) ? $this->view->conferenceDataArr[0] : array();

        $this->view->rowJson = json_encode($this->view->selectedRow);
        $this->view->id = $this->view->selectedRow['id'];

        if ($this->view->conferenceData['action']) {
            
            $this->view->selectedRow['startime'] = $this->view->conferenceData['starttime'];
            $this->view->selectedRow['endtime'] = $this->view->conferenceData['endtime'];
            
            $durationTimeInt = $this->view->conferenceData['diffsysdate'];
            $breakTimeInt = $this->view->conferenceData['diffbreaksysdate'];
            
            if ($this->view->conferenceData['action'] == '2') {
                $this->view->selectedRow['totalbreaktime'] = floor($breakTimeInt/3600) .':' . (floor(($breakTimeInt - floor($breakTimeInt/3600)*3600)/60)) . ':' . ($breakTimeInt - floor($breakTimeInt/3600)*3600)%60;
            } else {
                $this->view->selectedRow['totalbreaktime'] = $this->view->conferenceData['totalbreaktime'];
            }
            
            $this->view->selectedRow['duration'] = floor($durationTimeInt/3600) .':' . (floor(($durationTimeInt - floor($durationTimeInt/3600)*3600)/60)) . ':' . ($durationTimeInt - floor($durationTimeInt/3600)*3600)%60;
        }
        
        if ($this->view->conferenceData['action'] === '3') {
            $this->view->selectedRow['duration'] = $this->view->conferenceData['duration'];
            $this->view->selectedRow['totalbreaktime'] = $this->view->conferenceData['totalbreaktime'];
        }

        $paramMeeting = array(
            'id' => $this->view->id,
            'privacyType' => '',
        );
      
        $dataMeeting = Functions::runProcess('CMS_MEETING_FIRST_SAVE_GET_LIST_004', $paramMeeting);
        $privacytype = $dataMeeting['result']['privacytype'];
        $privacytypeId = array('participantroleid' => array(array('operator' => '=', 'operand' => $privacytype)));
        $this->view->member = $this->model->fncRunDataview("1562563260729012", "bookid", $this->view->selectedRow['id'], "=", $privacytypeId);
       
        $this->view->othermember = $this->model->fncRunDataview("1562316364962", "bookid", $this->view->selectedRow['id'], "=");
        $this->view->allmember = $this->model->fncRunDataview("1565833906233063", "bookid", $this->view->selectedRow['id'], "=");
        $this->view->issuelistdata = $this->model->fncRunDataview("1564710579741", "meetingbookid", $this->view->selectedRow['id'], "=", self::$heleltsegTypeId);
        $this->view->reviewgovdata= $this->model->fncRunDataview("1564710579741", "meetingbookid", $this->view->selectedRow['id'], "=", self::$taniltsahTypeId);
        $this->view->issuelist = Arr::sortBy('ordernum', $this->view->issuelistdata, 'asc');
        $this->view->reviewgov = Arr::sortBy('ordernum', $this->view->reviewgovdata, 'asc');
        $this->view->protocolList = $this->view->employeeList = array();
       
        if (issetParam($this->view->memberRole['readonly']) !== '1') {
            $employeeList = $this->model->getConferencingEmployeeModel();
            
            if (isset($employeeList['empList'])) {
                $this->view->employeeList = $employeeList['empList'];
            }

            if (isset($this->view->issuelist[0]['id']) && $this->view->issuelist[0]['id']) {
                $this->view->protocolList = $this->model->fncRunDataview("1565780715693831", "subjectid", $this->view->issuelist[0]['id'], "=");
            }
        }
       
        $this->view->defaultCss = $this->view->renderPrint('defaultCssDistrict', 'views/government/bzd/');
        $this->view->defaultJs = $this->view->renderPrint('defaultJsDistrict', 'views/government/bzd/');
        
        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('header');
        } 

        $this->view->render($this->viewName . '/bzd/conferenceDistrict');
    }

    public function memberpercent() {

        $memberid = Input::post('id');
        $memberbookid = Input::post('bookid');
        $keyid = Input::post('keyid');
        $statusid = Input::post('statusid');

        $data = $this->model->memberPercentIdModel($memberid, $memberbookid, $keyid, $statusid);
        echo json_encode($data);
    }

    public function interrupt() {
        $postData = Input::postData();
        $data = $this->model->interruptModel($postData, $postData['count']);
        echo json_encode($data);
    }

    public function memberpercent1() {

        $memberid = Input::post('id');
        $memberbookid = Input::post('bookid');
        $keyid = Input::post('keyid');

        $data = $this->model->memberPercentIdModelV1($memberid, $memberbookid, $keyid);
        echo json_encode($data);
    }

    public function memberpercent22() {

        $memberid = Input::post('id');
        $memberbookid = Input::post('bookid');
        $keyid = Input::post('keyid');
        $statusid = Input::post('statusid');

        $data = $this->model->memberPercentIdModel22($memberid, $memberbookid, $keyid, $statusid);
        echo json_encode($data);
    }

    public function memberpercent11() {

        $memberid = Input::post('id');
        $memberbookid = Input::post('bookid');
        $keyid = Input::post('keyid');

        $data = $this->model->memberPercentIdModelV11($memberid, $memberbookid, $keyid);
        echo json_encode($data);
    }

    public function memberpercent2() {

        $memberid = Input::post('id');
        $memberbookid = Input::post('bookid');
        $keyid = Input::post('keyid');
        $descrition = Input::post('descrition');

        $data = $this->model->memberPercentIdModelV2($memberid, $memberbookid, $keyid, $descrition);
        echo json_encode($data);
    }

    public function memberpercent3() {

        $memberid = Input::post('id');
        $memberbookid = Input::post('bookid');
        $keyid = Input::post('keyid');
        $descrition = Input::post('descrition');

        $data = $this->model->memberPercentIdModelV3($memberid, $memberbookid, $keyid, $descrition);
        echo json_encode($data);
    }

    public function memberpercent4() {

        $memberid = Input::post('id');
        $memberbookid = Input::post('bookid');
        $keyid = Input::post('keyid');
        $descrition = Input::post('descrition');

        $data = $this->model->memberPercentIdModelV4($memberid, $memberbookid, $keyid, $descrition);
        echo json_encode($data);
    }

    public function meetingstart() {
        $postData = Input::postData();
        $data = $this->model->meetingstartModel($postData);
        echo json_encode($data);
    }

    public function findMetaData() {
        $response = $this->model->findMetaData();
        echo json_encode($response);
    }

    private function convertAlltoArray($cellArray) {
        $allArray = array();
        foreach ($cellArray AS $rowIndex => $row) {
            $row = (array) $row;

            foreach ($row AS $colIndex => $col) {
                $tmpColArray[$colIndex] = (array) $col;
                $allArray[$rowIndex] = $tmpColArray;
            }
        }
        return $allArray;
    }

    public function endConference() {
        $postData = Input::postData();
        $data = $this->model->endMeetingModel($postData);
        echo json_encode($data);
    }

    public function wfstatus() {
        $userid = Input::post('userId');
        $desc = Input::post('desc');
        $bookId = Input::post('bookId');
        $status = Input::post('status');
        $employkeyId = Input::post('employkeyId');

        $data = $this->model->wfstatusModel($userid, $desc, $bookId, $status, $employkeyId);
        echo json_encode($data);
    }

    public function getConferenceMember() {
        (Array) $response = array();
        $data = $this->model->fncRunDataview("1561532239568490", "subjectid", Input::post('subjectId'), "=");
        if ($data) {
            
            $data = Arr::sortBy('ordernum', $data, 'asc');
            
            foreach ($data as $key => $row) {
                $row['jsonrow'] = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                array_push($response, $row);
            }
        }
        echo json_encode($response);
    }

    public function eaObject() {
        $this->load->model('mdasset', 'middleware/models/');

        $this->view->uniqId = getUID();

        if (!is_ajax_request()) {
            $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->render('header');
        }

        $this->view->metaDataId = '1565751393112';
        $this->view->metaDataId1 = '1565751393181';
        $this->view->leftSideBarMenu = $this->model->getLeftSidebarModel('1565751393112');

        $this->view->rightSideBarMenuChild = $this->model->getIntranedSidebarChildModel();
        $this->view->getIntranetAllContent = $this->model->getIntranetAllContentModel();

        $mdObjectCtrl = Controller::loadController('Mdobject', 'middleware/controllers/');
        $this->view->dataViewHeaderRealData = $mdObjectCtrl->dataViewHeaderDataCtl($this->view->metaDataId);

        $this->view->dataViewHeaderData = $mdObjectCtrl->findCriteria('1565751393181', $this->view->dataViewHeaderRealData);
        $this->view->render('ea/eaobject', self::viewPath);
    }

    public function eaRepository() {
        
        $this->view->uniqId = getUID();
        $this->load->model('mdasset', 'middleware/models/');

        if (!is_ajax_request()) {
            $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->render('header');
        }

        $this->view->metaDataId = '1559891180690';
        $this->view->metaDataId1 = '1559891180690';
        $this->view->leftSideBarMenu = $this->model->getLeftSidebarModel('1559891180690');

        $this->view->rightSideBarMenuChild = $this->model->getIntranedSidebarChildModel();
        $this->view->getIntranetAllContent = $this->model->getIntranetAllContentModel();

        $mdObjectCtrl = Controller::loadController('Mdobject', 'middleware/controllers/');
        $this->view->dataViewHeaderRealData = $mdObjectCtrl->dataViewHeaderDataCtl($this->view->metaDataId);
        $this->view->dataViewHeaderData = $mdObjectCtrl->findCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);

        $this->view->render('ea/repository', self::viewPath);
    }

    public function getSubMenuRender() {
        $postData = Input::postData();
        $metadata = Input::post('metadata');
        (String) $Html = "";
        $menuData = $this->model->getLeftSidebarModel($metadata, $postData['id']);

        if ($menuData) {
            foreach ($menuData as $key => $row) {
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                $Html .= '<li class="nav-item">
                            <a href="javascript:;" 
                            data-row="' . $rowJson . '"
                            li-status="closed"
                            onclick="getSubMenuEa_' . $postData['uniqId'] . '(this, ' . $row['id'] . ', ' . ($postData['subLevel'] + 1) . ', ' . (isset($row['metadataid']) ? $row['metadataid'] : '') . ')" class="nav-link pl-' . $postData['subLevel'] . '">' . $row['name'] . '</a>
                            <ul class="nav nav-group-sub add-submenu-' . $row['id'] . '" data-submenu-title="Layouts"></ul>
                        </li>';
            }

            $menu = '1';
        } else {
            $menu = '0';
        }

        echo json_encode(array('id' => $postData['id'], 'Html' => $Html, 'menu' => $menu, 'menuData' => $menuData));
    }

    public function renderContentEa() {

        $postData = Input::postData();

        $index = 1;
        (String) $Html = "";

        if (!isset($postData['metadataid']) || !$postData['metadataid']) {
            echo json_encode(array('postData' => $postData, 'Html' => '', 'menuData' => array()));
            die;
        }

        (Array) $filterParam = array();

        if (Input::postCheck('filterParam') && !Input::isEmpty('filterParam')) {
            parse_str(Input::post('filterParam'), $filterParam);
        }

        $menuData = $this->model->getSidebarContentModel($postData['metadataid'], $filterParam);

        $param = array('templateId' => $postData['menuId']);
        $pathList = $this->model->getProcessCodeResult('1565262536462', $param);

        if ($menuData) {
            foreach ($menuData as $key => $row) {

                $rowJson = Arr::encode(array('workSpaceParam' => $row, 'isFlow' => ''));

                $Html .= '<li>
                            <a href="javascript:void(0);" onclick="getEaContentRender_' . $postData['uniqId'] . '(this, \'' . $row['name'] . '\')" data-row="' . $rowJson . '" class="media d-flex align-items-center">
                                <div class="mr-2" style="margin-top: -3px;">
                                    <h1 class="rownumber">' . ( ($index < 10) ? '0' . $index : $index ) . '.</h1>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">
                                        ' . $row['name'] . '
                                    </div>';
                if ($pathList) {
                    foreach ($pathList as $path) {
                        $Html .= '<span class="text-muted font-weight-bold font-size-sm w-100 float-left" style="font-size: .65rem">';
                        $Html .= '<i class="' . ($path['icon'] ? $path['icon'] : '') . ' mr-1" style="font-size:13px;top:-1px;"></i> ';
                        if (isset($row[Str::lower($path['code'])])) {
                            $Html .= ($row[Str::lower($path['code'])] ? $row[Str::lower($path['code'])] : '');
                        }
                        $Html .= '</span>';
                    }
                } else {
                    $Html .= '<span class="text-muted font-weight-bold font-size-sm w-100 float-left" style="font-size: .65rem; height: 10px !important"></span>';
                    $Html .= '<span class="text-muted font-weight-bold font-size-sm w-100 float-left" style="font-size: .65rem; height: 10px !important"></span>';
                }

                $Html .= '</div>
                        </a>
                    </li>';
                $index++;
            }
        }

        echo json_encode(array('postData' => $postData, 'Html' => $Html, 'menuData' => $menuData));
    }

    public function eaLayout() {
        $this->load->model('mdasset', 'middleware/models/');

        $this->view->uniqId = getUID();

        if (!is_ajax_request()) {
            $this->view->css = array_unique(array_merge(array('custom/css/vr-card-menu.css'), AssetNew::metaCss()));
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->render('header');
        }

        $this->view->metaDataId = '1565262544864';
        $this->view->leftSideBarMenu = $this->model->getLeftSidebarModel('1565262544864');
        $this->view->rightSideBarMenuChild = $this->model->getIntranedSidebarChildModel();
        $this->view->getIntranetAllContent = $this->model->getIntranetAllContentModel();

        $mdObjectCtrl = Controller::loadController('Mdobject', 'middleware/controllers/');
        $this->view->dataViewHeaderRealData = $mdObjectCtrl->dataViewHeaderDataCtl($this->view->metaDataId);
        $this->view->dataViewHeaderData = $mdObjectCtrl->findCriteria($this->view->metaDataId, $this->view->dataViewHeaderRealData);

        $this->view->render('layout/repository', self::viewPath);
    }

    public function getLayoutSubMenuRender() {

        $postData = Input::postData();
        (String) $Html = "";
        $menuData = $this->model->getLeftSidebarModel('1565262544864', $postData['id']);

        if ($menuData) {
            foreach ($menuData as $key => $row) {
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                $Html .= '<li class="nav-item">
                            <a href="javascript:;" 
                            data-row="' . $rowJson . '"
                            li-status="closed"
                            onclick="getSubMenuEa_' . $postData['uniqId'] . '(this, ' . $row['id'] . ', ' . ($postData['subLevel'] + 1) . ', ' . (isset($row['metadataid']) ? $row['metadataid'] : '') . ')" class="nav-link pl-' . $postData['subLevel'] . '">' . $row['name'] . '</a>
                            <ul class="nav nav-group-sub add-submenu-' . $row['id'] . '" data-submenu-title="Layouts"></ul>
                        </li>';
            }

            $menu = '1';
        } else {
            $menu = '0';
        }

        echo json_encode(array('id' => $postData['id'], 'Html' => $Html, 'menu' => $menu, 'menuData' => $menuData));
    }

    public function getIntranetSubMenuRender() {

        $postData = Input::postData();
        (String) $Html = "";
        $menuData = $this->model->getIntranedSidebarModel($postData['id']);

        if ($menuData) {
            foreach ($menuData as $key => $row) {
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                $Html .= '<li class="nav-item">
                            <a href="javascript:;" 
                            data-row="' . $rowJson . '"
                            li-status="closed"
                            onclick="getMenuId(null,null, ' . $row['id'] . ')" class="nav-link pl-' . $postData['subLevel'] . '">' . $row['name'] . '</a>
                            <ul class="nav nav-group-sub add-submenu-' . $row['id'] . '" data-submenu-title="Layouts"></ul>
                        </li>';
            }

            $menu = '1';
        } else {
            $menu = '0';
        }

        echo json_encode(array('id' => $postData['id'], 'Html' => $Html, 'menu' => $menu, 'menuData' => $menuData));
    }

    public function saveConferencingProtocol() {

        $response = $this->model->saveConferencingProtocolModel();
        echo json_encode($response);
        exit;
    }

    public function getConferencingProtocolHistory() {

        $response = $this->model->fncRunDataview("1565780715693831", "subjectid", Input::post('subjectId'), "=");
        echo json_encode($response);
        exit;
    }

    public function getConferenceStartTime() {
        $resultArr = '';
        $currentDateTime = Date::currentDate();
        $dataRow = Input::post('dataRow');
        
        $data = $this->db->GetRow("SELECT ACTION FROM MMS_MEETING_BOOK WHERE ID = '". $dataRow['meetingbookid'] ."'");
        
        if ($data['ACTION'] === '0') {
            jsonResponse(array('status' => 'warning', 'text' => Lang::line('WARNING_CONFERENCE_START_001')));
            die;
        }
                
        $param = array(
            'id' => Input::post('id'),
            'wfmStatusId' => '1560736578898831',
            'CMS_META_WFM_LOG_DV' => array(
                'id' => '',
                'wfmStatusId' => '1560736578898831',
                'createdDate' => $currentDateTime,
                'createdUserId' => Ue::sessionUserKeyId()
            )
        );

        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $param['id']
                )
            )
        );

        $paramGroup = array(
            'systemMetaGroupId' => '1564710579741',
            'showQuery' => '0',
            'ignorePermission' => 1,
        );
        $paramGroup['criteria'] = $criteria;

        $dataGroup = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'PL_MDVIEW_004', $paramGroup);

        if (isset($dataGroup['result']) && $dataGroup['result']) {
            $dataGroup = isset($dataGroup['result'][0]) ? $dataGroup['result'][0] : array();
            
            if (empty($dataGroup['endtime']) && Input::isEmpty('noProcess')) {

                $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'CMS_SUBJECT_START_TIME_DV_002', $param);

                if ($data['status'] === 'success' && isset($data['result'])) {
                    $cmsSubjectTimeDv = array();
                    $temp = array (
                                    'id' => getUID(),
                                    'rowState' => 'added',
                                    'subjectId' => $data['result']['id'],
                                    'startTime' => $currentDateTime,
                                    'endTime' => '',
                                    'orderNum' => '1',
                                );
                    array_push($cmsSubjectTimeDv, $temp);

                    $param = array (
                                'id' => $data['result']['id'],
                                'maxOrder' => '1',
                                'CMS_SUBJECT_TIME_DV' => $cmsSubjectTimeDv,
                            );

                    $result = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_SUBJECT_LOG_DV_002', $param);
                    
                    if ($result) {
                        $this->db->AutoExecute("MMS_MEETING_SUBJECT", array('START_TIME' => $currentDateTime), "UPDATE", "ID = '". $data['result']['id'] ."'");
                    }
                    
                    $resultArr = $data['result'];
                    $resultArr['starttime'] = Date::formatter($resultArr['starttime'], 'H:i');
                    $resultArr['endtime'] = '00:00';
                }
            } else {
                $dataGroup['starttime'] = Date::formatter($dataGroup['starttime'], 'H:i');
                $dataGroup['endtime'] = (isset($dataGroup['endtime']) && $dataGroup['endtime']) ? Date::formatter($dataGroup['endtime'], 'H:i') : '';
                $resultArr =  $dataGroup;
            }
        }
        
        jsonResponse($resultArr);
    }

    public function getConferenceEndTime() {
        (Array) $dataResult = array();
        
        $dataRow = Input::post('dataRow');
        $data = $this->db->GetRow("SELECT ACTION FROM MMS_MEETING_BOOK WHERE ID = '". $dataRow['meetingbookid'] ."'");
        
        if ($data['ACTION'] === '0') {
            jsonResponse(array('status' => 'warning', 'text' => Lang::line('WARNING_CONFERENCE_FINISH_001')));
            die;
        }
        
        $currentDateTime = Date::currentDate();
        $param = array(
            'id' => Input::post('id'),
            'wfmStatusId' => '1560736578614233',
            'CMS_META_WFM_LOG_DV' => array(
                'id' => '',
                'wfmStatusId' => '1560736578614233',
                'createdDate' => $currentDateTime,
                'createdUserId' => Ue::sessionUserKeyId()
            )
        );
        
        $result = $this->model->fncRunDataview("1564710579741", "id", $param['id'], "=");
        
        if (isset($result[0]) && $result[0]) {
            $resultArr = $result[0];

            if (!empty($resultArr['starttime'])) {

                $data = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'CMS_SUBJECT_END_TIME_DV_002', $param);
                
                if ($data['status'] === 'success' && isset($data['result'])) {
                    $sid = $this->db->GetOne("SELECT ID FROM mms_subject_time WHERE SUBJECT_ID = '". $data['result']['id'] ."' AND ORDER_NUM = 1 ORDER BY ID ASC");
                    
                    $result = $this->db->AutoExecute("mms_subject_time", array('END_TIME' => $currentDateTime), 'UPDATE', "ID = '$sid'");
                    
                    if ($result) {
                        
                        $startTime = $this->db->GetOne("SELECT START_TIME FROM MMS_MEETING_SUBJECT WHERE ID = '". $data['result']['id'] ."'");
                        $datau = array('END_TIME' => $currentDateTime);
                        if (!$startTime) {
                            $datau = array('START_TIME' => $currentDateTime, 'END_TIME' => $currentDateTime);
                        }
                        
                        $this->db->AutoExecute("MMS_MEETING_SUBJECT", $datau, "UPDATE", "ID = '". $data['result']['id'] ."'");
                    }
                    
                    $dataResult = $data['result'];
                    $dataResult['starttime'] = Date::formatter($resultArr['starttime'], 'H:i:s');
                    $dataResult['endtime'] = Date::formatter($currentDateTime, 'H:i:s');
                }
                
            } else {
                $dataResult['starttime'] = '00:00';
                $dataResult['endtime'] = '00:00';
                $dataResult['text'] = 'Эхлээгүй асуудал тул зогсоох боломжгүйг анхаарна уу?';
                $dataResult['status'] = 'Warning';
            }
        }

        jsonResponse($dataResult);
    }

    public function finishLogForm() {
        
        $widgetCtrl = Controller::loadController('Mdwebservice', 'middleware/controllers/');                
        $this->view->dataList = $widgetCtrl->renderComboDataView('1553689034457119', '1');
        $this->view->uniqId = getUID();
        $this->view->id = Input::post('id');
        $this->view->dataRow = Input::post('dataRow');
        
        (Array) $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('finishLogForm', "views/government/"),
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        
        echo json_encode($response);
    }
    
    public function saveFinishLogForm() {
        $response = $this->model->saveFinishLogFormModel();
        echo json_encode($response);
    }

    public function changeTimeConferenceForm() {
        $this->view->dataRow = Input::post('dataRow');
        $this->view->issuelist = $this->model->fncRunDataview("1564710579741", "id", $this->view->dataRow['id'], "=");
        
        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_MEETING_SUBJECT_LOG_DV_004', array ('id' => $this->view->dataRow['id']));
        $this->view->dataRow['cms_subject_time_dv'] = isset($result['result']['cms_subject_time_dv']) ? $result['result']['cms_subject_time_dv'] : array();
        
        $this->view->uniqId = getUID();
        $this->view->width = '400';
        
        (Array) $response = array(
            'Title' => '',
            'Width' => $this->view->width,
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('changeTimeConferenceForm', "views/government/"),
            'save_btn' => Lang::line('save_btn'), 
            'close_btn' => Lang::line('close_btn')
        );
        
        echo json_encode($response);
    }
    
    public function saveTimeConferenceForm() {
        $response = $this->model->saveTimeConferenceFormModel();
        echo json_encode($response); 
    }
    
    public function saveConferenceOrderNumber() {
        $response = $this->model->saveConferenceOrderNumber();
        echo json_encode($response);
    }
    
    public function changeIssue() {
        $postData = Input::postData();
        $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'));
        if (isset($postData['dataRow']['typeid'])) {
            $typeId = ($postData['dataRow']['typeid'] === '1561974650875') ? '1561974650898' : '1561974650875';
            $result = $this->db->AutoExecute("MMS_MEETING_SUBJECT", array('TYPE_ID' => $typeId), "UPDATE", "ID = ". $postData['id']);
            if ($result) {
                $response = array('status' => 'success', 'text' => Lang::line('msg_save_success'));
            }
        }
        
        echo json_encode($response);
    }
    
    public function reloadConferenceIssue() {
        
        $postData = Input::postData();
        $xHtml = $tHtml = '';
        $this->view->issuelist = $this->model->fncRunDataview("1564710579741", "meetingbookid", $postData['id'], "=", self::$heleltsegTypeId);
        $this->view->reviewgov = $this->model->fncRunDataview("1564710579741", "meetingbookid", $postData['id'], "=", self::$taniltsahTypeId);
        
        if ($this->view->issuelist) {
            $this->view->issuelist = Arr::sortBy('ordernum', $this->view->issuelist, 'asc');
            
            foreach ($this->view->issuelist as $row => $issuelist) {
                $rowJson = htmlentities(json_encode($issuelist), ENT_QUOTES, 'UTF-8');
                $isFinished = ($issuelist['endtime'] && $issuelist['starttime']) ? '1' : '0';
                $class = ($issuelist['endtime'] && $issuelist['starttime']) ? 'issue-stop' : (($issuelist['starttime']) ? 'issue-start' : '');

                $issuelist['starttime'] = $starttime = Date::formatter($issuelist['starttime'], 'H:i:s');
                $issuelist['endtime'] = $endtime = Date::formatter($issuelist['endtime'], 'H:i:s'); 

                $tempRow = $issuelist;
                $tempRowJson = htmlentities(json_encode($tempRow), ENT_QUOTES, 'UTF-8');

                $more =  ' onclick="gridDrillDownLink(this, \'CMS_HELELTSEH_LIST\', \'bookmark\', \'1\', \'cmsSubjectWeblink\', \'1564710579741\', \'subjectname\', \'1564385570445960\', \'id='. $issuelist['id'] .'\', true, true)"';

                $xHtml.= '<li data-id="'. $issuelist['id'] .'" '; 
                    $xHtml.= 'data-row ="'. $tempRowJson .'"';
                    $xHtml.= 'id="subject_'. $issuelist['id'] .'" data-ordernum="'. $issuelist['ordernum'] .'"';
                    $xHtml.= 'class="c-issue-list media isitem d-flex justify-content-center align-items-center '. (($postData['role'] != '1') ? 'tiktok-'.$postData['uniqId'] . ' ' : '') . $class .'">';
                    $xHtml.= '<div class="mr-3 font-weight-bold number" row-number="'. $issuelist['ordernum'] .'">'. $issuelist['ordernum'] .'.</div>';
                    $xHtml.= '<div class="media-body">';
                        $xHtml.= '<p class="font-weight-bold line-height-normal mb-0 conf-issuelist-name">';
                            $xHtml.= '<a style="color: #000;" href="javascript:;" data-row="'. $tempRowJson .'" '. (($postData['role'] != '1') ? $more : '') . '>'. $issuelist['subjectname'] .'</a>';
                        $xHtml.= '</p>';
                        $xHtml.= '<ul class="media-title list-inline list-inline-dotted">';
                            $xHtml.= '<li class="list-inline-item">';
                                $xHtml.= '<span class="memberposition font-weight-bold">'. $issuelist['saidname'] .' - </span>';
                                $xHtml.= '<span class="memberposition1 font-weight-bold">'. $issuelist['departmentname'] .' - </span>';
                                $xHtml.= '<span class="memberposition2 font-weight-bold">'. $issuelist['referentname'] .'</span>';
                                $xHtml.= '<span class="memberpic hidden">'. $issuelist['saidphoto'] .'</span>';
                            $xHtml.= '</li>';
                        $xHtml.= '</ul>';
                        
                        $addinStyle = ($isFinished) ? '' : 'display:none;';
                        
                        $xHtml.= '<p class="font-weight-bold line-height-normal mb-0 conf-issuelist-start timestart conf-issuelist-timer "  style="text-align: right; '. $addinStyle .'">';
                            $xHtml.= '<span class="timestart timer-start"></span>';
                            $xHtml.= $starttime . ' - ' . $endtime;
                            $xHtml.= '<span class="icon-p"  data-toggle="tooltip" data-placement="bottom" title="Товлосон цагт засвар хийх" onclick="changeConferenceTimer_'. $postData['uniqId'] .'(this)"> ';
                                $xHtml.= '<i class="icon-alarm"></i>';
                            $xHtml.= '</span>';
                        $xHtml.= '</p>';
                        

                        $xHtml.= '<div class="w-100 participants align-self-center">';
                            $xHtml.= 'Ажиглагч нар: '. ( ($issuelist['subjectparticipantcount']) ? $issuelist['subjectparticipantcount'] : '0' );
                        $xHtml.= '</div>';
                    $xHtml.= '</div>';
                    $xHtml.= '<div class="fissue align-self-center ml-3">';
                        if ($isFinished) { 
                            $xHtml.= ' <span class="badge badge-success">'. ( ($issuelist['count']) ? $issuelist['count'] : '' ).'</span>';
                            $xHtml.= '<button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_'. $postData['uniqId'] .'(this, \''. $issuelist['id'] .'\')"><i class="fa fa-gavel"></i></button>';
                        }
                    $xHtml.= '</div>';
                $xHtml.= '</li>';
            }
        }
        
        if ($this->view->reviewgov) {
            $this->view->reviewgov = Arr::sortBy('ordernum', $this->view->reviewgov, 'asc');
            foreach ($this->view->reviewgov as $row => $issuelist) {
                $rowJson = htmlentities(json_encode($issuelist), ENT_QUOTES, 'UTF-8');
                $isFinished = ($issuelist['endtime'] && $issuelist['starttime']) ? '1' : '0';
                $class = ($issuelist['endtime'] && $issuelist['starttime']) ? 'issue-stop' : (($issuelist['starttime']) ? 'issue-start' : '');

                $issuelist['starttime'] = $starttime = Date::formatter($issuelist['starttime'], 'H:i:s');
                $issuelist['endtime'] = $endtime = Date::formatter($issuelist['endtime'], 'H:i:s'); 

                $tempRow = $issuelist;
                $tempRowJson = htmlentities(json_encode($tempRow), ENT_QUOTES, 'UTF-8');

                $more =  ' onclick="gridDrillDownLink(this, \'CMS_HELELTSEH_LIST\', \'bookmark\', \'1\', \'cmsSubjectWeblink\', \'1564710579741\', \'subjectname\', \'1564385570445960\', \'id='. $issuelist['id'] .'\', true, true)"';

                $tHtml.= '<li data-id="'. $issuelist['id'] .'" '; 
                    $tHtml.= 'data-row ="'. $tempRowJson .'"';
                    $tHtml.= 'id="subject_'. $issuelist['id'] .'" data-ordernum="'. $issuelist['ordernum'] .'"';
                    $tHtml.= 'class="c-issue-list media isitem d-flex justify-content-center align-items-center '. (($postData['role'] != '1') ? 'tiktok-'.$postData['uniqId'] . ' ' : '') . $class .'">';
                    $tHtml.= '<div class="mr-3 font-weight-bold number" row-number="'. $issuelist['ordernum'] .'">'. $issuelist['ordernum'] .'.</div>';
                    $tHtml.= '<div class="media-body">';
                        $tHtml.= '<p class="font-weight-bold line-height-normal mb-0 conf-issuelist-name">';
                            $tHtml.= '<a style="color: #000;" href="javascript:;" data-row="'. $tempRowJson .'" '. (($postData['role'] != '1') ? $more : '') . '>'. $issuelist['subjectname'] .'</a>';
                        $tHtml.= '</p>';
                        $tHtml.= '<ul class="media-title list-inline list-inline-dotted">';
                            $tHtml.= '<li class="list-inline-item">';
                                $tHtml.= '<span class="memberposition font-weight-bold">'. $issuelist['saidname'] .' - </span>';
                                $tHtml.= '<span class="memberposition1 font-weight-bold">'. $issuelist['departmentname'] .' - </span>';
                                $tHtml.= '<span class="memberposition2 font-weight-bold">'. $issuelist['referentname'] .'</span>';
                                $tHtml.= '<span class="memberpic hidden">'. $issuelist['saidphoto'] .'</span>';
                            $tHtml.= '</li>';
                        $tHtml.= '</ul>';

                        $addinStyle = ($isFinished) ? '' : 'display:none;';
                        
                        $tHtml.= '<p class="font-weight-bold line-height-normal mb-0 conf-issuelist-start timestart conf-issuelist-timer"  style="text-align: right; '. $addinStyle .'">';
                            $tHtml.= '<span class="timestart timer-start"> </span>';
                            $tHtml.= $starttime . ' - ' . $endtime;
                            $tHtml.= '<span class="icon-p"  data-toggle="tooltip" data-placement="bottom" title="Товлосон цагт засвар хийх" onclick="changeConferenceTimer_'. $postData['uniqId'] .'(this)"> ';
                                $tHtml.= '<i class="icon-alarm"></i>';
                            $tHtml.= '</span>';
                        $tHtml.= '</p>';

                        $tHtml.= '<div class="w-100 participants align-self-center">';
                            $tHtml.= 'Ажиглагч нар: '. ( ($issuelist['subjectparticipantcount']) ? $issuelist['subjectparticipantcount'] : '0' );
                        $tHtml.= '</div>';
                    $tHtml.= '</div>';
                    $tHtml.= '<div class="fissue align-self-center ml-3">';
                        if ($isFinished) { 
                            $tHtml.= ' <span class="badge badge-success">'. ( ($issuelist['count']) ? $issuelist['count'] : '' ).'</span>';
                            $tHtml.= '<button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_'. $postData['uniqId'] .'(this, \''. $issuelist['id'] .'\')"><i class="fa fa-gavel"></i></button>';
                        }
                    $tHtml.= '</div>';
                $tHtml.= '</li>';
            }
        }
        echo json_encode(array('xHtml' => $xHtml, 'tHtml' => $tHtml));
    }

    public function reloadConferenceIssue_1() {
        
        $postData = Input::postData();
        $xHtml = $tHtml = '';
        $this->view->issuelist = $this->model->fncRunDataview("1564710579741", "meetingbookid", $postData['id'], "=", self::$heleltsegTypeId);
        $this->view->reviewgov = $this->model->fncRunDataview("1564710579741", "meetingbookid", $postData['id'], "=", self::$taniltsahTypeId);
       
        if ($this->view->issuelist) {
            $this->view->issuelist = Arr::sortBy('ordernum', $this->view->issuelist, 'asc');
            
            foreach ($this->view->issuelist as $row => $issuelist) {

                $rowJson = htmlentities(json_encode($issuelist), ENT_QUOTES, 'UTF-8');
                $isFinished = ($issuelist['endtime'] && $issuelist['starttime']) ? '1' : '0';
                $class = ($issuelist['endtime'] && $issuelist['starttime']) ? 'issue-stop' : (($issuelist['starttime']) ? 'issue-start' : '');

                $issuelist['starttime'] = $starttime = Date::formatter($issuelist['starttime'], 'H:i:s');
                $issuelist['endtime'] = $endtime = Date::formatter($issuelist['endtime'], 'H:i:s'); 

                $tempRow = $issuelist;
                $tempRowJson = htmlentities(json_encode($tempRow), ENT_QUOTES, 'UTF-8');

                $more =  ' onclick="gridDrillDownLink(this, \'CMS_HELELTSEH_LIST\', \'bookmark\', \'1\', \'cmsSubjectWeblink\', \'1564710579741\', \'subjectname\', \'1564385570445960\', \'id='. $issuelist['id'] .'\', true, true)"';
                $addBackground = ($class === 'issue-start') ? 'background: #F4FAFF;' : '';
                $xHtml.= '<li data-id="'. $issuelist['id'] .'" '; 
                    $xHtml.= 'data-row ="'. $tempRowJson .'"';
                    $xHtml.= 'id="subject_'. $issuelist['id'] .'" data-ordernum="'. $issuelist['ordernum'] .'"';
                    $xHtml.= 'style="'. $addBackground .'"';
                    $xHtml.= 'class="c-issue-list media isitem d-flex justify-content-center align-items-center '. (($postData['role'] != '1') ? 'tiktok-'.$postData['uniqId'] . ' ' : '') . $class .' media align-items-stretch">';
                    $xHtml.= '<div class="p-1">';
                        $xHtml.= '<p style="height:100%; border:3px solid '. $issuelist['rowcolor'] .'"></p>';
                    $xHtml.= '</div>';
                    $xHtml.= '<div class="media-body">';
                        $xHtml.= '<div class="font-weight-bold number" row-number="'. $issuelist['ordernum'] .'">';
                        $xHtml.= '<p class="line-height-normal mb-0 conf-issuelist-name w-75">';
                            $xHtml.= '<a style="color: #000;" href="javascript:;" data-row="'. $tempRowJson .'" '. (($postData['role'] != '1') ? $more : '') . '>'. $issuelist['ordernum'] .'. '. $issuelist['subjectname'] .'</a>';
                        $xHtml.= '</p>';
                        $xHtml.= '</div>';
                        $xHtml.= '<ul class="media-title list-inline list-inline-dotted">';
                            $xHtml.= '<li class="list-inline-item w-75">';
                                $xHtml.= '<span class="memberposition font-weight-bold">'. $issuelist['saidname'] .' - </span>';
                                $xHtml.= '<span class="memberposition1 font-weight-bold">'. $issuelist['departmentname'] .' - </span>';
                                $xHtml.= '<span class="memberposition2 font-weight-bold">'. $issuelist['referentname'] .'</span>';
                                $xHtml.= '<span class="memberpic hidden">'. $issuelist['saidphoto'] .'</span>';
                            $xHtml.= '</li>';
                        $xHtml.= '</ul>';
                        
                        $addinStyle = ($isFinished) ? '' : 'display:none;';
                        
                        $xHtml.= '<p class="font-weight-bold line-height-normal mb-0 conf-issuelist-start timestart conf-issuelist-timer "  style="text-align: right; '. $addinStyle .'">';
                            $xHtml.= '<span class="timestart timer-start"></span>';
                            $xHtml.= $starttime . ' - ' . $endtime;
                            $xHtml.= '<span class="icon-p"  data-toggle="tooltip" data-placement="bottom" title="Товлосон цагт засвар хийх" onclick="changeConferenceTimer_'. $postData['uniqId'] .'(this)"> ';
                                $xHtml.= '<i class="icon-alarm"></i>';
                            $xHtml.= '</span>';
                        $xHtml.= '</p>';
                        $xHtml.= '<div class="w-100 participants align-self-center d-flex mt-1">';
                            $xHtml.= '<span>Ажиглагч нар: '. ( ($issuelist['subjectparticipantcount']) ? $issuelist['subjectparticipantcount'] : '0' ).'</span>';
                            $xHtml.= '<button type="button" class="btn btn-outline-primary border-none ml-auto px-1 py-0" onclick="totalSum(this,\''. $issuelist['id'] .'\')">';
                                $xHtml.= '<span class="huraldaan-total">'. ( ($issuelist['total']) ? $issuelist['total'] : '' ).'</span>';
                            $xHtml.= '</button>';
                        $xHtml.= '</div>';
                    $xHtml.= '</div>';
                    $xHtml.= '<div class="fissue align-self-center ml-3">';
                        if ($isFinished) { 
                            $xHtml.= ' <span class="badge badge-success">'. ( ($issuelist['count']) ? $issuelist['count'] : '' ).'</span>';
                            $xHtml.= '<button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_'. $postData['uniqId'] .'(this, \''. $issuelist['id'] .'\')"><i class="fa fa-gavel"></i></button>';
                        } elseif ($class === 'issue-start') {
                            $xHtml.= ' <button type="button" class="btn font-weight-bold finishadd" onclick="setProtocol_'. $postData['uniqId'] .'(this, \''. $issuelist['id'] .'\', \''. $issuelist['meetingbookid'] .'\' )"><i class="fa fa-microphone"></i></button>';
                            $xHtml.= ' <button type="button"';
                            $xHtml.= 'data-row ="'. $tempRowJson .'"';
                            $xHtml.= 'class="btn font-weight-bold finishFeedback" finishFeedback" onclick="totalProtocol'. $postData['uniqId'] .'(this, \''. $issuelist['id'] .'\', \''. $issuelist['mapid'] .'\', \''. $issuelist['meetingbookid'] .'\' )"">';
                                $xHtml.= ' <span>Санал хураалт</span>';
                            $xHtml.= ' </button>';
                        }
                    $xHtml.= '</div>';
                $xHtml.= '</li>';
            }
        }
        
        if ($this->view->reviewgov) {
            $this->view->reviewgov = Arr::sortBy('ordernum', $this->view->reviewgov, 'asc');
            foreach ($this->view->reviewgov as $row => $issuelist) {

                $rowJson = htmlentities(json_encode($issuelist), ENT_QUOTES, 'UTF-8');
                $isFinished = ($issuelist['endtime'] && $issuelist['starttime']) ? '1' : '0';
                $class = ($issuelist['endtime'] && $issuelist['starttime']) ? 'issue-stop' : (($issuelist['starttime']) ? 'issue-start' : '');

                $issuelist['starttime'] = $starttime = Date::formatter($issuelist['starttime'], 'H:i:s');
                $issuelist['endtime'] = $endtime = Date::formatter($issuelist['endtime'], 'H:i:s'); 
                $addBackground = ($class === 'issue-start') ? 'background: #F4FAFF;' : '';
                $tempRow = $issuelist;
                $tempRowJson = htmlentities(json_encode($tempRow), ENT_QUOTES, 'UTF-8');

                $more =  ' onclick="gridDrillDownLink(this, \'CMS_HELELTSEH_LIST\', \'bookmark\', \'1\', \'cmsSubjectWeblink\', \'1564710579741\', \'subjectname\', \'1564385570445960\', \'id='. $issuelist['id'] .'\', true, true)"';

                $tHtml.= '<li data-id="'. $issuelist['id'] .'" '; 
                    $tHtml.= 'data-row ="'. $tempRowJson .'"';
                    $tHtml.= 'id="subject_'. $issuelist['id'] .'" data-ordernum="'. $issuelist['ordernum'] .'"';
                    $tHtml.= 'style="'. $addBackground .'"';
                    $tHtml.= 'class="c-issue-list media isitem d-flex justify-content-center align-items-center '. (($postData['role'] != '1') ? 'tiktok-'.$postData['uniqId'] . ' ' : '') . $class .' media align-items-stretch">';
                    $tHtml.= '<div class="p-1">';
                        $tHtml.= '<p style="height:100%; border:3px solid '. $issuelist['rowcolor']  .'"></p>';
                    $tHtml.= '</div>';
                    $tHtml.= '<div class="media-body">';
                        $tHtml.= '<div class="font-weight-bold number" row-number="'. $issuelist['ordernum'] .'">';
                            $tHtml.= '<p class="line-height-normal mb-0 conf-issuelist-name w-75">';
                                $tHtml.= '<a style="color: #000;" href="javascript:;" data-row="'. $tempRowJson .'" '. (($postData['role'] != '1') ? $more : '') . '>'. $issuelist['ordernum'] .'. '. $issuelist['subjectname'] .'</a>';
                            $tHtml.= '</p>';
                        $tHtml.= '</div>';
                        $tHtml.= '<ul class="media-title list-inline list-inline-dotted">';
                            $tHtml.= '<li class="list-inline-item w-75">';
                                $tHtml.= '<span class="memberposition font-weight-bold">'. $issuelist['saidname'] .' - </span>';
                                $tHtml.= '<span class="memberposition1 font-weight-bold">'. $issuelist['departmentname'] .' - </span>';
                                $tHtml.= '<span class="memberposition2 font-weight-bold">'. $issuelist['referentname'] .'</span>';
                                $tHtml.= '<span class="memberpic hidden">'. $issuelist['saidphoto'] .'</span>';
                            $tHtml.= '</li>';
                        $tHtml.= '</ul>';

                        $addinStyle = ($isFinished) ? '' : 'display:none;';
                        
                        $tHtml.= '<p class="font-weight-bold line-height-normal mb-0 conf-issuelist-start timestart conf-issuelist-timer"  style="text-align: right; '. $addinStyle .'">';
                            $tHtml.= '<span class="timestart timer-start"> </span>';
                            $tHtml.= $starttime . ' - ' . $endtime;
                            $tHtml.= '<span class="icon-p"  data-toggle="tooltip" data-placement="bottom" title="Товлосон цагт засвар хийх" onclick="changeConferenceTimer_'. $postData['uniqId'] .'(this)"> ';
                                $tHtml.= '<i class="icon-alarm"></i>';
                            $tHtml.= '</span>';
                        $tHtml.= '</p>';
                        $tHtml.= '<div class="w-100 participants align-self-center d-flex mt-1">';
                            $tHtml.= '<span>Ажиглагч нар: '. ( ($issuelist['subjectparticipantcount']) ? $issuelist['subjectparticipantcount'] : '0' ).'</span>';
                            $tHtml.= '<button type="button" class="btn btn-outline-primary border-none ml-auto px-1 py-0" onclick="totalSum(this,\''. $issuelist['id'] .'\')">';
                                $tHtml.= '<span class="huraldaan-total">'. ( ($issuelist['total']) ? $issuelist['total'] : '' ).'</span>';
                            $tHtml.= '</button>';
                        $tHtml.= '</div>';
                    $tHtml.= '</div>';
                    $tHtml.= '<div class="fissue align-self-center ml-3">';
                        if ($isFinished) { 
                            $tHtml.= ' <span class="badge badge-success">'. ( ($issuelist['count']) ? $issuelist['count'] : '' ).'</span>';
                            $tHtml.= '<button type="button" class="btn font-weight-bold finishadd" onclick="finishByDescription_'. $postData['uniqId'] .'(this, \''. $issuelist['id'] .'\')"><i class="fa fa-gavel"></i></button>';
                        } elseif ($class === 'issue-start') {
                            $tHtml.= ' <button type="button" class="btn font-weight-bold finishadd" onclick="setProtocol_'. $postData['uniqId'] .'(this, \''. $issuelist['id'] .'\', \''. $issuelist['meetingbookid'] .'\' )"><i class="fa fa-microphone"></i></button>';
                            $tHtml.= ' <button type="button"';
                            $tHtml.= 'data-row ="'. $tempRowJson .'"';
                            $tHtml.= 'class="btn font-weight-bold finishFeedback" onclick="totalProtocol'. $postData['uniqId'] .'(this, \''. $issuelist['id'] .'\', \''. $issuelist['mapid'] .'\', \''. $issuelist['meetingbookid'] .'\' )"">';
                                $tHtml.= ' <span>Санал хураалт</span>';
                            $tHtml.= ' </button>';
                        }
                    $tHtml.= '</div>';
                $tHtml.= '</li>';
            }
        }
        echo json_encode(array('xHtml' => $xHtml, 'tHtml' => $tHtml));
    }
   
    public function saveConferenceMember() {
        $response = $this->model->saveConferenceMember();
        echo json_encode($response);
    }
    
    public function cancelIssue() {
        
        $postData = Input::postData();
        $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'));
        if (isset($postData['dataRow']['typeid'])) {
            $data = array('WFM_STATUS_ID' => '1566532581717313', 'START_TIME' => '', 'END_TIME' => '');
            $result = $this->db->AutoExecute("MMS_MEETING_SUBJECT", $data, "UPDATE", "ID = ". $postData['id']);
            
            if ($result) {
                $this->db->Execute("DELETE FROM MMS_SUBJECT_PROTOCOL WHERE SUBJECT_ID = '". $postData['id'] ."'");
                $this->db->Execute("DELETE FROM MMS_SUBJECT_TIME WHERE SUBJECT_ID = '". $postData['id'] ."'");
                $response = array('status' => 'success', 'text' => Lang::line('msg_save_success'));
            }
        }
        
        echo json_encode($response);
        
    }
    
    public function deleteIssue() {
        
        $postData = Input::postData();
        $response = array('status' => 'error', 'text' => Lang::line('msg_delete_error'));
        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_SUBJECT_MAP_DELETE_DV_005', array ('subjectId' => $postData['id']));
        
        if ($result) {
            $response = array('status' => 'success', 'text' => Lang::line('msg_delete_success'));;
        }
        
        echo json_encode($response);
    }
    
    public function setBreakTime() {
        $meetingBookId = Input::post('bookId');
        $currentDate = Date::currentDate();
        
        $this->db->AutoExecute('MMS_MEETING_BOOK', array('BREAK_TIME' => $currentDate, 'ACTION' => '2'), 'UPDATE', "ID = '". $meetingBookId ."'");
        $this->db->AutoExecute('MMS_MEETING_MINUTES', array('ID' => getUID(), 'CREATED_DATE' => $currentDate, 'TYPE_ID' => '2', 'DESCRIPTION' => 'break', 'MEETING_BOOK_ID' => $meetingBookId));
        echo json_encode(array('status' => 'success'));
    }
    
    public function setPlayTime() {
        $meetingBookId = Input::post('bookId');
        $currentDate = Date::currentDate();
        
        $this->db->AutoExecute('MMS_MEETING_BOOK', array('ACTION' => '1'), 'UPDATE', "ID = '$meetingBookId'");
        $this->db->AutoExecute('MMS_MEETING_MINUTES', array('ID' => getUID(), 'CREATED_DATE' => $currentDate, 'TYPE_ID' => '1', 'DESCRIPTION' => 'play', 'MEETING_BOOK_ID' => $meetingBookId));
    }
    
    public function setProcessingTime() {
        (Array) $paramData = array();
        try {
            $bookId = Input::post('bookId');
            $type = Input::post('type');
            $saveTime = Input::post('saveTime');
            $time = Input::post('time');

            $folderPath = UPLOADPATH.'timer/' . $bookId;

            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $actionType = $this->db->GetOne("SELECT ACTION FROM MMS_MEETING_BOOK WHERE ID = '$bookId'");
            if ($actionType !== '3') {
                switch ($type) {
                    case 'start':
                    case 'play':
                        $type = 'start';
                        $paramData = array('DURATION' => $saveTime, 'ACTION' => '1');
                        break;
                    case 'startData':
                        $type = 'startData';
                        $paramData = array('DURATION' => $saveTime, 'ACTION' => '1');
                        break;
                    case 'pause':
                        $paramData = array('TOTAL_BREAK_TIME' => $saveTime, 'ACTION' => '2');
                        break;
                }

                $this->db->AutoExecute('MMS_MEETING_BOOK', $paramData, 'UPDATE', "ID = '$bookId'");

                @file_put_contents($folderPath. '/time'. $type .'.txt', $saveTime);
            }
        } catch (Exception $ex) {
            $paramData['error'] = $ex;
        }
        
        echo json_encode($paramData);
    }
    
    public function getMemberConfig() {
        
        $postData = Input::postData();
        $response = array('status' => 'error', 'text' => Lang::line('msg_error'));
        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_ATTENDANCE_STATUS_GET_LIST_004', array ('id' => $postData['id'], 'bookId' => $postData['bookid']));
       
        if (isset($result['result'])) {
            $response = array('status' => 'success', 'config' => $result['result']);
        }
        
        echo json_encode($response);
    }
    
    public function memberchangestatus() {
        
        try {
            $postData = Input::postData();
            $currentDate = Date::currentDate('H:i');
            parse_str($postData['timeData'], $timedata);
            
            if (isset($postData['processcode']) && $postData['processcode'] === 'CMS_MEETING_PARTICIPANT_ABSENT_DV_002') {
                
                if (isset($postData['statusId'])) {
                    $response = $this->model->wfstatusModel($postData['id'], $postData['descrition'], $postData['bookid'], $postData['statusId'], $postData['keyid'], $timedata);
                } else {
                    $response = array(
                            'status' => 'success', 
                            'result' => array(),
                            'wfmdescription' => ''
                        );
                }
                
            } else {
                if (isset($postData['statusId']) && $postData['iscome'] === '0') {
                    $dparam = array(
                        'id' => $postData['id'],
                        'bookId' => $postData['bookid'],
                        // 'time26' => $currentDate,
                        'employeekeyid'=> $postData['keyid'],
                        'wfmstatusid' => $postData['statusId'],
                        'wfmStatus' => $postData['statusId'],
                        'bookid' => $postData['bookid'],
                        'wfmDescription' => $postData['descrition'],
                    );

                    $dparam[$postData['timeData']] = $currentDate;
                    
                    includeLib('Utils/Functions');
                    $data = Functions::runProcess($postData['processcode'], $dparam);
                    if (isset($data['status']) && $data['status'] === 'success') {

                        $response = array(
                            'status' => 'success', 
                            'result' => $data['result'],
                            'wfmdescription' => $postData['descrition']
                        );
                    } else {
                        $message = (isset($data['text']) && $data['text']) ? $data['text'] : 'Error олдсонгүй';
                        $response = array('status' => 'error', 'message' => $message );
                    }   
                } else {
                    $response = array(
                            'status' => 'success', 
                            'result' => array(),
                            'wfmdescription' => ''
                        );
                }
            }
            
            if ($response['status'] === 'success') {
                (Array) $params = array();
                
                
                includeLib('Utils/Functions');
                $result = Functions::runProcess('CMS_ATTENDANCE_STATUS_GET_LIST_004', array ('id' => $postData['id'], 'bookId' => $postData['bookid']));
                
                if (isset($result['result'])) {
                    
                    $config = $result['result'];
                    
                    $params = array(
                        'id' => $postData['id'],
                        'employeekeyid' => $postData['keyid'],
                        'wfmstatusid' => (isset($postData['statusId']) && $postData['iscome'] === '1') ? $postData['statusId'] : $config['statusid'],
                        'wfmdescription' => $config['wfmdescription'],
                        'bookid' => $postData['bookid'],
                    );

                    $paramPerc = array(
                        'id' => $postData['bookid'],
                    );

                    for ($i = 1; $i <= 30; $i++) {
                        $params['time' . $i] = (isset($timedata['time']) && isset($timedata['time'][$i])) ? $timedata['time'][$i] : (isset($config['time' . $i]) ? $config['time' . $i] : '');
                    }

                    $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_ATT_SHOW_DV_002', $params);
                    $dataPerc = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CMS_MEETING_PERC_SET_LIST_004', $paramPerc);
                    $response['data'] = $data;
                    $response['dataPerc'] = $dataPerc;

                    $result = Functions::runProcess('CMS_ATTENDANCE_STATUS_GET_LIST_004', array ('id' => $postData['id'], 'bookId' => $postData['bookid']));
                    $response['config'] = $result['result'];
                }
            }
            
            echo json_encode($response);
        } catch (Exception $exc) {
            echo json_encode(array('status' => 'error', 'message' => $exc ));
        }

    }
    
    public function deleteOtherMemberConfirmed() {
        
        $postData = Input::postData();
        $response = array('status' => 'error', 'text' => Lang::line('msg_delete_error'));
        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_SUBJECT_PARTICIPANT_DV_005', array ('id' => $postData['id']));
        
        if ($result) {
            $response = array('status' => 'success', 'text' => Lang::line('msg_delete_success'));
        }
        
        echo json_encode($response);
    }
    
    public function protocol() {
        $response = $this->model->setprotocolModel();
        echo json_encode($response);
    }
    
    public function rmprotocol() {
        $response = $this->model->rmprotocolModel();
        echo json_encode($response);
    }
    
    public function closeprotocol() {
        $response = $this->model->endprotocolModel();
        echo json_encode($response);
    }

    public function previewattenData() {
        $postData = Input::postData();
        $this->view->id = issetParam($postData['id']);
        includeLib('Utils/Functions');
        $data = Functions::runProcess('CMS_MEETING_ATTENDANCE_SUBJECT_LIST_004', array('id' => $this->view->id));

        $meetingConference_1 = $this->model->fncRunDataview("1564710579741", "meetingBookId", $this->view->id, "=", self::$heleltsegTypeId);
        $meetingConference_2 = $this->model->fncRunDataview("1564710579741", "meetingBookId", $this->view->id, "=", self::$taniltsahTypeId);
        $this->view->$meeting = array_merge($meetingConference_1, $meetingConference_2);
       
        $this->view->gov_meeting = $gov_meeting = issetParamArray($data['result']);
        $this->view->gov_meeting['durationtime'] = $this->view->gov_meeting['duration'];
        $this->view->gov_meeting['breaktime'] = $this->view->gov_meeting['totalbreaktime'];

        if ($this->view->gov_meeting['action']) {
            $this->view->selectedRow['startime'] = $this->view->gov_meeting['starttime'];
            $this->view->selectedRow['endtime'] = $this->view->gov_meeting['endtime'];

            $durationTimeInt = $this->view->gov_meeting['diffbreaksysdate'];
            // $durationTimeInt = $this->view->gov_meeting['diffsysdate'];
            $breakTimeInt = $this->view->gov_meeting['diffbreaksysdate'];

            if ($this->view->gov_meeting['action'] == '2') {
                $this->view->gov_meeting['breaktime'] = floor($breakTimeInt/3600) .':' . (floor(($breakTimeInt - $breakTimeInt/3600)/60)) . ':' . ($breakTimeInt - $breakTimeInt/3600)%60;
                // $this->view->gov_meeting['totalbreaktime'] = floor($breakTimeInt/3600) .':' . (floor(($breakTimeInt - $breakTimeInt/3600)/60)) . ':' . ($breakTimeInt - $breakTimeInt/3600)%60;
            } else {
                $this->view->gov_meeting['breaktime'] = $this->view->gov_meeting['totalbreaktime'];
                $this->view->gov_meeting['totalbreaktime'] = $this->view->gov_meeting['totalbreaktime'];
                $this->view->gov_meeting['duration'] = $this->view->gov_meeting['duration'];
            }
            // $this->view->gov_meeting['duration'] = floor($durationTimeInt/3600) .':' . (floor(($durationTimeInt - $durationTimeInt/3600)/60)) . ':' . ($durationTimeInt - $durationTimeInt/3600)%60;
        }

        if ($this->view->gov_meeting['action'] === '3') {
            $this->view->gov_meeting['duration'] = $this->view->gov_meeting['durationtime'];
            $this->view->gov_meeting['totalbreaktime'] = $this->view->gov_meeting['breaktime'];
        }

        if ($this->view->gov_meeting['duration']) {
            $explode1 = explode(':', $this->view->gov_meeting['duration']);
            $this->view->gov_meeting['duration'] = $explode1[0] . ' цаг ' . $explode1[1] . ' минут ' . $explode1[2] . ' секунд ';
        }

        if ($this->view->gov_meeting['totalbreaktime']) {
            $explode2 = explode(':', $this->view->gov_meeting['totalbreaktime']);
            if ($explode2) {
                $this->view->gov_meeting['totalbreaktime'] = $explode2[0] . ' цаг ' . $explode2[1] . ' минут ' . $explode2[2] . ' секунд ';
            }
        }

        $scheduledtime = $this->view->gov_meeting['scheduledtime'];
        $starttime = $this->view->gov_meeting['starttime'];
        $endtime = $this->view->gov_meeting['endtime'];
        $totalbreaktime = $this->view->gov_meeting['totalbreaktime'];
        $duration = $this->view->gov_meeting['duration'];
        $response = array(
            'scheduledtime' => $scheduledtime,
            'starttime' => $starttime,
            'endtime' => $endtime,
            'totalbreaktime' => $totalbreaktime,
            'duration' => $duration,
            'data'=> $this->view->$meeting,
        );
        
        echo json_encode($response);
    }

    public function reviewTotal($id = '', $uniqId="", $mapid="", $meetingBookId="") {
        $this->view->langCode = 'mn';
        $postData = Input::postData();
        $this->view->title = 'Санал хураалтын дүн';
        $this->load->model('contentui', 'projects/models/');
        $this->view->id = checkDefaultVal($postData['id'], $id);
        $this->view->name = issetParam($postData['name']);
        $this->view->selectedId = issetParam($postData['selectedId']);
        $this->view->selectedRow = issetParam($postData['id']);
        $this->view->subjectId = checkDefaultVal($this->view->selectedRow, $id);
        $this->view->modulId = '1';
        includeLib('Utils/Functions');

        $mainData = Functions::runProcess('CMS_MEETING_ATTENDANCE_SUBJECT_LIST_004', array('id' => $this->view->id));
        $data = Functions::runProcess('MMS_SUBJECT_REVIEW_GET_LIST_004', array('id' => issetParam($postData['id'])));
        $this->view->data = issetParamArray($data['result']);
        $this->view->uniqId = checkDefaultVal($postData['uniqid'], $uniqId);

        $criteria = array(
            'meetingBookId' => array(
                array(
                    'operator' => '=',
                    'operand' => issetParam($postData['meetingBookid']),
                )
            )
        );

        $districtresult = $this->model->fncRunDataview("1564710579741", "id", issetParam($postData['id']), "=", $criteria);
        $this->view->mapDuration = $districtresult[0]['mapduration'];
        
        $this->view->defaultCss = $this->view->renderPrint('defaultCssDistrict', 'views/government/bzd/');
        $this->view->defaultJs = $this->view->renderPrint('defaultJsDistrict', 'views/government/bzd/');
       
        $this->view->isAjax = is_ajax_request();
        
        if ($this->view->isAjax) {
            (Array) $response = array(
                'Title' => '',
                'Width' => '1200',
                'data' => $this->view->data,
                'id' => $this->view->id,
                'time' => $this->view->mapDuration,
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('reviewModal', 'views/government/bzd/'),
                'save_btn' => Lang::line('finish_btn'), 
                'close_btn' => Lang::line('close_btn'),
            );
    
            convJson($response);
        }
    }

    public function reviewTotalSum() {
        $this->view->langCode = 'mn';
        $this->view->modulId = '2';
        $postData = Input::postData();
        includeLib('Utils/Functions');
        $data = Functions::runProcess('MMS_SUBJECT_REVIEW_GET_LIST_004', array('id' => issetParam($postData['id'])));
        $this->view->data = issetParamArray($data['result']);
        $this->view->defaultCss = $this->view->renderPrint('defaultCssDistrict', 'views/government/bzd/');
        $this->view->isAjax = is_ajax_request();
        
        $response = array(
                'Html' => $this->view->renderPrint('reviewModal', 'views/government/bzd/'),
            );
        
        convJson($response);
    }

    public function protocalTalk() {
        $postData = Input::postData();
        
        includeLib('Utils/Functions');
        if (issetParam($postData['dataid']) == '1') {
            $data = Functions::runProcess('cmsSubjectMainParticipantTimeGetList_004',array ('id' => issetParam($postData['id']), 'subjectid' => issetParam($postData['subjectid'])));
        }else{
            $data = Functions::runProcess('cmsSubjectParticipantTimeGetList_004',array ('id' => issetParam($postData['id'])));
        }


        $raise = Functions::runProcess('CNF_RAISE_HAND_LIST_004_copy_copy',array ('subjectId' => issetParam($postData['subjectid']), 'raisedUserId' => issetParam($postData['userid'])));
        $this->view->dataraise = issetParamArray($raise['result']);
            if ($this->view->dataraise['wfmstatusid']=='1710575243249059') {
                $params = array(
                    'id' => $this->view->dataraise['id'],
                    'subjectid' => $this->view->dataraise['subjectid'],
                    'subjectname' => $this->view->dataraise['subjectname'],
                    'raisedUserId' => $this->view->dataraise['raiseduserid'],
                    'wfmStatusId' => '1710575243723352',
                );
                $dataraise = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'CNF_RAISE_HAND_DV_002', $params);
            }
        

        $response = array(
                'Html' => $data['result'],
                'datarise' =>$dataraise,
            );
        
        convJson($response);
    }

    public function protocalTalkEnd() {
        $postData = Input::postData();
        includeLib('Utils/Functions');
       
        if (issetParam($postData['dataid']) == '1') {
            $data = Functions::runProcess('cmsSubjectMainParticipantTimeGetList_004',array ('id' => issetParam($postData['id']), 'subjectid' => issetParam($postData['subjectid'])));
            $this->view->data = issetParamArray($data['result']);

            $params = array(
                'id' => issetParam($this->view->data['subjectparticipantid']),
                'meetingParticipantId' => issetParam($this->view->data['id']),
                'positionName' => $this->view->data['positionname'],
                'time1' => issetParam($postData['time1']),
                'subjectId' => $this->view->data['subjectid'],
                'firstName' => $this->view->data['firstname'],
                'orderNum' => '',
                'time2' => issetParam($postData['time2']),
            );

            $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'cmsSubjectParticipantAddDv_001', $params);

        }else{
            $params = array(
                'id' => issetParam($postData['id']),
                'time1' => issetParam($postData['time1']),
                'time2' => issetParam($postData['time2']),
            );

            $data = $this->ws->runArrayResponse(GF_SERVICE_ADDRESS, 'cmsSubjectParticipantTimeDv_002', $params);
        }

        $response = array(
            'data' =>  $data,
            );
        
        convJson($response);
    }

    public function reviewTotalEnd() {
        $postData = Input::postData();
        $this->view->id = issetParam($postData['subjectid']);
        $this->view->selectedId = issetParam($postData['selectedId']);
        $reviewData = array('status' => 'error', 'text' => Lang::line('msg_error'));
        includeLib('Utils/Functions');
        (array) $pollBookMap = array();

        $param = array(
            'id' => issetParam($postData['subjectid']),
            'name' => issetParam($postData['endData']['name']),
            'sysTime' => Date::currentDate('H:i:s'),
            'CNF_POLL_DV' => array(
                'id' => issetParam($postData['endData']['mmsmeetingpollbookdv']['id']), 
                'pollBookCode' => issetParam($postData['endData']['mmsmeetingpollbookdv']['pollbookcode']),
                'name' =>  issetParam($postData['endData']['mmsmeetingpollbookdv']['pollbookname']),
                'subjectId' => issetParam($postData['subjectid']),
                'startDate' => issetParam($postData['endData']['mmsmeetingpollbookdv']['startDate']),
                'endDate' => Date::currentDate(),
                'description' => '',
            ),
        );

        $reviewData = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'CNF_SUB_POLL_DV_008', $param);
       
        $response = array(
            'reviewData' =>  $reviewData,
        );
        
        echo json_encode($response);
    }

    public function reviewTotalData() {
        $this->view->langCode = 'mn';
        $postData = Input::postData();
       
        $this->view->title = 'Санал хураалтын дүн';
        $this->load->model('contentui', 'projects/models/');

        includeLib('Utils/Functions');
        
        $data = Functions::runProcess('MMS_SUBJECT_REVIEW_GET_LIST_004', array('id' => issetParam($postData['id'])));
        $this->view->data = issetParamArray($data['result']);

        $criteria = array(
            'meetingBookId' => array(
                array(
                    'operator' => '=',
                    'operand' => issetParam($postData['selectedId']),
                )
            )
        );

        $districtresult = $this->model->fncRunDataview("1564710579741", "id", issetParam($postData['id']), "=", $criteria);
        $this->view->mapDuration = $districtresult[0]['mapduration'];
        $explode1 = explode(':', $this->view->mapDuration);
        $this->view->time = $explode1[0];
        $this->view->minute = $explode1[1];
        $this->view->second = $explode1[2];

        $response = array(
            'data' =>  $this->view->data,
            'time' =>$this->view->time,
            'minute' =>$this->view->minute,
            'second' =>$this->view->second,
        );
        
        echo json_encode($response);
    }

    public function itemReviewTotal() {
        $this->view->langCode = 'mn';
        $postData = Input::postData();
        $this->view->title = 'Санал хураалтын дүн';
        $this->load->model('contentui', 'projects/models/');
        $this->view->id = issetParam($postData['id']);
        $this->view->subjectId = issetParam($postData['subjectid']);
        $this->view->uniqId = issetParam($postData['uniqId']);
        $this->view->currentTime = Date::currentDate('H:i');
        $this->view->currentDate = Date::currentDate();
        includeLib('Utils/Functions');

        $data = Functions::runProcess('MMS_SUBJECT_REVIEW_GET_LIST_004', array('id' => $this->view->id));
       
        $this->view->data = issetParamArray($data['result']);
        $this->view->mapid = $this->view->data['mapid'];

        $criteria = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => issetParam($postData['id']),
                )
            )
        );

        $districtresult = $this->model->fncRunDataview("1564710579741", "meetingBookId", issetParam($postData['subjectid']), "=", $criteria);
        $this->view->mapDurationIndex = $districtresult[0]['mapduration'];
     
        $this->view->defaultCss = $this->view->renderPrint('defaultCssDistrict', 'views/government/bzd/');
        $this->view->isAjax = is_ajax_request();
        
        if ($this->view->isAjax) {
        (Array) $response = array(
            'Title' => '',
            'Width' => '1200',
            'data' => $this->view->data,
            'id' => $this->view->id,
            'uniqId' => $this->view->uniqId,
            'Html2' => $this->view->renderPrint('header', 'views/government/bzd/new/'),
            'Html3' => $this->view->renderPrint('index', 'views/government/bzd/new/'),
            'Html4' => $this->view->renderPrint('footer', 'views/government/bzd/new/'),
            'save_btn' => Lang::line('finish_btn'),
            'close_btn' => Lang::line('close_btn')
           
        );
        convJson($response);
        }
    }

    public function puulreview() {
        $postData = Input::postData();
        $this->view->id = issetParam($postData['id']);
        includeLib('Utils/Functions');
        $data = Functions::runProcess('MMS_SUBJECT_REVIEW_GET_LIST_004', array('id' => issetParam($postData['id'])));
        $this->view->data = issetParamArray($data['result']);

        (Array) $response = array(
            'Title' => '',
            'Html' => $this->view->renderPrint('puul', 'views/government/bzd/new/'),
            'save_btn' => Lang::line('start_puul'),
        );
        convJson($response);
    }

    public function startDistrictTimePool() {
        $postData = Input::postData();
        $this->view->id = issetParam($postData['id']);
        $this->view->selectedId = issetParam($postData['selectedId']);
        $reviewData = array('status' => 'error', 'text' => Lang::line('msg_error'));

        includeLib('Utils/Functions');
        $data = Functions::runProcess('MMS_SUBJECT_REVIEW_GET_LIST_004', array('id' => issetParam($postData['id'])));
        $this->view->data = issetParamArray($data['result']);
        $puulNumber = Functions::runProcess('CRM_AUTONUMBER_BP', array('objectid' => '17103200321629'));

        $param = array(
            'id' => $this->view->data['mapid'],
            'startTime' => Date::currentDate('H:i:s'),
            'subjectId' => $this->view->data['id'],
            'mmsMeetingPollBookDv' => array(
                'id' => '',
                'pollBookCode' => $puulNumber['result']['result'],
                'pollBookName' =>  issetParam($postData['name']),
                'bookId' => $this->view->data['id'],
                'startDate' => Date::currentDate(),
            ),
            'mmsSubjectDv' => array(
                'isReview' => '1',
                'id' => $this->view->data['id'],
            ),
        );
        
        $reviewData = $this->ws->runResponse(GF_SERVICE_ADDRESS, 'mmsSubjectMapDv_001', $param); 
        $meetingPerson = $this->model->fncRunDataview("17107577367229", "bookid", issetParam($postData['meetingBookid']), "=");

        (Array) $response = array(
            'reviewData' => $reviewData,
            'person' => $meetingPerson,
        );
        convJson($response);
    }

    public function getRiseHand() { 
        $postData = Input::postData();

        $this->view->defaultJs = $this->view->renderPrint('defaultJsDistrict', 'views/government/bzd/');
        $this->view->id = issetParam($postData['subjectid']); 
        includeLib('Utils/Functions');
        $data = Functions::runProcess('CNF_RAISE_HAND_LIST_copy_004', array('id' => $this->view->id));
       
        $this->view->data = issetParamArray($data['result']);

        (Array) $response = array(
            'data' => $this->view->data,
        );
        convJson($response);
    }

    public function fileViewLog() {
        $postData = Input::postData();
        $this->view->id = issetParam($postData['id']);
        includeLib('Utils/Functions');
        $data = Functions::runProcess('FILE_VIEWS_004', array('contentid' => issetParam($postData['id'])));
        $this->view->data = issetParamArray($data['result']);

        (Array) $response = array(
            'data' => $this->view->data 
        );
        convJson($response);
    }
}

?>