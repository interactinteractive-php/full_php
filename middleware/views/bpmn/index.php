<link rel="stylesheet" href="https://unpkg.com/bpmn-js@8.9.1/dist/assets/diagram-js.css">
<link rel="stylesheet" href="https://unpkg.com/bpmn-js@8.9.1/dist/assets/bpmn-font/css/bpmn.css">
<!-- modeler distro -->
<script src="https://unpkg.com/bpmn-js@8.9.1/dist/bpmn-modeler.development.js"></script>

<style>
    .pf-header-main-content, #canvas {
      height: 100%;
      padding: 0;
      margin: 0;
    }
    .bpmn-icon-subprocess-expanded, .bpmn-icon-data-object, .bpmn-icon-data-store, 
    .bpmn-icon-group, .bpmn-icon-intermediate-event-none, .bpmn-icon-lasso-tool, .bpmn-icon-space-tool {
      display:none;
    }
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
  </style>

  <div class="" style="height: 100%;width:100%;display:table;">
    <div id="canvas" style="display: table-cell;"></div>
    <div class="p-2" id="properties" style="display: table-cell;width: 400px;min-width: 400px;max-width: 400px;border-left: 2px solid #e1e1e1;vertical-align: top;">
      <button type="button" class="btn btn-lg bg-success-400 btn-block bpmn-select-bp"><i class="far fa-check-circle"></i> Процесс сонгох</button>
      <button type="button" class="btn btn-lg bg-success-400 btn-block bpmn-parameter-config"><i class="far fa-cogs"></i> Параметер тохиргоо</button>
      <div class="bpmn-role-config hidden mt10">
      </div>
      <div>
        <textarea rows="5" class="mt8 hidden bpmn-bp-criteria" style="width: 100%;border-color: #ccc;font-size: 15px;padding: 5px; margin-bottom: -6px;" placeholder="BP Criteria"></textarea>
      </div>
      <button type="button" id="bpmn-save-button" class="btn btn-lg bg-primary-400 btn-block mt8"><i class="far fa-save"></i> Хадгалах</button>
    </div>
  </div>
  <div class="bpmntooltip"></div>

  <script>

    // var diagramUrl = 'https://cdn.staticaly.com/gh/bpmn-io/bpmn-js-examples/dfceecba/starter/diagram.bpmn';
    var diagram = "<?php echo "<?xml version='1.0' encoding='UTF-8'?>" .
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

    // modeler instance
    var bpmnModeler = new BpmnJS({
      container: '#canvas',
      keyboard: {
        bindTo: window
      }
    });

    var elementFactory = bpmnModeler.get('elementFactory'),
        modeling = bpmnModeler.get('modeling'),
        canvas = bpmnModeler.get('canvas');    
    var $tooltip = $('.bpmntooltip');

    bpmnModeler.on('element.hover', function(event) {
      var $self = this;
      var selectedElement = event.element;      
      // console.log('element.hover :>> ', selectedElement);

      if (selectedElement.type === "label") {
        $tooltip.css({"top": (selectedElement.y + 0) + "px", "left": selectedElement.x + "px"}).show().text(selectedElement.businessObject.$attrs.criteria);
      } else {
        $tooltip.hide();
      }
    });      

    bpmnModeler.on('element.click', function(event) {
      var selectedElement = event.element;
      console.log('element :>> ', selectedElement);
      if (selectedElement.type === "bpmn:SequenceFlow") {
        $(".bpmn-bp-criteria").removeClass("hidden");
        $(".bpmn-bp-criteria").val(selectedElement.businessObject.$attrs.criteria);
      } else {
        $(".bpmn-bp-criteria").addClass("hidden");
      }

      if (selectedElement.type === "bpmn:Participant") {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/getRole',
            data: {
              mainBpId: "<?php echo $this->mainBpId; ?>"
            },
            beforeSend: function () {
            },
            success: function (data) {
              var roleHtml = $('.bpmn-role-config');
              roleHtml.empty();
              var html = '<select class="form-control form-control-lg" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
              if (data && data.length > 0) {
                  var sel;
                  $.each(data, function (key, row) {
                    sel = "";
                    if (row.ROLE_ID == selectedElement.businessObject.$attrs["roleid"]) {
                      sel = " selected";
                    }
                      html += '<option'+sel+' value="' + row.ROLE_ID + '">' + row.ROLE_NAME + '</option>';
                  });
              }
              html += '</select>'; 
              roleHtml.html(html);              
              $(".bpmn-role-config").removeClass("hidden");
            },
            error: function () { alert("Error"); }
        });                
      } else {
        $(".bpmn-role-config").addClass("hidden");
      }
      // bpmnModeler.get('modeling').updateProperties(element, { id: 'bbbbbbbbbbbbb' });
    });      

    $(".bpmn-bp-criteria").on('change', function(event) {
      var selectedElements = bpmnModeler.get('selection').get();
      selectedElements[0].businessObject.set('criteria', $(this).val());
    });      

    $(".bpmn-role-config").on('change', 'select', function(event) {
      var $self = $(this);
      var selectedElements = bpmnModeler.get('selection').get();
      selectedElements[0].businessObject.set('roleid', $self.val());
      modeling.updateProperties(selectedElements[0], { name: $self.val() == "" ? "" : $self.find(":selected").text() });              
    });      

    callMetaParameter = async function () {
        
        var dialogName = '#bpChildDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var $dialog = $(dialogName);
        var selectedElements = bpmnModeler.get('selection').get();

        if (!selectedElements[0] || selectedElements[0]["type"] !== "bpmn:Task") {
          PNotify.removeAll();
          new PNotify({
              title: 'Warning',
              text: "Business Process таск дээр тохируулах боломжтой",
              type: 'warning',
              sticker: false
          });                
          return;
        }

        var doProcessId = selectedElements[0].businessObject.$attrs.processid;
        var result = await bpmnModeler.saveXML({ format: true });
        
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/getInputMetaParameterByProcess',
            data: {mainBpId: "<?php echo $this->mainBpId; ?>", doProcessId: doProcessId, connection2: result.xml},
            beforeSend: function () {
              Core.blockUI({
                message: "Loading...",
                boxed: true,
              });
            },
            success: function (data) {
                $dialog.empty().append(data);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Parameter',
                    width: '1200',
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdprocessflow/saveMetaProcessParameter',
                                data: $('#metaProcessParameter-form').serialize(),
                                dataType: "json",
                                beforeSend: function () {
                                    Core.blockUI({boxed: true, message: 'Хадгалж байна...'});
                                },
                                success: function (data) {
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                    if (data.status === 'success') {
                                        $dialog.dialog('close');
                                    } 
                                    Core.unblockUI();
                                },
                                error: function () { alert('Error'); }
                            });
                        }},
                        {text: plang.get('close_btn'), class: 'btn grey-cascade btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                }).dialogExtend({
                    "closable": true,
                    "maximizable": true,
                    "minimizable": true,
                    "collapsable": true,
                    "dblclick": "maximize",
                    "minimizeLocation": "left",
                    "icons": {
                        "close": "ui-icon-circle-close",
                        "maximize": "ui-icon-extlink",
                        "minimize": "ui-icon-minus",
                        "collapse": "ui-icon-triangle-1-s",
                        "restore": "ui-icon-newwin"
                    }
                });
                
                setTimeout(function(){
                    $dialog.dialogExtend('maximize');
                    $dialog.dialog('open');
                }, 50);
                
                Core.unblockUI();
            },
            error: function () { alert("Error"); }
        });
    };    

    /**
    * Save diagram contents and print them to the console.
    */
    async function exportDiagram() {

      try {
        var result = await bpmnModeler.saveXML({ format: true });
        $.ajax({
          type: "post",
          url: "mdprocessflow/saveBpmn",
          data: {
            xml: result.xml,
            mainBpId: "<?php echo $this->mainBpId; ?>"
          },
          dataType: "json",
          beforeSend: function () {
            Core.blockUI({
              message: "Loading...",
              boxed: true,
            });
          },
          success: function (data) {
            if (data.status === "success") {
              new PNotify({
                  title: 'Success',
                  text: "Амжилттай хадгаллаа",
                  type: 'success',
                  sticker: false
              });            
            } else {
              new PNotify({
                  title: 'Error',
                  text: data.text,
                  type: 'warning',
                  sticker: false
              });                    
            }
            Core.unblockUI();
          },
          error: function () {
            alert("Error");
          },
        });        
        console.log(result.xml);

      } catch (err) {

        console.error('could not save BPMN 2.0 diagram', err);
      }
    }

    /**
    * Open diagram in our modeler instance.
    *
    * @param {String} bpmnXML diagram to display
    */
    async function openDiagram(bpmnXML) {

      // import diagram
      try {

        await bpmnModeler.importXML(bpmnXML);

        // access modeler components
        var overlays = bpmnModeler.get('overlays');
        // zoom to fit full viewport
        canvas.zoom('fit-viewport');

        // attach an overlay to a node
        // overlays.add('SCAN_OK', 'note', {
        //   position: {
        //     bottom: 0,
        //     right: 0
        //   },
        //   html: '<div class="diagram-note">Mixed up the labels?</div>'
        // });

        // add marker
        // canvas.addMarker('SCAN_OK', 'needs-discussion');
      } catch (err) {

        console.error('could not import BPMN 2.0 diagram', err);
      }
    }

    // openDiagram(diagram);    

    // wire save button
    $('#bpmn-save-button').click(exportDiagram);
    $('.bpmn-parameter-config').click(callMetaParameter);

    $('.bpmn-select-bp').on('click', function () {
        commonMetaDataGrid('single', 'metaGroup', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId;?>|<?php echo Mdmetadata::$taskFlowMetaTypeId; ?>&isComplexProcess=1');
    });    

    selectableCommonMetaDataGrid = function (chooseType, elem, params) {
        if (elem === 'metaGroup') {
            var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
            if (metaBasketNum > 0) {
                var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
                var selectedElements = bpmnModeler.get('selection').get();
                selectedElements[0].businessObject.set('processid', rows[0]["META_DATA_ID"]);
                modeling.updateProperties(selectedElements[0], { name: '('+rows[0]["META_DATA_CODE"]+') '+rows[0]["META_DATA_NAME"] });                
            }
        }
    }    

    function CreateTask() {
      return elementFactory.create('shape', {
        type: 'bpmn:Task'
      });
    }    

    function CreateStartEvent() {
      return elementFactory.create('shape', {
        type: 'bpmn:StartEvent'
      });
    }    

    function CreateGateway() {
      return elementFactory.create('shape', {
        type: 'bpmn:Gateway'
      });
    }    

    function startSimulationBpmn () {
      bpmnModeler.createDiagram();
      <?php echo $this->generateBpmnScript; ?>
    }
    <?php if ($this->generateBpmnScript) { ?>
      startSimulationBpmn();
    <?php } else { ?>
      $.get("mdprocessflow/showBpmn/<?php echo $this->mainBpId; ?>", openDiagram, 'text');      
    <?php } ?>
  </script>
