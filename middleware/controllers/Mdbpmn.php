<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

/**
 * Mdbpmn Class 
 * 
 * @package     IA PHPframework
 * @subpackage	Middleware
 * @category	BPMN
 * @author	B.Och-Erdene <ocherdene@interactive.mn>
 * @link	http://www.interactive.mn/PHPframework/Middleware/Mdbpmn
 */
class Mdbpmn extends Controller {
    
    private static $viewPath = 'middleware/views/bpmn/';
    private static $mdViewPath = 'middleware/views/metadata/';
    private static $tag = array('&lt;', '&gt;', '&quot;', '&#34;', '&amp;#34;', "&nbsp;");
    private static $replaceTag = array('leftTagLeft', 'rightTagRight', 'doubleTagQuote', 'doubleTagQuotationMark', 'doubleTagQuotationMark', ' ');

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function viewGraph() {
        
        $this->view->graphXml = $this->model->getGraphRowModel();
        $this->view->graphXml = self::graphXmlSpecialCharReplace($this->view->graphXml);
        
        $this->view->render('viewGraph', self::$viewPath);
    }
    
    public function bpmLink() {
        $this->view->render('system/link/bpm/bpmLink', self::$mdViewPath);
    }
    
    public function bpmLinkEditMode() {
        
        $this->view->metaDataId = Input::numeric('metaDataId');
        $this->view->bpRow = $this->model->getBpmLinkModel($this->view->metaDataId);
        
        $this->view->graphXml = self::graphXmlSpecialCharReplace(Arr::get($this->view->bpRow, 'GRAPH_XML'));
                
        $this->view->render('system/link/bpm/bpmLinkEditMode', self::$mdViewPath);
    }
    
    public function bpmEditor() {
        $this->view->render('system/link/bpm/bpmEditor', self::$mdViewPath);
    }
    
