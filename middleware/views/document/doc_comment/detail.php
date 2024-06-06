<div class="dvecommerce dvecommerce-<?php echo $this->uniqid ?>">
    <div class="page-content">
        <?php require 'sidebar_left.php'; ?>
        <div class="col height-scroll">
            <div class="etop-detail mt-2">
            <span class="float-right" id="doc-left-accord" onclick="docLeftAccordion_<?php echo $this->uniqid ?>()">
                <i class="icon-arrow-right8"></i></span>
            <span class="float-left" id="doc-right-accord" onclick="docRightAccordion_<?php echo $this->uniqid ?>()">
                <i class="icon-arrow-left8"></i></span>
                <div class="d-flex justify-content-center">
                  <?php if(isset($this->sideButtonConf['docDocumentWfmHistory']) && $this->sideButtonConf['docDocumentWfmHistory']['VALUE'] == 1){ ?>
                    <?php if (isset($this->getRow['relationdocs']) && $this->getRow['relationdocs']) {
                    foreach ($this->getRow['relationdocs'] as $key => $row) { ?>
                        <div class="col-4 p-0 three-box <?php if($row['id'] == $this->rowId){ echo 'active'; } ?>" <?php if($row['id'] !== $this->rowId){ echo 'onclick="customWfmStatusChangeCallback_'.$this->rowId.'('.$row['id']. ',' .$row['metadataid'].')"'; } ?>>
                            <div class="card card-body p-2 border-0 border-radius-0 text-black <?php if($row['id'] == $this->rowId){ echo 'bg-primary'; } else { echo 'bg-light'; }; ?>">
                                <div class="media d-flex align-items-center">
                                    <div class="media-body ml-3 d-flex flex-row">
                                        <span class="opacity-75"><?php echo $row['directionname']; ?> БИЧИГ: 
                                          <?php echo $row['username']; ?></span>
                                    </div>
                                    <div class="calendar-i mr-3">
                                        <i class="icon-calendar mr-1"></i><?php echo substr($row['createddate'], 0, 16); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }}} ?>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#sidebarCollapse').on('click', function () {
                        $('#sidebar').toggleClass('active');
                    });
                    $('#sidebarCollapse').on('click', function () {
                        $('#content').toggleClass('active');
                    });
                    $('#sidebarCollapse2').on('click', function () {
                        $('#sidebarright').toggleClass('active2');
                    });
                    $('#sidebarCollapse2').on('click', function () {
                        $('#content').toggleClass('active');
                    });
                });
            </script>
            <?php if(isset($this->getRow['directionid']) && ($this->getRow['directionid'] == 2 || ($this->getRow['directionid'] == 3 && empty($this->getRow['templateid']))) 
                    && !empty($this->getFilePath))   { ?>
                <div class="barimts">
                    <div style="height: 80vh">
                        <?php if('pdf' == strtolower(pathinfo($this->getFilePath, PATHINFO_EXTENSION)) ){ ?>
                            <iframe id="iframe-detail-<?php echo $this->uniqid ?>" src="<?php echo URL; ?>api/pdf/web/viewer.html?file=<?php echo URL.$this->getFilePath; ?>" frameborder="0" style="width: 100%; height: 760px;"></iframe>
                        <?php } if(in_array( strtolower(pathinfo($this->getFilePath, PATHINFO_EXTENSION)), array('doc','docx'))) { ?>
                                <iframe id="iframe-detail-<?php echo $this->uniqid ?>" src="<?php echo URL . "mddoc/office?filename=" . $this->getFilePath; ?>&edit=<?php echo $this->getRow['isedit']; ?><?php echo isset($this->repDocNum) ? '&dcode=' . $this->getRow['documentnumber'] : '';?>&docname=<?php echo urlencode(Arr::get($this->getRow, 'documentindex')); ?>&docId=<?php echo $this->rowId; ?>" frameborder="0" style="width: 100%; height: 100%;"></iframe>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="barimts">
                    <?php
                    if (isset($this->getRow['ecmcontentmap']) && $this->getRow['ecmcontentmap']) {
                        foreach ($this->getRow['ecmcontentmap'] as $key => $row) {
                            if ($key === 0) {
                                switch ($row['ecmcontent']['fileextension']) {
                                    case 'pdf':
                                    case 'application/pdf':
                                        if (strpos($row["ecmcontent"]["physicalpath"], '/api/file/download') !== false) { 
                                            echo '<iframe src="'.URL.'api/pdf/web/viewer.html?file='. Config::getFromCache('DOCX_URL') .$row["ecmcontent"]["physicalpath"].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqid.'"></iframe>';
                                        } else {
                                            echo '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.URL.$row["ecmcontent"]["physicalpath"].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqid.'"></iframe>';
                                        }
                                        break;
                                    case 'doc':
                                    case 'docx':
                                    case 'application/msword':
                                    case 'application/octet-stream':
                                        echo '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL.$row["ecmcontent"]["physicalpath"].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                        break;
                                    case 'xls': 
                                    case 'xlsx': 
                                    case 'application/excel': 
                                    case 'application/msexcel': 
                                        echo '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL.$row["ecmcontent"]["physicalpath"].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                        break;
                                    default:
                                        if (strpos($row["ecmcontent"]["physicalpath"], '/api/file/download') !== false) { 
                                            echo '<iframe src="'.URL.'api/pdf/web/viewer.html?file='. Config::getFromCache('DOCX_URL') .$row["ecmcontent"]["physicalpath"].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqid.'"></iframe>';
                                        } else {
                                            echo '<img id="viewImageMain" style="width:auto" src="'.$row["ecmcontent"]["physicalpath"].'"  class="img-fluid mar-auto d-flex justify-content-center">';
                                        }
                                        break;
                                }
                            }
                        }
                    }
                    ?>
                </div>
            <?php } ?>
            <div class="clearfix w-100"></div>
        </div>
        <?php require 'sidebar_right.php'; ?>
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
    var row_<?php echo $this->uniqid ?> = JSON.parse('<?php echo $this->rowJson; ?>');
    var filename = '';
    var pdfPath = '';
    var metaDataId = '';
    var refStructureId = '';
    var wfmStatusId = '';
    var newWfmStatusColor = '';
    var newWfmStatusName = '';

    window.addEventListener('message',function(message){
      if(message.data.type=="documentNumberChanged-<?php echo $this->rowId; ?>"){
          var executed = false;
          if(!executed){
            Core.blockUI({
                message: 'Түр хүлээнэ үү...', 
                boxed: true
            });
            setTimeout(function(){
              customdocToPdf_<?php echo $this->uniqid ?>(<?php echo $this->rowId; ?>);
            }, 12000);
            executed = true;
          }
      }
    });

    window.addEventListener('message',function(message){
      if(message.data.type=="eventFromCanvas_<?php echo $this->uniqid ?>"){
          signPdfWithCoordinate(message.data.value);
      }
    });

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
              
          if (row_<?php echo $this->uniqid ?> && typeof row_<?php echo $this->uniqid ?>.wfmstatusid !== 'undefined' && 
            (row_<?php echo $this->uniqid ?>.lastassignuserid == row_<?php echo $this->uniqid ?>.currentuserid || row_<?php echo $this->uniqid ?>.aliasuserid == row_<?php echo $this->uniqid ?>.lastassignuserid )
            ) {
            if ( row_<?php echo $this->uniqid ?>.wfmstatusid == '1520321008250590' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1560749719306061' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1551934286116338' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1558603936616469' ||
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1546836502313351' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1572080909063831' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1560685299762127' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1587118436106' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1560685299762127' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1586856556763' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '1571401183003126' || 
                 row_<?php echo $this->uniqid ?>.wfmstatusid == '16100320577076'
                 ) {
                paramData = JSON.parse('[{"fieldPath":"id","inputPath":"recordid","value":"'+row_<?php echo $this->uniqid ?>.id+'"},{"fieldPath":"sessionUserkeyId","inputPath":"userid","value":"' +row_<?php echo $this->uniqid ?>.currentuserid+ '"}]');
                var response = $.ajax({
                    type: 'post',
                    url: 'mdwebservice/getProcessParam', 
                    data: {
                        processCode: 'getMetaWfmAssignmentList1_004', 
                        paramData: paramData 
                    },
                    success: function (res) {
                      if(res != null && ['1','2','4', '7'].indexOf(row_<?php echo $this->uniqid ?>.directionid) != -1){
                        var newTempStatus = '';
                        if (row_<?php echo $this->uniqid ?>.directionid == 1) {
                            newTempStatus = 1551168339725918;
                        } else if(row_<?php echo $this->uniqid ?>.directionid == 2){
                            newTempStatus = 1560749332691407;
                        // } else if(row_<?php //echo $this->uniqid ?>.directionid == 3){
                            // newTempStatus = 1530771218053555;
                        } else if(row_<?php echo $this->uniqid ?>.directionid == 4){
                            newTempStatus = 1560682229378955;
                        } else if(row_<?php echo $this->uniqid ?>.directionid == 7){
                            newTempStatus = 16100320577176;
                        }

                        // if(row_<?php // echo $this->uniqid ?>.directionid == 2 || row_<?php echo $this->uniqid ?>.directionid == 1){
                            var $_wfmParams = {
                                metaDataId: '<?php echo $this->metaDataId ?>', 
                                refStructureId: '1447239000602', 
                                dataRow: { id: row_<?php echo $this->uniqid ?>.id, wfmstatusid: row_<?php echo $this->uniqid ?>.wfmstatusid }, 
                                description: '', 
                                wfmStatusId: row_<?php echo $this->uniqid ?>.wfmstatusid,
                                newWfmStatusid: newTempStatus, 
                                signerParams: '',
                                newWfmStatusName: 'Танилцсан',
                                newWfmStatusColor: '#d0a442',
                                isMany: false
                            };

                            $.ajax({
                                type: 'post',
                                url: 'mdobject/setRowWfmStatus',
                                dataType: 'json',
                                data: $_wfmParams,
                                success: function (data) {
                                  if(data.status == "success"){
                                    row_<?php echo $this->uniqid ?>.wfmstatusid = newTempStatus;
                                    if(typeof customWfmStatusChangeCallback_<?php echo $this->rowId ?> === "function"){
                                      customWfmStatusChangeCallback_<?php echo $this->rowId ?>(<?php echo $this->rowId ?>);
                                    }
                                    dataViewReload(<?php echo $this->metaDataId; ?>, undefined, '1');
                                    dataViewReload(<?php echo $this->metaDataId; ?>);
                                  }
                                }
                            });
                      }
                    },
                    dataType: 'json',
                    async: false
                });
            }
          }
          getDocumentWorkflowNextStatus_<?php echo $this->rowId ?>('<?php echo $this->rowId ?>');
        
        var $iframeElement_<?php echo $this->uniqid; ?> = $('#iframe-detail-<?php echo $this->uniqid; ?>');
        setTimeout(function() {
            $iframeElement_<?php echo $this->uniqid; ?>.css('height', ($(window).height() - $iframeElement_<?php echo $this->uniqid; ?>.offset().top - 60)+'px');
        }, 1);
    });

    function docLeftAccordion_<?php echo $this->uniqid ?>(){
        element = $("#app_tab_documentEcmMapList_<?php echo $this->metaDataId ?>_<?php echo $this->rowId ?> .ecommerce-right-sidebar");
        element.parent().find('#doc-left-accord i').toggleClass("icon-arrow-right8 icon-arrow-left8");
        element.toggleClass('hidden');
    }

    function docRightAccordion_<?php echo $this->uniqid ?>(){
        element = $("#app_tab_documentEcmMapList_<?php echo $this->metaDataId ?>_<?php echo $this->rowId ?> .ecommerce-left-sidebar");
        element.parent().find('#doc-right-accord i').toggleClass("icon-arrow-right8 icon-arrow-left8");
        element.toggleClass('hidden');
    }
    
    function signPdfWithCoordinate(coordinate){
      $('#callIframeCanvas').empty().dialog('destroy').remove();
      var filename = coordinate.path.replace(/^.*[\\\/]/, '');
         $.ajax({
            type: 'post',
            url: 'mdpki/getInformationForDocumentSign',
            data: {filePath: coordinate.path},
            dataType: 'json',
            success: function (dataS) {
              if (dataS.status === 'success') {
                console.log(dataS, coordinate.path, null);
                  signPdfAndTextRun(dataS, coordinate.path, null, function (dat) {
                  if (dat.status === 'success') {
                      var $_wfmParams = {
                          metaDataId: metaDataId, 
                          refStructureId: refStructureId, 
                          dataRow: row_<?php echo $this->uniqid ?>, 
                          description: '', 
                          wfmStatusId: 1530100270102879,
                          newWfmStatusid: 1530100270102879,
                          signerParams: '',
                          newWfmStatusName: newWfmStatusName,
                          newWfmStatusColor: newWfmStatusColor,
                          isMany: false
                      };

                      $.ajax({
                          type: 'post',
                          url: 'mdobject/setRowWfmStatus',
                          dataType: 'json',
                          data: $_wfmParams,
                          success: function (data3) {
                          }
                      });
                      $.ajax({
                          type: 'post',
                          url: 'mddoc/setSignedPdfPath',
                          dataType: 'json',
                          data: {id: row_<?php echo $this->uniqid ?>.id, filename: filename},
                          success: function (data4) {
                              if(typeof customWfmStatusChangeCallback_<?php echo $this->rowId ?> === "function"){
                                customWfmStatusChangeCallback_<?php echo $this->rowId ?>(<?php echo $this->rowId ?>);
                              }
                          }
                      });
                      var pathStr = '<?php echo URL . UPLOADPATH . "signedDocument/"; ?>' + filename;
                      document.getElementById('iframe-detail-<?php echo $this->uniqid ?>').src = pathStr;
                  }
              }, Math.floor(1.33333333* coordinate.x), Math.floor(1.33333333 * (573-coordinate.y-45)), coordinate.pageNum, null, 1);
            }
          }
      });
    }

    function callIframeWithCanvas(pdfPath){
      var filename = pdfPath.replace(/^.*[\\\/]/, '');
      iframe = '<iframe id="frameStampPos" src="mddoc/canvasStampPos?uniqid=<?php echo $this->uniqid ?>&pdfPath='+pdfPath+'" height="100%" width="100%" frameBorder="0" ></iframe>';
      if(! $('#callIframeCanvas').length ){
        var div = document.createElement("div");
        div.id = 'callIframeCanvas';
        div.style = 'display: none';
        document.body.appendChild(div);
      }
      
      $('#callIframeCanvas').empty();
      $('#callIframeCanvas').append(iframe);
      
      $("#callIframeCanvas").dialog({
          cache: false,
          resizable: false,
          bgiframe: true,
          autoOpen: false,
          title: 'Тамганы байршил',
          width: 500-9,
          height: 750-20,
          modal: false,
            open: function (event, ui) {
                $('#callIframeCanvas').css('overflow', 'hidden'); 
              },
          close: function () {
              $('#callIframeCanvas').empty().dialog('destroy').remove();
          },
          buttons: [{text: 'Сонгох', class: 'btn blue-madison btn-sm', click: function () {
          var frame = $('#frameStampPos')[0];
          frame.contentWindow.postMessage({call:'canvasClickSendValue_<?php echo $this->uniqid ?>', value: pdfPath});
        }}]
      });

      $("#callIframeCanvas").dialog('open');
    }
      
    function relatedDocHtml_<?php echo $this->uniqid ?>(elem, recordId, documentName){
        appMultiTab({weburl: 'mddoc/documentEcmMapListNew', metaDataId: 'documentEcmMapList_' + recordId, title: documentName, type: 'selfurl', recordId: recordId}, elem);
    };

    function customdocToPdf_<?php echo $this->uniqid ?>(docid){
        docpath = '<?php echo $this->getFilePath; ?>';
        $.ajax({
            type: "POST",
            url: 'mddoc/docxToPdfConverter',
            data: {path: docpath},
            dataType: 'json',
            success: function (data) {
                pdfPath = data.url;
                if (data.status === 'success') {
                  callIframeWithCanvas(pdfPath);
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            }
        });
    }

    function docToPdf_<?php echo $this->uniqid ?>(metaDataId, refStructureId, wfmStatusId, newWfmStatusColor, newWfmStatusName) {
        var metaDataId = metaDataId;
        var refStructureId = refStructureId;
        var wfmStatusId = wfmStatusId;
        var newWfmStatusColor = newWfmStatusColor;
        var newWfmStatusName = newWfmStatusName;

        docpath = '<?php echo $this->getFilePath; ?>';
        ext = docpath.split('.').pop();
        if(ext == 'docx'){
          $.ajax({
              type: "POST",
              url: 'mddoc/docxToPdfConverter',
              data: {path: docpath},
              dataType: 'json',
              success: function (data) {
                  var pdfPath = data.url;
                  if (data.status === 'success') {
                      callIframeWithCanvas(pdfPath);
                  } else {
                      PNotify.removeAll();
                      new PNotify({
                          title: data.status,
                          text: data.message,
                          type: data.status,
                          sticker: false
                      });
                  }
                  Core.unblockUI();
              }
          });
        }else if(ext == 'pdf'){
          pdfPath = docpath;
          callIframeWithCanvas(pdfPath);
        }
    }

    function getDocumentWorkflowNextStatus_<?php echo $this->rowId ?>(rowId) {
      console.log(row_<?php echo $this->uniqid ?>);
      if(typeof row_<?php echo $this->uniqid ?> !== 'undefined'){
        siUser = (typeof row_<?php echo $this->uniqid ?>.signuserid !== 'undefined') ? row_<?php echo $this->uniqid ?>.signuserid : '';
        $.ajax({
            type: 'post',
            url: 'Mdobject/dataViewDataGrid/false/false',
            data: {
                metaDataId: '<?php echo $this->metaDataId ?>',
                defaultCriteriaData: 'param%5Bid%5D=' + '<?php echo $this->rowId ?>'
            },
            dataType: 'json',
            success: function(data) {
              if(data.rows.length > 0){
                selectedRows = data.rows;
                $.ajax({
                    type: 'post',
                    url: 'mdobject/getWorkflowNextStatus',      
                    data: {
                      dataRow: selectedRows[0], 
                      metaDataId: '<?php echo $this->metaDataId ?>'
                    },
                    dataType: "json",
                    success: function(response) {
                if (response.status === 'success') {
                  $('.workflow-buttons-<?php echo $this->uniqid; ?>').empty();
                    if (response.data) {
                        $.each(response.data, function (i, v) {
                              var advancedCriteria = '';
                              if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                console.log('case1');
                                  advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                              }
                              if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                console.log('case2');
                                  $('.workflow-buttons-<?php echo $this->uniqid; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.processname+'\', {cyphertext: \'<?php echo '0'; ?>\', plainText: \'\'});" id="'+ v.wfmstatusid +'"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+ v.processname +'</a>'); 
                              } else {
                                  if (typeof v.processname != 'undefined' && v.processname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                      if (v.wfmisneedsign == '1') {
                                          $('.workflow-buttons-<?php echo $this->uniqid; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="docToPdf_<?php echo $this->uniqid ?>(\'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+v.wfmstatusid+'\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.processname+'\');" id="'+ v.wfmstatusid +'"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+ v.processname +' <i class="fa fa-key"></i></a>'); 
                                      } else if (v.wfmisneedsign == '2') {
                                          $('.workflow-buttons-<?php echo $this->uniqid; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="beforeHardSignChangeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.processname+'\');" id="'+ v.wfmstatusid +'"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+ v.processname +' <i class="fa fa-key"></i></a>'); 
                                      } else {
                                          $('.workflow-buttons-<?php echo $this->uniqid; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" mcolor: #fff;" onclick="changeWfmStatusId(this, \''+v.wfmstatusid+'\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \''+$.trim(v.wfmstatuscolor)+'\', \''+v.processname+'\', {cyphertext: \'<?php echo '0'; ?>\', plainText: \'\'});" id="'+ v.wfmstatusid +'"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+ v.processname +'</a>'); 
                                      }
                                    } else if (v.wfmstatusprocessid != '' || v.wfmstatusprocessid != 'null' || v.wfmstatusprocessid != null) {
                                      var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                      if (v.wfmisneedsign == '1') {
                                          $('.workflow-buttons-<?php echo $this->uniqid; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo 'DOC_LIST_TULUVLULT_DOCUMENT' ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.processname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\', null, \'<?php echo htmlentities($this->rowJson); ?>\');"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+v.processname+' <i class="fa fa-key"></i></a>');
                                      } else if (v.wfmisneedsign == '2') {
                                          $('.workflow-buttons-<?php echo $this->uniqid; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo 'DOC_LIST_TULUVLULT_DOCUMENT' ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.processname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\', null, \'<?php echo htmlentities($this->rowJson); ?>\');"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+v.processname+' <i class="fa fa-key"></i></a>');
                                      } else {
                                        $('.workflow-buttons-<?php echo $this->uniqid; ?>').append('<a href="javascript:;" ' + advancedCriteria + ' class="btn btn-secondary btn-circle btn-sm" color: #fff;" onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \''+v.wfmstatusprocessid+'\', \'<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>\', \'toolbar\', this, {callerType: \'<?php echo 'DOC_LIST_TULUVLULT_DOCUMENT' ?>\', isWorkFlow: true, wfmStatusId: \''+v.wfmstatusid+'\', wfmStatusCode: \''+wfmStatusCode+'\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.processname+'&statusColor='+$.trim(v.wfmstatuscolor)+'&rowId='+rowId+'\', null, \'<?php echo htmlentities($this->rowJson); ?>\');"><i class="fa '+v.wfmstatusicon+'" style="color:#4b6af1"></i> '+v.processname+'</a>');
                                      }
                                  }
                              }
                          });
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
            }})
      }
    }

    function sidebarRefreshCustom_<?php echo $this->rowId ?>(){
      $.ajax({
        type: 'post',
        url: 'mddoc/getDocumentSideBars',
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

    function customWfmStatusChangeCallback_<?php echo $this->rowId ?>(id, metadataid = <?php echo $this->metaDataId; ?>){
      $.ajax({
        type: 'post',
        url: 'mddoc/documentEcmMapListNew',
        data: {
            recordId: id, 
            metaDataId: 'documentEcmMapList_' + metadataid + '_' + id
        },
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
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

    function popupRegistrationCard_<?php echo $this->uniqid ?>(dataVwId, statId){
      $.ajax({
          type: "POST",
          url: 'mdstatement/renderDataModelByFilter',
          data: { 'param[filterId]': <?php echo $this->rowId; ?>, 'criteriaCondition[filterId]': '=', 'dataViewId': dataVwId, 'statementId': statId },
          dataType: 'json',
          success: function (data) {
        var $dialogName = 'dialog-lastimportedcache-<?php echo $this->rowId; ?>';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
           $dialog.empty().append(data.htmlData);
              $dialog.dialog({
                  cache: false,
                  resizable: false,
                  bgiframe: true,
                  autoOpen: false,
                  title: data.Title,
                  width: 1000,
                  height: $(window).height() - 290,
                  modal: true,
                  position: {my:'top', at:'top+50'},
                  closeOnEscape: isCloseOnEscape, 
                  close: function () {
                      $dialog.empty().dialog('close');
                  },
                  buttons: [
                      {text: 'Хаах', class: 'btn blue-hoki btn-sm', click: function () {
                          $dialog.dialog('close');
                      }},
                      {text: 'Хэвлэх', class: 'btn blue-hoki btn-sm', click: function () {
                          // $dialog.dialog('close');
                        // var $this = $(this);
                        $.ajax({
                            type: 'post',
                            url: 'mdpreview/printCss',
                            data: {
                                orientation: 'portrait',
                                size: '',
                                top: '40px',
                                left: '50px',
                                bottom: '40px',
                                right: '50px',
                                width: '',
                                height: '',
                                fontFamily: "arial, helvetica, sans-serif"
                            },
                            beforeSend: function(){
                                Core.blockUI({
                                    boxed: true,
                                    message: 'Printing...'
                                });
                            },
                            success: function(dataCss){
                                
                                $dialog.printThis({
                                    debug: false,
                                    importCSS: false,
                                    printContainer: false,
                                    dataCSS: dataCss,
                                    removeInline: false
                                });
                            },
                            error: function(){
                                alert('Error');
                            }

                        }).done(function(){
                            Core.unblockUI();
                        });
                      }}
                  ]
              });
              $dialog.dialog('open');
        }})
    }

    function playAudioComment(commentId){
      $.ajax({
          type: "POST",
          url: 'mdwebservice/runProcess',
          data: { param: {id: commentId}, methodId: 1551083998110, processSubType: 'internal', create: 0, isSystemProcess: true, windowSessionId: '',
                    responseType: '', wfmStatusParams: '', wfmStringRowParams: '', dmMetaDataId: '', cyphertext: '', plainText: '', cacheId: '' },
          dataType: 'json',
          success: function (data) {
            $('#prepDivAudio_'+ commentId).after("<div class='d-flex align-items-center justify-content-between'><span class='text-center'><audio controls src='"+data.resultData.commentaudio+"'></audio></span></div>");
            if($('#prepAtAudio_'+ commentId).length){
              $('#prepAtAudio_'+ commentId)[0].onclick = 'javascript:;';
            }
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
              runDefaultGetParam: 'id='+rowId,
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
    
    function deleteDocCommentById(id, elem) {
        var $dialogName = 'dialog-doc-comment-remove';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }

        $("#" + $dialogName).empty().html("Та устгахдаа итгэлтэй байна уу?");
        $("#" + $dialogName).dialog({
            appendTo: "body",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: "Сануулга",
            width: 350,
            height: 'auto',
            modal: true,
            close: function(){
                $("#" + $dialogName).empty().dialog('destroy').remove();
            },                        
            buttons: [
                {text: 'Тийм', class: 'btn btn-sm blue', click: function() {                            
                    $.ajax({
                        type: "post",
                        url: "api/callProcess",
                        data: {
                            processCode: "ECM_COMMENT_DOC_PLAN_DV_005",
                            paramData: { id: id }
                        },
                        dataType: "json",
                        async: false,
                        success: function (data) {
                        console.log(data);
                        },
                    });                   

                    var $selfelem = $(elem);
                    var $tabselector = $selfelem.closest('.e-detailview-tab');
                    var $ulselector = $selfelem.closest('ul');

                    $selfelem.closest('li').remove();
                    $tabselector.find('.doccomment-counter').text('Явц/тэмдэглэл ('+($ulselector.children().length)+')');
                    
                    $("#" + $dialogName).dialog('close');
                }},
                {text: 'Үгүй', class: 'btn btn-sm blue-hoki', click: function() {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        });
        $("#" + $dialogName).dialog('open');             
    }

function reloadDocWfmAssignmentList() {
    var $div = $('.doc-wfm-assignment-list:visible:eq(0)');
    if ($div.length) {
        $.ajax({
            type: 'post',
            url: 'mddoc/docWfmAssignmentList',
            data: {recordId: $div.attr('data-recordid')},
            success: function(data) {
                $div.empty().append(data);
            }
        });
    }
}
function reloadDocCardClosedInfo() {
    var $div = $('.doc-card-closed-info:visible:eq(0)');
    if ($div.length) {
        $.ajax({
            type: 'post',
            url: 'mddoc/docCardClosedInfo',
            data: {recordId: $div.attr('data-recordid')},
            success: function(data) {
                $div.empty().append(data);
            }
        });
    }
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
    .dvecommerce button#sidebarCollapse,
    .dvecommerce button#sidebarCollapse2 {
      position: absolute;
      top: -3px;
      z-index: 999999;
      padding: 4px 8px;
      border-radius: 0;
      background: none;
      border: 0;
      color: #003d74;
    }
    .dvecommerce button#sidebarCollapse {
      left: -15px;
    }
    .dvecommerce button.btnsidebarCollapse2 {
      right: -15px;
    }
    .dvecommerce button#sidebarCollapse i,
    .dvecommerce button#sidebarCollapse2 i {
      font-size: 34px;
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
    .div-td-icon{
        min-width: 127px;
        padding: 5px;
        padding-right: 0px;
    }
    .div-td-txt{
        max-width: 160px;
    }
    
    .tb-left-doc{
        overflow-x: hidden;
    }
</style>