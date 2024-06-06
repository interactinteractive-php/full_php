<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<?php if (isset($this->hideMenu) && !$this->hideMenu) { ?>

<div class="page-sidebar-wrapper <?php echo (isset($this->hideMenu) && $this->hideMenu) ? 'hidden' : '' ?>">
    <div class="page-sidebar navbar-collapse collapse">      	
      <ul class="page-sidebar-menu page-sidebar-menu-hover-submenu1" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
        <!--        <li>
                    <div class="sidebar-toggler hidden-phone"></div>
                </li>-->
        <li>
          <a href="javascript:;">
            <i class="fa fa-bar-chart"></i> <span class="title">Тайлан</span> <span class="arrow"></span>
          </a>
          <?php if (sizeof($this->modelList) > 0) { ?>
            <ul class="sub-menu" style="display: block;">
              <?php foreach ($this->modelList as $report) { ?>
              <li style="border-top: 1px solid #3d4957;"></li>
                <li style="">
                    <!--<a href="javascript:;" onclick="setModelId(<?php echo $report['id']; ?>)"><i class="fa fa-caret-right"></i><?php echo $report['modelName']; ?></a>-->
                  <a href="javascript:;" onclick="setModelId(<?php echo $report['id']; ?>, this)"><i class="fa fa-line-chart"></i><?php echo $report['modelName']; ?></a>
                </li>
              <?php } ?>
              <?php echo Form::close(); ?>
            </ul>
          <?php } else { ?>
            <ul class="sub-menu">
              <li>
                <a href="javascript:;"><i class="fa fa-caret-right"></i>Үүсгэсэн тайлан байхгүй.</a>
              </li>
            </ul>
          <?php } ?>
        </li>
        <li>
          <a href="rmreport">
            <i class="icon-plus3 font-size-12"></i> <span class="title">Тайлан угсрах</span>
          </a>
        </li>
      </ul>
    </div>
</div>
<div class="page-content-wrapper">
  <div class="page-content" style="padding-top: 0px !important;">
    <div class="row">
      <div class="card light">
<?php }  ?>
        <style type="text/css">
          #mainReportBody .page-sidebar .page-sidebar-menu .sub-menu li > a {
            padding-left: 25px;
          }
          #mainReportBody .template-section{
            padding-left: 25px;
            padding-right: 25px;
          }     
          #mainReportBody .card {
            margin-bottom: 5px;
          }
          #mainReportBody .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #ddd;
          }
          #mainReportBody .card.light {
            padding: 0px 20px 15px 20px;
            background-color: #eaeaea;
          }
          #mainReportBody .page-content{
            background-color: #eaeaea !important;
          }
