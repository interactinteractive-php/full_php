<?php

if (!defined('_VALID_PHP'))
    exit('Direct access to this location is not allowed.');

class Government extends Controller {

    private static $viewName = "views/government";

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {
    }

    public function mail() {

        $this->view->title = 'Mail';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->uniqId = getUID();
        $this->view->otherUniqId = getUID();

        includeLib('Utils/Functions');
        $result = Functions::runProcess('MM_USER_SIGNATURE_LIST_004', array('userid' => Ue::sessionUserId()));
        $this->view->signature = isset($result['result']['signature']) ? '<br><br><br><br><br><br>' . $result['result']['signature'] : '';

        $this->view->isinbox = '0';
        $this->view->isrecover = '0';

        $this->view->mainData = $this->model->fncRunDataview("1571129513478852");

        $firstLoad = Arr::multidimensional_search($this->view->mainData, array('status' => 'active'));
        $this->view->unreadLoadDv = array();
        $this->view->firstLoadDv = isset($firstLoad['dataviewid']) ? $this->model->fncRunDataview($firstLoad['dataviewid']) : array();

        $unread = array('isread' => array(array('operator' => '=', 'operand' => '1')));

        $this->view->unreadLoadDv = isset($firstLoad['dataviewid']) ? $this->model->fncRunDataview($firstLoad['dataviewid'], '', '', '', $unread) : array();

        if (isset($this->view->firstLoadDv[0]) && $this->view->firstLoadDv[0]['getprocesscode']) {

            includeLib('Utils/Functions');
            $result = Functions::runProcess($this->view->firstLoadDv[0]['getprocesscode'], array('id' => $this->view->firstLoadDv[0]['id']));

            $this->view->isinbox = isset($this->view->firstLoadDv[0]['isinbox']) ? $this->view->firstLoadDv[0]['isinbox'] : '0';
            $this->view->isrecover = isset($this->view->firstLoadDv[0]['isrecover']) ? $this->view->firstLoadDv[0]['isrecover'] : '0';

            $this->view->unreadLoadDv = Arr::multidimensional_list($this->view->firstLoadDv, array('isread' => '0'));

            $this->view->firstLoadDv = Arr::groupByArrayOnlyRows($this->view->firstLoadDv, 'timegroupname');
            $this->view->unreadLoadDv = Arr::groupByArrayOnlyRows($this->view->unreadLoadDv, 'timegroupname');
        }


        $this->view->firstLoadContent = array();/*  isset($result['result']) ? $result['result'] : array(); */

        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->menu = $this->view->renderPrint('/mail/menu', self::$viewName);

        $this->view->list = $this->view->renderPrint('/mail/list', self::$viewName);
        $this->view->unreadlist = $this->view->renderPrint('/mail/unreadlist', self::$viewName);

        $this->view->content = $this->view->renderPrint('/mail/content', self::$viewName);

        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('/mail/index', self::$viewName);
            $this->view->render('footer');
        } else {
            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/mail/index', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
        }
    }

    public function mailFolder() {

        $this->view->unreadLoadDv = array();
        $this->view->uniqId = Input::post('uniqId');
        $this->view->search = Input::post('search');
        $this->view->otherUniqId = getUID();
        $this->view->dataviewid = Input::post('dataviewid');
        $this->view->isforward = ($this->view->dataviewid === '1571206999387') ? '1' : '0';
        $this->view->orderby = (Input::post('orderBy')) ? Input::post('orderBy') : 'desc';

        if (Input::postCheck('search') && !Input::isEmpty('search')) {
            $this->view->firstLoadDv = $this->model->fncRunDataview($this->view->dataviewid, 'searchfield', 'like', $this->view->search, '', 'createddate', $this->view->orderby);
        } else {
            $this->view->firstLoadDv = $this->model->fncRunDataview($this->view->dataviewid, '', '', '', '', 'createddate', $this->view->orderby);
        }

        $this->view->isinbox = '0';
        $this->view->isrecover = '0';

        if (isset($this->view->firstLoadDv[0]) && $this->view->firstLoadDv[0]['timegroupname']) {

            $this->view->isinbox = isset($this->view->firstLoadDv[0]['isinbox']) ? $this->view->firstLoadDv[0]['isinbox'] : '0';
            $this->view->isrecover = isset($this->view->firstLoadDv[0]['isrecover']) ? $this->view->firstLoadDv[0]['isrecover'] : '0';

            $this->view->unreadLoadDv = Arr::multidimensional_list($this->view->firstLoadDv, array('isread' => '0'));

            $this->view->firstLoadDv = Arr::groupByArrayOnlyRows($this->view->firstLoadDv, 'timegroupname');
            $this->view->unreadLoadDv = Arr::groupByArrayOnlyRows($this->view->unreadLoadDv, 'timegroupname');
        }

        (array) $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'isforward' => $this->view->isforward,
            'firstLoadDv' => $this->view->firstLoadDv,
            'isinbox' => $this->view->isinbox,
            'Html' => $this->view->renderPrint('/mail/list', self::$viewName),
            'Html2' => $this->view->renderPrint('/mail/unreadlist', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function mailFolderData() {

        (array) $unread = array();
        $this->view->uniqId = (Input::post('uniqId')) ? Input::post('uniqId') : getUID();
        $this->view->processCode = Input::post('getprocesscode');
        $this->view->selectedRow = Input::post('dataRow');
        $this->view->isForward = Input::post('isforward');

        includeLib('Utils/Functions');
        $result = Functions::runProcess($this->view->processCode, array('id' => $this->view->selectedRow['id']));

        $this->view->firstLoadContent = isset($result['result']) ? $result['result'] : array();

        if (isset($this->view->selectedRow['readprocesscode']) && $this->view->selectedRow['readprocesscode']) {
            $unread = Functions::runProcess($this->view->selectedRow['readprocesscode'], array('id' => $this->view->selectedRow['recipientid']));
        }

        (array) $this->view->recipentList = isset($this->view->firstLoadContent['mm_recipient_get_list']) ? $this->view->firstLoadContent['mm_recipient_get_list'] : array();
        $html = $this->view->renderPrint('/mail/content', self::$viewName);

        if (isset($this->view->firstLoadContent['issent']) && $this->view->firstLoadContent['issent'] === '0') {
            $this->view->isdraft = '1';


            includeLib('Utils/Functions');
            $result = Functions::runProcess('MM_USER_SIGNATURE_LIST_004', array('userid' => Ue::sessionUserId()));
            $this->view->signature = isset($result['result']['signature']) ? $result['result']['signature'] : '';

            //Batbilguun өөрчлөв. :191 дээр Str::htmltotext авсан
            $this->view->firstLoadContent['body'] = $this->view->firstLoadContent['body'];

            $this->view->recipentList = $this->view->firstLoadContent['mm_recipient_get_list'];
            $html = $this->view->renderPrint('/mail/forward', self::$viewName);
        }

        $response = array(
            'status' => 'success',
            'readprocess' => $unread,
            'Html' => $html,
            'recipentList' => $this->view->recipentList,
            'isforward' => isset($this->view->firstLoadContent['issent']) ? $this->view->firstLoadContent['issent'] : '0'
        );

        echo json_encode($response);
    }

    public function sendmail($type = '0') {
        $response = $this->model->govsendMailModel($type);
        echo json_encode($response);
    }

    public function runProcess() {
        $response = $this->model->govrunProcessModel();
        echo json_encode($response);
    }

    public function reloadmenu() {

        $this->view->uniqId = Input::post('uniqId');
        $this->view->mainData = $this->model->fncRunDataview("1571129513478852");
        $firstLoad = Arr::multidimensional_search($this->view->mainData, array('status' => 'active'));

        if (isset($this->view->firstLoadDv[0]) && $this->view->firstLoadDv[0]['getprocesscode']) {

            includeLib('Utils/Functions');
            $result = Functions::runProcess($this->view->firstLoadDv[0]['getprocesscode'], array('id' => $this->view->firstLoadDv[0]['id']));

            $this->view->firstLoadDv = Arr::groupByArrayOnlyRows($this->view->firstLoadDv, 'timegroupname');
        }

        $this->view->firstLoadContent = array(); //isset($result['result']) ? $result['result'] : array();

        (array) $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('/mail/menu', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }

    public function printContent($processCodeDec) {
        $processCode = Crypt::decrypt($processCodeDec);
        $tempArr = explode('{mainId}=', $processCode);

        includeLib('Utils/Functions');
        $result = Functions::runProcess($tempArr[0], array('id' => $tempArr[1]));
        $this->view->firstLoadContent = isset($result['result']) ? $result['result'] : array();

        $html = '<b>' . $this->view->firstLoadContent['subject'] . '</b><br>'
                . '<div style="font-size:14px; color:gray; padding:18px;">'
                . '<div>' . html_entity_decode($this->view->firstLoadContent['body']) . '</div>'
                . '</div>';

        $this->view->content = $html;
        $this->view->render('/mail/print', self::$viewName);
    }

    public function replyForward() {

        (array) $unread = array();
        $this->view->uniqId = Input::post('uniqId');
        $this->view->otherUniqId = getUID();
        $this->view->selectedRow = Input::post('dataRow');
        $this->view->type = Input::post('type');

        $this->view->id = $this->view->selectedRow['id'];
        $this->view->processCode = (Input::post('processCode')) ? Input::post('processCode') : $this->view->selectedRow['getprocesscode'];

        includeLib('Utils/Functions');
        $result = Functions::runProcess('MM_USER_SIGNATURE_LIST_004', array('userid' => Ue::sessionUserId()));
        $this->view->signature = isset($result['result']['signature']) ? $result['result']['signature'] : '';

        $result = Functions::runProcess($this->view->processCode, array('id' => $this->view->id));

        $this->view->iscanceled = true;
        $this->view->firstLoadContent = isset($result['result']) ? $result['result'] : array();

        $this->view->firstLoadContent['subject'] = ($this->view->type == 'r') ? 'RE: ' . $this->view->firstLoadContent['subject'] : 'FW: ' . $this->view->firstLoadContent['subject'];
        $this->view->firstLoadContent['mm_content_get_list'] = ($this->view->type == 'r') ? array() : $result['result']['mm_content_get_list'];
        $this->view->firstLoadContent['body'] = '<!DOCTYPE html>
        <html>
            <head></head>
            <body>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br> ' . $this->view->signature . '
                <hr>
                <div><strong>' . Lang::line('mail_from') . ': </strong>' . $this->view->firstLoadContent['personname'] . '</div>
                <div><strong>' . Lang::line('mail_date') . ': </strong> ' . Lang::line(Str::sanitize($this->view->firstLoadContent['dday'])) . ', ' . $this->view->firstLoadContent['createddate'] . ' ' . $this->view->firstLoadContent['timer'] . '</div>
                <div><strong>' . Lang::line('mail_to') . ': </strong> ' . $this->view->firstLoadContent['receiver'] . '</div>
                <br>
                ' . $this->view->firstLoadContent['body'] . '
            </body>
        </html>';

        (array) $this->view->recipentList = ($this->view->type == 'r') ? isset($this->view->firstLoadContent['mm_reply_recipient_get_list']) ? $this->view->firstLoadContent['mm_reply_recipient_get_list'] : array() : array();
        $html = $this->view->renderPrint('/mail/forward', self::$viewName);

        $response = array(
            'status' => 'success',
            'readprocess' => $unread,
            'Html' => $html,
            'recipentList' => $this->view->recipentList,
            'isforward' => $this->view->firstLoadContent
        );

        echo json_encode($response);
    }

    /*
     * Intranet
     */

    public function intranet($metadataId = '1565319131590210', $showbtn = false) {

        if (!is_ajax_request()) {

            $this->view->css = AssetNew::metaCss();
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');

            /*
              $this->view->css = AssetNew::metaCss();
              $this->view->js = AssetNew::metaOtherJs();

              $this->view->fullUrlJs = AssetNew::amChartJs(); */
        }

        $this->view->uniqId = getUID();
        $this->view->postParams = Input::post('selectedRow');

        if (isset($_POST['paramData'][3]['value'])) {
            $this->view->drillId = $_POST['paramData'][3]['value'];
        }

        $this->view->title = 'Олон нийт';
        $this->view->showSearchBtn = $showbtn;

        if (Config::getFromCache('intranetDvId')) {
            $this->view->showSearchBtn = true;
        }

        $this->view->level2MetaDataId = '1564662649211486';
        $this->view->metaDataId = $metadataId;
        $this->view->menuData = $this->model->getIntranedSidebarModel("", "", '1', $this->view->metaDataId);

        $this->view->intranetModals = $this->view->renderPrint('/intranet/sub/modals', self::$viewName);
        $this->view->defaultJs = $this->view->renderPrint('/intranet/sub/defaultJs', self::$viewName);
        $this->view->defaultCss = $this->view->renderPrint('/intranet/sub/defaultCss', self::$viewName);

        $this->view->menu = $this->view->renderPrint('/intranet/menu', self::$viewName);
        $this->view->webmail = $this->view->renderPrint('/intranet/webmail', self::$viewName);
        $this->view->ajax = false;

        if (!is_ajax_request()) {
            $this->view->ajax = true;

            $this->view->render('header');
            $this->view->render('/intranet/index', self::$viewName);
            $this->view->render('footer');
        } else {
            $response = array(
                'Html' => $this->view->renderPrint('/intranet/index', self::$viewName),
                'Title' => $this->view->title,
                'uniqId' => $this->view->uniqId
            );
            echo json_encode($response);
        }
    }

    public function getsecondContent() {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);

        $this->view->secondMetaDataId = '1564662649211486';
        $this->view->mainRow = Input::post('mainRow');

        if (isset($this->view->mainRow['dvmetadataid']) && $this->view->mainRow['dvmetadataid']) {
            $this->view->secondMetaDataId = $this->view->mainRow['dvmetadataid'];
        }

        $this->view->uniqId = Input::post('uniqId');
        $this->view->otherUniqId = getUID();
        $this->view->searchValue = Input::post('searchValue');
        $this->view->isEdit = (Input::post('id') && Input::post('id') == '3') ? false : true;

        $paramFilter = array(
            'sessionuserid' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            ),
            'filterStartDate' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionFiscalPeriodStartDate()
                )
            ),
            'filterEndDate' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionFiscalPeriodEndDate()
                )
            ),
            'ispin' => array(
                array(
                    'operator' => '=',
                    'operand' => '0'
                )
            )
        );

        $paramIsPintFilter = array(
            'sessionuserid' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionUserId()
                )
            ),
            'filterStartDate' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionFiscalPeriodStartDate()
                )
            ),
            'filterEndDate' => array(
                array(
                    'operator' => '=',
                    'operand' => Ue::sessionFiscalPeriodEndDate()
                )
            ),
            'ispin' => array(
                array(
                    'operator' => '=',
                    'operand' => '1'
                )
            )
        );

        if (Input::post('unread')) {
            $cr['isread'][] = array(
                'operator' => '=',
                'operand' => '0'
            );

            $paramFilter = array_merge($paramFilter, $cr);
            $paramIsPintFilter = array_merge($paramIsPintFilter, $cr);
        }

        // var_dump($paramFilter, $paramIsPintFilter);
        // die;

        $orderBy = (isset($this->view->mainRow['isorder']) && $this->view->mainRow['isorder'] == '0') ? '' : 'createddate';

        if (Input::post('offset') && Input::isEmpty('offset') == false) {

            if (Input::post('id') && Input::post('categoryId') == '0') {
                $this->view->firstLoadDv = $this->model->fncRunDataview($this->view->secondMetaDataId, "typeId", "=", Input::post('id'), $paramFilter, $orderBy, 'desc', '0', true);
            } else {
                $this->view->firstLoadDv = $this->model->fncRunDataview($this->view->secondMetaDataId, "categoryId", "=", Input::post('categoryId'), $paramFilter, $orderBy, 'desc', '0', true);
            }

            if (Input::post('searchValue')) {
                $this->view->firstLoadDv = $this->model->fncRunDataview($this->view->secondMetaDataId, "description", "like", Input::post('searchValue'), $paramFilter, $orderBy, 'desc', '0', true);
            }

            unset($this->view->firstLoadDv['paging']);
            unset($this->view->firstLoadDv['aggregatecolumns']);

            $firstLoadDv = isset($this->view->firstLoadDv['result']) ? $this->view->firstLoadDv['result'] : array();
            (array) $response = $firstLoadDv;
        } else {

            if (Input::post('id') && Input::post('categoryId') == '0') {
                $this->view->firstLoadDv = $this->model->fncRunDataview($this->view->secondMetaDataId, "typeId", "=", Input::post('id'), $paramFilter, $orderBy, 'desc', '0', true);
                $this->view->ispinpostDv = $this->model->fncRunDataview($this->view->secondMetaDataId, "typeId", "=", Input::post('id'), $paramIsPintFilter, $orderBy, 'desc', '0', true);
            } else {
                $this->view->firstLoadDv = $this->model->fncRunDataview($this->view->secondMetaDataId, "categoryId", "=", Input::post('categoryId'), $paramFilter, $orderBy, 'desc', '0', true);
                $this->view->ispinpostDv = $this->model->fncRunDataview($this->view->secondMetaDataId, "categoryId", "=", Input::post('categoryId'), $paramIsPintFilter, $orderBy, 'desc', '0', true);
            }

            if (Input::post('searchValue')) {
                $this->view->firstLoadDv = $this->model->fncRunDataview($this->view->secondMetaDataId, "description", "like", Input::post('searchValue'), $paramFilter, $orderBy, 'desc', '0', true);
                $this->view->ispinpostDv = $this->model->fncRunDataview($this->view->secondMetaDataId, "description", "like", Input::post('searchValue'), $paramIsPintFilter, $orderBy, 'desc', '0', true);
            }

            $totalcount = isset($this->view->firstLoadDv['paging']['totalcount']) ? $this->view->firstLoadDv['paging']['totalcount'] : '0';

            unset($this->view->firstLoadDv['paging']);
            unset($this->view->firstLoadDv['aggregatecolumns']);

            unset($this->view->ispinpostDv['paging']);
            unset($this->view->ispinpostDv['aggregatecolumns']);

            $firstLoadDv = isset($this->view->firstLoadDv['result']) ? $this->view->firstLoadDv['result'] : array();
            $ispinpostDv = isset($this->view->ispinpostDv['result']) ? $this->view->ispinpostDv['result'] : array();

            $this->view->ispinpostDv = isset($ispinpostDv) ? $ispinpostDv : array();
            $this->view->firstLoadDv = Arr::groupByArrayOnlyRows(isset($firstLoadDv) ? $firstLoadDv : array(), 'timegroupname');

            (array) $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'totalcount' => $totalcount,
                'Html' => $this->view->renderPrint('/intranet/second', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
        }

        echo json_encode($response);
    }

    public function contentByIntranet() {

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('default_socket_timeout', 30000);
        $this->view->currentDate = Date::currentDate();
        $this->view->uniqId = Input::post('uniqId');
        $this->view->selectedRow = Input::post('dataRow');
        $this->view->id = Input::post('id') ? Input::post('id') : $this->view->selectedRow['id'];

        $this->view->isEdit = (isset($this->view->selectedRow['typeid']) && $this->view->selectedRow['typeid'] == '3' && isset($this->view->selectedRow['canedit']) && $this->view->selectedRow['canedit'] == '0') ? false : true;
        $this->view->isFinish = false;

        $this->view->data = $this->model->getIntranetContentDtlModel($this->view->id);
        $this->view->notvote = $this->model->fncRunDataview('1608135920844316', 'postid', '=', $this->view->id);
        (array) $response = array(
            'Title' => '',
            'Width' => '700',
            'data' => $this->view->data,
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('/intranet/sub/content', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function printContentNewWindow($id) {
        $data = $this->model->getIntranetContentDtlModel($id);
        //print_array($data); die;
        $html = '';

        if (isset($data) && $data) {
            $html = '<b style="font: bold 16px Arial;">' . $data['description'] . '</b><br>'
                    . '<div style="font-size:14px; color:gray; padding:18px;">'
                    . '<div>' . html_entity_decode($data['longdescr']) . '</div>'
                    . '</div>';
        }

        if (isset($data['scl_posts_result_list'][0]) && $data['scl_posts_result_list'][0]) {
            $html .= ''
                    . '<center><table style="width: 80%;" border="1">'
                    . '<tbody>'
                    . '<tr>'
                    . '<td>Нийт санал</td>'
                    . '<td><b>' . $data['scl_posts_result_list'][0]['votedcount'] . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>Дуусах хугацаа</td>'
                    . '<td><b>' . $data['scl_posts_result_list'][0]['enddate'] . '</b></td>'
                    . '</tr>'
                    . '<tr>'
                    . '<td>Үлдсэн хоног</td>'
                    . '<td><b>' . $data['scl_posts_result_list'][0]['leftdays'] . '</b></td>'
                    . '</tr>'
                    . '</tbody>'
                    . '</table></center><center><p class="votetitle" style="font-size: 17px;">Санал асуулгын үр дүнгийн дэлгэрэнгүй</p></center>';

            if (isset($data['scl_posts_result_list'][0]['scl_result_question_list']) && $data['scl_posts_result_list'][0]['scl_result_question_list']) {
                $maIndex = 1;
                foreach ($data['scl_posts_result_list'][0]['scl_result_question_list'] as $key => $questions) {

                    $html .= '<p style="font-size: 15px; font-weight: bold">' . $maIndex . '. ' . $questions['questiontxt'] . '</p>';
                    $index = 1;

                    foreach ($questions['scl_post_result_answer_list'] as $akey => $answers) {
                        $peopleListHtml = '';
                        if ($answers['answertxt'] !== 'Бусад') {
                            if ($answers['scl_result_user_list']) {
                                foreach ($answers['scl_result_user_list'] as $pkey => $people) {
                                    $peopleListHtml .= $people['personname'] . '<br>';
                                }
                            }
                            $html .= '<table style="width: 100%;">'
                                . '<tbody>'
                                . '<tr style="border-bottom: 1px solid #d8d8d8;">'
                                . '<td width="5%">' . $index . '</td>'
                                . '<td width="5%"><b>' . $answers['answerpercent'] . '%</b></td>'
                                . '<td width="30%">' . $answers['answertxt'] . '</td>'
                                . '<td width="60%" style="text-align: left;">' . $peopleListHtml . '</td>'
                                . '</tr>'
                                . '</tbody>'
                                . '</table><br>';    
                        } else {
                            
                            if ($answers['scl_result_user_list']) {
                                foreach ($answers['scl_result_user_list'] as $pkey => $people) {
                                    $html .= '<table style="width: 100%;">'
                                        . '<tbody>'
                                        . '<tr style="border-bottom: 1px solid #d8d8d8;">'
                                        . '<td width="30%" colspan="2">' . $people['personname']  . '</td>'
                                        . '<td width="70%" colspan="2">' . $people['answerdescription'] . '</td>'
                                        . '</tr>'
                                        . '</tbody>'
                                        . '</table><br>';  
                                }
                            }  
                        }
                        
                        $index++;
                    }

                    $maIndex++;
                }
            }
        }
        $this->view->content = $html;
        $this->view->render('/mail/print', self::$viewName);
    }

    public function saveIntanetComment() {

        $data = $this->model->saveIntanetCommentModel();
        $this->view->uniqId = Input::post('uniqId');
        
        includeLib('Utils/Functions');
        $postsData = Functions::runProcess('NTR_SOCIAL_COMMENT_POST_LIST_004', array('id' => $data['postId']));
        
        (array) $this->view->treeData = array();
        if (issetParamArray($postsData['result']['ntr_social_comment_list'])) {
            $this->view->post = $postsData['result'];
            $this->view->treeData = Arr::buildTree($postsData['result']['ntr_social_comment_list'], $parentId = 0, 'id', 'parentid');
            
            if ($this->view->treeData) {
                $this->view->uniqId = Input::post('uniqId');
                $data['html'] = $this->view->renderPrint('/social/comment', self::$viewName);
                $data['data'] = $postsData;
            }
        }
        echo json_encode($data);
    }

    public function deleteComment() {
        $data = $this->model->deleteCommentModel();
        echo json_encode($data);
    }

    public function saveComment() {
        $data = $this->model->saveCommentModel();
        echo json_encode($data);
    }

    public function getLikeInformation() {
        (array) $response = array();
        $processName = '';

        try {
            $commentId = Input::post('commentId');
            $targetType = Input::post('targetType');

            if ($targetType == 'post') {
                throw new Exception("post С‚РѕС…РёСЂРіРѕРѕ С…РёР№РіРґСЌСЌРіТЇР№ Р±Р°Р№РЅР°.");
            } else if ($targetType == 2) {
                $processCode = 'SCL_POSTS_COMMENT_GET_LIST_004';
            } else if ($targetType == 3) {
                $processCode = 'SCL_POSTS_COMMENT_REPLY_LIST_004';
            }

            includeLib('Utils/Functions');
            $result = Functions::runProcess($processCode, array('id' => $commentId));

            if ($result) {

                if ($result['status'] == 'success') {
                    $response = $result['result'];
                }

                $response = $result;
            }
        } catch (Exception $ex) {
            (array) $response = array();

            $response['status'] = 'warning';
            $response['text'] = $ex->getMessage();
        }


        echo json_encode($response);
    }

    public function getRealTimeData() {
        $this->view->id = Input::post('id');
        $response = $this->model->getIntranetContentDtlModel($this->view->id);
        echo json_encode($response);
    }

    public function getIntranetPhotoLibrary() {
        (array) $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'));

        includeLib('Utils/Functions');
        $result = Functions::runProcess('SCL_POSTS_PHOTO_ALBUM_LIST_004', array('id' => Input::post('postId')));
        if ($result) {
            if ($result['status'] == 'success') {
                $response = $result['result'];
            }
        }

        echo json_encode($response);
    }

    public function getIntranetFileLibrary() {
        (array) $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'));

        includeLib('Utils/Functions');
        $result = Functions::runProcess('SCL_FILES_GET_LIST_004', array('id' => Input::post('postId')));
        if ($result) {
            if ($result['status'] == 'success') {
                $response = $result['result'];
            }
        }

        echo json_encode($response);
    }

    public function getIntranetVoting() {
        (array) $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'));

        includeLib('Utils/Functions');
        $result = Functions::runProcess('SCL_POSTS_SANAL_LIST_004', array('id' => Input::post('postId')));

        if ($result && $result['status'] == 'success') {
            $response = $result['result'];
        }

        echo json_encode($response);
    }

    public function getIntranetPollAttept() {
        $data = $this->model->getPollAtteptModel();
        echo json_encode($data);
    }

    public function getIntranetPollResult() {
        (array) $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'));

        includeLib('Utils/Functions');
        $result = Functions::runProcess('SCL_SANAL_GET_LIST_004', array('id' => Input::post('postId')));
        if ($result) {
            if ($result['status'] == 'success') {
                $response = $result['result'];
            }
        }

        echo json_encode($response);
    }

    public function reloadIntranetMenu() {

        $this->view->uniqId = Input::post('uniqId');
        $this->view->menuData = $this->model->getIntranedSidebarModel("", "", '1', Input::post('metaDataId'));

        (array) $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('/intranet/menu', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function rightsidebarWidget() {
        $this->view->uniqId = Input::post('uniqId');
        $currentDate = Date::currentDate('Y-m-d');
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

        $this->view->layoutPositionArr['pos_7_dvid'] = "1568889882063";
        $this->view->layoutPositionArr['pos_8_0_dvid'] = "1568889881645";
        $this->view->layoutPositionArr['pos_8_1_dvid'] = "1568889881884";
        $this->view->layoutPositionArr['pos_9_0_dvid'] = "1568889882293";
        $this->view->layoutPositionArr['pos_9_1_dvid'] = "1568889882478";

        $this->view->layoutPositionArr['pos_7'] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_7_dvid'], 'filteruserid', '=', Ue::sessionUserId(), $paramFilter, "", "", "1");

        $this->view->layoutPositionArr['pos_8'] = array();
        $this->view->layoutPositionArr['pos_8'][0] = array(); //$this->model->fncRunDataview($this->view->layoutPositionArr['pos_8_0_dvid'], 'filteruserid', '=', Ue::sessionUserId(), $paramFilter, "", "", "1");
        $this->view->layoutPositionArr['pos_8'][1] = array(); //$this->model->fncRunDataview($this->view->layoutPositionArr['pos_8_1_dvid'], 'filteruserid', '=', Ue::sessionUserId(), $paramFilter, "", "", "1");

        $this->view->layoutPositionArr['pos_9'] = array();
        $this->view->layoutPositionArr['pos_9'][0] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_9_0_dvid'], 'filteruserid', '=', Ue::sessionUserId(), $paramFilter, "", "", "1");
        $this->view->layoutPositionArr['pos_9'][1] = $this->model->fncRunDataview($this->view->layoutPositionArr['pos_9_1_dvid'], 'filteruserid', '=', Ue::sessionUserId(), $paramFilter, "", "", "1");

        $this->view->mainData = $this->model->fncRunDataview("1567754961182", 'filteruserid', '=', Ue::sessionUserId());

        (array) $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('/intranet/sub/widget', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function getIntranetSubMenuRender() {
        $this->view->postData = Input::postData();
        $this->view->uniqId = Input::post('uniqId');
        $this->view->menuData = $this->model->getIntranedSidebarModel($this->view->postData['id'], $this->view->postData, '0', Input::post('metaDataId'));

        echo json_encode(array('treeData' => $this->view->menuData));
    }

    public function getIntranetComments() {
        $this->view->uniqId = Input::post('uniqId');
        $this->view->postId = Input::post('postId');
        $this->view->data = $this->model->getIntranetCommentModel($this->view->postId);

        (array) $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'data' => $this->view->data,
            'Html' => $this->view->renderPrint('/intranet/sub/comments', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function groups() {

        $this->view->uniqId = getUID();
        $this->view->groups = $this->model->fncRunDataview("1572247923506234", 'createduserid', '=', Ue::sessionUserId());
        $this->view->groupOrderPosition = $this->model->fncRunDataview("1572259703142520");

        (array) $response = array(
            'Title' => 'Нэмэх',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('/groups/popup', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function members() {
        $data = $this->model->getMemberDvModel();
        echo json_encode($data);
        die;
    }

    public function addGroups() {
        $response = $this->model->saveGroupModel();
        echo json_encode($response);
    }

    public function editGroups() {
        $response = $this->model->editGroupModel();
        echo json_encode($response);
    }

    public function reloadGroups() {
        $result = $this->model->getGroupDvModel();
        echo json_encode($result);
    }

    public function deleteGroup() {
        $id = Input::post('id');
        includeLib('Utils/Functions');
        $result = Functions::runProcess('INT_GROUPS_DV_005', array('id' => $id));
        echo json_encode($result);
    }

    public function deleteMember() {
        $id = Input::post('id');
        includeLib('Utils/Functions');
        $result = Functions::runProcess('INT_GROUP_MEMBERS_DV_005', array('id' => $id));
        echo json_encode($result);
    }

    public function addMember() {
        $response = $this->model->saveMemberModel();
        echo json_encode($response);
    }

    public function addForm() {

        $this->view->uniqId = Input::post('uniqId');
        $this->view->otherUniqId = getUID();
        $this->view->selectedRow = Input::post('dataRow');
        $this->view->groupOption = array();
        $this->view->order = '0';
        $this->view->isEdit = false;
        $this->view->saveProcessCode = issetParam($this->view->selectedRow['windowtypeid']) == '9988' ? 'NTR_SOCIAL_GROUP_DV_001' : issetParam($this->view->selectedRow['createprocesscode']);

        try {
            /*
              if (issetParam($this->view->selectedRow['posttypeid']) || empty($this->view->selectedRow['posttypeid'])) {
              throw new Exception("Т®РЅРґСЃСЌРЅ С†СЌСЃРЅСЌСЌСЃ СЃРѕРЅРіРѕР»С‚ С…РёР№РіРґСЌСЌРіТЇР№ Р±Р°Р№РЅР°!");
              }
             */
            $this->view->groupOption = $this->model->fncRunDataview("1572361912944014", 'posttypeid', '=', $this->view->selectedRow['posttypeid']);

            $this->view->addinDiv = $this->view->renderPrint('/intranet/sub/addinDiv', self::$viewName);

            (array) $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'option' => $this->view->groupOption,
                'Html' => $this->view->renderPrint('/intranet/sub/addForm', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
        } catch (Exception $ex) {
            (array) $response = array();

            $response['status'] = 'warning';
            $response['text'] = $ex->getMessage();
        }

        echo json_encode($response);
    }

    public function addinDv() {

        $this->view->uniqId = Input::post('uniqId');
        $this->view->otherUniqId = Input::post('otherUniqId');
        $this->view->order = Input::post('order');

        (array) $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('/intranet/sub/addinDiv', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }

    /*
     * Documentation
     */

    public function documentation($metaDataId = '1572596811089741') {

        $this->view->uniqId = getUID();
        $this->view->menu = $this->model->fncRunDataview($metaDataId);

        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');

        $this->view->defaultJs = $this->view->renderPrint('/documentation/js', self::$viewName);


        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('/documentation/index', self::$viewName);
            $this->view->render('footer');
        } else {
            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/documentation/index', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
        }
    }

    public function documentationContent($metaDataId = '1572596822675') {

        $this->view->uniqId = Input::post('uniqId');
        $this->view->firstLoadDv = $this->model->fncRunDataview($metaDataId, "materialtypeid", "=", Input::post('id'), "", "", "");

        /*
         * OBAMA boliulaw
         */
        
        /*
        if (isset($this->view->firstLoadDv['0']['groupname'])) {
            $this->view->firstLoadDv = Arr::groupByArrayOnlyRows($this->view->firstLoadDv, 'groupname');
        }
        */

        (array) $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('/documentation/content', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function readContent() {
        $data = $this->model->readContentModel();
        echo json_encode($data);
    }

    public function deletePost() {
        $data = $this->model->deletePostModel();
        echo json_encode($data);
    }

    public function editPost() {

        $this->view->uniqId = Input::post('uniqId');
        $this->view->otherUniqId = getUID();
        $this->view->selectedRow = Input::post('dataRow');
        $this->view->paramData = Input::post('selectedRow');

        $this->view->issingle = Input::post('issingle');

        $this->view->groupOption = array();
        $this->view->order = '0';

        $this->view->mainData = $this->model->editPostModel($this->view->paramData);
        /* var_dump($this->view->mainData); die; */
        $this->view->isEdit = true;

        try {
            /*
              if ((issetParam($this->view->selectedRow['postcategoryid']) && issetParam($this->view->selectedRow['posttypeid']))) {
              throw new Exception("Т®РЅРґСЃСЌРЅ С†СЌСЃРЅСЌСЌСЃ СЃРѕРЅРіРѕР»С‚ С…РёР№РіРґСЌСЌРіТЇР№ Р±Р°Р№РЅР°!");
              } */

            if (issetParam($this->view->selectedRow['postcategoryid']) || empty($this->view->selectedRow['postcategoryid'])) {
                $this->view->groupOption = $this->model->fncRunDataview("1572361912944014", 'posttypeid', '=', $this->view->selectedRow['posttypeid']);
            }

            if (issetParamArray($this->view->mainData['int_poll_question_dv'])) {
                $this->view->addinDiv = $this->view->renderPrint('/intranet/sub/editinDiv', self::$viewName);
            } else {
                $this->view->addinDiv = $this->view->renderPrint('/intranet/sub/addinDiv', self::$viewName);
            }

            if (isset($this->view->paramData['postcategoryid'])) {
                $this->view->selectedRow['postcategoryid'] = $this->view->paramData['postcategoryid'];
            }

            $this->view->saveProcessCode = issetParam($this->view->selectedRow['windowtypeid']) == '9988' ? 'NTR_SOCIAL_GROUP_DV_001' : issetParam($this->view->selectedRow['createprocesscode']);

            (array) $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'mainData' => $this->view->mainData,
                'Html' => $this->view->renderPrint('/intranet/sub/addForm', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
        } catch (Exception $ex) {
            (array) $response = array();

            $response['status'] = 'warning';
            $response['text'] = $ex->getMessage();
        }

        echo json_encode($response);
    }

    public function savePost() {
        $response = $this->model->savePostModel();
        echo json_encode($response);
    }

    public function saveSinglePost() {
        $response = $this->model->saveSinglePostModel();
        $response['Html'] = '';
        if (issetParam($response['result']) && issetParam($response['result']['id'])) {
            includeLib('Utils/Functions');
            $postsData = Functions::runProcess('NTR_SOCIAL_PUBLIC_LIST_004', array('pageNumber' => '1', 'pageRows' => '1', 'id' => $response['result']['id'], 'groupid' => $response['result']['groupid']));

            $this->view->postsData = issetParamArray($postsData['result']['ntr_scl_social_main_list']);
            $this->view->uniqId = Input::post('uniqId');
            $response['Html'] = $this->view->renderPrint('/social/posts', self::$viewName);
        }

        echo json_encode($response);
    }

    public function allDownloadContent() {
        $downloadFilePaths = Input::post('dataFiles');

        $tempdir = UPLOADPATH . 'zip';

        if (!is_dir($tempdir)) {
            mkdir($tempdir, 0777);
        } else {
            $currentHour = (int) Date::currentDate('H');
            /* Оройны 18 цагаас 19 цагийн хооронд шалгаж өмнө нь үүссэн файлуудыг устгана */
            if ($currentHour >= 18 && $currentHour <= 19) {
                $files = glob($tempdir . '/*');
                $now = time();
                $day = 0.5;

                foreach ($files as $file) {
                    if (is_file($file) && ($now - filemtime($file) >= 60 * 60 * 24 * $day)) {
                        @unlink($file);
                    }
                }
            }
        }

        $zip = new ZipArchive;
        $zipFilename = $tempdir . '/documents_' . getUID() . '.zip';
        $zip->open($zipFilename, ZipArchive::CREATE);
        $currentDate = Date::currentDate();
        $userId = Ue::sessionUserId();
        foreach ($downloadFilePaths as $document) {
            if (!is_dir($document['physicalpath'])) {
                $filename = explode('/', $document['physicalpath']);
                $name = end($filename);
                $zip->addFile($document['physicalpath'], $name);
                try {
                    $data = array(
                        'CONTENT_USER_ID' => getUID(),
                        'CONTENT_ID' => $document['id'],
                        'USER_ID' => $userId,
                        'READ_DATE' => $currentDate,
                        'CREATED_DATE' => $currentDate,
                        'TYPE' => '1',
                        'CREATED_USER_ID' => $userId
                    );

                    $this->db->AutoExecute('SCL_CONTENT_USER', $data);
                } catch (Exception $ex) {
                    
                }
            }
        }

        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=documents.zip');
        header('Content-Length: ' . filesize($zipFilename));
        readfile($zipFilename);
    }

    public function saveLike($static = '0') {
        $data = $this->model->saveLikeModel($static);
        echo json_encode($data);
    }

    public function savePoll() {
        $response = $this->model->savePollModel();
        echo json_encode($response);
    }

    public function editAttendance() {
        $this->view->selectedRow = Input::post('selectedRow');

        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_SAID_IRTS_UPDATE_GET_DV_004', array('id' => $this->view->selectedRow['id']));
        $this->view->statusOption = $this->model->fncRunDataview("1560471715605711");
        $this->view->data = isset($result['result']) ? $result['result'] : array();

        includeLib('Compress/Compression');
        $this->view->mainData = Compression::encode_string_array($this->view->data);

        $this->view->title = 'Ирц засах';
        $this->view->uniqId = getUID();

        if (is_ajax_request()) {
            $response = array(
                'Html' => $this->view->renderPrint('/attendance/edit', self::$viewName),
                'Title' => $this->view->title,
                'uniqId' => $this->view->uniqId
            );
            echo json_encode($response);
        } else {
            $this->view->render('header');
            $this->view->render('/attendance/edit', self::$viewName);
            $this->view->render('footer');
        }
    }

    public function saveAttendanceProcess() {
        $postData = Input::postData();

        includeLib('Compress/Compression');
        $mainData = Compression::decode_string_array($postData['mainData']);

        (array) $CMS_MEETING_PARTICIPANT_DV = array();
        (array) $CMS_OTHER_6_GET_LIST = array();

        foreach ($postData['employeeKeyId'] as $key => $row) {
            $temp = array(
                'id' => $postData['id'][$key],
                'bookId' => $postData['bookId'][$key],
                'employeeKeyId' => $postData['employeeKeyId'][$key],
                'time1' => $postData['time1'][$key],
                'time9' => $postData['time9'][$key],
                'time2' => $postData['time2'][$key],
                'time3' => $postData['time3'][$key],
                'time4' => $postData['time4'][$key],
                'time5' => $postData['time5'][$key],
                'wfmDescription' => $postData['wfmDescription'][$key],
                'wfmStatusId' => $postData['wfmStatusId'][$key],
                'participantRoleId' => $postData['participantRoleId'][$key],
                'orderNum' => $postData['orderNum'][$key],
            );

            array_push($CMS_MEETING_PARTICIPANT_DV, $temp);
        }

        foreach ($postData['otheremployeeKeyId'] as $key => $row) {
            $temp = array(
                'id' => $postData['otherId'][$key],
                'bookId' => $postData['otherbookId'][$key],
                'employeeKeyId' => $postData['otheremployeeKeyId'][$key],
                'time1' => $postData['othertime1'][$key],
                'time9' => $postData['othertime9'][$key],
                'time2' => $postData['othertime2'][$key],
                'time3' => $postData['othertime3'][$key],
                'time4' => $postData['othertime4'][$key],
                'time5' => $postData['othertime5'][$key],
                'wfmDescription' => $postData['otherwfmDescription'][$key],
                'wfmStatusId' => $postData['otherwfmStatusId'][$key],
                'participantRoleId' => $postData['otherparticipantRoleId'][$key],
                'orderNum' => $postData['otherorderNum'][$key],
            );
            array_push($CMS_OTHER_6_GET_LIST, $temp);
        }

        $params = array();

        foreach ($mainData as $key => $row) {
            if (!is_array($row)) {
                switch ($key) {
                    case 'name':
                    case 'starttime':
                    case 'endtime':
                    case 'duration':
                    case 'totalbreaktime':
                    case 'percentofattendance':

                        break;

                    default:
                        $params[$key] = $row;
                        break;
                }
            }
        }

        $params['name'] = $postData['name'];
        $params['starttime'] = $postData['startTime'];
        $params['endtime'] = $postData['endTime'];
        $params['duration'] = $postData['duration'];
        $params['totalbreaktime'] = $postData['totalBreakTime'];
        $params['percentofattendance'] = $postData['percent'];

        $params['CMS_MEETING_PARTICIPANT_DV'] = $CMS_MEETING_PARTICIPANT_DV;
        $params['CMS_OTHER_6_GET_LIST'] = $CMS_OTHER_6_GET_LIST;

        includeLib('Utils/Functions');
        $result = Functions::runProcess('CMS_SAID_IRST_UPDATE_DV_002', $params);

        if ($result['status'] === 'success') {
            $result['text'] = Lang::line('msg_save_success');
        }
        echo json_encode($result);
    }

    public function omsconferenceAddForm() {
        try {
            $this->view->uniqId = getUID();

            $this->view->paramData = array(); //Input::post('paramData');
            $this->view->isedit = Input::post('isedit');
            $this->view->id = Input::post('id');

            if ($this->view->id && $this->view->isedit) {
                includeLib('Utils/Functions');
                $data = Functions::runProcess('OMS_CONFERENCE_GET_DV_004', array('id' => $this->view->id));
            }

            $this->view->data = isset($data['result']) ? $data['result'] : array();

            $this->view->option = $this->model->fncRunDataview("1574750241012291");
            $this->view->optionType = $this->model->fncRunDataview("1580353549320637");
            $this->view->selectedIds = isset($this->view->data['oms_meta_dm_record_map']) ? Arr::groupByArrayOnlyKey($this->view->data['oms_meta_dm_record_map'], 'trgrecordid') : array();

            (array) $response = array(
                'Title' => Lang::line('ADD_CONFERENCE_001'),
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'data' => $this->view->data,
                'Html' => $this->view->renderPrint('/oms/addForm', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn'),
            );
            //$response = array_unique(array_merge($response, $this->view->paramData));
        } catch (Exception $ex) {
            (array) $response = array();

            $response['status'] = 'warning';
            $response['text'] = $ex->getMessage();
        }

        echo json_encode($response);
    }

    public function saveOmsConference() {
        $response = $this->model->saveOmsConferenceModel();
        echo json_encode($response);
    }

    //хурлын өрөөний захиалга
    public function omsMeeting() {
        $this->load->model('government', 'models/');
        $this->view->title = 'Хурлын календар';
        $this->view->uniqId = getUID();
        $this->view->metaDataId = '1568018176869';
        $this->view->data = array(); //$this->model->fncRunDataview($this->view->metaDataId);
        $this->view->searchRoom = $this->model->fncRunDataview("1574750241012291");
        $this->view->wfmStatusList = $this->model->fncRunDataview("1580193305168844");

        if (is_ajax_request()) {
            $this->view->isAjax = true;
            $response = array(
                'Html' => $this->view->renderPrint('/meeting/full_calendar', self::$viewName),
                'Title' => $this->view->title,
                'uniqId' => $this->view->uniqId
            );
            echo json_encode($response);
        } else {
            $this->view->isAjax = false;
            $this->view->css = AssetNew::metaCss();
            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
            $this->view->js = array_unique(array_merge(array(
                'custom/addon/admin/pages/scripts/app.js',
                'custom/gov/multiselect.js'
                            ), AssetNew::metaOtherJs()));

            $this->view->render('header');
            $this->view->render('/meeting/full_calendar', self::$viewName);
            $this->view->render('footer');
        }
    }

    public function getMeetingRoomDetail() {
        includeLib('Utils/Functions');
        $this->view->uniqId = getUID();
        $id = Input::post('id');
        $result = Functions::runProcess('OMS_MEETING_LIST_004', array('id' => $id));
        $data = Functions::runProcess('OMS_CONFERENCE_GET_DV_004', array('id' => $id));

        echo json_encode(array(
            'uniqId' => $this->view->uniqId,
            'close_btn' => Lang::line('close_btn'),
            'data' => $result['result'],
            'cdata' => $data['result']
        ));
    }

    public function downloadFile() {

        $readFile = Input::get('file');
        $fileName = Input::get('filename');
        //            $ext = substr($readFileUrl, strrpos($readFileUrl, '.') + 1);
        //            $fileName = 'VERITECH_ERP_FILE_' . getUID() . '.' . strtolower($ext);

        fileDownload($fileName, $readFile);
    }

    public function checkMeetingRoomSlot() {
        $postData = Input::postData();
        includeLib('Utils/Functions');

        $params['filterDate'] = $postData['date'];
        $params['filterTemplateId'] = $postData['roomId'];
        $params['filterStartDate'] = $postData['startDate'];
        $params['filterEndDate'] = $postData['endDate'];

        $result = Functions::runProcess('omsTimeUpdateGetDV_004', $params);

        echo json_encode($result['result']);
    }

    public function getSuggestedTime() {
        $postData = Input::postData();
        includeLib('Utils/Functions');

        $params['startDate'] = $postData['date'];
        $params['templateId'] = $postData['roomId'];

        $result = Functions::runProcess('OMS_ADD_CONFERENCE_GET_TIME_LIST_004', $params);

        if (isset($result['result']['timedtl'])) {
            $response = $result['result'];
        } else {

            (array) $response = array();
            $response['timedtl'] = array();
            $currentDate = Date::currentDate('Y-m-d');

            if ($postData['date'] == $currentDate) {

                $currentTime = Date::currentDate('H:i');

                $checkDate = $this->db->GetRow("SELECT
                                                    COUNT(T0.ID) COUNTT
                                                FROM
                                                    MMS_MEETING_BOOK T0
                                                WHERE
                                                    T0.BOOK_TYPE_ID = 2 
                                                    AND T0.TEMPLATE_ID = '" . $params['templateId'] . "'
                                                    AND TO_CHAR(T0.START_DATE, 'YYYY-MM-DD') = '$currentDate'");

                if ($checkDate['COUNTT'] == '0' && $currentTime < '17:30') {
                    $stime = ('08:30' < $currentTime) ? $currentTime : '08:30';
                    $result = array(array('stime' => $stime, 'etime' => '17:30', 'conftime' => $stime . ' - 17:30'));
                    $response['timedtl'] = $result;
                } else {
                    $response['timedtl'] = null;
                }
            } else {
                $checkDate = $this->db->GetRow("SELECT
                                                    COUNT(T0.ID) COUNTT
                                                FROM
                                                    MMS_MEETING_BOOK T0
                                                WHERE
                                                    T0.BOOK_TYPE_ID = 2 
                                                    AND T0.TEMPLATE_ID = '" . $params['templateId'] . "'
                                                    AND TO_CHAR(T0.START_DATE, 'YYYY-MM-DD') = '$currentDate'");

                if ($checkDate['COUNTT'] == '0') {
                    $response['timedtl'] = array(array('stime' => '08:30', 'etime' => '17:30', 'conftime' => '08:30 - 17:30'));
                } else {
                    $checkDate = $this->db->GetRow("SELECT
                                                    COUNT(T0.ID) COUNTT
                                                FROM
                                                    MMS_MEETING_BOOK T0
                                                WHERE
                                                    T0.BOOK_TYPE_ID = 2 
                                                    AND T0.TEMPLATE_ID = '" . $params['templateId'] . "'
                                                    AND (TO_CHAR(T0.START_DATE, 'YYYY-MM-DD') = '" . $params['startDate'] . "')");
                    if ($checkDate['COUNTT'] == '0') {
                        $response['timedtl'] = array(array('stime' => '08:30', 'etime' => '17:30', 'conftime' => '08:30 - 17:30'));
                    } else {
                        $response = null;
                    }
                }
            }
        }

        echo json_encode($response);
    }

    //task management calendar
    public function taskManagement() {
        $this->load->model('government', 'models/');
        $this->view->title = 'Ажил үүргийн календар';
        $this->view->uniqId = getUID();

        $this->view->metaDataId = '1579055688430';
        $this->view->data = array();

        if (is_ajax_request()) {
            $response = array(
                'Html' => $this->view->renderPrint('/task/calendar', self::$viewName),
                'Title' => $this->view->title,
                'uniqId' => $this->view->uniqId
            );
            echo json_encode($response);
        } else {
            $this->view->css = AssetNew::metaCss();
            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
            $this->view->js = array_unique(array_merge(array(
                'custom/addon/admin/pages/scripts/app.js',
                'custom/gov/multiselect.js'
                            ), AssetNew::metaOtherJs()));

            $this->view->render('header');
            $this->view->render('/task/calendar', self::$viewName);
            $this->view->render('footer');
        }
    }

    public function government($id = null) {

        $this->view->title = 'Parliament';
        $this->view->did = Input::post('dataViewId');
        $this->view->uniqId = getUID();
        $this->view->selectedRow = Input::post('selectedRow');

        //        var_dump($this->view->selectedRow); die;
        $this->view->cmsYamReviewList = array();
        $this->view->reviewLookup = array();

        $mainData = $this->model->fncRunDataview($this->view->did, 'id', '=', $this->view->selectedRow['id']);
        $this->view->mainData = ($mainData) ? (is_array($mainData) && $mainData[0] ? $mainData[0] : $mainData) : $this->view->selectedRow;

        $this->view->tagArr = (isset($this->view->mainData['tagname']) && $this->view->mainData['tagname']) ? explode(', ', $this->view->mainData['tagname']) : array();

        $this->view->attachFilesDv = $this->model->attachFilesDetailModel($this->view->selectedRow['id']);
        $this->view->result3 = $this->model->authorDetailModel($this->view->selectedRow['id']);
        $this->view->result4 = $this->model->checkListDetailModel($this->view->selectedRow['id']);
        $this->view->result5 = $this->model->participantsDetailModel($this->view->selectedRow['id']);
        $this->view->reviewDetail = $this->model->reviewDetailModel($this->view->selectedRow['id']);

        $this->view->reviewDetail = isset($this->view->reviewDetail['reviewdtl']) ? $this->view->reviewDetail['reviewdtl'] : array();

        $this->view->result7 = $this->model->decisionDetailModel($this->view->selectedRow['id']);
        $this->view->result8 = $this->model->legalFrameworkModel($this->view->selectedRow['id']);
        $this->view->subjectReviewCitizen = $this->model->subjectReviewCitizen($this->view->selectedRow['id']);

        $this->view->reviewLookup = $this->model->fncRunDataview("1560218638466921", '', '', '', '', 'code', 'asc');

        $cmsYamReviewList = Functions::runProcess('CMS_YAM_REVIEW_LIST_004', array('id' => $this->view->selectedRow['id']));

        if (isset($this->view->selectedRow['id']) && $this->view->selectedRow['id'] && issetParam($this->view->selectedRow['isdotoodhural']) == '1') {
            $subjectDecisionGet = Functions::runProcess('OCS_SUBJECT_DECISION_GET_LIST_004', array('id' => $this->view->selectedRow['id']));
            //            var_dump($subjectDecisionGet); die;
            if (isset($subjectDecisionGet['result']) && $subjectDecisionGet['result']) {
                //                var_dump($subjectDecisionGet['result']); die;
                $this->view->subjectDecisionGet = $subjectDecisionGet['result'];
            }
        }

        if (isset($cmsYamReviewList['result']) && $cmsYamReviewList['result']) {
            $this->view->cmsYamReviewList = $cmsYamReviewList['result'];
        }

        includeLib('Compress/Compression');
        $this->view->reviewDetailData = Compression::encode_string_array($this->view->reviewDetail);


        if (!is_ajax_request()) {
            $this->view->isAjax = false;
            $this->view->render('header');
        }

        $response = array(
            'html' => $this->view->renderPrint('/detail', self::$viewName),
            'uniqId' => $this->view->uniqId,
        );

        echo json_encode($response);
        exit;
    }

    public function saveSubjectReview() {
        $file_arr = Input::fileData();
        $postData = Input::postData();

        $result6Data = array();

        if (isset($postData['result6Data'])) {
            includeLib('Compress/Compression');
            $result6Data = Compression::decode_string_array($postData['result6Data']);
            $result6Data = Arr::groupByArrayOnlyRow($result6Data, 'id', false);
        }

        $wfmStatus = '';
        $wfmDescription = '';

        if ($postData['issend'] == '1') {
            $wfmStatus = 2000;
            $wfmDescription = 'Санал илгээсэн';
        } else {
            $wfmStatus = '';
            $wfmDescription = '';
        }

        foreach ($postData['id'] as $key => $row) {
            $fileAttach_multiFile = array();

            if (isset($_FILES['bp_file'])) {

                $file_path = Mdwebservice::bpUploadCustomPath('/metavalue/file/');
                $file_arr = $file_arr['bp_file'];

                foreach ($file_arr['name'][$row] as $f => $file) {
                    if (isset($file_arr['name'][$row][$f]) && $file_arr['name'][$row][$f] != '' && $file_arr['size'][$row][$f] != null) {

                        $newFileName = 'file_' . getUID() . $f;
                        $fileExtension = strtolower(substr($file_arr['name'][$row][$f], strrpos($file_arr['name'][$row][$f], '.') + 1));
                        $fileName = $newFileName . '.' . $fileExtension;

                        FileUpload::SetFileName($fileName);
                        FileUpload::SetTempName($file_arr['tmp_name'][$row][$f]);
                        FileUpload::SetUploadDirectory($file_path);
                        FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
                        FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize()); //10mb
                        $uploadResult = FileUpload::UploadFile();

                        if ($uploadResult) {

                            $tempparam = array(
                                'id' => '',
                                'rowState' => 'ADDED',
                                'fileName' => $file_arr['name'][$row][$f],
                                'fileSize' => $file_arr['size'][$row][$f],
                                'fileExtension' => $fileExtension,
                                'physicalPath' => $file_path . $fileName,
                                'ecmContentMap' =>
                                array(
                                    'orderNum' => 0,
                                    'refStructureId' => '456',
                                ),
                            );
                            array_push($fileAttach_multiFile, $tempparam);
                        }
                    }
                }
            }

            if (isset($result6Data[$row]['filedtl'])) {
                foreach ($result6Data[$row]['filedtl'] as $fk => $dtl) {
                    if (!isset($postData['reviewFile']) || !in_array($dtl['id'], $postData['reviewFile'])) {
                        $tempparam = array(
                            'id' => $dtl['id'],
                            'rowState' => 'REMOVED',
                        );
                        array_push($fileAttach_multiFile, $tempparam);
                    }
                }
            }

            $temp = array(
                'id' => $postData['id'][$key],
                'description' => $postData['description'][$key],
                'reviewtypeid' => isset($postData['reviewTypeId'][$key]) ? $postData['reviewTypeId'][$key] : '',
                'subjectid' => $postData['subjectId'][$key],
                'departmentid' => $postData['departmentId'][$key],
                'wfmstatusid' => $wfmStatus,
                'wfmdescription' => $wfmDescription,
                'issend' => $postData['issend'],
                'fileAttach_multiFile' => $fileAttach_multiFile
            );

            includeLib('Utils/Functions');
            $result = Functions::runProcess('CMS_SUBJECT_REVIEW_FOR_YAM_DV_002', $temp);

            if ($result['status'] === 'success') {
                $result['text'] = Lang::line('msg_save_success');
            }
            echo json_encode($result);
        }
    }

    public function getUserData() {
        includeLib('Utils/Functions');
        $result = Functions::runProcess('SCL_UM_USER_LIST_004', array('id' => Input::post('userId')));

        echo json_encode($result);
    }

    public function dashboardv1() {
        self::agentdashboard();
    }

    public function agentdashboard() {

        set_time_limit(0);
        ini_set('memory_limit', '-1');
        (Array) $xchange = array();
        
        $this->view->agent = true;

        $this->view->css = AssetNew::metaCss();
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->currentDate = Date::currentDate('Y-m-d');
        $this->view->currentTime = Date::currentDate('H:i');

        $this->view->layoutPositionArr = $this->model->fdashboardLayoutDataModel();
        $this->view->str = json_encode(issetParamArray($this->view->layoutPositionArr['pos_16']));

        $this->view->uniqId = getUID();
        $this->view->pollData = $this->model->getPollDataModel();

        $this->load->model('mdintegration', 'middleware/models/');
        $this->view->weatherData = self::getForecast5day();

        $this->view->eventBox = $this->view->renderPrint('/meeting/event', self::$viewName);
        $this->view->pollBox = $this->view->renderPrint('/meeting/poll', self::$viewName);

        $this->view->app_js = $this->view->renderPrint('/meeting/include/js', self::$viewName);
        $this->view->app_css = $this->view->renderPrint('/meeting/include/css', self::$viewName);
        
        if (Config::getFromCache('isNotaryServer')) {
            $url = "http://monxansh.appspot.com/xansh.json?currency=USD|EUR|KRW|CNY|RUB|GBP|JPY";
            $data = file_get_contents($url);
            $xchange = json_decode($data, true);
        }

        $this->view->exchangeData = $xchange;

        $this->view->title = 'Хяналтын самбар';

        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('/meeting/agentdashboard', self::$viewName);
            $this->view->render('footer');
        } else {
            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/meeting/agentdashboard', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response);
            die;
        }
    }

    public function saveSinglePoll() {

        (array) $intPollResultDv = array();
        $sessionuserId = Ue::sessionUserId();
        $postData = Input::postData();

        if (!isset($postData['questionid'])) {
            echo json_encode(array('status' => 'warning', 'text' => 'Хариулт бөглөөгүй байна.'));
            exit();
        }

        foreach ($postData['questionid'] as $key => $question) {
            if (issetParam($postData['answerid'][$key]) && !is_array($postData['answerid'][$key])) {
                $temp = array(
                    'answerId' => $postData['answerid'][$key],
                    'userId' => $sessionuserId,
                    'pollQuestionId' => $question,
                    'answerDescription' => issetParam($postData['answerdesc'][$key]),
                    'id' => NULL,
                    'postId' => NULL,
                );
                array_push($intPollResultDv, $temp);
            } elseif (issetParam($postData['answerid'][$key]) && is_array($postData['answerid'][$key])) {
                foreach ($postData['answerid'][$key] as $k => $answerData) {
                    $temp = array(
                        'answerId' => $answerData,
                        'userId' => $sessionuserId,
                        'pollQuestionId' => $question,
                        'answerDescription' => issetParam($postData['answerdesc'][$key][$k]),
                        'id' => NULL,
                        'postId' => NULL,
                    );
                    array_push($intPollResultDv, $temp);
                }
            }
        }

        $params = array(
            'id' => Input::post('postId'),
            'INT_POLL_RESULT_DV' => $intPollResultDv
        );

        includeLib('Utils/Functions');
        $response = Functions::runProcess('INT_POST_RESULT_DV_001', $params);

        if ($response['status'] === 'success') {
            $response['text'] = Lang::line('msg_save_success');
            $this->view->uniqId = Input::post('uniqId');
            $this->view->pollData = $this->model->getPollDataModel();
            $response['html'] = $this->view->renderPrint('/meeting/poll', self::$viewName);
        }

        echo json_encode($response);
    }

    public function getSinglePoll() {

        $this->view->uniqId = Input::post('uniqId');
        $this->view->pollData = $this->model->getPollDataModel();

        if (isset($this->view->pollData['id'])) {
            $response[$this->view->pollData['id']] = $this->view->renderPrint('/meeting/poll', self::$viewName);
        } else {
            $response = array();
        }

        echo json_encode($response);
    }

    public function unitdashboard() {
        $this->view->isNotary = Config::getFromCache('isNotaryServer');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $this->view->agent = false;

        $this->view->css = AssetNew::metaCss();
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->currentDate = Date::currentDate('Y-m-d');
        $this->view->currentTime = Date::currentDate('H:i');

        $this->view->layoutPositionArr = $this->model->fdashboardLayoutDataModel();
        $this->view->uniqId = getUID();
        $this->view->pollData = $this->model->getPollDataModel();

        if (Config::getFromCache('isNotaryServer')) {
            $this->view->notariesList = $this->model->fncRunDataview('1584011654594693');
            $this->view->locationLookup = $this->model->fncRunDataview('1584012022800161');
            $this->view->timeTableLookup = $this->model->fncRunDataview('1584010421279967');
            $this->view->genderChart = json_encode($this->model->fncRunDataview('1591166562522846'));
            $this->view->notariasAge = json_encode($this->model->fncRunDataview('1591167141214065'));
            $this->view->notariasStatus = json_encode($this->model->fncRunDataview('1591168674711351'));
            $this->view->notariasStatusP = $this->model->fncRunDataview('1591168674711351');
            $this->view->legalList = $this->model->fncRunDataview('1593498151336726');
        }


        $this->load->model('mdintegration', 'middleware/models/');
        $this->view->weatherData = self::getForecast5day();

        $this->view->eventBox = $this->view->renderPrint('/meeting/event', self::$viewName);
        $this->view->pollBox = $this->view->renderPrint('/meeting/poll', self::$viewName);

        // $this->view->unit_js = $this->view->renderPrint('/meeting/include/js', self::$viewName);
        $this->view->app_js = $this->view->renderPrint('/meeting/include/js', self::$viewName);
        $this->view->app_css = $this->view->renderPrint('/meeting/include/css', self::$viewName);

        if (Config::getFromCache('noUseKhanbankApi') === '1') {
            $this->view->exchangeData = array();
        } else {
            /* $url = "https://kbknew.khanbank.com/api/site/currency?lang=mn&site=personal&date";
            $data = file_get_contents($url);
            $xchange = json_decode($data, true); */
            $this->view->exchangeData = array(); /* issetParamArray($xchange['data']); */
        }


        $this->view->title = 'Хяналтын самбар';

        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('/meeting/agentdashboard', self::$viewName);
            $this->view->render('footer');
        } else {

            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/meeting/agentdashboard', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response);
            die;
        }
    }

    public function request() {
        $this->view->uniqId = Input::post('uniqId');
        $response = $this->model->requestDataModel();
        if (Input::post('isagent') !== '1') {
            $this->view->layoutPositionArr = $this->model->dashboardLayoutDataModel('pos11');
            $response['pos11'] = $this->view->renderPrint('/meeting/workin', self::$viewName);
        } else {
            $response['pos11'] = '';
        }
        
        echo json_encode($response);
    }

    public function getweatherFileIcon($id = '') {
        $data = array(
            array('id' => '2', 'name' => 'Цэлмэг', 'filepath' => 'assets/custom/img/weather/weather-01.png',),
            array('id' => '3', 'name' => 'Үүлэрхэг', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '5', 'name' => 'Багавтар үүлтэй', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '7', 'name' => 'Багавтар үүлтэй', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '9', 'name' => 'Үүлшинэ', 'filepath' => 'assets/custom/img/weather/weather-03.png',),
            array('id' => '10', 'name' => 'Үүлшинэ', 'filepath' => 'assets/custom/img/weather/weather-03.png',),
            array('id' => '20', 'name' => 'Үүл багаснa', 'filepath' => 'assets/custom/img/weather/weather-02.png',),
            array('id' => '23', 'name' => 'Ялимгүй цас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '24', 'name' => 'Ялимгүй цас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '27', 'name' => 'Ялимгүй хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '28', 'name' => 'Ялимгүй хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-04.png',),
            array('id' => '60', 'name' => 'Бага зэргийн бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '61', 'name' => 'Бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '63', 'name' => 'Их бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '65', 'name' => 'Хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '66', 'name' => 'Их хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '67', 'name' => 'Аадар их хур тунадас', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '68', 'name' => 'Их усархаг бороо', 'filepath' => 'assets/custom/img/weather/weather-06.png',),
            array('id' => '71', 'name' => 'Цас', 'filepath' => 'assets/custom/img/weather/weather-08.png',),
            array('id' => '73', 'name' => 'Их цас', 'filepath' => 'assets/custom/img/weather/weather-08.png',),
            array('id' => '75', 'name' => 'Аадар их цас', 'filepath' => 'assets/custom/img/weather/weather-08.png',),
            array('id' => '80', 'name' => 'Бага зэргийн аадар', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '81', 'name' => 'Бага зэргийн аадар', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '82', 'name' => 'Аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '83', 'name' => 'Аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '84', 'name' => 'Усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '85', 'name' => 'Усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '86', 'name' => 'Усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '87', 'name' => 'Усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '90', 'name' => 'Аянга цахилгаантай бага зэргийн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '91', 'name' => 'Аянга цахилгаантай бага зэргийн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '92', 'name' => 'Аянга цахилгаантай аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '93', 'name' => 'Аянга цахилгаантай аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '94', 'name' => 'Аянга цахилгаантай усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '95', 'name' => 'Аянга цахилгаантай усархаг аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '96', 'name' => 'Аянга цахилгаантай усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',),
            array('id' => '97', 'name' => 'Аянга цахилгаантай усархаг ширүүн аадар бороо', 'filepath' => 'assets/custom/img/weather/weather-05.png',)
        );
        $data = Arr::groupByArrayOnlyRow($data, 'id', false);
        $response = $data;

        if ($id) {
            $response = isset($data[$id]) ? $data[$id]['filepath'] : 'assets/custom/img/weather/weather-01.png';
        }

        return $response;
    }

    public function getForecast5day($cityName = 'Улаанбаатар') {
        $currentDate = Date::currentDate('y_m_d');

        $cache = phpFastCache();
        $data = $cache->get('bpForecast5day_' . $currentDate);

        if ($data == null  && Config::getFromCache('noUseTsagAgaarApi') !== '1') {

            $prevDate = date('y_m_d',strtotime("-1 days"));
            @unlink(Mdcommon::getCacheDirectory()."/*/bp/bpForecast5day_".$prevDate.".txt");

            $url = 'http://tsag-agaar.gov.mn/forecast_xml';
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml;charset=UTF-8'));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $str = curl_exec($ch);
            if (curl_errno($ch)) {
                return null;
            }            
            curl_close($ch);             

            if ($str) {
                $data = Xml::createArray($str);

                if ($data) {
                    $cache->set('bpForecast5day_' . $currentDate, $data, '144000000');
                }
            }            
        }

        (array) $mainData = array();
        if (isset($data['xml']['forecast5day'])) {
            foreach ($data['xml']['forecast5day'] as $key => $row) {
                if (isset($row['city']) && $row['city'] === $cityName && isset($row['data']['weather'])) {
                    foreach ($row['data']['weather'] as $row) {
                        $row['filepath'] = self::getweatherFileIcon($row['phenoIdDay']);
                        array_push($mainData, $row);
                    }
                }
            }
        }

        return $mainData;
    }

    public function getcalendarData() {
        (array) $response = array();
        $response = $this->model->getcalendarDataModel();
        echo json_encode($response);
    }

    public function getPostViewData() {
        includeLib('Utils/Functions');
        $result = Functions::runProcess('SCL_POSTS_VIEW_GET_LIST_004', array('id' => Input::post('postId')));

        (array) $data = array();
        if (issetParamArray($result['result']['scl_posts_view_dv'])) {
            $data = Arr::groupByArrayOnlyRows($result['result']['scl_posts_view_dv'], 'isread');
        }

        echo json_encode(array('response' => $data));
    }

    public function social() {

        $this->view->title = 'Сошиал';
        $this->view->uniqId = Input::post('uniqId');
        $this->view->mainRow = Input::post('mainRow');
        $this->view->userId = Ue::sessionUserId();

        $this->view->css = array('custom/css/social/css/style.css');
        $this->view->metaDataId = isset($this->view->mainRow['dvmetadataid']) ? $this->view->mainRow['dvmetadataid'] : '';
        $this->view->dvresult = false;
        $this->view->categoryId = $this->view->mainRow['categoryid'];
        $this->view->typeId = $this->view->mainRow['typeid'];

        if ($this->view->metaDataId) {
            //'1589509636468'
            $this->view->dvresult = true;
            includeLib('Utils/Functions');
            //$this->view->activeGroupList = $this->model->fncRunDataview('1589509636468');

            if (isset($this->view->mainRow) && isset($this->view->mainRow['categoryid']) && $this->view->mainRow['categoryid'] == '9988') {
                $this->view->groupId = $this->view->mainRow['id'];
                $this->view->categoryId = $this->view->mainRow['categoryid'];
                $this->view->typeId = $this->view->mainRow['typeid'];
                $this->view->activeUserList = $this->model->fncRunDataview('1588319904318812', "groupId", "=", $this->view->mainRow['id'], array(), 'createddate', 'desc', '0', true, '10');
                $this->view->requestUserList = $this->model->fncRunDataview('1593585458991901', "groupId", "=", $this->view->mainRow['id'], array(), 'createddate', 'desc', '0', true, '10');

                $this->view->userInfo = Functions::runProcess('NTR_SOCIAL_USER_INFO_DV_004', array('userid' => Ue::sessionUserId(), 'groupId' => $this->view->mainRow['id']));
                $postsData = (issetParam($this->view->mainRow['isapproved']) == '1' || issetParam($this->view->mainRow['postcategoryid']) == '9999') ? Functions::runProcess('NTR_SOCIAL_PUBLIC_LIST_004', array('pageNumber' => '1', 'pageRows' => '20', 'groupId' => $this->view->mainRow['id'], 'typeId' => $this->view->mainRow['typeid'])) : array();
                $this->view->groupData = Functions::runProcess('NTR_SOCIAL_GROUP_DV_004', array('pageNumber' => '1', 'pageRows' => '20', 'id' => $this->view->mainRow['id']));
            } else {
                $this->view->activeUserList = $this->model->fncRunDataview('1588319904318812', "ispin", "=", '0', array(), 'createddate', 'desc', '0', true, '10');
                $this->view->userInfo = Functions::runProcess('NTR_SOCIAL_USER_INFO_DV_004', array('userid' => Ue::sessionUserId()));
                $postsData = (issetParam($this->view->mainRow['isapproved']) == '1' || issetParam($this->view->mainRow['postcategoryid']) == '9999') ? Functions::runProcess('NTR_SOCIAL_PUBLIC_LIST_004', array('pageNumber' => '1', 'pageRows' => '20')) : array();
            }

            $pinpostData = Functions::runProcess('NTR_SOCIAL_PUBLIC_LIST_004', array('pageNumber' => '1', 'pageRows' => '5'));
            $postsDataArr = issetParamArray($postsData['result']['ntr_scl_social_main_list']);
            $this->view->pinpostData = issetParamArray($pinpostData['result']['ntr_scl_social_main_list']);
            $this->view->postsData = array();

            foreach ($postsDataArr as $key => $postsKey) {
                $comList = $postsKey['ntr_social_comment_list'];
                $postsKey['ntr_social_comment_list'] = array();
                if ($comList) {
                    $postsKey['ntr_social_comment_list'] = Arr::buildTree($comList, $parentId = 0, 'id', 'parentid');
                }

                array_push($this->view->postsData, $postsKey);
            }

            $this->view->posts = $this->view->renderPrint('/social/posts', self::$viewName);
            $this->view->postsCount = '10';
        } else {
            $this->view->posts = self::defaultPosts();
            $this->view->postsCount = $this->model->getSocialPostsCountModel();
        }

        $this->view->createdGroups = $this->model->getCreatedGroupsModel();
        $this->view->joinedGroups = $this->model->getJoinedGroupsModel();

        $this->view->createPost = (issetParam($this->view->mainRow['isapproved']) == '1' || issetParam($this->view->mainRow['postcategoryid']) == '9999') ? $this->view->renderPrint('/social/createPost', self::$viewName) : '';

        $this->view->mainLeft = '';
        $this->view->mainRight = $this->view->renderPrint('/social/mainRight', self::$viewName);

        if (!is_ajax_request()) {
            $this->view->ajax = false;
            $this->view->mainLeft = $this->view->renderPrint('/social/mainLeft', self::$viewName);

            $this->view->render('social/header');
            $this->view->render('social/index');
            $this->view->render('social/footer');
        } else {

            $this->view->ajax = true;
            $html = $this->view->renderPrint('/intranet/sub/scCss', self::$viewName);
            $html .= $this->view->renderPrint('/social/index', self::$viewName);
            //$html .= $this->view->renderPrint('assets/css/social/js/social.js', 'assets/custom');

            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'mainRow' => $postsData,
                'groupList' => array(),
                'Html' => $html,
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response);
        }
    }

    public function searchIntranet() {
        $content = $this->getFilterIntranetForm(Input::post('searchval'));
        $filterTypeList = $this->model->fncRunDataview("1589430185841155");
        echo json_encode(array('content' => $content, 'filterTypeList' => $filterTypeList));
    }

    public function getFilterIntranetForm($searchVal = '') {
        return '';
        die();
        includeLib('Utils/Functions');
        $postsData = Functions::runProcess('NTR_SOCIAL_FILTER_LIST_004', array('filterValue' => $searchVal, 'pageNumber' => '1', 'pageRows' => '10'));

        $this->view->dvresult = true;
        $this->view->activeUserList = $this->model->fncRunDataview('1588319904318812', "ispin", "=", '0', array(), 'createddate', 'desc', '0', true, '10');

        $this->view->postsData = issetParamArray($postsData['result']['ntr_social_filter_list']);
        $this->view->pinpostData = array();

        $this->view->userInfo = Functions::runProcess('NTR_SOCIAL_USER_INFO_DV_004', array('userid' => Ue::sessionUserId()));

        $this->view->postsCount = '10';
        return $this->view->renderPrint('/social/posts', self::$viewName);
    }

    public function updateIntanetComment() {
        includeLib('Utils/Functions');
        $commentId = Input::post('commentId');
        $this->db->AutoExecute('SCL_POSTS_COMMENT', array('COMMENT_TXT' => Input::post('commentText')), 'UPDATE', "ID = '$commentId'");
        $commentData = Functions::runProcess('NTR_SOCIAL_COMMENT_LIST_004', array('id' => $commentId));
        echo json_encode(issetParamArray($commentData['result']));
    }

    public function contentViewUser() {
        try {
            $currentDate = Date::currentDate();
            $userId = Ue::sessionUserId();
            $data = array(
                'CONTENT_USER_ID' => getUID(),
                'CONTENT_ID' => Input::post('contentId'),
                'USER_ID' => $userId,
                'READ_DATE' => $currentDate,
                'CREATED_DATE' => $currentDate,
                'TYPE' => '1',
                'CREATED_USER_ID' => $userId
            );

            $this->db->AutoExecute('SCL_CONTENT_USER', $data);
        } catch (Exception $ex) {
            echo 'aldaa';
        }
    }

    public function downloadFileUser() {
        try {
            $currentDate = Date::currentDate();
            $userId = Ue::sessionUserId();
            $data = array(
                'CONTENT_USER_ID' => getUID(),
                'CONTENT_ID' => Input::get('contentId'),
                'USER_ID' => $userId,
                'READ_DATE' => $currentDate,
                'CREATED_DATE' => $currentDate,
                'TYPE' => '2',
                'CREATED_USER_ID' => $userId
            );

            $this->db->AutoExecute('SCL_CONTENT_USER', $data);
            header("location: " . URL . 'mdobject/downloadFile?file=' . Input::get('file') . '&fileName=' . Input::get('fileName'));
        } catch (Exception $ex) {
            echo 'aldaa';
        }
    }

    public function lawTree() {
        $this->view->isAjax = false;
        $this->view->title = 'APP MENU';
        $this->view->show = true;
        $this->view->uniqId = getUID();
        $this->view->css = AssetNew::metaCss();
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
        $this->view->jsonData = '{}';

        if (!is_ajax_request()) {
            $this->view->isAjax = true;
            $this->view->render('header');
            $this->view->render('/lawrender/lawtree', self::$viewName);
            $this->view->render('footer');
        } else {
            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/lawrender/lawtree', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
        }
    }

    public function lawTextParsing() {
        // var_dump($_FILES);die;
        try {
            $files = $_FILES['lawdocx'];
            $oldName = substr($files['name'], 0, strrpos($files['name'], '.'));
            $newFileName = 'law_' . getUID();
            $fileExtension = strtolower(substr($files['name'], strrpos($files['name'], '.') + 1));
            $fileName = $newFileName . '.' . $fileExtension;
            $filePath = UPLOADPATH;
            FileUpload::SetFileName($fileName);
            FileUpload::SetTempName($files['tmp_name']);
            FileUpload::SetUploadDirectory($filePath);
            $uploadResult = FileUpload::UploadFile();

            $path = $filePath . $newFileName . '.docx';
            $url = URL . $path;
            // $url = 'http://192.168.193.173/' . $path;
            $data = file_get_contents('http://win.interactive.mn/file_converter/Converter.aspx?mode=txt&FromUrl=' . $url);
            $lines = explode("\n", $data);
            $str_num_mn = array('Нэг', 'Хоёр', 'Гурав', 'Дөрөв', 'Тав', 'Зургаа', 'Долоо', 'Найм', 'Ес', 'Арав', 'Хорь', 'Гуч', 'Дөч', 'Тавь', 'Жар', 'Дал', 'Ная', 'Ер', 'Зуу', 'Мянга');
            $lines = array_filter($lines);
            mb_internal_encoding("UTF-8");
            $law = array();
            foreach ($lines as $key => $law_line) {
                if (mb_strlen($law_line) > 2 && !empty($law_line)) {
                    if (self::contains($law_line, $str_num_mn, '.')) {
                        array_push($law, array('text' => $law_line, 'type' => 1, 'law_general_data_second_dv' => array()));
                    } else {
                        $cleanTxt = preg_replace('/^[^A-Za-zА-Яа-яӨөҮү ]+/', '', $law_line);
                        $cleanTxt = Str::utf8_substr($cleanTxt, 0);
                        $numbering = substr($law_line, 0, strlen($law_line) - strlen($cleanTxt));
                        $num_array = array_filter(explode('.', trim($numbering)));
                        foreach ($num_array as $key => $val) {
                            if (!empty($val)) {
                                if ($key == 1) {
                                    if (
                                            is_numeric($num_array[$key - 1]) &&
                                            isset($law[$num_array[$key - 1] - 1]) &&
                                            isset($law[$num_array[$key - 1] - 1]['law_general_data_second_dv'])
                                    ) {
                                        if ($key + 1 == sizeof($num_array)) {
                                            array_push($law[$num_array[$key - 1] - 1]['law_general_data_second_dv'], array('text' => $cleanTxt, 'type' => 2, 'numbering' => $numbering));
                                        } else {
                                            if (isset($law[$num_array[$key - 1] - 1]['law_general_data_second_dv'])) {
                                                if (
                                                        isset($law[$num_array[$key - 1] - 1]['law_general_data_second_dv'][$val - 1]['law_general_data_third_dv']) && is_array($law[$num_array[$key - 1] - 1]['law_general_data_second_dv'][$val - 1]['law_general_data_third_dv'])
                                                ) {
                                                    array_push($law[$num_array[$key - 1] - 1]['law_general_data_second_dv'][$val - 1]['law_general_data_third_dv'], array('text' => $cleanTxt, 'type' => 3, 'numbering' => $numbering));
                                                } else {
                                                    $law[$num_array[$key - 1] - 1]['law_general_data_second_dv'][$val - 1]['law_general_data_third_dv'] = array();
                                                    array_push($law[$num_array[$key - 1] - 1]['law_general_data_second_dv'][$val - 1]['law_general_data_third_dv'], array('text' => $cleanTxt, 'type' => 3, 'numbering' => $numbering));
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($law)) {
                $law = array('name' => Input::post('lawname'), 'description' => '', 'typeid' => 0, 'law_general_data_first_dv' => $law);
            } else {
                $law = array('name' => Input::post('lawname'), 'description' => '', 'typeid' => 0);
            }
            $result = $this->model->lawInsertData($law);
            if ($result['status'] == 'success') {
                $this->view->id = $result['result']['id'];

                $result = $this->model->getLawDataModel($this->view->id);

                if (issetParam($result['status']) == 'success') {
                    $result['result']['text'] = $result['result']['name'];
                    if (isset($result['result']['law_general_data_first_dv'])) {
                        foreach ($result['result']['law_general_data_first_dv'] as $key => &$value2) {
                            if (isset($value2['law_general_data_second_dv'])) {
                                foreach ($value2['law_general_data_second_dv'] as $key => &$value3) {
                                    if (isset($value3['law_general_data_third_dv'])) {
                                        $value3['children'] = $value3['law_general_data_third_dv'];
                                        unset($value3['law_general_data_third_dv']);
                                    }
                                }
                                $value2['children'] = $value2['law_general_data_second_dv'];
                                unset($value2['law_general_data_second_dv']);
                            }
                        }
                        $result['result']['children'] = $result['result']['law_general_data_first_dv'];
                        unset($result['result']['law_general_data_first_dv']);
                        $this->view->jsonData = json_encode($result['result']['children']);
                    } else {
                        $this->view->jsonData = '{}';
                    }
                    $this->view->id = $result['result']['id'];
                    echo json_encode(
                            array(
                                'status' => 'success',
                                'url' => '/government/lawRender/' . $this->view->id,
                                'Html' => $this->view->renderPrint('government/lawrender/lawtreecustom')
                            // 'Html' => $this->view->renderPrint('/lawrender/lawtree', self::$viewName),
                            )
                    );
                    // header("Location: /government/lawEditPage/" . $this->view->id);
                    // exit;
                }
            }
        } catch (\Throwable $th) {
            var_dump($th);
            die;
        }
    }

    public function lawRender($id = '') {
        if (issetParam($id) == '') {
            $this->view->postParams = Input::post('selectedRow');
            $id = issetParam($this->view->postParams['id']);
        }
        $this->view->id = $id;
        $result = $this->model->getLawDataModel($id);
        if (issetParam($result['status']) == 'success') {
            $result['result']['text'] = $result['result']['name'];
            foreach ($result['result']['law_general_data_first_dv'] as $key => &$value2) {
                if (isset($value2['law_general_data_second_dv'])) {
                    foreach ($value2['law_general_data_second_dv'] as $key => &$value3) {
                        if (isset($value3['law_general_data_third_dv'])) {
                            $value3['children'] = $value3['law_general_data_third_dv'];
                            unset($value3['law_general_data_third_dv']);
                        }
                    }
                    $value2['children'] = $value2['law_general_data_second_dv'];
                    unset($value2['law_general_data_second_dv']);
                }
            }
            $result['result']['children'] = $result['result']['law_general_data_first_dv'];
            unset($result['result']['law_general_data_first_dv']);
            $this->view->lawData = $result;
            $this->view->id = $result['result']['id'];
            $this->view->title = 'APP MENU';
            $this->view->css = AssetNew::metaCss();
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->render('government/lawrender/lawCustom');
        }
    }

    public function lawHeadings($id = '') {
        if (issetParam($id) == '') {
            $this->view->postParams = Input::post('selectedRow');
            $id = issetParam($this->view->postParams['id']);
        }
        $result = $this->model->getLawDataModel($id);

        if (issetParam($result['status']) == 'success') {
            $result['result']['text'] = $result['result']['name'];
            foreach ($result['result']['law_general_data_first_dv'] as $key => &$value2) {
                if (isset($value2['law_general_data_second_dv'])) {
                    foreach ($value2['law_general_data_second_dv'] as $key => &$value3) {
                        if (isset($value3['law_general_data_third_dv'])) {
                            $value3['children'] = $value3['law_general_data_third_dv'];
                            unset($value3['law_general_data_third_dv']);
                        }
                    }
                    $value2['children'] = $value2['law_general_data_second_dv'];
                    unset($value2['law_general_data_second_dv']);
                }
            }
            $result['result']['children'] = $result['result']['law_general_data_first_dv'];
            unset($result['result']['law_general_data_first_dv']);
            $this->view->lawData = $result;
            $this->view->id = $result['result']['id'];
            $this->view->title = 'APP MENU';
            $this->view->css = AssetNew::metaCss();
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            // $this->view->render('header');
            $this->view->render('government/lawrender/lawHeadings');
            // $this->view->render('footer');
        }
    }

    public function lawEditPage($id = '') {
        $this->view->uniqId = getUID();
        if (issetParam($id) == '') {
            $this->view->postParams = Input::post('selectedRow');
            $id = issetParam($this->view->postParams['id']);
        }

        $result = $this->model->getLawDataModel($id);

        if (issetParam($result['status']) == 'success') {
            $result['result']['text'] = $result['result']['name'];
            foreach ($result['result']['law_general_data_first_dv'] as $key => &$value2) {
                if (isset($value2['law_general_data_second_dv'])) {
                    foreach ($value2['law_general_data_second_dv'] as $key => &$value3) {
                        if (isset($value3['law_general_data_third_dv'])) {
                            $value3['children'] = $value3['law_general_data_third_dv'];
                            unset($value3['law_general_data_third_dv']);
                        }
                    }
                    $value2['children'] = $value2['law_general_data_second_dv'];
                    unset($value2['law_general_data_second_dv']);
                }
            }
            $result['result']['children'] = $result['result']['law_general_data_first_dv'];
            unset($result['result']['law_general_data_first_dv']);
            $this->view->jsonData = json_encode($result['result']['children']);
            $this->view->id = $result['result']['id'];
            $this->view->title = 'APP MENU';
            $this->view->css = AssetNew::metaCss();
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

            if (!is_ajax_request()) {
                $this->view->isAjax = true;
                $this->view->render('header');
                $this->view->render('/lawrender/lawtreecustom', self::$viewName);
                $this->view->render('footer');
            } else {
                $response = array(
                    'Title' => '',
                    'Width' => '700',
                    'uniqId' => $this->view->uniqId,
                    'Html' => $this->view->renderPrint('/lawrender/lawtreecustom', self::$viewName),
                    'save_btn' => Lang::line('save_btn'),
                    'close_btn' => Lang::line('close_btn')
                );
                echo json_encode($response);
            }
        }
    }

    public function editorDialog() {
        $response = array(
            'html' => $this->view->renderPrint('government/lawrender/texteditor'),
            'title' => 'Editor',
            'choose_btn' => Lang::line('choose_btn'),
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response);
    }

    public function saveAdditional() {
        $result = $this->model->saveAdditionalModel();
        echo json_encode($result);
    }

    public function lawTreeCustom() {
        $this->view->isAjax = false;
        $this->view->title = 'APP MENU';
        $this->view->show = true;
        $this->view->uniqId = getUID();
        $this->view->css = AssetNew::metaCss();
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
        $this->view->jsonData = '{}';

        if (!is_ajax_request()) {
            $this->view->isAjax = true;
            $this->view->render('header');
            $this->view->render('/lawrender/lawtreecustom', self::$viewName);
            $this->view->render('footer');
        } else {
            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/lawrender/lawtreecustom', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
        }
    }

    public function lawSideBar($id = '') {
        if (issetParam($id) == '') {
            $this->view->postParams = Input::post('selectedRow');
            $id = issetParam($this->view->postParams['id']);
        }
        $result = $this->model->getLawDataModel($id);
        if ($result['status'] == 'success') {
            $result['result']['text'] = $result['result']['name'];
            foreach ($result['result']['law_general_data_first_dv'] as $key => &$value2) {
                if (isset($value2['law_general_data_second_dv'])) {
                    foreach ($value2['law_general_data_second_dv'] as $key => &$value3) {
                        if (isset($value3['law_general_data_third_dv'])) {
                            $value3['children'] = $value3['law_general_data_third_dv'];
                            unset($value3['law_general_data_third_dv']);
                        }
                    }
                    $value2['children'] = $value2['law_general_data_second_dv'];
                    unset($value2['law_general_data_second_dv']);
                }
            }
            $result['result']['children'] = $result['result']['law_general_data_first_dv'];
            unset($result['result']['law_general_data_first_dv']);
            $this->view->lawData = $result;
            $this->view->id = $result['result']['id'];
            $this->view->render('government/lawrender/sidebarLeft');
        }
    }

    public function lawMaincontent($id = '') {
        if (issetParam($id) == '') {
            $this->view->postParams = Input::post('selectedRow');
            $id = issetParam($this->view->postParams['id']);
        }
        // $id = 1586857199156;
        $result = $this->model->getLawDataModel($id);
        if ($result['status'] == 'success') {
            $result['result']['text'] = $result['result']['name'];
            foreach ($result['result']['law_general_data_first_dv'] as $key => &$value2) {
                if (isset($value2['law_general_data_second_dv'])) {
                    foreach ($value2['law_general_data_second_dv'] as $key => &$value3) {
                        if (isset($value3['law_general_data_third_dv'])) {
                            $value3['children'] = $value3['law_general_data_third_dv'];
                            unset($value3['law_general_data_third_dv']);
                        }
                    }
                    $value2['children'] = $value2['law_general_data_second_dv'];
                    unset($value2['law_general_data_second_dv']);
                }
            }
            $result['result']['children'] = $result['result']['law_general_data_first_dv'];
            unset($result['result']['law_general_data_first_dv']);
            $this->view->lawData = $result;
            $this->view->id = $result['result']['id'];
            $this->view->render('government/lawrender/mainContent');
        }
    }

    public function getDocEditorKey($fileName) {
        $key = $fileName;
        $stat = filemtime($key);
        $key = $key . $stat;
        return $this->generateRevisionId($key);
    }

    public function generateRevisionId($expected_key) {
        if (strlen($expected_key) > 20)
            $expected_key = crc32($expected_key);
        $key = preg_replace("[^0-9-.a-zA-Z_=]", "_", $expected_key);
        $key = substr($key, 0, min(array(strlen($key), 20)));
        return $key;
    }

    function contains($str, array $arr, $suffix = '') {
        foreach ($arr as $a) {
            if (stripos($str, $a . $suffix) !== false)
                return true;
        }
        return false;
    }

    public function uploadGeneric() {

        $result = array('uploaded' => '0', 'error' => 'failed to upload');

        if (!empty($_FILES) && isset($_FILES['upload'])) {

            $file = $_FILES['upload'];
            $newFileName = 'ckEditorImage_' . getUID();
            $fileExtension = strtolower(substr($file['name'], strrpos($file['name'], '.') + 1));
            $fileName = $newFileName . '.' . $fileExtension;
            $filePath = UPLOADPATH . 'ckeditor/';
            FileUpload::SetFileName($fileName);
            FileUpload::SetTempName($file['tmp_name']);
            FileUpload::SetUploadDirectory($filePath);
            FileUpload::SetValidExtensions(explode(',', Config::getFromCache('CONFIG_FILE_EXT')));
            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize());
            $uploadResult = FileUpload::UploadFile();

            if ($uploadResult) {
                $result = array('uploaded' => 1, 'fileName' => $fileName, 'url' => URL . $filePath . $fileName);
            }
        }

        echo json_encode($result);
    }

    public function lawTreeUpdate() {
        $type = Input::post('type');
        $result = false;
        switch ($type) {
            case 'move':
                $result = $this->model->moveLawTree();
                break;
            case 'create':
                $result = $this->model->createLawTree();
                break;
            case 'rename':
                $result = $this->model->renameLawTree();
                break;
            case 'remove':
                $result = $this->model->deleteLawTree();
                break;
        }

        echo json_encode($result);
    }

    public function getPostFileViewData() {
        includeLib('Utils/Functions');
        $result = Functions::runProcess('INT_CONTENT_VIEW_PERSON_004', array('id' => Input::post('contentId')));

        echo json_encode(issetParamArray($result['result']));
    }

    public function changeContent() {
        Session::set(SESSION_PREFIX . 'startupContent', '1');
        $this->db->Execute("UPDATE UM_USER SET IS_VIEWED_CONTENT = 0");

        $tmp_dir = Mdcommon::getCacheDirectory();
        $clearFiles = array();

        $sysFiles = glob($tmp_dir . "/*/sy/sy*.txt");
        $clearFiles = array_merge_recursive($clearFiles, $sysFiles);

        $this->load->model('mdmeta', 'middleware/models/');
        $this->model->serviceReloadConfigModel();

        if (count($clearFiles)) {
            foreach ($clearFiles as $clearFile) {
                @unlink($clearFile);
            }
        }
        Config::setCache();
    }

    public function legalinfo($page = 'index') {
        $this->view->render('/legalinfo_portal/' . $page, self::$viewName);
    }

    public function getNotaries() {
        $response = $this->model->getNotariesModel();
        echo json_encode($response);
    }

    public function likePeople() {

        $this->view->people = $this->model->getLikePeopleByPostIdModel();

        $response = array(
            'status' => 'success',
            'count' => count($this->view->people),
            'html' => $this->view->renderPrint('/social/like_people', self::$viewName),
        );
        echo json_encode($response);
        exit;
    }

    public function sendRequestGroup() {
        $response = $this->model->sendRequestGroupModel();
        echo json_encode($response);
        exit;
    }

    public function requestActionGroup() {
        $response = $this->model->requestActionGroupModel();
        echo json_encode($response);
        exit;
    }

    public function getTaxonomyWords() {
        $result = $this->model->getTaxonomyWordsModel();
        echo json_encode($result);
    }

    public function saveSemanticMap() {
        $response = $this->model->saveSemanticMapModel();
        echo json_encode($response);
    }

    public function testLawSearch($id = null) {
        $this->view->css = AssetNew::metaCss();
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $paramFilter = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );
        $this->view->lawInfo = $this->model->fncRunDataview('1591170775374416', "", "", "", $paramFilter, "", "", "1", true);

        $lawCategoryId = null;
        if (!empty($this->view->lawInfo['result'])) {
            $lawCategoryId = $this->view->lawInfo['result'][0]['lawcategoryid'];
        }

        $paramFilter = array(
            'lawid' => array(
                array(
                    'operator' => '=',
                    'operand' => $id
                )
            )
        );
        $this->view->lawData = $this->model->fncRunDataview('1594260464329891', "", "", "", $paramFilter, "", "", "1", true);

        array_multisort(
                array_column($this->view->lawData['result'], 'parentid'),
                array_column($this->view->lawData['result'], 'id'),
                $this->view->lawData['result']
        );
        // var_dump($this->view->lawData);die;
        // var_dump(array_keys( array_column($this->view->lawData['result'], 'parentid') , null));die;

        $paramFilter = array(
            'id' => array(
                array(
                    'operator' => '=',
                    'operand' => $lawCategoryId
                )
            )
        );
        $this->view->tabData = $this->model->fncRunDataview('1594019364745610', "", "", "", $paramFilter, "", "", "1", true);
        $this->view->render('header');
        $this->view->render('/lawrender/lawSearchText', self::$viewName);
        $this->view->render('footer');
    }

    public function forum() {

        $this->view->uniqId = getUID();
        $this->view->rightSideCategory = $this->model->fncRunDataview('1576141209825418');
        $this->view->countList = $this->model->fncRunDataview('1576143218264363');
        $this->view->reviewType = $this->model->fncRunDataview('1576143908333703');
        $this->view->pollList = $this->model->fncRunDataview('1594273464344', '', '', '', "", "", "", "", true);
        $this->view->dcss = $this->view->renderPrint('/forum/css', self::$viewName);

        if (!is_ajax_request()) {

            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->css = AssetNew::metaCss();
            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');

            $this->view->render('header');
            $this->view->render('/forum/index', self::$viewName);
            $this->view->render('footer');
        } else {

            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/forum/index', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response);
        }
    }

    public function srcforum() {
        $filterName = Input::post('filterName');
        $filterType = Input::post('filterType');
        $categoryData = Input::post('filterCategory');

        $filterCategory = '';
        if (isset($categoryData) && $categoryData) {
            foreach ($categoryData as $key => $cat) {
                $filterCategory .= $cat['category'] . ',';
            }
            $filterCategory = substr($filterCategory, 0, -1);
        }

        $type = Input::post('type');
        $paramFilter = array();

        $data = array();

        if ($filterType != null || $filterCategory != null) {
            $paramFilter = array(
                'filterName' => array(
                    array(
                        'operator' => 'like',
                        'operand' => $filterName
                    )
                ),
                'tuluw' => array(
                    array(
                        'operator' => '=',
                        'operand' => $filterType
                    )
                ),
                'departmentId' => array(
                    array(
                        'operator' => 'IN',
                        'operand' => $filterCategory
                    )
                )
            );
            $data = $this->model->fncRunDataview('1571135603102', "", "", $filterType, $paramFilter, "", "", "1", true);
        } else {
            $data = $this->model->fncRunDataview('1571135603102', 'filterName', 'like', $filterName, "", "", "", "", true);
        }

        echo json_encode($data);
    }

    public function forumdtl($id) {

        $this->view->uniqId = getUID();
        $this->view->id = $id;
        
        includeLib('Utils/Functions');
        $this->view->dataRow = Functions::runProcess('LIS_LAW_DISCUSSION_DETAIL_GET_LIST_004', array('id' => $this->view->id));
        $this->view->title = '';
        $this->view->dcss = $this->view->renderPrint('/forum/css', self::$viewName);

        if (!is_ajax_request()) {

            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->css = AssetNew::metaCss();
            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');

            $this->view->render('header');

            if (issetParamArray($this->view->dataRow['result'])) {
                $this->view->data = $this->view->dataRow['result'];
                $this->view->render('/forum/detail', self::$viewName);
            } else {
                $this->view->title = 'Унших өгөгдөл олдсонгүй';
                $this->view->render('/lawrender/error', self::$viewName);
            }

            $this->view->render('footer');
        } else {

            if (issetParamArray($this->view->dataRow['result'])) {
                $this->view->data = $this->view->dataRow['result'];
                $this->view->content = $this->view->renderPrint('/forum/detail', self::$viewName);
            } else {
                $this->view->title = 'Унших өгөгдөл олдсонгүй';
                $this->view->content = $this->view->renderPrint('/lawrender/error', self::$viewName);
            }

            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->content,
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response);
        }
    }

    public function saveAddonlaw() {
        try {

            var_dump($_POST);
        } catch (Exception $ex) {
            (array) $response = array();

            $response['status'] = 'warning';
            $response['text'] = $ex->getMessage();
        }

        echo json_encode($response);
    }

    public function saveForumPoll() {

        $postData = Input::postData();
        $currentDate = Date::currentDate();
        $sessionUserKeyId = Ue::sessionUserKeyId();
        includeLib('Utils/Functions');

        if (issetParam($postData['checkvalue'])) {
            foreach ($postData['checkvalue'] as $key2 => $dtlFact2) {
                $exkey = explode('_', $dtlFact2);
                $key = $exkey[0];
                $dtlFact = $exkey[1];
                $param = array(
                    'bookId' => $postData['id'],
                    'indicatorId' => $postData['indicatorid'][$dtlFact][$key],
                    'templateDtlId' => $postData['templatedtlid'][$dtlFact][$key],
                    'fact1' => $postData['scoreid'][$dtlFact][$key],
                    'fact2' => NULL,
                    'createdDate' => $currentDate,
                    'createdUserId' => $sessionUserKeyId,
                    'id' => NULL,
                    'fact3' => NULL,
                    'fact16' => NULL,
                    'dimensionId' => NULL,
                    'fact17' => NULL,
                    'fact15' => NULL,
                    'fact13' => NULL,
                    'fact7' => NULL,
                    'fact11' => NULL,
                    'fact6' => NULL,
                    'fact4' => NULL,
                    'fact14' => NULL,
                    'fact5' => NULL,
                    'subTemplateId' => NULL,
                    'fact18' => NULL,
                    'fact19' => NULL,
                    'fact8' => NULL,
                    'rootTemplateId' => NULL,
                    'fact10' => NULL,
                    'fact20' => NULL,
                    'fact12' => NULL,
                    'fact9' => NULL,
                    'rootIndicatorId' => NULL,
                );
                
                $result = Functions::runProcess('LIS_KPI_DM_DTL_DV_001', $param);
            }
        }

        echo json_encode(array('status' => 'success', 'type' => 'success', 'text' => 'Баярлалаа, Таны хүсэлтийг хүлээн авлаа.'));
    }

    public function saveCommentLaw() {
        $sessionUserKeyId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate();

        $param = array(
            'id' => NULL,
            'refStructureId' => '1594273392339',
            'recordId' => Input::post('recordId'),
            'commentText' => json_decode(str_replace('\n', '<br>', json_encode($_POST['commentText']))),
            'createdUserId' => $sessionUserKeyId,
            'createdDate' => $currentDate,
            'isModified' => '0',
            'isDeleted' => '0',
            'modifiedDate' => NULL,
            'modifiedUserId' => NULL,
            'isReply' => '0',
            'parentId' => NULL,
            'commentTypeId' => NULL,
            'categoryId' => NULL,
            'wfmLogId' => NULL,
            'commentAudio' => NULL,
            'subType' => NULL,
            'fileAttach_multiFileRefStructureId' => '159159',
            'LIS_DISCUSSION_COMMENT_USER_DV' =>
            array(
                'id' => NULL,
                'commentId' => NULL,
                'userId' => $sessionUserKeyId,
                'createdDate' => $currentDate,
                'createdUserId' => $sessionUserKeyId,
            ),
        );

        includeLib('Utils/Functions');
        $response = Functions::runProcess('LIS_DISCUSSION_COMMENT_DV_001', $param);
        $html = '';
        $html .= '<div class="media flex-column flex-md-row">';
        $html .= '<div class="mr-md-3 mb-2 mb-md-0">';
        $html .= '<a href="javascript:void(0);"><img src="storage/uploads/process/file_1537353877188322_14930215614883211.png" class="rounded-circle" width="36" height="36" alt=""></a>';
        $html .= '</div>';
        $html .= '<div class="media-body">';
        $html .= '<div class="media-title">';
        $html .= '<a href="javascript:void(0);" class="font-weight-bold">' . Ue::getSessionPersonWithLastName() . '</a>';
        $html .= '<span class="font-size-sm text-muted ml-sm-2 mb-2 mb-sm-0 d-block d-sm-inline-block">саяхан</span>';
        $html .= '</div>';
        $html .= '<p>' . $_POST['commentText'] . '</p>';
        $html .= '<ul class="list-inline font-size-sm mb-0">';
        $html .= '<li class="list-inline-item"><a href="javascript:void(0);" class="bgbtn">Хариулах</a></li>';
        $html .= '<li class="list-inline-item mr-2"><a href="javascript:void(0);"><i class="icon-thumbs-up2" style="top:-2px;"></i></a></li>';
        $html .= '<li class="list-inline-item"><a href="javascript:void(0);"><i class="icon-thumbs-down2"></i></a></li>';
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';

        $response['text'] = Lang::line('msg_save_success');
        $response['html'] = $html;
        echo json_encode($response);
    }

    public function lawfollow() {

        $sessionUserKeyId = Ue::sessionUserKeyId();
        $currentDate = Date::currentDate();

        $followed = Input::post('type');
        $recordId = Input::post('recordId');
        $uniqId = Input::post('uniqId');

        switch ($followed) {
            case '0':
                $params = array(
                    'id' => NULL,
                    'refStructureId' => '1594273392339',
                    'recordId' => $recordId,
                    'userId' => $sessionUserKeyId,
                    'roleId' => NULL,
                    'isActive' => '1',
                    'createdDate' => $currentDate,
                    'createdUserId' => $sessionUserKeyId,
                );


                includeLib('Utils/Functions');
                $response = Functions::runProcess('LIS_DISCUSSION_FOLLOW_DV_001', $params);

                break;
            default:
                $params = array(
                    'id' => NULL,
                    'isActive' => '1',
                    'modifiedDate' => $currentDate,
                    'modifiedUserId' => $sessionUserKeyId,
                );

                includeLib('Utils/Functions');
                $response = Functions::runProcess('LIS_DISCUSSION_UNFOLLOW_DV_002', $params);
                break;
        }

        echo json_encode($response);
    }

    public function civProfile() {
        $this->view->title = $this->lang->line('profile_title');

        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->breadcrumbs[] = array('title' => $this->view->title);

        $this->view->isEdit = true;
        $this->view->row = $this->model->getProfileData();

        $this->view->render('header');
        $this->view->render('/lawrender/civprofile', self::$viewName);
        $this->view->render('footer');
    }

    public function editParagraph() {
        
        $postData = Input::postData();
        $this->view->isEdit = 'true';
        $this->view->uniqId = $postData['uniqId'];
        
        $this->view->paragraphKeyId = issetParam($postData['paragraphKeyId']);
        
        if ($postData['iseditOnly'] == 'false') {
            $this->view->ajaxTree = (issetParam($this->view->paragraphKeyId) !== '') ? $this->model->getAddonchangesTree($postData['lawId']) : array();
        } else {
            $this->view->ajaxTree = array();
        }
        
        $this->view->lawtypeid = issetParam($postData['lawtypeid']);
        $this->view->lawtype = array();
        $this->view->semanticConnection = array();
        
        if ($this->view->lawtypeid) {
            $this->view->lawtype = $this->db->GetRow("SELECT * FROM LIS_LAW_TYPE WHERE ID = '". $this->view->lawtypeid ."'");
        }

        if (1 == 0) {
            $this->view->semanticConnection = $this->db->GetAll("SELECT 
                                                                        t0.ID,
                                                                        t1.ID AS LAW_KEY_ID,
                                                                        t2.PARAGRAPH_NUMBER ||' '||t2.PARAGRAPH_TEXT AS OLD_PARAGRAPH_TEXT,
                                                                        t4.PARAGRAPH_NUMBER ||' '||t4.PARAGRAPH_TEXT AS NEW_PARAGRAPH_TEXT,
                                                                        t6.NAME
                                                                    FROM META_DM_RECORD_MAP t0
                                                                    INNER JOIN LIS_LAW_KEY t1 ON t0.TRG_RECORD_ID = t1.ID AND t1.LAW_ID = '". $postData['lawId'] ."'
                                                                    INNER JOIN LIS_LAW t5 ON t1.LAW_ID = t5.ID 
                                                                    INNER JOIN LIS_LAW_TYPE t6 ON t5.LAW_TYPE_ID = t6.ID
                                                                    INNER JOIN LIS_LAW_PARAGRAPH t2 ON t1.PARAGRAPH_ID = t2.ID
                                                                    INNER JOIN LIS_LAW_KEY t3 ON t0.SRC_RECORD_ID = t3.ID
                                                                    INNER JOIN LIS_LAW_PARAGRAPH t4 ON t3.PARAGRAPH_ID = t4.ID
                                                                    WHERE t0.SRC_TABLE_NAME = 'LIS_LAW_KEY' 
                                                                        AND t0.TRG_TABLE_NAME = 'LIS_LAW_KEY' 
                                                                        AND t0.SEMANTIC_TYPE_ID = '2003'  
                                                                        AND t0.SRC_RECORD_ID = '". $this->view->paragraphKeyId ."' ORDER BY t0.ID");

            if ($this->view->semanticConnection) {
                $this->view->semanticConnection = Arr::groupByArrayOnlyRows($this->view->semanticConnection, 'NAME');
            }
        }

        $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => issetParam($postData['paragraphKeyId']) ? $this->view->renderPrint('/lawrender/paragraph/edit', self::$viewName) : '',
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function addParagraph() {
        
        $postData = Input::postData();
        $this->view->isEdit = 'true';
        $this->view->uniqId = $postData['uniqId'];
        
        $this->view->paragraphKeyId = issetParam($postData['paragraphKeyId']);
        
        $this->view->ajaxTree = $this->model->getAddonchangesTree($postData['lawId']);
        $this->view->lawtypeid = issetParam($postData['lawtypeid']);
        $this->view->lawtype = array();
        $this->view->semanticConnection = array();
        
        $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('/lawrender/paragraph/add', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function deleteParagraphForm() {
        
        $postData = Input::postData();
        
        $this->view->isEdit = 'true';
        $this->view->uniqId = $postData['uniqId'];
        $this->view->paragraphKeyId = issetParam($postData['paragraphKeyId']);
        $this->view->ajaxTree = $this->model->getAddonchangesTree($postData['lawId']);
        $this->view->defdescription = $this->db->GetOne("SELECT TITLE||' '|| TO_CHAR(ENACTED_DATE, 'YYYY-MM-DD')||' өдрийн нэмэлт өөрчлөлтөөр хүчингүйд тооцов.'   FROM LIS_LAW WHERE ID = '". $postData['lawId'] ."'");
        $this->view->lawtypeid = issetParam($postData['lawtypeid']);
        
        $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => $this->view->renderPrint('/lawrender/paragraph/delete', self::$viewName),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function viewParagraph() {
        
        $postData = Input::postData();
        $this->view->isEdit = 'false';
        $this->view->uniqId = $postData['uniqId'];
        $this->view->paragraphKeyId = issetParam($postData['paragraphKeyId']);
        
        $this->view->paragrapthData = $this->db->GetRow("
                                                            SELECT 
                                                                t0.ID, 
                                                                t0.paragraph_id, 
                                                                NVL(t1.DESCRIPTION, t1.PARAGRAPH_TEXT) AS PARAGRAPH_TEXT, 
                                                                t1.TITLE,
                                                                t1.LAW_ID 
                                                            FROM lis_law_KEY t0 
                                                            INNER JOIN lis_law_paragraph t1 ON t0.paragraph_id = t1.id
                                                            WHERE t0.ID = '". $this->view->paragraphKeyId ."'");
        
        $this->view->semanticConnection = $this->db->GetAll("SELECT 
                                                                    t0.ID,
                                                                    t1.ID AS LAW_KEY_ID,
                                                                    t2.PARAGRAPH_NUMBER ||' '||t2.PARAGRAPH_TEXT AS OLD_PARAGRAPH_TEXT,
                                                                    t2.TITLE,
                                                                    t4.PARAGRAPH_NUMBER ||' '||t4.PARAGRAPH_TEXT AS NEW_PARAGRAPH_TEXT,
                                                                    t6.NAME,
                                                                    t1.LAW_ID,
                                                                    t5.LAW_TYPE_ID
                                                                FROM META_DM_RECORD_MAP t0
                                                                INNER JOIN LIS_LAW_KEY t1 ON t0.TRG_RECORD_ID = t1.ID
                                                                INNER JOIN LIS_LAW t5 ON t1.LAW_ID = t5.ID 
                                                                INNER JOIN LIS_LAW_TYPE t6 ON t5.LAW_TYPE_ID = t6.ID
                                                                INNER JOIN LIS_LAW_PARAGRAPH t2 ON t1.PARAGRAPH_ID = t2.ID
                                                                INNER JOIN LIS_LAW_KEY t3 ON t0.SRC_RECORD_ID = t3.ID
                                                                INNER JOIN LIS_LAW_PARAGRAPH t4 ON t3.PARAGRAPH_ID = t4.ID
                                                                WHERE t0.SRC_TABLE_NAME = 'LIS_LAW_KEY' 
                                                                    AND t0.TRG_TABLE_NAME = 'LIS_LAW_KEY' 
                                                                    AND t0.SEMANTIC_TYPE_ID = '2003'  
                                                                    AND t0.SRC_RECORD_ID = '". $this->view->paragraphKeyId ."' ORDER BY t0.ID");
        
        if ($this->view->semanticConnection) {
            $this->view->semanticConnection = Arr::groupByArrayOnlyRows($this->view->semanticConnection, 'NAME');
        }
        
        $response = array(
            'Title' => '',
            'Width' => '700',
            'uniqId' => $this->view->uniqId,
            'Html' => issetParam($postData['paragraphKeyId']) ? $this->view->renderPrint('/lawrender/paragraph/view', self::$viewName) : '',
            'Text' => issetParam($this->view->paragrapthData['PARAGRAPH_TEXT']),
            'save_btn' => Lang::line('save_btn'),
            'close_btn' => Lang::line('close_btn')
        );

        echo json_encode($response);
    }

    public function generateHtml() {
        
        (Array) $response = array();
        (String) $law = '';

        $postData = Input::postData();
        (Array) $idAddtion = array();
        
        if (issetParamArray($postData['idAddtion'])) {
            foreach ($postData['idAddtion'] as $key => $row) {
                if(!in_array($row, $idAddtion)) {
                    array_push($idAddtion, $row);
                }
            }
        }
        
        if (issetParam($postData['justsave']) === 'true') {
            $law = $this->model->lawFormatterNoType($postData['html'], false, true, true, false);
        }
        elseif (issetParam($postData['isfulledit']) === '6') {
            $postData['html'] = $postData['descAddtion'];
        }
        elseif (issetParam($postData['isAddtion']) === '0' && issetParam($postData['idAddtion']) === '' && issetParam($postData['paragraphKeyId']) !== '' && issetParam($postData['lawtypeid']) === '1') {
            
            $law = '';
            
            $pid = $this->db->GetOne("SELECT PARAGRAPH_ID FROM LIS_LAW_KEY WHERE ID = '". $postData['paragraphKeyId'] ."'");
            $text = (strpos($postData['html'], '<table') === false) ? Str::htmltotext($postData['html']) : $postData['html'];
            
            $this->db->AutoExecute('LIS_LAW_PARAGRAPH', array('TITLE' => Str::htmltotext($postData['html'])), 'UPDATE', "ID = '". $pid ."'");
            $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'PARAGRAPH_TEXT', $text, " ID = '". $pid ."'");
            $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'DESCRIPTION', $text, " ID = '". $pid ."'");
            
        } 
        elseif (issetParam($postData['paragraphKeyId'])) {
            if (issetParam($postData['lawtypeid']) !== '' && issetParam($postData['lawtypeid']) !== '1' && issetParam($postData['lawtypeid']) !== '2') {
                $postData['html'] = $postData['descAddtion'];
            }
            
            $response = $this->model->paragraphSave($postData);
            $law = $this->model->lawFormatter(array($postData['paragraphKeyId'] => $postData['html']), false, true, true, true);
            
        }
        elseif (issetParam($postData['addParagraphId']) !== '') {
            $response = $this->model->paragraphSave($postData);
            $law = $this->model->lawFormatter(array($postData['paragraphKeyId'] => $postData['html']), false, true, true, true);
        }
        
        echo json_encode(array('html' => $law, 'response' => $response));
        
    }
    
    public function updateAdditionalParagraph() {
        
        $postData = Input::postData();
        (Array) $idAddtion = array();
        
        if (issetParamArray($postData['idAddtion'])) {
            foreach ($postData['idAddtion'] as $key => $row) {
                if(!in_array($row, $idAddtion)) {
                    array_push($idAddtion, $row);
                }
            }
        }
        
        $postData['idAddtion'] = $idAddtion;
        
        $response = $this->model->paragraphSave($postData);
        echo json_encode(array('html' => '', 'response' => $response));
    }
    
    public function saveAddParagraph() {
        
        (Array) $response = array();
        (String) $law = '';

        $postData = Input::postData();
        $response = $this->model->paragraphSave($postData);
        $law = ''; //$this->model->lawFormatter(array($postData['paragraphKeyId'] => $postData['html']), false, true, true, true);
        
        echo json_encode(array('html' => $law, 'response' => $response));
    }
    
    public function updateParagraph() {
        
        (Array) $response = array();
        (String) $law = '';

        $postData = Input::postData();
        
        if (issetParam($postData['justsave']) === 'true') {
            $law = $this->model->lawFormatterNoType($postData['html'], false, true, true, false);
        } 
        elseif (issetParam($postData['isfulledit']) === '6') {
            $postData['html'] = $postData['descAddtion'];
        } 
        elseif (issetParam($postData['isAddtion']) === '0' && issetParam($postData['idAddtion']) === '' && issetParam($postData['paragraphKeyId']) !== '' && issetParam($postData['lawtypeid']) === '1') {
            
            $law = '';
            
            $pid = $this->db->GetOne("SELECT PARAGRAPH_ID FROM LIS_LAW_KEY WHERE ID = '". $postData['paragraphKeyId'] ."'");
            $text = (strpos($postData['html'], '<table') === false) ? Str::htmltotext($postData['html']) : $postData['html'];
            
            $this->db->AutoExecute('LIS_LAW_PARAGRAPH', array('TITLE' => Str::htmltotext($postData['html'])), 'UPDATE', "ID = '". $pid ."'");
            $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'PARAGRAPH_TEXT', $text, " ID = '". $pid ."'");
            $this->db->UpdateClob('LIS_LAW_PARAGRAPH', 'DESCRIPTION', $text, " ID = '". $pid ."'");
            
        } 
        elseif (issetParam($postData['paragraphKeyId'])) {
            
            if (issetParam($postData['lawtypeid']) !== '' && issetParam($postData['lawtypeid']) !== '1' && issetParam($postData['lawtypeid']) !== '2') {
                $postData['html'] = $postData['descAddtion'];
            }
            
            $response = $this->model->paragraphSave($postData);
            $law = $this->model->lawFormatter(array($postData['paragraphKeyId'] => $postData['html']), false, true, true, true);
            
        }
        elseif (issetParam($postData['addParagraphId']) !== '') {
            $response = $this->model->paragraphSave($postData);
            $law = $this->model->lawFormatter(array($postData['paragraphKeyId'] => $postData['html']), false, true, true, true);
        }
        
        echo json_encode(array('html' => $law, 'response' => $response));
        
    }

    public function reloadLaw() {
        
        includeLib('Utils/Functions');
        $lawId = Input::post('lawId');
        
        $lawInfo = Functions::runProcess('LIS_LAW_GET_LIST_004', array('id' => $lawId));
        $edit = Input::post('edit') === '1' ? true : false;
        $data = issetParamArray($lawInfo['result']);
        
        if (issetParam($data['lawrelationid']) !== '' && !isset($_POST['isroot'])) {
            $response = Functions::runProcess('LIS_LAW_GET_LIST_004', array('id' => $data['lawrelationid']));
            $dataRoot = issetParamArray($response['result']);
            $content = $this->model->getDataLawContentModel($dataRoot['id'], $dataRoot, false, false, false, true, true, $lawId);
        } else {
            $content = $this->model->getDataLawContentModel(Input::post('lawId'), $data, false, $edit, false, true, false, Input::post('rootId', ''));
        }
        
        echo json_encode(array('Html' => $content));
        
    }
    
    public function doctohtmlform($id = '') {
        
        $this->view->uniqId = getUID();
        $this->view->otherUniqId = getUID();

        $this->view->title = Input::post('title', 'Дэлгэрэнгүй') ;
        $this->view->metaDataId = Input::post('metaDataId');
        $this->view->issave = 'true';
        $this->view->isaddition = 'true';
        $this->view->isEdit = true;
        $this->view->rootId = '';
        
        includeLib('Utils/Functions');

        try {
            
            $this->view->postData = Input::postData();
            $this->view->rowId = ($id) ? $id : $this->view->postData['rowId'];
            $this->view->drillDown = array();
            
            if (issetParam($this->view->postData['drillDownDefaultCriteria']) !== '') {
                parse_str($this->view->postData['drillDownDefaultCriteria'], $filterParam);
                $this->view->drillDown = $filterParam;
            }
            
            $mdata = Functions::runProcess('LIS_LAW_BRIEF_GET_LIST_004', array('id' => $this->view->rowId));
            $this->view->mdata = issetParamArray($mdata['result']);
            
            $lawInfo = Functions::runProcess('LIS_LAW_GET_LIST_004', array('id' => $this->view->rowId));
            $this->view->data = issetParamArray($lawInfo['result']);
            
            $this->view->tabData = issetParamArray($this->view->data['lis_law_type_reference_list']);
            
            $this->view->criteria = issetParam($this->view->postData['selectedRow']['drillcriteria']);

            $this->view->postData = Input::postData();
            $this->view->dataViewId = issetParam($this->view->postData['dataViewId']);
            
            if ($this->view->rowId) {
                $check = $this->db->GetOne("SELECT COUNT(ID) FROM LIS_LAW_PARAGRAPH WHERE LAW_ID = '" . $this->view->rowId . "'");
                $this->view->issave = issetParam($check) === '0' ? 'false' : 'true';
            }
            
            if ($this->view->issave === 'true') { 
                $this->view->isEdit = true;
                $this->view->content = $this->model->getDataLawContentModel($this->view->rowId, $this->view->data, false, false, false, true);
                //test
                //$this->view->content = $this->model->getcontentTreeListV2($this->view->rowId, $this->view->data, false, false, false, true);
            } 
            else {
                $this->view->filePath = issetParam($this->view->data['attachfile']);
                if (!file_exists($this->view->filePath)) {
                    $this->view->title = '404 Not Found';
                    $this->view->content = $this->view->renderPrint('government/lawrender/error');
                } else {
                    $this->view->justSave = 'true';
                    $urlPath = URL;
                    $url = "http://win.interactive.mn/file_converter/Converter.aspx?mode=html&FromUrl=" . $urlPath . $this->view->filePath;
                    $saveCache = file_get_contents($url);
                    $saveCache = str_replace('&#xa0;', "", $saveCache);
                    $this->view->content = $this->model->generatecontentModel($saveCache, $this->view->data);
                }
            }
            
        } catch (Exception $ex) {
            $this->view->title = '404 Not Found';
            $this->view->setMessage = $ex->getMessage();
            $this->view->content = $this->view->renderPrint('government/lawrender/error');
        }

        if (!is_ajax_request()) {
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->css = AssetNew::metaCss();
            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
            
            $this->view->render('header');
            $this->view->render('/lawrender/doctohtmlForm', self::$viewName);
            $this->view->render('footer');
        } else {

            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/lawrender/doctohtmlForm', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response);
        }
        
    }
    
    public function doctohtmlfrm($id = '') {
        
        $this->view->uniqId = getUID();
        $this->view->otherUniqId = getUID();
        $this->view->metaDataId = Input::post('metaDataId');
        $this->view->title = 'docToHtmlForm';
        $this->view->isaddition = 'false';
        $this->view->issave = 'true';
        includeLib('Utils/Functions');
        
        try {
            
            $this->view->postData = Input::postData();
            $this->view->rowId = ($id) ? $id : $this->view->postData['rowId'];
            $this->view->criteria = '';
            
            $lawInfo = Functions::runProcess('LIS_LAW_GET_LIST_004', array('id' => $this->view->rowId));
            $this->view->lawInfo = $this->view->data = issetParamArray($lawInfo['result']);
            
            $this->view->postData = Input::postData();
            $this->view->dataViewId = issetParam($this->view->postData['dataViewId']);
            
            if ($this->view->rowId) {
                $this->view->criteria = issetParam($this->view->data['drillcriteria']);
                
                if (issetParam($this->view->data['bodytext'])) {
                    unset($this->view->data['bodytext']);
                }

                $check = $this->db->GetOne("SELECT COUNT(ID) FROM LIS_LAW_PARAGRAPH WHERE LAW_ID = '" . $this->view->rowId . "'");
                $this->view->issave = issetParam($check) === '0' ? 'false' : 'true';
            }
            
            $this->view->filePath = issetParam($this->view->data['attachfile']);

            if ($this->view->issave === 'true') {
                
                $this->view->isEdit = true;
                
                if (issetParam($this->view->data['lawrelationid']) !== '') {
                    if ($this->view->data['lawtypeid'] === '2') {
                        $this->view->connectedLaw = $this->db->GetAll(" SELECT 
                                                                                T1.REF_LAW_ID,
                                                                                T1.ID,
                                                                                T1.TITLE,
                                                                                T2.NAME
                                                                            FROM LIS_LAW T1 
                                                                            INNER JOIN LIS_LAW_TYPE T2 ON T1.LAW_TYPE_ID = T2.ID
                                                                            WHERE  T1.ID = '". $this->view->data['lawrelationid'] ."' 
                                                                        UNION ALL
                                                                            SELECT 
                                                                                T1.REF_LAW_ID,
                                                                                T1.ID,
                                                                                T1.TITLE,
                                                                                T2.NAME||' /'||T1.TITLE||'/' AS NAME
                                                                            FROM LIS_LAW T0
                                                                            INNER JOIN LIS_LAW T1 ON T0.ID = T1.REF_LAW_ID
                                                                            INNER JOIN LIS_LAW_TYPE T2 ON T1.LAW_TYPE_ID = T2.ID
                                                                            INNER JOIN LIS_LAW_KEY T3 ON T1.ID = T3.LAW_ID 
                                                                            WHERE T0.ID = '". $this->view->data['lawrelationid'] ."' AND T2.ID <> '2'
                                                                            GROUP BY T1.REF_LAW_ID,
                                                                                T1.ID,
                                                                                T1.TITLE,
                                                                                T2.NAME");

                    }
                    
                    $response = Functions::runProcess('LIS_LAW_GET_LIST_004', array('id' => $this->view->data['lawrelationid']));
                    $this->view->dataRoot = issetParamArray($response['result']);
                    $this->view->rootlaw = $this->model->getDataLawContentModel($this->view->dataRoot['id'], $this->view->dataRoot, false, false, false, true, true, $this->view->rowId);
                    
                }
                
                $this->view->content = $this->model->getDataLawContentModel($this->view->rowId, $this->view->data, false, false);
            } else {
                $this->view->filePath = issetParam($this->view->data['attachfile']);
                if (!file_exists($this->view->filePath)) {
                    $this->view->title = '404 Not Found';
                    $this->view->content = $this->view->renderPrint('government/lawrender/error');
                } else {
                    $this->view->justSave = 'true';
                    $url = "http://win.interactive.mn/file_converter/Converter.aspx?mode=html&FromUrl=" . URL . $this->view->filePath;
                    $saveCache = file_get_contents($url);
                    $saveCache = str_replace('&#xa0;', "", $saveCache);
                    $this->view->content = $this->model->generatecontentModel($saveCache, $this->view->data);
                }
            }
            
        } catch (Exception $ex) {
            $this->view->title = '404 Not Found';
            $this->view->setMessage = $ex->getMessage();
            $this->view->content = $this->view->renderPrint('government/lawrender/error');
        }

        if (!is_ajax_request()) {
            
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->css = AssetNew::metaCss();
            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');

            $this->view->render('header');
            $this->view->render('/lawrender/doctohtmlForm', self::$viewName);
            $this->view->render('footer');
        } else {
            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/lawrender/doctohtmlForm', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response);
        }
    }

    public function savelaw() {
        $response = $this->model->saveLawProcessModel();
        echo json_encode($response);
    }

    public function migrationlaw__($rowId) {
        
        $this->view->uniqId = getUID();
        $this->view->otherUniqId = getUID();

        $this->view->title = 'docToHtmlForm';
        $this->view->issave = 'false';
        $this->view->isaddition = 'true';
        $this->view->justSave = 'true';

        try {
            
            $this->view->postData = Input::postData();
            $this->view->rowId = $rowId;

            $lawInfo = $this->model->fncRunDataview('1591170775374416', "id", "=", $this->view->rowId);
            $this->view->lawInfo = issetParamArray($lawInfo[0]);
            $this->view->criteria = issetParam($this->view->postData['selectedRow']['drillcriteria']);
            
            $this->view->tabData = array();

            if (!issetParam($this->view->lawInfo['lawcategoryid'])) {
                throw new Exception("not_working_get");
            }

            $lawCategoryId = issetParam($this->view->lawInfo['lawcategoryid']) ? $this->view->lawInfo['lawcategoryid'] : "1593408542060";
            $this->view->tabData = $this->model->fncRunDataview('1594019364745610', "id", "=", $lawCategoryId, array(), "", "", "0", true);
            
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->css = AssetNew::metaCss();
            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');

            $this->view->postData = Input::postData();
            $this->view->dataViewId = issetParam($this->view->postData['dataViewId']);
            
            $this->view->data = array();
            $saveCache = $this->db->GetOne("SELECT BODY_TEXT FROM LIS_LAW WHERE ID = '". $this->view->rowId ."'");
            
            $saveCache = cleanOut($saveCache);
            $saveCache = asci2uni($saveCache);
            
            $this->view->content = $this->model->generatecontentModel($saveCache, $this->view->data, true);
            
        } catch (Exception $ex) {
            
            $this->view->title = '404 Not Found';
            $this->view->setMessage = $ex->getMessage();
            $this->view->content = $this->view->renderPrint('government/lawrender/error');
            
        }

        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('/lawrender/doctohtmlForm', self::$viewName);
            $this->view->render('footer');
        } else {
            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/lawrender/doctohtmlForm', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );
            echo json_encode($response);
        }
        
    }

    public function deleteSemantic() {
        $result = $this->db->Execute("DELETE FROM META_DM_RECORD_MAP WHERE ID = '". Input::post('recordId') ."'");
        echo json_encode(array('result' => $result));
    }
    
    public function removeParagraph() {
        
        $postData = Input::postData();
        
        if (issetParam($postData['idAddtion']) === '') {
            echo json_encode(array('status' => 'error', 'title' => 'error', 'message' => 'error'));
            exit();
        }
        
        try {
            
            $currentDate = Date::currentDate();
            $sessionUserKeyId = Ue::sessionUserKeyId();
            $paragraphKeyId = $postData['paragraphKeyId'];
            $idAddtion = $postData['idAddtion'];
            $html = issetParam($postData['html']);

            if (strpos($html, '<table') === false) { 
                $html = Str::htmltotext($html);
            }

            $qry = "SELECT 
                        T0.ID,
                        T0.LAW_ID,
                        T0.PARAGRAPH_ID,
                        T0.VERSION_NUMBER,
                        T0.ACT_NUMBER,
                        T1.PARAGRAPH_TEXT,
                        T1.DESCRIPTION,
                        T1.DISPLAY_ORDER,
                        T1.RELATED_ID,
                        T1.PARENT_ID,
                        T1.VERSION_NUMBER AS PVERSION_NUMBER
                    FROM LIS_LAW_KEY T0 
                    LEFT JOIN LIS_LAW_PARAGRAPH T1 ON T0.PARAGRAPH_ID = T1.ID
                    WHERE T0.ID = '$paragraphKeyId'";

            $oldData = $this->db->GetRow($qry);

            $oldLaw = $this->model->getDataLawContentModel($oldData['LAW_ID'], array(), false, false, true);
            $newLaw = $this->model->newLawParagraphSave($postData, $currentDate, $sessionUserKeyId, $oldData, '1');

            $qry = "SELECT 
                        T0.ID,
                        T0.LAW_ID,
                        T0.PARAGRAPH_ID,
                        T0.VERSION_NUMBER,
                        T0.ACT_NUMBER,
                        T1.PARAGRAPH_TEXT,
                        T1.DESCRIPTION,
                        T1.DISPLAY_ORDER,
                        T1.RELATED_ID,
                        T1.VERSION_NUMBER AS PVERSION_NUMBER
                    FROM LIS_LAW_KEY T0 
                    LEFT JOIN LIS_LAW_PARAGRAPH T1 ON T0.PARAGRAPH_ID = T1.ID
                    WHERE T0.ID = '$idAddtion'";

            $chooseData = $this->db->GetRow($qry);
            
            $id = getUID();
            $data = array(
                'ID' => $id,
                'SRC_RECORD_ID' => $paragraphKeyId,
                'TRG_RECORD_ID' => $idAddtion,
                'SEMANTIC_TYPE_ID' => '2003',
                'SRC_TABLE_NAME' => 'LIS_LAW_KEY',
                'TRG_TABLE_NAME' => 'LIS_LAW_KEY'
            );

            $result = $this->db->AutoExecute('META_DM_RECORD_MAP', $data);

            if (!$result) {
                throw new Exception("new META_DM_RECORD_MAP error!");
            }

            $data = array(
                'ID' => $id,
                'BOOK_DATE' => $currentDate,
                'LAW_ID' => $oldData['LAW_ID'],
                'CHANGE_LAW_ID' => $chooseData['LAW_ID'],
                'CREATED_DATE' => $currentDate,
                'CREATED_USER_ID' => $sessionUserKeyId,
                'ROW_STATE_CODE' => '',
            );

            $result = $this->db->AutoExecute('LIS_LAW_CHANGE_BOOK', $data);

            if (!$result) {
                throw new Exception("new LIS_LAW_CHANGE_BOOK error!");
            }

            $result = $this->db->UpdateClob('LIS_LAW_CHANGE_BOOK', 'PREV_BODY_TEXT', $oldLaw, " ID = '". $id ."'");

            if (!$result) {
                throw new Exception("old LIS_LAW_CHANGE_BOOK PREV_BODY_TEXT error!");
            }

            $result = $this->db->UpdateClob('LIS_LAW_CHANGE_BOOK', 'NEXT_BODY_TEXT', $newLaw, " ID = '". $id ."'");

            if (!$result) {
                throw new Exception("old LIS_LAW_CHANGE_BOOK NEXT_BODY_TEXT error!");
            }

            $data = array(
                'ID' => $id,
                'BOOK_ID' => $id,
                'PARAGRAPH_NUMBER' => $oldData['ACT_NUMBER'],
                'MAP_ID' => $id,
            );

            $result = $this->db->AutoExecute('LIS_LAW_CHANGE_BOOK_DTL', $data);

            if (!$result) {
                throw new Exception("new LIS_LAW_CHANGE_BOOK_DTL error!");
            }

            $result = $this->db->UpdateClob('LIS_LAW_CHANGE_BOOK_DTL', 'PREV_PARAGRAPH_TEXT', $oldData['PARAGRAPH_TEXT'], " ID = '". $id ."'");

            if (!$result) {
                throw new Exception("old LIS_LAW_CHANGE_BOOK_DTL PREV_PARAGRAPH_TEXT error!");
            }

            $result = $this->db->UpdateClob('LIS_LAW_CHANGE_BOOK_DTL', 'NEXT_PARAGRAPH_TEXT', $html, " ID = '". $id ."'");

            if (!$result) {
                throw new Exception("old LIS_LAW_CHANGE_BOOK_DTL NEXT_PARAGRAPH_TEXT error!");
            }
            
            $response = array('status' => 'success', 'text' => Lang::line('msg_save_success'));
            
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'text' => Lang::line('msg_save_error'), 'ex' => $ex->msg);
        }
        
        echo json_encode($response);
        
    }
    
    public function migrationlaw($rowId) {
        
        $this->view->uniqId = getUID();
        $this->view->otherUniqId = getUID();

        $this->view->title = 'docToHtmlForm';
        $this->view->issave = 'false';
        $this->view->isaddition = 'true';
        $this->view->justSave = 'true';

        try {
            $this->view->postData = Input::postData();
            $this->view->rowId = $rowId;
            $this->view->data = array();
            $lawInfo = $this->model->fncRunDataview('1591170775374416', "id", "=", $this->view->rowId);
            
            $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
            $this->view->fullUrlJs = AssetNew::metaJs();
            $this->view->css = AssetNew::metaCss();
            $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
            
            $saveCache = $this->db->GetOne("SELECT BODY_TEXT FROM LIS_LAW WHERE ID = '". $this->view->rowId ."'");
            
            $saveCache = cleanOut($saveCache);
            $saveCache = asci2uni($saveCache);
            
            $this->view->content = $this->model->generateContentModelV2($saveCache);
            
        } catch (Exception $ex) {
            $this->view->title = '404 Not Found';
            $this->view->setMessage = $ex->getMessage();
            $this->view->content = $this->view->renderPrint('government/lawrender/error');
        }

        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('/lawrender/doctohtmlForm_1', self::$viewName);
            $this->view->render('footer');
        } else {

            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/lawrender/doctohtmlForm_1', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response);
        }
        
        
    }

    public function saveMigrationlaw() {
        $postData = Input::postData();
        $lawId = $postData['lawId'];
        $html = $postData['content'];

        $html = strip_tags($html, "<title><table><thead><tbody><tfood><tr><th><td><p><section><img>");
        $html = explode('</title>', $html);
        $html = (isset($html[1]) ? $html[1] : $html[0]);
        
        $law_arr = preg_split('/(<[^>]*[^\/]>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        
        (Int) $tcounter = 0;
        (String) $htmlcontent = $table = '';
        $law = array();
        foreach ($law_arr as $key => $row) {
            
            if (strpos($row, '<table') !== false) {
                $htmlcontent .= $row;
                $tcounter ++;
            }
            
            if (strpos($row, '/table') !== false) {
                $htmlcontent .= $row;
                $tcounter--;
            }
            
            if ($tcounter !== 0) {
                $htmlcontent .= $row;
            } else {
                if (!$htmlcontent) {
                    $htmlcontent = strpos($row, '<img') !== false ? $row : Str::sanitize($row);
                }
                
                if ($htmlcontent) {
                    array_push($law, $htmlcontent);
                }
                
                $htmlcontent = '';
            }
        }
        
        $response = $this->model->saveLawProcessModel_1($law, $html);
        echo json_encode($response);

    }
    
    public function coviddashboardv1() {
        
        $currentDate = Date::currentDate('y_m_d');
        $cache = phpFastCache();
        
        $this->view->taskNews = $this->model->fncRunDataview('1586846284071', "", "", '', array(), 'createddate', 'desc', '0', false, '6');
        
        $this->view->title = 'Дашбоард';
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
       
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main2.css');
        $this->view->isAjax = is_ajax_request();
        $this->view->uniqId = getUID();
        
        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('/custom/dasboardv1', self::$viewName);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
        
    }
    
    public function vaccinesdashboard () {
        
        $this->view->title = 'Дашбоард';
        
        $currentDate = Date::currentDate('y_m_d');
        $cache = phpFastCache();
        
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
       
        $this->view->fullUrlCss = array('middleware/assets/css/covid19/main2.css');
        $this->view->isAjax = is_ajax_request();
        $this->view->uniqId = getUID();
        $this->load->model('mddatamodel', 'middleware/models/');

        $vaccinesAll = $cache->get('bpvaccinesAll_gl_' . $currentDate);
        if ($vaccinesAll == null) {
            $this->view->vaccinesAll = $this->model->getDataMartDvRowsModel('1601620413233162');
            if ($this->view->vaccinesAll) {
                $cache->set('bpvaccinesAll_gl_' . $currentDate, $this->view->vaccinesAll, '144000000');
            }
        } else {
            $this->view->vaccinesAll = $vaccinesAll;
        }

        $agevaccines = $cache->get('bpagevaccines_gl_' . $currentDate);
        if ($agevaccines == null) {
            $this->view->agevaccines = $this->model->getDataMartDvRowsModel('1601623681232143');
            if ($this->view->agevaccines) {
                $cache->set('bpagevaccines_gl_' . $currentDate, $this->view->agevaccines, '144000000');
            }
        } else {
            $this->view->agevaccines = $agevaccines;
        }

        $popvaccines = $cache->get('bppopvaccines_gl_' . $currentDate);
        if ($popvaccines == null) {
            $this->view->popvaccines = $this->model->getDataMartDvRowsModel('1601624596247730');
            if ($this->view->popvaccines) {
                $cache->set('bppopvaccines_gl_' . $currentDate, $this->view->popvaccines, '144000000');
            }
        } else {
            $this->view->popvaccines = $popvaccines;
        }

        if (!$this->view->isAjax) {
            $this->view->render('header');
        }
        
        $this->view->render('/custom/vaccines', self::$viewName);
        
        if (!$this->view->isAjax) {
            $this->view->render('footer');
        }
        
    }
    
    public function dataMigrationLisLaw() {
        $data = $this->db->GetAll("select * from lis_law where is_imported = 0 LIMIT 5000");       
        foreach ($data as $key => $rowLaw) {

            $lawId = $rowLaw['ID'];
            $html = $rowLaw['ORIG_BODY_TEXT'];
            $_POST['lawId'] = $rowLaw['ID'];

            $html = str_replace('&lt;img src=&quot;/image/b.gif&quot; /&gt;', '', $html);
            $html = cleanOut($html);
            $html = asci2uni($html);
            $html = strip_tags($html, "<title><table><thead><tbody><tfood><tr><th><td><p><section><img>");
            $html = str_replace('amp;', '', $html);

            $html = explode('</title>', $html);

            $html = (isset($html[1]) ? $html[1] : $html[0]);
            
            $law_arr = preg_split('/(<[^>]*[^\/]>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            
            $law_arr = preg_split('/(<[^>]*[^\/]>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            
            (Int) $tcounter = 0;
            (String) $htmlcontent = $table = '';
            $law = array();
            foreach ($law_arr as $key => $row) {
                
                if (strpos($row, '<table') !== false) {
                    $htmlcontent .= $row;
                    $tcounter ++;
                }
                
                if (strpos($row, '/table') !== false) {
                    $htmlcontent .= $row;
                    $tcounter--;
                }
                
                if ($tcounter !== 0) {
                    $htmlcontent .= $row;
                } else {
                    if (!$htmlcontent) {
                        $htmlcontent = strpos($row, '<img') !== false ? $row : Str::sanitize($row);
                    }
                    
                    if ($htmlcontent) {
                        array_push($law, $htmlcontent);
                    }
                    
                    $htmlcontent = '';
                }
            }
            
            $response = $this->model->saveLawProcessModel_1($law, $html);
            $this->db->Execute("UPDATE LIS_LAW SET IS_IMPORTED = 1, IMPORTED_DATE = '". Date::currentDate() ."' WHERE ID = '". $lawId ."'");
            echo 'success = ' . $lawId . '; <br>';
        }
    }
    
    public function mohsapi() {
            
        $currentDate = Date::currentDate('Y-m-d');
        $beforeDate = Date::beforeDate('Y-m-d', '-1 days');
        $methodname = 'api.health.gov.mn.getVacs';
        $checkData = $this->db->GetOne("SELECT COUNT(ID) FROM SYSINT_SERVICE_METHOD_LOG WHERE web_service_name = '$methodname' AND TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') = '". $currentDate ."'");
        
        if (issetParam($checkData) !== '0') {
            echo $currentDate . ' inserted';
            die;
        }
        
        $username = 'tYUdk2m4uq';
        $password = 'u*ZGm%235tN-6e';
        $mainurl = "https://api.health.gov.mn/oauth/token";
        $url = $mainurl . "?username=$username&password=$password&grant_type=password";
        
        $opts = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic VV9mZl05Qmp5WlhMbUcmZHcmOlo3JHtFenlyRDRheUN9RkxkJg=="
        ));

        
        $curl = curl_init();
        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $insertdata = array();
        $id = getUID();
        $this->load->model('mdintegration', 'middleware/models/');
        
        if ($err) {
            $this->model->createServiceMethodLog($mainurl, 'api.health.gov.mn', $url, $err, true);
            $response = $err;
            $result = array('status' => 'error', 'code' => 'curl', 'message' => $err);
        } else {
            
            $this->model->createServiceMethodLog($mainurl, 'api.health.gov.mn', $url, $response, true);
            $token = json_decode($response, true);
            
            $url = "https://api.health.gov.mn/moh/getVacs?access_token=". $token['access_token'] ."&bDate=". $beforeDate ."&eDate=". $currentDate ."&page=1&size=1000";
            $opts = array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                    );
            
            curl_setopt_array($curl, $opts);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            if ($err) {
                $this->model->createServiceMethodLog($url, $methodname . '.error', $url, $response, true);
                $response = $err;
                $result = array('status' => 'error', 'code' => 'curl', 'message' => $err);
            } else {
                $vacdata = json_decode($response, true);
                $this->model->createServiceMethodLog($url, $methodname, $url, $response, true);
                
                if (issetParam($vacdata['total']) !== '') {
                    $total = issetParamZero($vacdata['total']);
                    $insertdata = issetParamArray($vacdata['data']);
                    
                    if (1000 < $total) {
                        $length1 = ($total%1000) > 0 ? 1 : 0;
                        $length = round($total/1000) + $length1;
                        $insertdata = array();
                        
                        for ($index = 1; $index <= $length; $index++) {
                            
                            $url = "https://api.health.gov.mn/moh/getVacs?access_token=". $token['access_token'] ."&bDate=". $beforeDate ."&eDate=". $currentDate ."&page=". $index ."&size=1000";
                            $opts = array(
                                CURLOPT_URL => $url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "GET",
                            );
                            curl_setopt_array($curl, $opts);
                            $response = curl_exec($curl);
                            $err = curl_error($curl);
                            
                            if ($err) {
                                $this->model->createServiceMethodLog($url, $methodname . '.error', $url, $response, true);
                                $response = $err;
                                $result = array('status' => 'error', 'code' => 'curl', 'message' => $err);
                            } else {
                                $this->model->createServiceMethodLog($url, $methodname, $url, $response, true);
                                
                                $vacdata = json_decode($response, true);
                                if (issetParamArray($vacdata['data'])) {
                                    $insertdata = array_merge($insertdata, $vacdata['data']);
                                }
                            }
                            
                        }
                    }
                }
            }
        }
        
        curl_close($curl);
        
        foreach ($insertdata as $row) {
            $temp = array(  'DIM1' => $row['accId'], 
                            'TEXT1' => $row['regDate'], 
                            'DIM2' => $row['vacId'], 
                            'TEXT2' => $row['vacAge'], 
                            'TEXT3' => $row['hospitalName'], 
                            'TEXT4' => $row['hosOfficeCode'], 
                            'TEXT5' => $row['hosOfficeName'], 
                            'TEXT6' => $row['hosSubOffCode'], 
                            'TEXT7' => $row['hosSubOffName'], 
                            'TEXT8' => $row['vacDate'], 
                            'TEXT9' => $row['weight'], 
                            'TEXT10' => $row['height'], 
                            'TEXT11' => $row['vacType'], 
                            'TEXT12' => $row['gender'], 
                            'TEXT13' => $row['birthDate'], 
                            'CREATED_DATE' => $beforeDate, 
                            'TEXT14' => $row['status']);
            $this->db->AutoExecute('HIS_REPORT_API_SERVICE_LOG', $temp);
        }
        
        echo $beforeDate . ' success ';
    }
    
    public function vacdistrictData() {
        $this->load->model('mddatamodel', 'middleware/models/');
        $criteria = array();
        $criteria['filtercityid'][] = array(
                        'operator' => '=',
                        'operand' =>  Input::post('aimagId')
                    );
        $this->view->map = $this->model->getDataMartDvRowsModel('1600661473043', $criteria);
        
        echo json_encode($this->view->map);
    }

    public function hrunitdashboard() {
        $this->view->isNotary = Config::getFromCache('isNotaryServer');
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $this->view->agent = false;

        $this->view->css = AssetNew::metaCss();
        $this->view->fullUrlCss = array('middleware/assets/css/intranet/style.css');
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->currentDate = Date::currentDate('Y-m-d');
        $this->view->currentTime = Date::currentDate('H:i');

        $this->view->layoutPositionArr = $this->model->dashboardLayoutDataModel();
        $this->view->uniqId = getUID();
        $this->view->pollData = $this->model->getPollDataModel();

        if (Config::getFromCache('isNotaryServer')) {
            $this->view->notariesList = $this->model->fncRunDataview('1584011654594693');
            $this->view->locationLookup = $this->model->fncRunDataview('1584012022800161');
            $this->view->timeTableLookup = $this->model->fncRunDataview('1584010421279967');
            $this->view->genderChart = json_encode($this->model->fncRunDataview('1591166562522846'));
            $this->view->notariasAge = json_encode($this->model->fncRunDataview('1591167141214065'));
            $this->view->notariasStatus = json_encode($this->model->fncRunDataview('1591168674711351'));
            $this->view->notariasStatusP = $this->model->fncRunDataview('1591168674711351');
            $this->view->legalList = $this->model->fncRunDataview('1593498151336726');
        }


        $this->load->model('mdintegration', 'middleware/models/');
        $this->view->weatherData = self::getForecast5day();

        $this->view->eventBox = $this->view->renderPrint('/meeting/hrevent', self::$viewName);
        $this->view->pollBox = $this->view->renderPrint('/meeting/poll', self::$viewName);

        // $this->view->unit_js = $this->view->renderPrint('/meeting/include/js', self::$viewName);
        $this->view->app_js = $this->view->renderPrint('/meeting/include/hrjs', self::$viewName);
        $this->view->app_css = $this->view->renderPrint('/meeting/include/css', self::$viewName);

        //        $url = "http://monxansh.appspot.com/xansh.json?currency=USD|EUR|KRW|CNY|RUB|GBP|JPY";
        
        if (Config::getFromCache('noUseKhanbankApi') === '1') {
            $this->view->exchangeData = array();
        } else {
            /* $url = "https://kbknew.khanbank.com/api/site/currency?lang=mn&site=personal&date";
            $data = file_get_contents($url);
            $xchange = json_decode($data, true); */
            $this->view->exchangeData = array(); /* issetParamArray($xchange['data']); */
        }

        $this->view->title = 'Хяналтын самбар';

        if (!is_ajax_request()) {
            $this->view->render('header');
            $this->view->render('/meeting/hragentdashboard', self::$viewName);
            $this->view->render('footer');
        } else {

            $response = array(
                'Title' => '',
                'Width' => '700',
                'uniqId' => $this->view->uniqId,
                'Html' => $this->view->renderPrint('/meeting/hragentdashboard', self::$viewName),
                'save_btn' => Lang::line('save_btn'),
                'close_btn' => Lang::line('close_btn')
            );

            echo json_encode($response);
            die;
        }      
    }    
    
    public function exportExcel($id) {

        includeLib('Utils/Functions');
        $result = Functions::runProcess('SCL_POLL_GET_LIST_004', array('id' => $id));
        $data = issetParamArray($result['result']);

        $html = '<center>';
            $html.= '<table style="width: 80%;" >';
                $html .= '<tbody>';
                    $html .= '<tr>';
                        $html .= '<td colspan="6" style="min-height: 50px; text-align: left"><b>' . issetParam($data['description']) . '</b></td>';
                    $html .= '</tr>';
                    $html .= '<tr>';
                        $html .= '<td colspan="6"  style="min-height: 50px; text-align: left;"><b>awfawfawf' . html_entity_decode($data['longdescr'], ENT_QUOTES, 'UTF-8') . '</b></td>';
                    $html .= '</tr>';
                $html .= '</tbody>';
            $html .= '</table>';
        $html .= '</center>';

        $html .= '';
        $html.= '<center>';
            $html.= '<table style="width: 80%;" border="1">';
                $html .= '<tbody>';
                    $html .= '<tr>';
                        $html .= '<td colspan="3" style="text-align: right">Нийт санал</td>';
                        $html .= '<td colspan="3" style="text-align: left"><b>' . issetParam($data['par_scl_result_list'][0]['votedcount']) . '</b></td>';
                    $html .= '</tr>';
                    $html .= '<tr>';
                        $html .= '<td colspan="3" style="text-align: right">Дуусах хугацаа</td>';
                        $html .= '<td colspan="3" style="text-align: left"><b>' . issetParam($data['par_scl_result_list'][0]['enddate']) . '</b></td>';
                    $html .= '</tr>';
                    $html .= '<tr>';
                        $html .= '<td colspan="3" style="text-align: right">Үлдсэн хоног</td>';
                        $html .= '<td colspan="3" style="text-align: left"><b>' . issetParam($data['par_scl_result_list'][0]['leftdays']) . '</b></td>';
                    $html .= '</tr>';
                $html .= '</tbody>';
            $html .= '</table>';
        $html .= '</center><br>';

        $html .= '<table style="width: 100%; " border="1" class="table table-bordered">';
            $html .= '<thead>';
                $html .= '<tr>';
                    $html .= '<th style="width: 20px; background: #4caf50; color: #FFF" rowspan="2" class="fs-9 text-center"><b>№</b></th>';
                    $html .= '<th style="width: 150px; background: #4caf50; color: #FFF" rowspan="2" class="fs-9 text-center"><b>Нэр</b></th>';
                    $html .= '<th style="width: 150px; background: #4caf50; color: #FFF" rowspan="2" class="fs-9 text-center"><b>Салбар нэгж</b></th>';
                    $html .= '<th style="width: 150px; background: #4caf50; color: #FFF" rowspan="2" class="fs-9 text-center"><b>Албан тушаал</b></th>';
                    foreach ($data['scl_posts_question_list'] as $row) {
                        $html .= '<th class="fs-9 text-center" style=" background: #4caf50; color: #FFF" colspan="'. sizeOf($row['scl_posts_answer_list']) .'"><b>'. $row['questiontxt'] .'</b></th>';
                    }
                $html .= '</tr>';
                $html .= '<tr>';
                foreach ($data['scl_posts_question_list'] as $row) {
                    foreach($row['scl_posts_answer_list'] as $alist)
                    $html .= '<th class="fs-9 text-center" style=" background: #4caf50; color: #FFF"> <span class="rotate-90-inverse">'. $alist['answertxt'] .'</span></th>';
                }
                $html .= '</tr>';

            $html .= '</thead>';
            $html .= '<tbody>';
            $index = 1;

            foreach($data['par_scl_user_list'] as $row) {
                $html .= '<tr style="border-bottom: 1px solid #d8d8d8;">';
                    $html .= '<td class="fs-9 text-center">' . $index++ . '</td>';
                    $html .= '<td class="fs-9">' . $row['personname'] . '</td>';
                    $html .= '<td class="fs-9">' . $row['departmentname'] . '</td>';
                    $html .= '<td class="fs-9">' . $row['positionname'] . '</td>';
                    foreach ($data['scl_posts_question_list'] as $row1) {
                        foreach($row1['scl_posts_answer_list'] as $alist) {
                            if (issetParam($alist['par_scl_poll_result_dv'])) {
                                $groupAnswerArr = Arr::groupByArrayOnlyRows($alist['par_scl_poll_result_dv'], 'userid');
                                if (issetParam($groupAnswerArr[$row['userid']])) {
                                    if (issetParam($row1['isother']) === '1') {
                                        $html .= '<td class="fs-9 text-center">'. issetDefaultVal($groupAnswerArr[$row['userid']][0]['answerdescription'], '+') .'</td>';
                                    } else {
                                        $html .= '<td class="fs-9 text-center">+</td>';
                                    }
                                } else {
                                    $html .= '<td class="fs-9 text-center"></td>';
                                }
                            } else {
                                $html .= '<td class="fs-9 text-center"></td>';   
                            }
                        }
                    }
                $html .= '</tr>';
            }

            $html .= '</tbody>';
        $html .= '</table><br>';



        
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        $reportName = $fileName = Date::currentDate('YmdHi').'.xls';
        
        try {
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=true; path=/');
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header('Content-Type: application/vnd.ms-excel;charset=utf-8');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            flush();
            
            
            $htmlContent = $html;
            /* $htmlContent = $this->view->render('/mail/print', self::$viewName); */
            
            
            echo excelHeadTag($htmlContent, $reportName, issetParam($_POST['editableObjs'])); exit();
            
        } catch (Exception $e) {
            
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Set-Cookie: fileDownload=false; path=/');
            
            echo $e->getMessage(); exit();
        }
    }
    
}
