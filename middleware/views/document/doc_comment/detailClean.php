<div class="dvecommerce dvecommerce-<?php echo $this->uniqid ?>">
    <div class="page-content">
        <?php require 'sidebarClean_left.php'; ?>
        <div class="col">
            <div class="barimts">
                <?php
                if (isset($this->getRow['ecmcontentmap']) && $this->getRow['ecmcontentmap']) {
                    foreach ($this->getRow['ecmcontentmap'] as $key => $row) {
                        if ($key === 0) {
                            $path_parts = pathinfo($row['ecmcontent']['physicalpath']);
                             switch ($path_parts['extension']) {
                                case 'pdf':
                                    echo '<iframe id="iframe-detail-'. $this->uniqid . '" src="'. URL. 'api/pdf/web/viewer.html?file=' .URL. $row["ecmcontent"]["physicalpath"] .  '" frameborder="0" style="width: 100%; height: 760px;"></iframe>';
                                    break;
                                case 'doc':
                                case 'docx': 
                                    if(Config::getFromCache('DOC_SERVER')){ ?>
                                        <iframe id="iframe-detail-<?php echo $this->uniqid ?>" src="<?php echo URL . "mddoc/officeClean?contentid=" . $row['contentid']; ?>&edit=1&review=1&docname=<?php echo urlencode(Arr::get($this->getRow, 'documentindex')); ?>&docId=<?php echo $this->rowId; ?>" frameborder="0" style="width: 100%; height: 100%;"></iframe>
                                        <?php 
                                    }else{
                                        echo '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL.$row["ecmcontent"]["physicalpath"].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                    }
                                    break;
                                case 'xls': 
                                case 'xlsx': 
                                    echo '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url=' .URL. $row["ecmcontent"]["physicalpath"].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                    break;
                                default:
                                    echo '<img id="viewImageMain" style="width:auto" src="'.$row["ecmcontent"]["physicalpath"].'"  class="img-fluid mar-auto d-flex justify-content-center">';
                                    break;
                            }
                        }
                    }
                }
                ?>
            </div>
          <div class="clearfix w-100"></div>
        </div>
        <?php require 'sidebarClean_right.php'; ?>
    </div>
    <div class="navbar navbar-expand-md navbar-light fixed-bottom p-1 border-top-1px">
        <div class="text-center d-md-none w-100 ">
            <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
                <i class="icon-unfold mr-2"></i>
                Footer
            </button>
        </div>
    </div>
</div>
<div class="popupLocateCanvas" id="popupLocateStamp" style="display: none">
    <canvas id="mask_canvas" width="800" height="600"></canvas>
</div>
<style type="text/css">
    .popupLocateCanvas canvas {
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
<script type="text/javascript">
    var row_<?php echo $this->uniqid ?> = <?php echo $this->rowJson; ?>;
    var filename = '';
    var pdfPath = '';
    var metaDataId = '';
    var refStructureId = '';
    var wfmStatusId = '';
    var newWfmStatusColor = '';
    var newWfmStatusName = '';

    $(function () {
          $('.dvecommerce-<?php echo $this->uniqid ?>').find('.colplr').find('img#viewImage').show();
          $('.dvecommerce-<?php echo $this->uniqid ?>').find('.colplr').find('iframe#viewFile').hide();   
          var viewerMainHeight = $(window).height() - 350;
          $("#topbuttonInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".dvecommerce .topbutton .btn-lg").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        getDocumentWorkflowNextStatus_<?php echo $this->rowId ?>('<?php echo $this->rowId ?>');
        
        var $iframeElement_<?php echo $this->uniqid; ?> = $('#iframe-detail-<?php echo $this->uniqid; ?>');
        setTimeout(function() {
            $iframeElement_<?php echo $this->uniqid; ?>.css('height', ($(window).height() - $iframeElement_<?php echo $this->uniqid; ?>.offset().top - 60)+'px');
        }, 1);
    });

    function getDocumentWorkflowNextStatus_<?php echo $this->rowId ?>(rowId) {

        $.ajax({
            type: 'post',
            url: 'mdobject/getWorkflowNextStatus',      
            data: {
                dataRow: <?php echo json_encode($this->row, JSON_UNESCAPED_UNICODE); ?>, 
                metaDataId: '<?php echo $this->metaDataId ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var $workflowDropdown = $('.workflow-buttons-<?php echo $this->uniqid; ?>');
                    $workflowDropdown.empty();

                    if (response.data) {
                        $.each(response.data, function (i, v) {
                            var advancedCriteria = '';
                            if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                console.log('case1');
                                advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                            }
                            if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                console.log('case2');
                                $workflowDropdown.append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\', {cyphertext: \'<?php echo '0'; ?>\', plainText: \'\'});" id="'+ v.wfmstatusid +'" data-is-maintabclose="1" data-dm-id="<?php echo $this->metaDataId; ?>"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+ v.processname +'</a>'); 
                            } else {
                                if (typeof v.processname != 'undefined' && v.processname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    if (v.wfmisneedsign == '1') {
                                        $workflowDropdown.append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="docToPdf_<?php echo $this->uniqid ?>(\'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+v.wfmstatusid+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+ v.processname +' <i class="fa fa-key"></i></a>'); 
                                    } else if (v.wfmisneedsign == '2') {
                                        $workflowDropdown.append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\');" id="'+ v.wfmstatusid +'"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+ v.processname +' <i class="fa fa-key"></i></a>'); 
                                    } else {
                                        $workflowDropdown.append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.wfmstatusname+'\', {cyphertext: \'<?php echo '0'; ?>\', plainText: \'\'});" id="'+ v.wfmstatusid +'" data-is-maintabclose="1" data-dm-id="<?php echo $this->metaDataId; ?>"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+ v.processname +'</a>'); 
                                    }
                                } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                    if (v.wfmisneedsign == '1') {
                                        $workflowDropdown.append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo 'DOC_LIST_TULUVLULT_DOCUMENT' ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+v.processname+' <i class="fa fa-key"></i></a>');
                                    } else if (v.wfmisneedsign == '2') {
                                        $workflowDropdown.append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo 'DOC_LIST_TULUVLULT_DOCUMENT' ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+v.processname+' <i class="fa fa-key"></i></a>');
                                    } else {
                                        $workflowDropdown.append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo 'DOC_LIST_TULUVLULT_DOCUMENT' ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\');" data-is-maintabclose="1" data-dm-id="<?php echo $this->metaDataId; ?>"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+v.processname+'</a>');
                                    }
                                }
                            }
                        });

                        if (response.hasOwnProperty('getUseAssignRuleId')) {
                            $workflowDropdown.append('<a href="javascript:;" onclick="userDefAssignWfmStatus(this, \''+response.getUseAssignRuleId+'\', \'<?php echo $this->metaDataId ?>\');" class="btn btn-secondary btn-circle btn-sm"><i class="icon-transmission"></i> '+plang.get('MET_99990846')+'</a>');
                        }
                    }
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: response.message,
                        type: response.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    }

    function sidebarRefreshCustom_<?php echo $this->rowId ?>(){
      $.ajax({
        type: 'post',
        url: 'mddoc/getDocumentSideBarsClean',
        data: {
            uniqid: <?php echo $this->uniqid; ?>,
            rowid: <?php echo $this->rowId; ?>
        },
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        dataType: 'json',
        success: function (data) {
            $('#sidebarLeft-<?php echo $this->uniqid ?>').empty();
            $('#sidebarLeft-<?php echo $this->uniqid ?>').append(data.left);

            $('#sidebarRight-<?php echo $this->uniqid ?>').empty();
            $('#sidebarRight-<?php echo $this->uniqid ?>').append(data.right);

            getDocumentWorkflowNextStatus_<?php echo $this->rowId ?>(<?php echo $this->rowId; ?>);
            Core.unblockUI();
        }
      });   
    }

    function customWfmStatusChangeCallback_<?php echo $this->rowId ?>(id){
      $.ajax({
        type: 'post',
        url: 'mddoc/documentEcmMapListClean',
        data: {
            recordId: id, 
            metaDataId: 'documentEcmMapList_' + <?php echo $this->metaDataId; ?> + '_' + id
        },
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        dataType: 'json',
        success: function (data) {
            if(typeof data.html !== 'undefined'){
              $('.dvecommerce-<?php echo $this->uniqid ?>').replaceWith(data.html);
            }
            Core.unblockUI();
        }
      });   
    }

    function statusLogPdfPopUp(docname, title){
        var url = '<?php echo URL; ?>';
        var $dialogName = 'dialog-hrm-hdrbp-pdf';
          if (!$('#' + $dialogName).length) {
              $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
          }
        var $dialog = $('#' + $dialogName);         

        fileExt = docname.split('.').pop();
        switch(fileExt.toLowerCase()) {
          case 'pdf':
            $dialog.empty().append('<iframe src="api/pdf/web/viewer.html?file='+url+docname+'" frameborder="0" style="width: 100%;height: 760px;"></iframe>');
            break;
          case 'doc':
            $dialog.empty().append('<iframe id="viewFileMain" src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>DocEdit.aspx?showRb=0&url='+url+docname+'" frameborder="0" style="width: 100%;height: 760px;"></iframe>');
            break;
          case 'docx':
            $dialog.empty().append('<iframe id="viewFileMain" src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>DocEdit.aspx?showRb=0&url='+url+docname+'" frameborder="0" style="width: 100%;height: 760px;"></iframe>');
            break;
          case 'xls':
            $dialog.empty().append('<iframe id="viewFileMain" src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>SheetEdit.aspx?showRb=0&url='+url+docname+'" frameborder="0" style="width: 100%;height: 760px;"></iframe>');
            break;
          case 'xlsx':
            $dialog.empty().append('<iframe id="viewFileMain" src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>SheetEdit.aspx?showRb=0&url='+url+docname+'" frameborder="0" style="width: 100%;height: 760px;"></iframe>');
            break;
          default:
            $dialog.empty().append('<img id="viewImageMain" style="width:auto" src='+url+docname+' frameborder="0" style="width: 100%;height: 760px;"></iframe>');
            break;
        }
        
          var $processForm = $('#wsForm', '#' + $dialogName),
          processUniqId = $processForm.parent().attr('data-bp-uniq-id');
          var buttons = [
              {
                  text: 'Хаах',
                  class: 'btn blue-madison btn-sm',
                  click: function() {
                      $dialog.dialog('close');
                  }
              }
          ];

          $dialog.dialog({
              cache: false,
              resizable: true,
              bgiframe: true,
              autoOpen: false,
              title: title,
              width: '1200',
              height: 'auto',
              modal: true,
              closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape),
              close: function() {
                  $dialog.empty().dialog('destroy').remove();
              },
              buttons: buttons
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
    }

    function callMethodProcess(id, metaDataId){
      var $dialogName = 'callMethodProcess';
      if (!$('#' + $dialogName).length) {
          $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
      }
      var $dialog = $('#' + $dialogName);
      $.ajax({
              type: "POST",
              url: 'mdwebservice/callMethodByMeta',
              data: { 'fillDataParams': 'id='+id, 'callerType' : 'mddocdetail_'+ id, 'isSystemMeta': false, 'isDialog': true, 'metaDataId': metaDataId },
              dataType: 'json',
              success: function (data) {
                $dialog.empty().append(data.Html);
                var $processForm = $('#wsForm', '#' + $dialogName), 
                processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

                if (data.isDialogSize === 'auto') {
                    dialogWidth = 1200;
                    dialogHeight = 'auto';
                }

                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: dialogWidth,
                    height: dialogHeight,
                    modal: true,
                    closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape), 
                    close: function () {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: buttons
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
              if (data.dialogSize === 'fullscreen') {
                  $dialog.dialogExtend("maximize");
              }
              $dialog.dialog('open');
          },
          error: function () {
              alert("Error");
          }
        }).done(function () {
            Core.initBPAjax($dialog);
            Core.unblockUI();
        });
    }

    function runProcessFromDetail(callertype, metaDataId, rowId, fillDataParams){
      var $dialogName = 'dialog-hrm-hdrbp-' + callertype;
      $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
      var $dialog = $('#' + $dialogName);
      $.ajax({
          type: 'post',
          url: 'mdwebservice/callMethodByMeta',
          data: {
              metaDataId: metaDataId,
              isDialog: true,
              isSystemMeta: false,
              runDefaultGet: 1,
              runDefaultGetParam: '{"id":"'+rowId+'}',
              callerType: callertype,
              fillDataParams: fillDataParams,
              postData: '{"dmMetaDataId":'+metaDataId+'}',
              openParams: '{"callerType":"'+callertype+'}'
          },
          dataType: 'json',
          beforeSend: function() {
              Core.blockUI({
                  message: 'Loading...',
                  boxed: true
              });
          },
          success: function(data) {
              $dialog.empty().append(data.Html);
              var $processForm = $('#wsForm', '#' + $dialogName),
                  processUniqId = $processForm.parent().attr('data-bp-uniq-id');
              var dialogWidth = data.dialogWidth,
                  dialogHeight = data.dialogHeight;
              var buttons = [{
                      text: data.run_btn,
                      class: 'btn green-meadow btn-sm bp-btn-save',
                      click: function(e) {
                        if(typeof eval('processBeforeSave_' + data.uniqId) == 'function'){
                          if(eval('processBeforeSave_' + data.uniqId)()){
                            $processForm.ajaxSubmit({
                                type: 'post',
                                url: 'mdwebservice/runProcess',
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({
                                        boxed: true,
                                        message: 'Түр хүлээнэ үү'
                                    });
                                },
                                success: function(responseData) {

                                    PNotify.removeAll();
                                    new PNotify({
                                        title: responseData.status,
                                        text: responseData.message,
                                        type: responseData.status,
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                    window['processAfterSave_'+data.uniqId]('', responseData.status, responseData);
                                    if (responseData.status == 'success') {
                                        $dialog.dialog('close');
                                        if(typeof customWfmStatusChangeCallback_<?php echo $this->rowId ?> === "function"){
                                          customWfmStatusChangeCallback_<?php echo $this->rowId ?>(<?php echo $this->rowId ?>);
                                          getDocumentWorkflowNextStatus_<?php echo $this->rowId ?>('<?php echo $this->rowId ?>');
                                        }
                                        dataViewReload(metaDataId);
                                    }
                                    Core.unblockUI();
                                },
                                error: function() {
                                    alert("Error");
                                }
                            });
                          }
                        }
                      }
                  },
                  {
                      text: data.close_btn,
                      class: 'btn blue-madison btn-sm',
                      click: function() {
                          $dialog.dialog('close');
                      }
                  }
              ];

              $dialog.dialog({
                  cache: false,
                  resizable: true,
                  bgiframe: true,
                  autoOpen: false,
                  title: data.Title,
                  width: dialogWidth,
                  height: dialogHeight,
                  modal: true,
                  closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape),
                  close: function() {
                      $dialog.empty().dialog('destroy').remove();
                  },
                  buttons: buttons
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
          }
      }).done(function() {
          Core.initBPAjax($dialog);
          Core.unblockUI();
      });
    }
</script>
<style type="text/css">
    .main-charvideo {
        margin-bottom: 20px;
        background: #f2f2f2;
        margin-left: -15px;
        margin-right: -15px;
        padding-left: 15px;
        margin-top: -20px;
        padding-top: 10px;
        padding-bottom: 5px;
    }
    .dvecommerce-body .ui-dialog .ui-widget-header {
        height: 40px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-title {
        line-height: 24px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane button {
        padding: 5px 20px;
        text-transform: uppercase;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane {
        margin-top: 0;
        background: #DDD;
        border: 0;
        padding: 5px 10px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-content {
        padding: 10px 15px 0;
    }
    .dvecommerce .detrightsidebar,
    .dvecommerce .detpr4 {
        width: 320px;
    }
    .dvecommerce .detrscfr {
        margin-right: 320px;
    }
    #sidebar.active {
        margin-left: -250px;
        transition: all 0.3s;
    }
    .dvecommerce .rightsidebar.active {
        margin-right: -320px;
        transition: all 0.3s;
    }
    #content {
        transition: all 0.3s;
    }
    #content.active {
      margin-left: 0;
    }
    #content.active i.actived {
      display: block !important;
    }
    #content.active i.actived2 {
      display: none !important;
    }
    .dvecommerce .right-sidebar-content-for-resize {
      margin-top: 20px;
    }
    ul.media-list span.badge.badge-mark,
    #navbar-footer span.badge.badge-mark {
        padding: 0;
    }
    @media (min-width: 768px) {
        .dvecommerce-<?php echo $this->uniqid ?> .sidebar-expand-md.sidebar-right.sidebar-right-2,
        .dvecommerce-<?php echo $this->uniqid ?> .sidebar-expand-md.sidebar-secondary.sidebar-secondary-2 {
            display: block !important;
        }
    }
</style>