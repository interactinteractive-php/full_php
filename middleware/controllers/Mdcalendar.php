<?php

if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdcalendar Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Calendar
 * @author	S.Satjan <satjan@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdcalendar
 */
class Mdcalendar extends Controller {

    private static $viewPath = 'middleware/views/metadata/system/link/calendar/';

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function calendarRenderByPost() {

        $this->view->calUniqId = getUID();
        $this->view->calendarMetaRow = $this->model->getCalendarLinkDataModel(Input::numeric('metaDataId'));

        if ($this->view->calendarMetaRow) {

            $this->load->model('mdobject', 'middleware/models/');
            $this->view->metaDataId = $this->view->calendarMetaRow['TARGET_META_DATA_ID'];
            $this->view->dataViewHeaderData = $this->model->dataViewHeaderDataModel($this->view->metaDataId);
            $this->view->defaultCriteria = $this->view->renderPrint('defaultCriteria', 'middleware/views/dashboard/');

            $response = array(
                'Html' => $this->view->renderPrint('renderCalendar', self::$viewPath),
                'Width' => !is_null($this->view->calendarMetaRow['WIDTH']) ? ($this->view->calendarMetaRow['WIDTH']) : 1000,
                'Title' => $this->view->calendarMetaRow['META_DATA_NAME'],
                'close_btn' => Lang::line('close_btn')
            );
            
            echo json_encode($response); exit;
        }
    }

    public function getCalendarEvents() {
        
        $response = $this->model->getCalendarEventsModel();
        
        echo json_encode($response); exit;
    }

    public function getRefTimeIntervalList() {
        
        $this->load->model('mdcalendar', 'middleware/models/');
        $response = $this->model->getRefTimeIntervalListModel();

        return $response;
    }

}
