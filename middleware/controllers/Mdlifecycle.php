<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdlifecycle Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	Lifecycle
 * @author	S.Satjan <satjan@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdlifecycle
 */
class Mdlifecycle extends Controller {

    private static $viewPath = 'middleware/views/lifecycle/';
    private static $uploadedFiles = array();

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }

    public function index() {

        $this->view->srcRecordId = Input::numeric('srcRecordId');
        $this->view->selectedRow = Input::post('selectedRow');
        $this->view->uniqId = getUID();

        $response = array(
            'Html' => $this->view->renderPrint('index', self::$viewPath),
            'Title' => 'Lifecycle',
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function index_v1() {
        
        $this->view->uniqId = getUID();
        
        $this->view->lifecycleId = Input::post('lifecycleid');
        $this->view->selectedRow = Input::post('selectedRow');
        $this->view->mainMetaDataId = Input::post('dataViewId');
        $this->view->recordId = $this->view->selectedRow['id'];
        $this->view->lifecycletaskId = (isset($this->view->selectedRow['lifecycletaskid'])  && $this->view->selectedRow['lifecycletaskid']) ? $this->view->selectedRow['lifecycletaskid'] : '';
        
        $this->load->model('mdobject', 'middleware/models/');
        $mainAttributes = $this->model->getDataViewMetaValueAttributes(null, null, $this->view->mainMetaDataId);
        
        $this->view->mainMetaDataCode = strtolower($mainAttributes['code']);
        $this->view->mainMetaDataName = strtolower($mainAttributes['name']);
        
        $this->view->treeDvId = issetParam($this->view->selectedRow['treedvid']);

        $response = array(
            'Html' => $this->view->renderPrint('index_v1', self::$viewPath),
            'Title' => 'Сайтын ажил',
            'close_btn' => Lang::line('close_btn')
        );
        echo json_encode($response); exit;
    }

    public function getlifeCycleTreeList() {
        
        $selectiveId = Input::get('selectiveId');
        $result = $this->model->getlifeCycleTreeListModel($selectiveId);
        
        $response = $this->recursiveLifeCycleList($result, $selectiveId);

        echo json_encode($response); exit;
    }
    
    public function getlifeCycleTreeList_v1() {
        
        $lifecycleId = Input::post('lifecycleId');
        $recordId = Input::post('recordId');
        $lifecycletaskId = (Input::postCheck('lifecycletaskId')) ? Input::post('lifecycletaskId') : '';
        $treeDvId = Input::post('treeDvId');
        
        if (Input::post('parent') !== 'ok') {
			
            $param = array(
                'parentid' => array(
                    array(
                        'operator' => '=',
                        'operand' => Input::post('parent')
                    )
                )
            );
		} else {
			$param = array(
				'parentid' => array(
					array(
						'operator' => 'IS NULL',
						'operand' => ''
					)
				)
			);
		}
        
        $result = $this->model->getlifeCycleTreeListModel_v1($lifecycleId, $recordId, $param, $lifecycletaskId, $treeDvId);
        
        $response = $this->recursiveLifeCycleList_v1($result, $lifecycleId, $recordId, $lifecycletaskId, $treeDvId);

        echo json_encode($response); exit;
    }

    private function recursiveLifeCycleList($result, $selectiveId) {
        
        $this->load->model('mdlifecycle', 'middleware/models/');
        
        $response = array();

        foreach ($result as $value) {
            
            $param = array(
                'parentid' => array(
                    array(
                        'operator' => '=',
                        'operand' => $value['trgrecordid']
                    )
                )
            );

            $children = false;

            $color = isset($value['wfmstatuscolor']) ? $value['wfmstatuscolor'] : '';
            $wfmStatusName = isset($value['wfmstatusname']) ? $value['wfmstatusname'] : '';
            $taskname = isset($value['taskname']) ? (strlen($value['taskname']) > 35 ? Str::utf8_substr($value['taskname'], 0, 25) : $value['taskname']) : '';
            $count = isset($value['count']) ? $value['count'] : '0';
            $countFile = $this->model->countAttachedFilesModel($selectiveId, $value['trgrecordid']);
            $addCss = $inlineStyle = '';
            
            if ($countFile == 0) {
                $addCss = 'background-color:#e87e04;';
            }
            
            if ($value['isplanned'] == '1') {
                $inlineStyle = 'font-weight: bold;';
            }

            $response[] = array(
                'children' => $children,
                'icon' => 'fa fa-folder text-orange-400',
                'id' => $value['id'],
                'li_attr' => array('data-tid' => $value['trgrecordid']),
                'data' => $value,
                'state' => array(
                    'disabled' => false,
                    'loaded' => true,
                    'opened' => true,
                    'selected' => false,
                ),
                'text' => '<span data-selectedlifecyclewfm="' . $value['selectedlifecyclewfm'] . '" title="' . $value['taskname'] . '" style="'.$inlineStyle.'">' . $taskname . '</span> <span class="count-selective-task taskFileUploadBtn" style="'
                . $addCss . 'padding:0px 6px;"><i class="fa fa-upload" style="font-size:12px;"></i></span><span class="count-selective-task" style="margin-right:29px;">'
                . $count . '</span> <span class="count-selective-task" style="background-color: ' . $color . ';margin-right:60px;">' . $wfmStatusName . '</span>',
            );
        }

        return $response;
    }
    
    private function recursiveLifeCycleList_v1($result, $lifecycleId, $recordId, $lifecycletaskId = null, $treeDvId = null) {
        $response = array();

        foreach ($result as $value) {
            $param = array(
                'parentid' => array(
                    array(
                        'operator' => '=',
                        'operand' => $value['id']
                    )
                )
            );
            
			if (issetParam($value['haschild']) === '1') { 
				$children = true;
			} else {
				$resultChild = $this->model->getlifeCycleTreeListModel_v1($lifecycleId, $recordId, $param, $lifecycletaskId, $treeDvId);
				
				$children = false;
				
				if (!empty($resultChild)) {
					$children = true;
					$children = $this->recursiveLifeCycleList_v1($resultChild, $lifecycleId, $recordId, $lifecycletaskId, $treeDvId);
				}
			}
            
            $color = isset($value['wfmstatuscolor']) ? $value['wfmstatuscolor'] : '';
            $wfmStatusName = isset($value['wfmstatusname']) ? $value['wfmstatusname'] : '';
            $taskname = isset($value['taskname']) ? (strlen($value['taskname']) > 35 ? Str::utf8_substr($value['taskname'], 0, 25) : $value['taskname']) : '';
            $count = isset($value['count']) ? $value['count'] : '0';
            $countFile = '0'; //$this->model->countAttachedFilesModel($selectiveId, $value['trgrecordid']);
            $addCss = $inlineStyle = '';
            $countText = ($count !== '0') ? ' <span class="count-selective-task" style="margin-right:29px;">'. $count . '</span>' : '';
            if ($countFile == 0) {
                $addCss = 'background-color:#e87e04;';
            }
            /*
            if ($value['isplanned'] == '1') {
                $inlineStyle = 'font-weight: bold;';
            }
            */
            $response[] = array(
                'children' => $children,
                'icon' => 'fa fa-folder text-orange-400',
                'id' => $value['id'],
                'li_attr' => array('data-tid' => $value['parentid']),
                'data' => $value,
                'state' => array(
                    'disabled' => false,
                    'loaded' => true,
                    'opened' => false,
                    'selected' => false,
                ),
                'text' => '<span title="' . $value['taskname'] . '" style="'.$inlineStyle.'">' . $taskname . '</span> ' . $countText,
            );
        }

        return $response;
    }

    // <editor-fold defaultstate="collapsed" desc="File upload">
    
    public function getFileUploadModal() {
        $this->view->mapId = Input::post('id');
        $this->view->taskId = Input::post('taskId');

        $this->load->model('mdmetadata', 'middleware/models/');
        $this->view->metaValueFileRows = $this->model->getMetaDataValueFilesModel($this->view->mapId, $this->view->taskId);

        $response = array(
            'Title' => 'File',
            'width' => '1000px',
            'height' => '500px',
            'close_btn' => Lang::line('close_btn'),
            'save_btn' => Lang::line('save_btn'),
            'html' => $this->view->renderPrint('lcFileList', self::$viewPath),
        );
        echo json_encode($response);
    }

    public function uploadFileLifeCycle() {
        (Array) self::$uploadedFiles = array();
        (Array) $fileParamData = array();
        (Array) $fileData = Input::fileData();

        $fileData = $fileData['bp_file'];

        if (count($fileData) > 0) {

            $metaDataId = Input::numeric('metaDataId');
            $metaValueId = Input::post('metaValueId');

            $newFileName = "file_" . getUID();
            $oldFileName = $fileData['name'];
            $fileExtension = strtolower(substr($oldFileName, strrpos($oldFileName, '.') + 1));
            $fileName = $newFileName . "." . $fileExtension;
            $filePath = UPLOADPATH . "contentui/";
            FileUpload::SetFileName($fileName);
            FileUpload::SetTempName($fileData['tmp_name']);
            FileUpload::SetUploadDirectory($filePath);
            FileUpload::SetValidExtensions(explode(",", Config::getFromCache('CONFIG_FILE_EXT')));
            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize()); //10mb
            $uploadResult = FileUpload::UploadFile();

            if ($uploadResult) {
                $attachFileId = getUID();
                $dataAttachFile = array(
                    'CONTENT_ID' => $attachFileId,
                    'FILE_NAME' => $oldFileName,
                    'PHYSICAL_PATH' => UPLOADPATH . "contentui/" . $fileName,
                    'FILE_EXTENSION' => $fileExtension,
                    'FILE_SIZE' => $fileData['size'],
                    'CREATED_USER_ID' => Ue::sessionUserKeyId(),
                    'IS_PHOTO' => '0'
                );

                $attachFile = $this->db->AutoExecute('ECM_CONTENT', $dataAttachFile);
                if ($attachFile) {
                    $dataMetaValue = array(
                        'ID' => getUID(),
                        'REF_STRUCTURE_ID' => $metaDataId,
                        'RECORD_ID' => $metaValueId,
                        'CONTENT_ID' => Input::param($attachFileId),
                        'ORDER_NUM' => 1
                    );
                    $this->db->AutoExecute('ECM_CONTENT_MAP', $dataMetaValue);
                }
                if ($attachFile) {
                    echo json_encode(array('status' => 'success', 'attachFile' => $fileName, 'attachName' => $oldFileName, 'extension' => $fileExtension,
                        'attachId' => $attachFileId, 'fileExtension' => $fileExtension, 'message' => 'Амжилттай нэмлээ'));
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'Файл хуулаад бичлэг нэмхэд алдаа гарлаа'));
                }
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Файл хуулахад алдаа гарлаа'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Файл сонгоогүй байна'));
        }
    }

    public function updateFileLifeCycle() {
        (Array) self::$uploadedFiles = array();
        (Array) $fileParamData = array();

        $attachId = Input::post('attachId');
        $bpFileName = Input::post('bp_file_name');

        if (isset($_FILES['bp_file'])) {

            $newFileName = "file_" . getUID();
            $fileData = $_FILES['bp_file'];
            $fileName = $fileData['name'];
            $fileExtension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
            $fileName = $newFileName . "." . $fileExtension;
            $filePath = UPLOADPATH . "contentui/";
            FileUpload::SetFileName($fileName);
            FileUpload::SetTempName($fileData['tmp_name']);
            FileUpload::SetUploadDirectory($filePath);
            FileUpload::SetValidExtensions(explode(",", Config::getFromCache('CONFIG_FILE_EXT')));
            FileUpload::SetMaximumFileSize(FileUpload::GetConfigFileMaxSize()); //10mb
            $uploadResult = FileUpload::UploadFile();

            if ($uploadResult) {
                $r = $this->db->GetRow("SELECT PHYSICAL_PATH FROM ECM_CONTENT WHERE CONTENT_ID = " . $attachId);
                if (count($r) > 0) {
                    if (is_file($r['PHYSICAL_PATH'])) @unlink($r['PHYSICAL_PATH']);
                }

                $dataAttachFile = array(
                    'PHYSICAL_PATH' => UPLOADPATH . "contentui/" . $fileName,
                    'FILE_EXTENSION' => $fileExtension,
                    'FILE_SIZE' => $fileData['size'],
                );

                if ($bpFileName != '') {
                    $dataAttachFile = array_merge($dataAttachFile, array('FILE_NAME' => $bpFileName));
                }
                $attachFile = $this->db->AutoExecute('ECM_CONTENT', $dataAttachFile, 'UPDATE', 'CONTENT_ID = ' . $attachId);
                if ($attachFile) {
                    echo json_encode(array('status' => 'success', 'attachFile' => $fileName, 'attachName' => $bpFileName, 'extension' => $fileExtension,
                        'attachId' => $attachId, 'message' => 'Амжилттай заслаа'));
                } else {
                    echo json_encode(array('status' => 'success', 'message' => 'Алдаа гарлаа'));
                }
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Файл хуулахад алдаа гарлаа'));
            }
        } else {
            $dataAttachFile = array(
                'FILE_NAME' => $bpFileName
            );
            $attachFile = $this->db->AutoExecute('ECM_CONTENT', $dataAttachFile, 'UPDATE', 'CONTENT_ID = ' . $attachId);
            if ($attachFile) {
                echo json_encode(array('status' => 'success', 'attachFile' => '', 'attachName' => $bpFileName, 'extension' => '', 'attachId' => $attachId,
                    'message' => 'Амжилттай заслаа'));
            } else {
                echo json_encode(array('status' => 'success', 'message' => 'Алдаа гарлаа'));
            }
        }
    }

    // </editor-fold>
}