/*          #mainReportBody .btn.default{
            background-color: #B5B1B1;
          }*/
          #mainReportBody label.error{
            font-size: 12px;
          }
          #mainReportBody .select2-container .select2-choice, .select2-chosen {
            height: 15px !important;
            min-height: 25px !important;
            line-height: 10px !important;
            font-size: 12px !important;
            padding-top: 4px !important;
            padding-left: 7px !important;
          }
          #mainReportBody table th, #mainReportBody table td{
            font-size: 12px !important;
            padding: 5px !important;
          }
          #mainReportBody .customSelect {
            height: 24px !important;
            padding: 0px !important;
            width: 87px !important;
          }
          #mainReportBody .dateInit {
            width: 100px !important;
            padding: 0px 15px 0px 15px;
          }
          /*        .page-sidebar .page-sidebar-menu .sub-menu > li > a {
                      color: #485a6a;
                  }*/
            .filter-condition {
                padding-right: 0;
                width: 87px;
                height: 24px;
            }
            
            .filter-operator {
                border-right: #FFF;
                border-bottom-right-radius: 0;
                border-top-right-radius: 0;
            }
            .filter-operation {
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
            }
        </style>
        <div id="noChosenReportMessage <?php echo (isset($this->hideMenu) && $this->hideMenu) ? 'hidden' : '' ?>" style="display: none; padding-top: 30px;">
            <h1 class="page-title" style="text-align: center;">ТА ТАЙЛАН СОНГОНО УУ</h1>
        </div>
        <div class="card-body xs-form" id="mainReportBody" style="display: none;">
            <div class="row">
                <div class="col-sm-12" style="padding-left: 5px; text-align: center;" id='controlbox'>
                    <?php echo Form::create(array('role' => 'form', 'id' => 'mainForm', 'method' => 'POST')) ?>
                    <?php echo Form::hidden(array('name' => 'templateId', 'id' => 'templateId', 'value' => 0, 'class' => 'form-control')); ?>
                    <div class="form-body">
                        <?php echo Form::hidden(array('name' => 'modelId', 'id' => 'modelId', 'data' => $this->modelList, 'op_value' => 'id', 'op_text' => 'modelName', 'selectedIndex' => "2", 'class' => 'form-control', 'onchange' => 'reloadFilter()')); ?>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div id='containerfield' class="col-sm-12 template-section">
                    <div class="row">
                        <div class="card light shadow">
                            <div class="card-header card-header-no-padding header-elements-inline" style="min-height: 0px;">
                                <div class="caption p-0 card-collapse expand"><i class="fa fa-search"></i> Шүүлтүүр</div>
                                <div class="tools p-0"> 
                                    <a href="javascript:;" class="tool-collapse collapse"></a>
                                </div>
                            </div>
                            <div class="card-body form xs-form top-sidebar-content mb10 pl0 pr0" style="display: block;">
                                <div id="filter" style="padding: 10px"></div>
                            </div>
                        </div>
                    </div>
                    <div class='row' id="showReport" style="margin-top: 10px; display: none;">
                        <?php echo Form::create(array('role' => 'form', 'id' => 'reportForm', 'method' => 'POST')) ?>
                            <div class="row" style="">
                                <div class="col-md-12">
                                    <div class="btn-toolbar float-right">
                                        <div class="btn-group btn-group-solid">
                                            <?php echo Html::anchor('javascript:;', '<i class="fa fa-file-excel-o"></i> ' . $this->lang->line('excel_btn'), array('class' => 'btn btn-sm green exportExcel', 'onclick' => "exportExcel()")); ?>
                                            <?php echo Html::anchor('javascript:;', '<i class="fa fa-file-excel-o"></i>  ' . $this->lang->line('excel_btn'), array('class' => 'btn btn-sm green exportExcel2', 'onclick' => "exportExcelNew()")); ?>
                                            <!--<?php echo Html::anchor('javascript:;', '<i class="fa fa-file-pdf-o "></i> ' . $this->lang->line('pdf_btn'), array('class' => 'btn btn-sm default', 'onclick' => "exportPDF()")); ?>-->
                                            <?php echo Html::anchor('javascript:;', '<i class="fa fa-print "></i> ' . "Хэвлэх", array('class' => 'btn btn-sm default', 'onclick' => "printPartOfPage('prvReportSource')", 'style' => 'margin-left: 7px;')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card box" style="margin-top: 10px; border: 1px solid #60aee4;" id="reportPortlet">
                              <?php if (isset($this->hideMenu) && !$this->hideMenu) { ?>
                              <div class="card-header card-header-no-padding header-elements-inline" style="background-color: #3598dc;">
                                <div class="card-title">
                                  <span id="reportName"></span>
                                </div>
                                <div class="tools">
                                  <a href="" class="fullscreen" data-original-title="" title="" id="reportFullscreen" style="padding-top: 5px; padding-right: 5px;">
                                  </a>
                                                    </div>
                              </div>
                              <?php } ?>
                                <div class="card-body form xs-form display-none" style="display: block;">
                                    <div class="template-section">
                                        <div class="row" id="pagingRow" style="padding-top: 5px;">
                                            <div class="float-left" id="reportRowNum"></div>
                                            <div class="float-right" id="reportPaging"></div>
                                            <div class="float-right">&nbsp; Нийт <span id="totalNum" style="font-weight: bold;"></span> байна. &nbsp;&nbsp;</div>
                                        </div>
                                        <div class="row" id="reportPreview">
                                            <div id='prvReportHeader'>
                                            </div>
                                            <div id="reportHeader">
                                            </div>
                                            <div class="table-scrollable" style=" height: 400px; overflow-y: scroll;">

                                              <div id="prvReportSource">
                                                <style>
                                                  #prvReportSource tbody{
                                                    font-size: 12px;
                                                  }
                                                </style>
                                              </div>
                                            </div>
                                            <div id="reportFooter">
                                            </div>
                                            <div id="prvReportFooter">
                                            </div>
                                            <div id='prvReportFoot'>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php echo Form::close(); ?>
                    </div>
                </div>
                <a id="dlink"  style="display:none;"></a>
            <?php echo Form::close(); ?>
            </div>
        </div>
