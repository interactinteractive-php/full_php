<link rel="stylesheet" href="https://unpkg.com/bpmn-js@8.9.1/dist/assets/diagram-js.css">
<link rel="stylesheet" href="https://unpkg.com/bpmn-js@8.9.1/dist/assets/bpmn-font/css/bpmn.css">

<style>
    .pf-header-main-content, #canvas {
      height: 100%;
      padding: 0;
      margin: 0;
    }
    .bjs-powered-by {
      display:none;
    }
    /* .bpmn-icon-subprocess-expanded, .bpmn-icon-data-object, .bpmn-icon-data-store, 
    .bpmn-icon-group, .bpmn-icon-intermediate-event-none, .bpmn-icon-lasso-tool, .bpmn-icon-space-tool {
      display:none;
    } */
    /* .djs-container {
      margin-left: 70px;
    } */
    /* .diagram-note {
      background-color: rgba(66, 180, 21, 0.7);
      color: White;
      border-radius: 5px;
      font-family: Arial;
      font-size: 12px;
      padding: 5px;
      min-height: 16px;
      width: 50px;
      text-align: center;
    } */

    /* .needs-discussion:not(.djs-connection) .djs-visual > :nth-child(1) {
      stroke: rgba(66, 180, 21, 0.7) !important;
    } */
    .bpmntooltip{
        background-color:#000;
        color:#fff;
        border-radius:4px;
        padding:5px;
        position:absolute;
        display:none;
        max-width:600px;
    }    
    .bpmn-header-title {
      font-size: 13px;
      padding-left: 10px;
      border-bottom: 2px solid #e1e1e1;
      padding-bottom: 7px;      
      display: none;
    }
</style>

<div class="bpmn-main-container">
  <div class="bpmn-header-title"></div>
  <div class="bpmn-render-wrap" style="height: 100%;width:100%;display:table;">
    <div id="canvas" style="display: table-cell;"></div>
    <div class="p-2 pt0" id="properties" style="display: table-cell;width: 200px;min-width: 200px;max-width: 200px;border-left: 2px solid #e1e1e1;vertical-align: top;">
      <button class="btn btn-secondary btn-sm btn-circle" title="Fullscreen" onclick="bpmnFullscreenMode(this);return false" style="position: absolute;margin-left: -55px;"><i class="fa fa-expand"></i></button>
      <button class="btn btn-secondary btn-sm btn-circle" title="Export" onclick="bpmnExportXml(this);return false" style="position: absolute;margin-left: -98px;"><i class="fa fa-download"></i></button>
      <button class="btn btn-secondary btn-sm btn-circle" title="Import" onclick="bpmnImportXml(this)" style="position: absolute;margin-left: -132px;"><i class="fa fa-upload"></i><input class="d-none" type='file' id="bpmn_hidden_file_input" accept='text/xml' onchange='bpmnOpenFile(event)'></button>      
      <button type="button" class="btn bg-success-400 btn-block bpmn-select-domainbp" style="white-space: nowrap;"><i class="far fa-check-circle"></i> Домайн процесс сонгох</button>
      <div class="d-flex mt8 hidden">
        <button type="button" class="btn bg-success-400 btn-block bpmn-select-datamodel hidden"><i class="far fa-database"></i> Дата модель</button>
        <button type="button" class="btn bg-success-400 bpmn-datamodel-parameter-config" style="white-space: nowrap;overflow: hidden;" title=""><i class="far fa-cogs"></i> DM параметер тохиргоо</button>        
      </div>
      <button type="button" class="hidden btn bg-success-400 bpmn-run-datamodel mt8 btn-block" style="white-space: nowrap;overflow: hidden;" title=""><i class="far fa-play"></i> Ажиллуулах</button>      
      <input type="hidden" class="bpmn-data-model-id" value="<?php echo issetParam($this->savedValue["DATA_MODEL_ID"]) ?>">
      <button type="button" class="btn bg-success-400 btn-block bpmn-parameter-config mt8 hidden"><i class="far fa-cogs"></i> Параметер тохиргоо</button>
      <div class="bpmn-role-config hidden mt10">
        <select class="form-control select2 form-control-lg" multiple data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option></select>
      </div>
      <div>
        <textarea rows="5" class="mt8 hidden bpmn-bp-criteria" style="width: 100%;border-color: #ccc;font-size: 15px;padding: 5px; margin-bottom: -6px;" placeholder="BP Criteria"></textarea>
      </div>
      <button type="button" id="bpmn-acceptsave-button" class="btn green-meadow btn-block mt8 hidden"><i class="far fa-check"></i> Баталгаажуулах</button>
      <button type="button" id="bpmn-save-button" class="btn bg-primary-400 btn-block mt8"><i class="far fa-save"></i> Хадгалах</button>
      <div class="bpmn-datamodel-param-list hidden">
      </div>
    </div>
  </div>
  <div class="bpmntooltip"></div>