    public function bpmEditorById() {
        
        $id = Input::post('id');
        
        $this->view->graphXml = $this->model->getBpmGraphXmlById($id);
        $this->view->graphXml = self::graphXmlSpecialCharReplace($this->view->graphXml);
        
        $response = array(
            'title' => 'BPM', 
            'html' => $this->view->renderPrint('system/link/bpm/bpmEditorById', self::$mdViewPath), 
            'id' => $id, 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveBpmGraphXml() {
        $response = $this->model->saveBpmGraphXmlModel();
        echo json_encode($response); exit;
    }
    
    public function bpmEditorByConfig() {
        
        $this->view->graphXml = $this->model->getBpmGraphXmlByConfigId();
        $this->view->graphXml = self::graphXmlSpecialCharReplace($this->view->graphXml);
        
        $response = array(
            'title' => 'BPM', 
            'html' => $this->view->renderPrint('system/link/bpm/bpmEditorById', self::$mdViewPath), 
            'save_btn' => $this->lang->line('save_btn'), 
            'close_btn' => $this->lang->line('close_btn')
        );
        echo json_encode($response); exit;
    }
    
    public function saveBpmGraphXmlByConfig() {
        $response = $this->model->saveBpmGraphXmlByConfigModel();
        echo json_encode($response); exit;
    }
    
    public function bpmEditorByPostInput() {
        
        $this->view->graphXml = Input::postNonTags('graphData');
        $this->view->graphXml = self::graphXmlSpecialCharReplace($this->view->graphXml);
        
        $response = array(
            'html' => $this->view->renderPrint('system/link/bpm/bpmEditorById', self::$mdViewPath)
        );
        echo json_encode($response); exit;
    }
    
    public static function graphXmlSpecialCharReplace($graphXml) {
        
        $graphXml = str_replace(self::$tag, self::$replaceTag, $graphXml);
        
        return $graphXml;
    }
    
    public static function graphXmlSpecialCharReplaceReverse($graphXml) {
        
        $graphXml = str_replace(self::$replaceTag, self::$tag, $graphXml);
        
        return $graphXml;
    }

    public function bpmn($mainBpId = null) 
    {
        $this->view->css = AssetNew::metaCss();
        $this->view->js = AssetNew::metaOtherJs();
        $this->view->generateBpmnScript = "";

        $_POST["mainBpId"] = $mainBpId;

        $metaRow = $this->model->getMetaDataModel(Input::post('mainBpId'));
        if (empty($metaRow['ADDON_XML_DATA'])) {
            $oldBpmnData = (new Mdprocessflow)->drawProcessHtml(true);
            $this->view->generateBpmnScript = self::generateBpmnScriptFunction($oldBpmnData);
        }
        $this->view->mainBpId = $mainBpId;

        $this->view->render('header');
        $this->view->render('index', self::$viewPath);
        $this->view->render('footer');
    }    

    public function bpmn2($mainBpId = "") 
    {
        $this->view->generateBpmnScript = '';
        $this->view->savedValue = [];

        $this->load->model('mdprocessflow', 'middleware/models/');
        $metaRow = $this->model->getBpmnXmlModel($mainBpId);
        if ($metaRow) {
            $this->view->generateBpmnScript = $metaRow["BPMN"];
            $this->view->savedValue = $metaRow;
        }
        $this->view->mainBpId = $mainBpId;
        $this->view->uniqId = getUID();
        $this->view->bpUniqId = Input::post('bpUniqId');
        $this->view->bpPath = "";

        $response['html'] = $this->view->renderPrint('index2', self::$viewPath);
        echo json_encode($response); exit;                    
    }         

    public function generateBpmnScriptFunction($oldBpmnData) {
        $generateBpmnScript = "";

        foreach ($oldBpmnData["object"] as $row) {
            if ($row["id"] == 0) {
                $generateBpmnScript .= "var shape".$row["id"]." = CreateStartEvent();";
            } else {
                $generateBpmnScript .= "var shape".$row["id"]." = CreateTask();";
                $generateBpmnScript .= " shape".$row["id"].".businessObject.set('processid', '".$row["doBpId"]."'); ";                
                $generateBpmnScript .= " modeling.updateProperties(shape".$row["id"].", { name: '(".$row["metaDataCode"].") ".$row["title"]."' }); ";
            }
        }
        $connectGroup = Arr::groupByArrayOnlyRows($oldBpmnData["connect"], "SOURCE");        
        $objectGroup = Arr::groupByArray($oldBpmnData["object"], "id");        
        $isGateway = false;
        $generateBpmnScript .= " setTimeout(function() { var root = canvas.getRootElement(); ";
            
        foreach ($connectGroup as $rowkey => $row) {
            if (count($row) > 1) {
                $isGateway = true;
            }
        }        

        foreach ($oldBpmnData["object"] as $row) {
            $left = 80;
            $top = 80;
            if ($isGateway && $row["id"] != 0) {
                $left = 250;
            }
            $generateBpmnScript .= "modeling.createShape(shape".$row["id"].", {x: ".($row["positionLeft"]+$left).", y: ".($row["positionTop"]+$top)."}, root); ";
        }                

        // if ($isGateway) {
        //     $generateBpmnScript .= "modeling.connect(shape0, shape".$connectGroup[0][0]["TARGET"]."); ";
        // }

        // pa($connectGroup);
        foreach ($connectGroup as $rowkey => $row) {
            if (count($row) > 1) {

                $ckey2 = 0.5;
                $generateBpmnScript .= "var gateway".$rowkey." = CreateGateway();";
                $generateBpmnScript .= "modeling.createShape(gateway".$rowkey.", {x: ".($rowkey == 0 ? 250 : $objectGroup[$rowkey]["row"]["positionLeft"] + $left - 100).", y: ".($objectGroup[$rowkey]["row"]["positionTop"] + $ckey2 * 100)."}, root); ";                

                foreach ($row as $ckey => $crow) {
                    $generateBpmnScript .= "modeling.updateProperties(modeling.connect(gateway".$rowkey.", shape".$crow["TARGET"]."), { name: '".Str::sub($crow["CRITERIA"], 35)."', criteria: '".Str::smart_clean($crow["CRITERIA"])."' }); ";
                    // if ($ckey > 1) {
                    //     $generateBpmnScript .= "modeling.updateProperties(modeling.connect(gateway".($ckey - 1).", gateway".$ckey."), { name: 'No' }); ";
                    // }
                    if ($ckey === 0) {
                        $generateBpmnScript .= "modeling.connect(shape".$crow["SOURCE"].", gateway".$rowkey."); ";
                    }
                    // $ckey2++;
                }
            } else {
                $generateBpmnScript .= "var taskConn = modeling.connect(shape".$rowkey.", shape".$row[0]["TARGET"]."); ";
                $generateBpmnScript .= " taskConn.businessObject.set('criteria', ''); ";                
            }
        }
        $generateBpmnScript .= " var elementRegistry = bpmnModeler.get('elementRegistry');
        var elementStart = elementRegistry.get('StartEvent_1');
        modeling.removeElements([ elementStart ]); ";
        $generateBpmnScript .= " }); ";

        return $generateBpmnScript;
    }    
    
}