<?php if (isset($this->hideMenu) && !$this->hideMenu) { ?>
            </div>
            </div>
        </div>
    </div>
<?php }  ?>

<script src="middleware/assets/js/rmbase.js" type="text/javascript"></script>
<script type="text/javascript">
          var dgPreviewReport='#dgPreviewReport';
          var tempModelId = '<?php echo isset($this->modelId) ? $this->modelId : '' ?>';
          var is_hide=false;
          $(document).ready(function(){
            Core.initAjax();
                reloadFilter();
//                reloadModel();
                control_toggle();
            
            $('.menu-toggler').click(function(){
              if($('body').hasClass('page-sidebar-closed')){
                if($('#reportPortlet').hasClass('card-fullscreen')){
                  $('#reportFullscreen').trigger('click');
                }
              }
            });
            if($('body').hasClass('page-sidebar-closed')){
              $('.menu-toggler').trigger('click');
            }
            if (tempModelId != '') {
                setModelId(tempModelId);
            }
            if($('#modelId').val() === ''){
              $('#mainReportBody').hide();
              $('#noChosenReportMessage').show();
            } else {
              $('#noChosenReportMessage').hide();
              $('#mainReportBody').show();
            }
            $('#reportFullscreen').click(function(){
              if($('#reportPortlet').hasClass('card-fullscreen')){
                $('#reportPortlet').css('margin-top', '10px');
                $('.table-scrollable').css('height', '400px');
                $('#reportPortlet').removeClass('card-fullscreen');
              } else {
                var pageLogoHeight = $('.page-logo').height();
                $('#reportPortlet').css('margin-top', pageLogoHeight);
                $('.table-scrollable').css('height', '500px');
                $('#reportPortlet').addClass('card-fullscreen');
              }
            });
            $.each($('.customSelect'), function(key, value){
              $(this).val('=');
            });
          });
          
          function reportSelectableGrid(metaDataCode, chooseType, elem, rows){
              var row = rows[0];
              var valueField = $(elem).attr('data-valueField');
              valueField = valueField.toLowerCase();
              console.log(valueField);
              $(elem).parent().parent().find("input[class*='valueField']").val(row[valueField]);
          }
            
          function setModelId(modelId, element){
            $('#modelId').val(modelId);
            $('.sub-menu').find('li.active').removeClass('active');
            $(element).parent().addClass('active');
            reloadFilter();
            var reportName=$(element).text();
            $('#reportName').text(reportName);
            $('#totalNum').text(0);
            $('#reportPaging').empty();
            $('#reportRowNum').empty();
            $('#reportHeader').empty();
            $('#reportFooter').empty();
          }

