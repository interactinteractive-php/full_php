<link rel="stylesheet" href="https://unpkg.com/bpmn-js@8.9.1/dist/assets/diagram-js.css">
<link rel="stylesheet" href="https://unpkg.com/bpmn-js@8.9.1/dist/assets/bpmn-font/css/bpmn.css">
<!-- modeler distro -->
<!-- <script src="https://unpkg.com/bpmn-js@8.9.1/dist/bpmn-modeler.development.js"></script> -->

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
      <div class="d-flex mt8">
        <button type="button" class="btn bg-success-400 btn-block bpmn-select-datamodel hidden"><i class="far fa-database"></i> Дата модель</button>
        <button type="button" class="btn bg-success-400 bpmn-datamodel-parameter-config" style="white-space: nowrap;overflow: hidden;" title=""><i class="far fa-cogs"></i> DM параметер тохиргоо</button>        
      </div>
      <button type="button" class="btn bg-success-400 bpmn-run-datamodel mt8 btn-block" style="white-space: nowrap;overflow: hidden;" title=""><i class="far fa-play"></i> Ажиллуулах</button>      
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
    // $.cachedScript('https://unpkg.com/bpmn-js@8.9.1/dist/bpmn-modeler.development.js').done(function() {
      $(".bpmn-render-wrap").css("height", $(window).height() - $(".bpmn-render-wrap").offset().top - 60);    
      var indicatorId =  $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').length ? $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').find('input[data-path="id"]').val() : '<?php echo issetVar($this->indicatorId) ?>';

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
        var selectedElements = bpmnModeler.get('selection').get();
        // console.log('bpmnModeler.get :>> ', bpmnModeler.get('selection').get(bpmnModeler.get('elementRegistry').get('Activity_0xj0hcu')));      
        if (selectedElement.type === "bpmn:SequenceFlow" && selectedElements.length) {
          $(".bpmn-bp-criteria").removeClass("hidden");
          $(".bpmn-bp-criteria").val(selectedElement.businessObject.$attrs.criteria);
        } else {
          $(".bpmn-bp-criteria").addClass("hidden");
        }

        if (selectedElement.type === "bpmn:Participant" && selectedElements.length) {
          $.ajax({
              type: 'post',
              // url: 'mdprocessflow/getRole2',
              url: 'mdprocessflow/getRole',
              data: {
                // mainBpId: $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').find('input[data-path="id"]').val(), 
                mainBpId: 1453876478231, 
              },
              beforeSend: function () {
              },
              success: function (data) {
                var comboDatas = [];
                var roleHtml = $('.bpmn-role-config');
                var $select = roleHtml.find("select");
                $select.empty();
                $select.append($('<option />').val('').text('- Cонгох -'));                
                if (data && data.length > 0) {
                    $.each(data, function (key, row) {
                      if (selectedElement.businessObject.$attrs["roleid"] && selectedElement.businessObject.$attrs["roleid"].indexOf(row.ROLE_ID) !== -1) {
                        $select.append($("<option />")
                          .val(row.ROLE_ID)
                          .text(row.ROLE_NAME)
                          .attr("selected", "selected"));
                      } else {
                        $select.append($("<option />")
                          .val(row.ROLE_ID)
                          .text(row.ROLE_NAME)); 
                      }
                      comboDatas.push({
                          id: row.ROLE_ID,
                          text: row.ROLE_NAME
                      });                              
                    });
                }              
                $select.select2({results: comboDatas});              
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
        var values = $self.val();
        var text = $self.find(":selected").toArray().map(item => item.text).join(",");

        selectedElements[0].businessObject.set('roleid', values.join(","));
        modeling.updateProperties(selectedElements[0], { name: text });   
      });      

      callMetaParameter = async function () {
          
          var dialogName = '#bpChildDialog';
          if (!$(dialogName).length) {
              $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
          }
          var $dialog = $(dialogName);
          var selectedElements = bpmnModeler.get('selection').get();
          var bpmRegistry = bpmnModeler.get('elementRegistry');        
          var elements = bpmRegistry._elements, 
              domainbp = [indicatorId];                  

          if (!selectedElements[0] || selectedElements[0]["type"] !== "bpmn:Task") {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Process таск дээр тохируулах боломжтой",
                type: 'warning',
                sticker: false
            });                
            return;
          }

          for (var elem in elements) {
            if (elements[elem].element.type === "bpmn:Task") {
              if (elements[elem].element.businessObject.$attrs.processid) {
                domainbp.push(elements[elem].element.businessObject.$attrs.processid);
              } else {
                modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});                 
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: "Домайн процесс тохируулаагүй байна!",
                    type: 'warning',
                    sticker: false
                });                
                return;                             
              }
            }
          }      

          var response = $.ajax({
            type: "post",
            url: "mdprocessflow/checkBp",
            data: {
              domainbp: domainbp,
            },
            dataType: "json",
            async: false,
          });
          var responseValue = response.responseJSON;

          if (responseValue.status == 'exist') {
            for (var elem in elements) {
              if (elements[elem].element.type === "bpmn:Task") {          
                for (var elem2 in responseValue.ids) {
                  if (elements[elem].element.businessObject.$attrs.processid == responseValue.ids[elem2]) {
                    modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});        
                  }
                }                
              }                
            }                
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Процесс тохируулаагүй байна!",
                type: 'warning',
                sticker: false
            });                
            return;          
          }

          if (responseValue.status == 'empty') {
            for (var elem in elements) {
              if (elements[elem].element.type === "bpmn:Task") {          
                modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});       
              }                
            }                
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Процесс тохируулаагүй байна!",
                type: 'warning',
                sticker: false
            });                
            return;                    
          }        

          var doProcessId = selectedElements[0].businessObject.$attrs.processid;
          var result = await bpmnModeler.saveXML({ format: true });
          
          $.ajax({
              type: 'post',
              url: 'mdprocessflow/getInputMetaParameterByProcess2',
              data: {
                mainBpId: indicatorId, 
                doProcessId: doProcessId, 
                connection2: result.xml
              },
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

      runDataModelParameter = async function () {
        var postData = {param: {indicatorId: '<?php echo issetParam($this->savedValue["DATA_MODEL_ID"]) ?>'}};
        
        $.ajax({
            type: 'post',
            url: 'mdform/kpiIndicatorTemplateRender',
            data: postData, 
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                PNotify.removeAll();
                
                if (data.status == 'success') {
                    
                    var $dialogName = 'dialog-kpiindicatorvalue-'+getUniqueId(1);
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialog = $('#' + $dialogName);
        
                    $dialog.empty().append('<form method="post" enctype="multipart/form-data">' + data.html + '</form>');
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'KPI Indicator value',
                        width: 950,
                        height: 'auto',
                        modal: true,
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-save', click: function () {
                                    
                                var $form = $dialog.find('form');    
                                $form.validate({errorPlacement: function () {}});
                            
                                if ($form.valid()) {
                                    $form.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdform/saveKpiDynamicDataByList',
                                        dataType: 'json',
                                        beforeSend: function () {
                                            Core.blockUI({message: 'Loading...', boxed: true});
                                        },
                                        success: function (data) {

                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false, 
                                                addclass: pnotifyPosition
                                            });

                                            if (data.status == 'success') {
                                                $dialog.dialog('close');
                                                dataViewReload(indicatorId);
                                            } 

                                            Core.unblockUI();
                                        }
                                    });
                                }
                            }},
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki bp-btn-close', click: function () {
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
                    $dialog.dialog('open');
                
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: pnotifyPosition
                    });
                }
                
                Core.unblockUI();
            },
            error: function () { alert('Error'); Core.unblockUI(); }
        });
      }

      callDataModelParameter = async function () {
          
          var dialogName = '#dataModelChildDialog';
          if (!$(dialogName).length) {
              $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
          }
          var $dialog = $(dialogName);
          var selectedElements = bpmnModeler.get('selection').get();
          var bpmRegistry = bpmnModeler.get('elementRegistry');        
          var elements = bpmRegistry._elements, 
              domainbp = [],
              dataModelId = $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').find('.bpmn-data-model-id').val();                  

          if (dataModelId == "") {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Дата модель сонгоно уу!",
                type: 'warning',
                sticker: false
            });                
            return;
          }

          if (!selectedElements[0] || selectedElements[0]["type"] !== "bpmn:Task") {
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Process таск дээр тохируулах боломжтой",
                type: 'warning',
                sticker: false
            });                
            return;
          }              

          if (selectedElements[0].businessObject.$attrs.processid) {
            domainbp.push(selectedElements[0].businessObject.$attrs.processid);
          } else {
            modeling.setColor(bpmRegistry.get(selectedElements[0].id), {stroke:'#ff4d4d'});                 
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Домайн процесс тохируулаагүй байна!",
                type: 'warning',
                sticker: false
            });                
            return;                             
          }

          // for (var elem in elements) {
          //   if (elements[elem].element.type === "bpmn:Task") {
          //     if (elements[elem].element.businessObject.$attrs.processid) {
          //       domainbp.push(elements[elem].element.businessObject.$attrs.processid);
          //     } else {
          //       modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});                 
          //       PNotify.removeAll();
          //       new PNotify({
          //           title: 'Warning',
          //           text: "Домайн процесс тохируулаагүй байна!",
          //           type: 'warning',
          //           sticker: false
          //       });                
          //       return;                             
          //     }
          //   }
          // }      

          var response = $.ajax({
            type: "post",
            url: "mdprocessflow/checkBp",
            data: {
              domainbp: domainbp,
            },
            dataType: "json",
            async: false,
          });
          var responseValue = response.responseJSON;

          if (responseValue.status == 'exist') {
            for (var elem in elements) {
              if (elements[elem].element.type === "bpmn:Task") {          
                for (var elem2 in responseValue.ids) {
                  if (elements[elem].element.businessObject.$attrs.processid == responseValue.ids[elem2]) {
                    modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});        
                  }
                }                
              }                
            }                
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Процесс тохируулаагүй байна!",
                type: 'warning',
                sticker: false
            });                
            return;          
          }

          if (responseValue.status == 'empty') {
            for (var elem in elements) {
              if (elements[elem].element.type === "bpmn:Task") {          
                modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});       
              }                
            }                
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Процесс тохируулаагүй байна!",
                type: 'warning',
                sticker: false
            });                
            return;                    
          }        

          var doProcessId = "";
          var result = await bpmnModeler.saveXML({ format: true });
          
          $.ajax({
              type: 'post',
              url: 'mdprocessflow/getInputMetaParameterByProcess3',
              data: {
                mainBpId: indicatorId, 
                doProcessId: domainbp[0], 
                connection2: result.xml,
                dataModelName: $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').find('.data-model-name').text(),
                dataModelId: dataModelId
              },
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
                                  url: 'mdprocessflow/saveMetaProcessParameter2',
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
          var bpmRegistry = bpmnModeler.get('elementRegistry');        
          var elements = bpmRegistry._elements;                  

          for (var elem in elements) {
            if (elements[elem].element.type === "bpmn:Task") {          
              modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke: '#000'});       
            }                
          }      

          for (var elem in elements) {
            if (elements[elem].element.type === "bpmn:Task") {
              if (!elements[elem].element.businessObject.$attrs.processid) {
                modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});            
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: "Домайн процесс тохируулаагүй байна!",
                    type: 'warning',
                    sticker: false
                });                
                return;                    
              }
            }
          }      

          var result = await bpmnModeler.saveXML({ format: true });
          // console.log(result.xml);
          $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').find('[data-path="<?php echo $this->bpPath ?>"]:hidden').val(result.xml);
          if ($('#dialog-bpmnEditor').length && $('#dialog-bpmnEditor').is(":visible")) {
            $('#dialog-bpmnEditor').dialog('close');
          }

        } catch (err) {

          console.error('could not save BPMN 2.0 diagram', err);
        }
      }

      /**
      * Save diagram contents and print them to the console.
      */
      async function exportDiagram2() {

        try {

          var bpmRegistry = bpmnModeler.get('elementRegistry');        
          var elements = bpmRegistry._elements, 
              domainbp = [];                  

          for (var elem in elements) {
            if (elements[elem].element.type === "bpmn:Task") {          
              modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke: '#000'});       
            }                
          }      

          for (var elem in elements) {
            if (elements[elem].element.type === "bpmn:Task") {
              if (elements[elem].element.businessObject.$attrs.processid)
                domainbp.push(elements[elem].element.businessObject.$attrs.processid);
              else {
                modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});                 
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: "Домайн процесс тохируулаагүй байна!",
                    type: 'warning',
                    sticker: false
                });                
                return;                             
              }
            }
          }      

          var result = await bpmnModeler.saveXML({ format: true });        
          var mainBpId = indicatorId;

          Core.blockUI({
            message: "Loading...",
            boxed: true,
          });        

          $.ajax({
            type: "post",
            url: "mdprocessflow/saveBpmnDraft",
            data: {
              xml: result.xml,
              dataModelId: $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').find('.bpmn-data-model-id').val(),
              mainBpId: mainBpId
            },
            dataType: "json",
            async: false,
          });

          var response = $.ajax({
            type: "post",
            url: "mdprocessflow/checkBp",
            data: {
              domainbp: domainbp,
            },
            dataType: "json",
            async: false,
          });
          var responseValue = response.responseJSON;
          Core.unblockUI();

          if (responseValue.status == 'exist') {
            for (var elem in elements) {
              if (elements[elem].element.type === "bpmn:Task") {          
                for (var elem2 in responseValue.ids) {
                  if (elements[elem].element.businessObject.$attrs.processid == responseValue.ids[elem2]) {
                    modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});        
                  }
                }                
              }                
            }                
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Процесс тохируулаагүй байна!",
                type: 'warning',
                sticker: false
            });                
            return;          
          }

          if (responseValue.status == 'empty') {
            for (var elem in elements) {
              if (elements[elem].element.type === "bpmn:Task") {          
                modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke:'#ff4d4d'});       
              }                
            }                
            PNotify.removeAll();
            new PNotify({
                title: 'Warning',
                text: "Процесс тохируулаагүй байна!",
                type: 'warning',
                sticker: false
            });                    
            return;                    
          }
          new PNotify({
              title: 'Success',
              text: "Амжилттай хадгаллаа",
              type: 'success',
              sticker: false
          });                
          
          $.ajax({
            type: "post",
            url: "mdprocessflow/saveBpmn2",
            data: {
              xml: result.xml,
              mainBpId: mainBpId
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
                // $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').find('[data-path="<?php echo $this->bpPath ?>"]:hidden').val(result.xml);
                if ($('#dialog-bpmnEditor').length && $('#dialog-bpmnEditor').is(":visible")) {
                  $('#dialog-bpmnEditor').dialog('close');
                }              
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

          // var bpmRegistry = bpmnModeler.get('elementRegistry');        
          // var elements = bpmRegistry._elements;                  

          // for (var elem in elements) {
          //   if (elements[elem].element.type === "bpmn:Task") {          
          //     console.log(elements[elem].element.businessObject.$attrs.processid);
          //     // modeling.setColor(bpmRegistry.get(elements[elem].element.id), {stroke: '#000'});       
          //   }                
          // }      

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
      $('#bpmn-save-button').click(exportDiagram2);
      // $('#bpmn-save-button').click(exportDiagram);
      $('#bpmn-acceptsave-button').click(exportDiagram2);
      $('.bpmn-parameter-config').click(callMetaParameter);
      $('.bpmn-datamodel-parameter-config').click(callDataModelParameter);
      $('.bpmn-run-datamodel').click(runDataModelParameter);

      $('.bpmn-select-domainbp').on('click', function () {
        var selectedElements = bpmnModeler.get('selection').get();      
        if (!selectedElements[0] || selectedElements[0]["type"] !== "bpmn:Task") {
          PNotify.removeAll();
          new PNotify({
              title: 'Warning',
              text: "Process таск дээр тохируулах боломжтой",
              type: 'warning',
              sticker: false
          });                
          return;
        }              
        dataViewCustomSelectableGrid('processList2column', 'single', 'bpmnSelectabledGrid_<?php echo $this->uniqId; ?>', '', this);
        // dataViewCustomSelectableGrid('PRL_CALC_DV2', 'single', 'calcSelectabledGrid_<?php //echo $this->uniqId; ?>', 'param[booktypeid]=15001&param[calctypeid]='+$("#calcInfoForm_<?php //echo $this->uniqId; ?>").find('select[name="calcTypeId"]').val(), this);
      });    

      $('.bpmn-select-datamodel').on('click', function () {      
        dataViewCustomSelectableGrid('data_indicatorListAll', 'single', 'bpmnDatamodelSelectabledGrid_<?php echo $this->uniqId; ?>', '', this);
      });    
      
      function bpmnSelectabledGrid_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
          var row = rows[0];
          var selectedElements = bpmnModeler.get('selection').get();
          selectedElements[0].businessObject.set('processid', row.id);
          modeling.updateProperties(selectedElements[0], { name: row.name });                        
          modeling.setColor(selectedElements[0], {stroke: '#000'});
          //modeling.setColor(selectedElements[0], {stroke: 'green', stroke: 'black'})        
      }        

      function showDataModelInfo_<?php echo $this->uniqId; ?>(row){
          // var getParamList = $.ajax({
          //   type: "post",
          //   url: "api/callDataview",
          //   data: {
          //     dataviewId: "1642419374729118",
          //     criteriaData: {
          //       filterMainId: [{ operator: "=", operand: row["id"] }],
          //     },
          //   },
          //   dataType: "json",
          //   async: false,
          //   success: function (data) {
          //     return data.result;
          //   },
          // });
          // getParamList = getParamList.responseJSON.result;                
          var html = "";
          // if (getParamList && getParamList.length) {
          //   for (var ii = 0; ii < getParamList.length; ii++) {
          //     html += '<li class="nav-item ml16"><div>'+(getParamList[ii]['labelname']?getParamList[ii]['labelname']:'хоосон')+'</div><div style="color:#7e7e7e">'+getParamList[ii]['name']+'</div></li>';
          //   }
          // }
          $(".bpmn-datamodel-param-list").html('<div class="mt10 data-model-name" style="background-color: #efefef;padding: 6px;border-radius: 3px;">'+row['name']+'</div><ul class="nav nav-sidebar mt10">'+html+'</ul>');
      }

      <?php if (issetParam($this->savedValue["DATA_MODEL_ID"])) { ?>
              var infoMeta = $.ajax({
                type: "post",
                url: "api/callDataview",
                data: {
                  dataviewId: "16424911273171",
                  criteriaData: {
                    id: [{ operator: "=", operand: <?php echo $this->savedValue["DATA_MODEL_ID"]; ?> }],
                  },
                },
                dataType: "json",
                async: false,
                success: function (data) {
                  return data.result;
                },
              });
              infoMeta = infoMeta.responseJSON.result;            
              showDataModelInfo_<?php echo $this->uniqId; ?>(infoMeta[0]);
      <?php } ?>
      
      function bpmnDatamodelSelectabledGrid_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
          var row = rows[0];

          $('div[data-bp-uniq-id="<?php echo $this->bpUniqId; ?>"]').find('.bpmn-data-model-id').val(row["id"]);
          showDataModelInfo_<?php echo $this->uniqId; ?>(row);
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

      function bpmnFullscreenMode(elem) {
        var $dialogName = $('div.bpmn-main-container');
        if ($dialogName.hasClass('bp-dtl-fullscreen')) {
          $dialogName.removeClass('bp-dtl-fullscreen');
          $(elem).find('i').removeClass('fa-compress').addClass('fa-expand');
          $(".bpmn-render-wrap").css("height", $(window).height() - $(".bpmn-render-wrap").offset().top - 60);
        } else {
          $dialogName.addClass('bp-dtl-fullscreen');
          $(elem).find('i').removeClass('fa-expand').addClass('fa-compress');
          $(".bpmn-render-wrap").css("height", $(window).height());
        }
        return;
      }

      async function bpmnExportXml(elem) {
        var result = await bpmnModeler.saveXML({ format: true });
        var dataStr = "data:text/plain;charset=utf-8," + result.xml;
        var dlAnchorElem = document.getElementById('downloadAnchorElem');
        dlAnchorElem.setAttribute("href", dataStr);
        dlAnchorElem.setAttribute("download", "bpmn_export"+getUniqueId(1)+".xml");
        dlAnchorElem.click();
        return;
      }

      function bpmnImportXml(elem) {
          document.getElementById('bpmn_hidden_file_input').click();
          return;
      }
      
        var bpmnOpenFile = function(event) {
          var input = event.target;

          var reader = new FileReader();
          reader.onload = function() {
            var text = reader.result;
            bpmnModeler.importXML(text);
          };
          reader.readAsText(input.files[0]);
        };      

      <?php if (empty($this->generateBpmnScript)) { ?>
        openDiagram(diagram);
      <?php } else { ?>
        openDiagram('<?php echo html_entity_decode(Str::smart_clean($this->generateBpmnScript), ENT_QUOTES, 'UTF-8'); ?>');
      <?php } ?>
    // });
  </script>