</div>
<a id="downloadAnchorElem" style="display:none"></a>

<script>
    $("head").append('<link rel="stylesheet" type="text/css" href="assets/bpmnjs/vendor/bpmn-js/assets/bpmn-js.css"/>');
    $("head").append('<link rel="stylesheet" type="text/css" href="assets/bpmnjs/vendor/bpmn-js/assets/diagram-js.css"/>');

    var indicatorId =  $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').length ? $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').find('input[data-path="id"]').val() : '<?php echo $this->indicatorId ?>';
    var diagramXML = "<?php echo "<?xml version='1.0' encoding='UTF-8'?>" .
      "<bpmn:definitions xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:bpmn2='http://www.omg.org/spec/BPMN/20100524/MODEL' xmlns:bpmndi='http://www.omg.org/spec/BPMN/20100524/DI' xmlns:dc='http://www.omg.org/spec/DD/20100524/DC' xmlns:di='http://www.omg.org/spec/DD/20100524/DI' xsi:schemaLocation='http://www.omg.org/spec/BPMN/20100524/MODEL BPMN20.xsd' id='sample-diagram' targetNamespace='http://bpmn.io/schema/bpmn'>" .
      "<bpmn:process id='Process_1' isExecutable='false'>" .
      "<bpmn:startEvent id='StartEvent_1'/>" .
      "</bpmn:process>" .
      "<bpmndi:BPMNDiagram id='BPMNDiagram_1'>" .
      "<bpmndi:BPMNPlane id='BPMNPlane_1' bpmnElement='Process_1'>" .
      "<bpmndi:BPMNShape id='_BPMNShape_StartEvent_2' bpmnElement='StartEvent_1'>" .
      "<dc:Bounds height='36.0' width='36.0' x='200.0' y='150.0'/>" .
      "</bpmndi:BPMNShape>" .
      "</bpmndi:BPMNPlane>" .
      "</bpmndi:BPMNDiagram>" .
      "</bpmn:definitions>"; ?>";    

    <?php if (!empty($this->generateBpmnScript)) { ?>
      diagramXML = '<?php echo html_entity_decode(Str::smart_clean($this->generateBpmnScript), ENT_QUOTES, 'UTF-8'); ?>';
    <?php } ?>    

//    $.cachedScript("http://localhost:8080/app.js").done(function() {
     $.cachedScript('<?php echo autoVersion('assets/bpmnjs/app.js'); ?>').done(function() {
      $(".bpmn-render-wrap").css("height", $(window).height() - $(".bpmn-render-wrap").offset().top - 50);          
    });     
    
    $('.bpmn-role-config').on('change', 'select', function(event) {
         var $self = $(this);
         var values = $self.val();
         var text = $self.find(":selected").toArray().map(item => item.text).join(",");
         comboValueSetToModeling(values, text);
    });    
    
    $('.bpmn-select-domainbp').on('click', function () {
//        var selectedElements = bpmnModeler.get('selection').get();      
//        if (!selectedElements[0] || selectedElements[0]["type"] !== "bpmn:Task") {
//          PNotify.removeAll();
//          new PNotify({
//              title: 'Warning',
//              text: "Process таск дээр тохируулах боломжтой",
//              type: 'warning',
//              sticker: false
//          });                
//          return;
//        }              
        dataViewCustomSelectableGrid('processList2column', 'single', 'bpmnSelectabledGrid_<?php echo $this->uniqId; ?>', '', this);
        // dataViewCustomSelectableGrid('PRL_CALC_DV2', 'single', 'calcSelectabledGrid_<?php //echo $this->uniqId; ?>', 'param[booktypeid]=15001&param[calctypeid]='+$("#calcInfoForm_<?php //echo $this->uniqId; ?>").find('select[name="calcTypeId"]').val(), this);
    });            
    
    function bpmnSelectabledGrid_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        //modeling.setColor(selectedElements[0], {stroke: 'green', stroke: 'black'})        
        domainbpValueSetToModeling(row);
    }       

    function bpmnFullscreenMode(elem) {
      var $dialogName = $('div.bpmn-main-container');
      if ($dialogName.hasClass('bp-dtl-fullscreen')) {
        $dialogName.removeClass('bp-dtl-fullscreen');
        $(elem).find('i').removeClass('fa-compress').addClass('fa-expand');
        $(".bpmn-render-wrap").css("height", $(window).height() - $(".bpmn-render-wrap").offset().top - 50);
      } else {
        $dialogName.addClass('bp-dtl-fullscreen');
        $(elem).find('i').removeClass('fa-expand').addClass('fa-compress');
        $(".bpmn-render-wrap").css("height", $(window).height());
      }
      return;
    }        
    
    function comboDatasBpmnFunction(comboDatas) {
      var roleHtml = $('.bpmn-role-config');
      var $select = roleHtml.find("select");    
      $select.select2({results: comboDatas});
    }
</script>