//          function clearInput(){
//            $("input[name$='[value1]']").val('');
//            $("input[name$='[value2]']").val('');
//            $('.select2-container').select2('val', '');
//          }
          function clearInput(){
//            $("input[name$='[value1]']").val('');
//            $("input[name$='[value2]']").val('');
            $('.clearClass').select2('val', '');
            $('.clearClass').val('');
          }
          
          function printPartOfPage(elementId)
          {
            var printContent=document.getElementById(elementId);
            var windowUrl='about:blank';
            var uniqueName=new Date();
            var windowName='Print' + uniqueName.getTime();
            var printWindow=window.open(windowUrl, windowName, 'left=50000,top=50000,width=0,height=0');


            var header=document.getElementById("prvReportHeader");

            var footer=document.getElementById("prvReportFooter");



            printWindow.document.write(header.innerHTML + printContent.innerHTML + footer.innerHTML);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
          }


          function control_toggle(){

        //        if (is_hide) {
        //            $("#controlbox").show();
        //            $("#containerfield").addClass('col-sm-9');
        //            is_hide = false;
        //        } else {
        //            $("#controlbox").hide();
        //            $("#containerfield").removeClass('col-sm-9');
        //            is_hide = true;
        //        }
          }

          function printPDF(){
            var pdf=new jsPDF('p', 'pt', 'a4');
            var options={
              pagesplit: true
            };

            pdf.addHTML($("#reportPreview"), options, function()
            {
              pdf.save("test.pdf");
            });

          }

          function exportExcel()
          {
            var rep=document.getElementById('reportPreview');
        //        console.log(rep.innerHTML);
            var selectedModelId=$('#modelId').val();
            var inputs="<input type='hidden' name='html' value='" + rep.innerHTML.replace(/(\r\n|\n|\r)/gm,
                    '').replace(' ', '') + "' />\n\
                <input type='hidden' name='modelId' value='" +
                    selectedModelId + "'/>";
            jQuery("<form action='rmreport/export_excel' method='post' _target>" + inputs + "</form>").
                    appendTo('body').submit().remove();
          }

          function exportExcelNew(){
            var serializeValues=$("#mainForm").serializeArray();
            var serializeFilters=$("#filterForm").serializeArray();
            var values='';
            var filters='';

            for(var i=0, count=serializeValues.length, checkCountLast=count - 1; i < count; i++){

              if(i === checkCountLast){
                values+='values[]=' + serializeValues[i]['name'] + '=' + serializeValues[i]['value'];
              } else {
                values+='values[]=' + serializeValues[i]['name'] + '=' + serializeValues[i]['value'] + '&';
              }
            }


            for(var i=0, count=serializeFilters.length, checkCountLast=count - 1; i < count; i++){

              if(i === checkCountLast){
                filters+='filters[]=' + serializeFilters[i]['name'] + '=' + serializeFilters[i]['value'];
              } else {
                filters+='filters[]=' + serializeFilters[i]['name'] + '=' + serializeFilters[i]['value'] +
                        '&';
              }
            }
            location.href='/rmreport/export_excel_new?' + values + '&' + filters;
          }

          function exportPDF()
          {
            printPDF();
          }

        function reloadFilter() {
            $('#filter').empty();
            $('#showReport').hide();
            $('#prvReportSource').empty();
            $('#pagingRow').hide();
            $.ajax({
                type: 'post',
                url: 'rmreport/getFilterArea',
                dataType: "json",
                data: {
                    values: $('#mainForm').serialize()
                },
                beforeSend: function() {
                    Core.blockUI({
                        target: ".page-container",
                        message: 'Уншиж байна. Та түр хүлээнэ үү...',
                        boxed: true
                    });
                },
                success: function(result) {
                    console.log(result.Html);
                    $('#filter').empty().html(result.Html);
                    if($('#collapseAccordion').hasClass('collapsed')) {
                        $('#collapseAccordion').trigger('click');
                    }
                    $('#noChosenReportMessage').hide();
                    $('#mainReportBody').show();
                    $(".dateInit").each(function() {
                        $(this).datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true
                        });
                        $(this).datepicker({
                            format: "yyyy-mm-dd",
                            viewMode: "days",
                            minViewMode: "days",
                            autoclose: true,
                            language: 'mn'
                        });
                    });
                    Core.unblockUI(".page-container");
                },
                error: function(msg) {
                    Core.unblockUI(".page-container");
                }
            }).done(function() {
                Core.unblockUI(".page-container");
                Core.initAjax();
            });
        }

        function reloadModel() {
            var filterValues=$("#filterForm").serialize();

            $('#prvReportSource').empty();
            $('#pagingRow').show();
            $('#filterForm').validate({errorPlacement: function() {}});
            if($('#filterForm').valid()){
                $.ajax({
                    type: 'post',
                    url: 'rmreport/getReportSource',
                    dataType: "json",
                    data: {
                        values: $("#mainForm").serialize(),
                        filters: $("#filterForm").serialize(),
                        page: $('#pagingSelect').val(),
                        rowNum: $('#rowNumSelect').val()
                    },
                    beforeSend: function() {
                        Core.blockUI({
                            target: ".page-container",
                            message: 'Уншиж байна. Та түр хүлээнэ үү...',
                            boxed: true
                        });
                    },
                    success: function(result) {
                        PNotify.removeAll(); 
                        if(result.data.length === 0) {
                            if (typeof result.$exception.length != 0) {
                                new PNotify({
                                    title: 'Алдаа',
                                    text: "Алдаа : " + result.$exception  + ', </br>' + result.$exceptionquery,
                                    type: 'warning',
                                    sticker: false
                                });
                            } else {
                                new PNotify({
                                    title: 'Мэдэгдэл',
                                    text: "Таны хайлтын утгад тохирох утга байхгүй байна.",
                                    type: 'warning',
                                    sticker: false
                                });
                            }

                          Core.unblockUI(".page-container");
                        } else {
    //                      if(filterValues !== ''){
                            var totalDatas=result.data['total'];
                            var totalNum = result.data['total'];
                            var pageNum=0;
                            var fromNum = 1;
                            var toNum = 0;
                            var rowNum=result.data['rowNum'];
                            var selectedPage;
                            var pagingHtml='';
                            var rowNumHtml='';
                            var mergeRowCount = 0;
                            totalDatas=totalDatas / rowNum;
                            if(totalDatas % 1 === 0){
                              pageNum=totalDatas;
                            } else {
                              pageNum=parseInt(totalDatas);
                              pageNum=pageNum + 1;
                            }
                            //                    console.log(result.data['page']);
                            pagingHtml+=
                                    "<select id='pagingSelect' name='pagingSelect' class='form-control select2me' data-placeholder='" +
                                    result.data['page'] + "'>";
                            selectedPage=parseInt(result.data['page']);
                            for(var j=1; j <= pageNum; j++){
                              if(j === selectedPage){
                                pagingHtml+="<option value='" + j + "' selected>" + j + "</option>";
                              } else {
                                pagingHtml+="<option value='" + j + "'>" + j + "</option>";
                              }
                            }
                            pagingHtml+="</select>";
                            //                    console.log(result.data['rowNums']);
                            rowNumHtml+="<select id='rowNumSelect' name='rowNumSelect' class='form-control'>";
                            if(typeof result.data['rowNums'] !== 'undefined'){
                              for(var i=0; i < result.data['rowNums'].length; i++){
                                if(result.data['rowNums'][i] === rowNum){
                                  rowNumHtml+="<option value='" + result.data['rowNums'][i] + "' selected>" +
                                          result.data['rowNums'][i] + "</option>";
                                } else {
                                  rowNumHtml+="<option value='" + result.data['rowNums'][i] + "'>" +
                                          result.data['rowNums'][i] + "</option>";
                                }
                                //                      console.log(result.data['rowNums'][i]);
                              }
                            }
                            //                    rowNumHtml += "<option value='1000' selected='selected'>1000</option>";
                            //                    rowNumHtml += "<option value='100'>100</option>";
                            //                    rowNumHtml += "<option value='10'>10</option>";
                            rowNumHtml+="</select>";
      //                      if(result.data['page'] !== 1){
      //                        fromNum = selectedPage * rowNum + 1;
      //                      }
      //                      if(result.data['page'] === 1){
      ////                        alert(1);
      //                        fromNum = 1;
      //                        toNum = result.data['pageDatasCount'];
      //                      } else {
      ////                        alert(2);
      ////                        fromNum = selectedPage * rowNum + 1;
      //                        fromNum = selectedPage * rowNum + 1;
      //                        toNum = selectedPage * rowNum + result.data['pageDatasCount'];
      //                      }
      //                      alert(result.data['pageDatasCount']);
      //                      $('#fromNum').text(fromNum);
      //                      $('#toNum').text(toNum);
                            $('#totalNum').text(totalNum);
                            $('#reportPaging').empty().html(pagingHtml);
                            $('#reportRowNum').empty().html(rowNumHtml);
                            //                    $('#prvReportSource').empty().html(getTableHtml(result.rows, result.cols, result.facts, result.data, filterValues));
                            $('#prvReportSource').empty().html(getTableHtml(result.rows, result.cols,
                                    result.facts, result.data['datas'], filterValues));
                            $('#reportHeader').empty().html(result.headerHtml);   
                            $('#reportFooter').empty().html(result.footerHtml);

                            for(var i = 0; i < result.rows.length; i++){
                              var myTable=$('#table1'), tmpText="", tmpMergeTds={}, tmpIndex;
                              if(result.rows[i]['isMerge'] === '1'){
                                mergeRowCount++;
                                tmpMergeTds={};
                                  myTable.find("td.mergeClass"+mergeRowCount+"").each(function(key, val){
                                    if(tmpText !== $(this).html()){
                                      tmpIndex=key;
                                    }

                                    tmpText=$(this).html();
                                    if(typeof tmpMergeTds[tmpIndex] === "undefined"){
                                       tmpMergeTds[tmpIndex]= [$(this)];
                                    } else {
                                      tmpMergeTds[tmpIndex].push($(this));
                                    }
                                  });

                                $.each(tmpMergeTds, function(){
                                  var mergeEls = $(this);
                                  if(mergeEls.length > 1){
                                    mergeEls[0].attr("rowspan", $(this).length);
                                    mergeEls[0].css('vertical-align', 'middle');
                                    for(var j=1; j<mergeEls.length;j++){
                                      mergeEls[j].remove();
                                    }
                                  }
                                  });
                              }
                            }
                            if(result.facts.length > 0){
                              $('.exportExcel2').hide();
                              $('.exportExcel').show();
                              $('#pagingRow').hide();
                            } else {
                              $('.exportExcel').hide();
                              $('.exportExcel2').show();
                              $('#pagingRow').show();
                            }
                            $('#rowNumSelect').change(function(){
                              $('#pagingSelect').val('1');
                              reloadModel();
                            });

                            $('#pagingSelect').change(function(){
                              reloadModel();
                            });

                            //                    Core.handleSelect2($("#pagingSelect"));
                            $('#pagingSelect').select2({
                              placeholder: "Select",
                              allowClear: true
                            });
                            if(!$('body').hasClass('page-sidebar-closed')){
                              $('.menu-toggler').trigger('click');
                            }
    //                        $('#dataUpdatedDate').text($('#s2id_reportMonth').select2('val'));
                            $('#collapseAccordion').trigger('click');
                            $('#showReport').show();
    //                        $('#reportHeader').ScrollTo();
    //                      }
                          Core.unblockUI(".page-container");
                        }
                    },
                    error: function(msg){
                        Core.unblockUI(".page-container");
                    }
                });
            }
        }

          function selectTemplate(templateId)
          {
            document.getElementById('templateId').value=templateId;
            reloadTemplate();
          }
          function reloadTemplate()
          {
            $('#prvReportHeader').empty();
            $('#prvReportFooter').empty();
            $.ajax({
              type: 'post',
              url: 'rmreport/getReportTemplate',
              dataType: "json",
              data: {
                values: $("#mainForm").serialize()
              },
              beforeSend: function(){
              },
              success: function(result){
                $('#prvReportHeader').empty().html(result.headerHtml);
                $('#prvReportFooter').empty().html(result.footerHtml);
              },
              error: function(msg){
                console.log(msg);
              }
            });
          }
          
          function rmRunProcess(processId, elem) {
              var _this = $(elem);
        
                $.ajax({
                    type: 'post',
                    url: 'mdstatement/processRun',
                    data: {
                        processMetaId: processId,
                        filterStartDate: _this.closest("form").find("input.dateInit:visible:eq(0)").val(),
                        filterEndDate: _this.closest("form").find("input.dateInit:visible:eq(1)").val()
                    }, 
                    dataType: 'json', 
                    beforeSend: function () {
                        Core.blockUI({
                            message: '<?php echo $this->lang->line('process_running'); ?>',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        Core.unblockUI();
                    },
                    error: function () {
                        alert("Error");
                    }
                });
          }
        </script>