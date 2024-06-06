<?php
if (!$this->isAjax) {
    ?>
    <div class="col-md-12">
        <div class="card light shadow">
            <div class="card-header card-header-no-padding header-elements-inline">
                <div class="card-title">
                    <i class="fa fa-pencil-square"></i> <?php echo $this->title; ?>
                </div>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="fullscreen"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
<?php
}
?>  
                <div class="row" id="finFiscalPeriodCloseWindow_<?php echo $this->uniqId; ?>">
                    <div class="col-md-12 center-sidebar">
                        <form id="finFiscalPeriodCloseForm_<?php echo $this->uniqId; ?>" class="form-horizontal xs-form" method="post">
                            <div class="form-body">
                                <div class="row">
                                    <div class="card light shadow">
                                        <div class="card-header card-header-no-padding header-elements-inline" style="min-height: 0px;">
                                            <div class="caption p-0 card-collapse _collapse">Ерөнхий мэдээлэл</div>
                                            <div class="tools p-0"> 
                                                <a href="javascript:;" class="tool-collapse collapse"></a>
                                            </div>
                                        </div>
                                        <div class="card-body form xs-form display-none top-sidebar-content mb10 pl0 pr0" style="display: block;">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                    <div class="next-generation-input-wrap">
                                                        <div class="next-generation-input-label green">
                                                            Салбар нэгж
                                                            <div class="next-generation-input-group">
                                                                <div class="meta-autocomplete-wrap">
                                                                    <div class="input-group">
                                                                        <input type="hidden" id="departmentId_valueField" class="departmentId_valueField" name="param[departmentId]" value="" required="required">
                                                                        <input type="text" id="calcTypeCode_displayField" name="calcTypeCode" class="form-control form-control-sm meta-autocomplete-salary lookup-code-autocomplete-salary calcTypeCode_displayField" value="" required="required" title="" placeholder="Код" data-metadataid="0" data-processid="0" data-lookupid="1457081813808" data-lookuptypeid="200101010000016">
                                                                        <span class="input-group-btn">
                                                                            <button type="button" id="searchCalcTypeButton" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('<?php echo $this->departmentDV; ?>', 'single', 'chooseRowGrid_<?php echo $this->uniqId; ?>', '', this);"><i class="fa fa-search"></i></button>
                                                                        </span>
                                                                    </div>                                                                
                                                                </div>                                                                
                                                            </div>    
                                                        </div>
                                                        <div class="next-generation-input-body green">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                    <div class="next-generation-input-wrap">
                                                        <div class="next-generation-input-label blue">
                                                            Тайлант үе
                                                            <div class="next-generation-input-group">
                                                                <div class="meta-autocomplete-wrap">
                                                                    <div class="input-group">
                                                                        <input type="hidden" id="periodId_valueField" class="periodId_valueField" name="periodId" value="" required="required">
                                                                        <input type="text" id="calcTypeCode_displayField" name="calcTypeCode" class="form-control form-control-sm meta-autocomplete-salary lookup-code-autocomplete-salary calcTypeCode_displayField" value="" required="required" title="" placeholder="Код" data-metadataid="0" data-processid="0" data-lookupid="1461311873195" data-lookuptypeid="200101010000016">
                                                                        <span class="input-group-btn">
                                                                            <button type="button" id="searchCalcTypeButton" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('<?php echo $this->finFiscalPeriodDV; ?>', 'single', 'chooseRowGridPeriod_<?php echo $this->uniqId; ?>', '', this);"><i class="fa fa-search"></i></button>
                                                                        </span>
                                                                    </div>                                                                
                                                                </div>                                                                
                                                            </div>    
                                                        </div>
                                                        <div class="next-generation-input-body blue">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                    <div class="next-generation-input-wrap">
                                                        <div class="next-generation-input-label orange">
                                                            Хаалт хийсэн огноо
                                                            <div class="next-generation-input-group">
                                                                <div class="meta-autocomplete-wrap">
                                                                    <div class="dateElement input-group">
                                                                        <input type="text" id="fromDate" name="fromDate" class="form-control form-control-sm dateInit" value="" >
                                                                        <span class="input-group-btn input-group-append "><button onclick="return false;" class="btn"><i class="fal fa-calendar"></i></button></span>
                                                                    </div>                                                              
                                                                </div>                                                                
                                                            </div>    
                                                        </div>
                                                        <div class="next-generation-input-body orange">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                    <div class="">
                                                        <?php
                                                        echo Form::button(array('class' => 'btn btn-circle btn-success searchCalcInfo btn-sm', 'value' => '<i class="fa fa-check-circle"></i> Шалгах'));
                                                        echo Form::button(array('class' => 'btn blue-steel btn-circle btn-sm finFiscalPeriodBtn ml5', 'value' => '<i class="fa fa-ban"></i> Тайлант үе хаах'));
                                                        ?>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="filterStartDate" name="param[filterStartDate]" class="form-control form-control-sm dateInit fin-fiscalperiod-enddate" value="" placeholder="Шүүлт дуусах огноо" data-metadataid="" data-path="" data-field-name="">
                            <input type="hidden" id="filterEndDate" name="param[filterEndDate]" class="form-control form-control-sm dateInit fin-fiscalperiod-enddate" value="" placeholder="Шүүлт дуусах огноо" data-metadataid="" data-path="" data-field-name="">
                        </form>
                        
                        <div class="col-md-12 jeasyuiTheme3 mt5" id="dataGridDiv">
                            <table class="no-border mt0" id="finRepGuilgeeDataGrid" style="width: 100%; "></table>
                        </div>                         
                </div>        
            </div>    
<?php
if (!$this->isAjax) {
?>       
            </div>
        </div>
    </div>
<?php
}
?>

<style type="text/css">
    .selectedDepartment_<?php echo $this->uniqId; ?> {
        background-color: #FFF;
        cursor: default !important;
    }    
    .departmentlist-jtree-<?php echo $this->uniqId; ?> {
        overflow: auto;
        z-index: 101;
        position:absolute;
        border-right: 1px solid #CCC;
        border-bottom: 1px solid #CCC;
        border-left: 1px solid #CCC;
        max-height: 350px;
        padding-bottom: 10px;
        background: #FFF;
    }    
    .departmentlist-jtree-<?php echo $this->uniqId; ?> .jstree-container-ul {
        background: #FFF !important;
    }
    .search-tree-<?php echo $this->uniqId; ?> > span {
        float: left;
        margin-right: 5px;
        margin-left: 5px;
        padding-top: 5px;
        font-size: 12px;
    }
    .search-tree-<?php echo $this->uniqId; ?> {
        background: #FFF !important; 
        padding-top: 6px;
        padding-bottom: 2px;
        border-bottom: 1px solid #ccc;
        padding-left: 8px;
        padding-right: 8px;        
        font-size: 12px;
    }
    .search-tree-<?php echo $this->uniqId; ?> > input {
        border-radius: 0 !important;
        width: 100% !important;
    }    
    
    #calculateSalarySheetDiv .customLabel {
        padding-top: 3px;
        font-size: 14px;
        
        font-weight: 400;
        color: #444;
        text-align: right;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> #fieldExpressionSpan_<?php echo $this->uniqId; ?>, #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> #fieldSpan_<?php echo $this->uniqId; ?>{
        font-size: 14px !important;
        
        text-align: justify;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .form-control-sm {
        border-radius: 0px !important        
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-body .datagrid-cell{
        padding: 0px !important;
        /*padding-left: 5px !important;*/
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body td input[type="text"]:focus{
        border: 1px solid #999 !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body td{
        /*border-width: 0px 0px 0px 0px !important;*/
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-body .form-control {
        border: 0px !important;
        font-size: 12px !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .jeasyuiTheme3 .datagrid-footer .form-control {
        font-size: 12px !important;
    }

    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-footer input{
        border: 0px !important;
        font-weight: bold !important;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-footer .form-control[readonly]{
        background-color: #fff;
    }
    #dialog-prl-call-process_<?php echo $this->uniqId; ?> .datagrid-body{
        height: auto !important;
    }

    #dialog-prl-call-process_<?php echo $this->uniqId; ?> .datagrid-wrap{
        height: 240px !important;
    }
    #dialog-prl-call-process_<?php echo $this->uniqId; ?> .datagrid-view{
        height: 200px !important;
    }
    #dialog_for_employee_<?php echo $this->uniqId; ?>{
        height: 200px !important;
    }
    .datagrid-row-over td {
        background: #fff;
    }  
    .datagrid-row-selected td {
        background: #98ccff !important;
        border-bottom-color: #98ccff;
        color: #000;
    }  
    .datagrid-row-selected input {
        background: #98ccff !important;
        color: #000;
    }  
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-body .datagrid-cell input.saved-log-data-cell {
        width: 76%;
        float: left;
    }
    #calculateSalarySheetDiv_<?php echo $this->uniqId; ?> .datagrid-body .datagrid-cell a.btn-secondary {
        float: right;
        border-bottom-style: none;
        border-right-style: none;
        border-top-style: none;
        border-radius: 50%;
        border-left-color: #333;
        margin-top: 1px;    
    }
    .jeasyuiTheme3 .datagrid-header .datagrid-cell span {
        font-size: 11px;
        color: #333;
    }
    .multipleAddFilterBtn {
        position: absolute;
        margin-top: -22px;
        right: 0;
        margin-right: 30px;        
    }        
    .context-menu-list {
        box-shadow: 5px 5px 5px -3px rgba(0,0,0,0.6);
    }
    .selectedDepartmentNamesContainer {
        max-height: 58px;
        overflow: hidden;
        color: #505050;
    }
    .selectedDepartmentNamesContainerBtn {
        text-align: center;
        font-size: 11px;
        text-transform: lowercase;
        color: #789e26;
        border-top: 1px solid #c2da8e;
    }
    .selectedDepartmentNamesContainerBtn:hover {
        color: #0057c7;
        cursor: pointer;
    }
    
    /* Custom Card CSS Start */
    .next-generation-input-wrap {
        width: 100%;
        height: 80px;
        -moz-box-shadow: 0px 0px 10px 0px #dadada;
        -webkit-box-shadow: 0px 0px 10px 0px #dadada;
        box-shadow: 0px 0px 10px 0px #dadada;        
    }
    @media screen and (min-width: 1220px) {
        .next-generation-input-label {
            width: 40%;
        }
        .next-generation-input-body {
            width: 60%;
        }
    }
    @media screen and (max-width: 1220px) {
        .next-generation-input-label {
            width: 60%;
        }
        .next-generation-input-body {
            width: 40%;
        }        
    }
    .next-generation-input-label {
        float: left;
        height: inherit;
        padding: 8px;
        padding-top: 5px;
        position: relative;
    }    
    .next-generation-input-label > .next-generation-input-group > .meta-autocomplete-wrap > .input-group > input {
        border: none;
    }
    .next-generation-input-body {
        background-color: #f3f7e8;
        float: left;
        height: inherit;
        padding: 8px;
        padding-top: 5px;
    }
    .next-generation-input-label.green {
        background-color: #e1ebcb;
        border-left: 5px solid #abc66a;
    }    
    .next-generation-input-label.green label {
        color: #666;
        font-size: 11px;
    }
    .next-generation-input-label.green .checker {
        margin-top: 0px !important;
        margin-left: 0px !important;
    }
    .next-generation-input-body.green {
        background-color: #f3f7e8;
    }
    .next-generation-input-label.blue {
        background-color: #c5e7f0;
        border-left: 5px solid #57bcd3;
    }    
    .next-generation-input-body.blue {
        background-color: #e4f5fa;
    }
    .next-generation-input-label.orange {
        background-color: #fbdfc4;
        border-left: 5px solid #f79b37;
    }    
    .next-generation-input-body.orange {
        background-color: #fef1e3;
    }
    .next-generation-input-label > .next-generation-input-group {
        position: absolute;
        bottom: 8px;
    }       
    .groupDepartmentId_<?php echo $this->uniqId; ?> {
        padding-right: 8px;
    }       
    .next-generation-input-label .input-group-btn {
        width: 0 !important;
        padding-right: 8px;
    }       
    .salarySheetActions {
        margin-top: -8px;
    }
    @media screen and (min-width: 1050px) {
        .input-icon.right .form-control {
            padding-right: 0px;
            margin-right: 85px;
        }
    }
    /* Custom Card CSS End */
    .select2-container-multi .select2-choices {
        min-height: 24px;
        height: 24px !important;
        padding-top: 0;
    }
    .select2-container-multi .select2-choices .select2-search-choice {
        margin: 1px 0 3px 1px;
    }
    .existSalaryBook {
        font-style: italic;
        color: #e80505; 
        text-align: center; 
        display: none; 
        position: relative; 
        top: 8px;        
        font-size: 12px;
    }
    .select2-container-multi .select2-choices .select2-search-field input {    
        padding: 2px;
    }
    .page-footer {
        height: 0px !important;
    }
</style>

<script type="text/javascript">
    var finFiscalPeriodMainWin = '#finFiscalPeriodCloseWindow_<?php echo $this->uniqId; ?>';
    
    $(function(){
        $('.finFiscalPeriodBtn', finFiscalPeriodMainWin).on('click', function(){
            if($('#departmentId_valueField', finFiscalPeriodMainWin).val() == '' && $('#periodId_valueField', finFiscalPeriodMainWin).val() == '') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: "Салбар нэгж болон Тайлан үеээ сонгоно уу!",
                    type: 'warning',
                    sticker: false
                });               
                return;
            }
            
            $.ajax({
                type: 'POST',
                url: 'mdgl/fiscalPeriodDepartmentCloseService',
                data: {
                    departmentId: $('#departmentId_valueField', finFiscalPeriodMainWin).val(),
                    id: $('#periodId_valueField', finFiscalPeriodMainWin).val(),
                    isClosed: 'true'
                },
                dataType: "json",
                beforeSend: function() {
                    var blockMsg='Түр хүлээнэ үү...';
                    Core.blockUI({
                        message: blockMsg,
                        boxed: true
                    });
                },        
                success: function(resp) {
                    PNotify.removeAll();
                    if(resp.status === 'success') {
                        new PNotify({
                            title: resp.status,
                            text: resp.text,
                            type: resp.status,
                            sticker: false
                        });
                    } else {
                        new PNotify({
                            title: resp.status,
                            text: resp.text,
                            type: resp.status,
                            sticker: false
                        });                  
                    }
                    Core.unblockUI();
                },
                error: function(){
                    alert("Ajax Error!");
                }
            });
        });
        
        $('.searchCalcInfo', finFiscalPeriodMainWin).on('click', function(){
            var dgHeaderData = [{
                'field': 'accountcode',   
                'title': 'Дансны код',   
                'width': '150',
                'align': 'left'
            },{
                'field': 'accountname',   
                'title': 'Дансны нэр',   
                'width': '350',
                'align': 'left'
            },{
                'field': 'begindebitamount',   
                'title': 'Эхний үлдэгдэл Дт',   
                'width': '150',
                'align': 'left'
            },{
                'field': 'begincreditamount',   
                'title': 'Эхний үлдэгдэл Кт',   
                'width': '150',
                'align': 'left'
            },{
                'field': 'debitamount',   
                'title': 'Дебит дүн',   
                'width': '150',
                'align': 'left'
            },{
                'field': 'creditamount',   
                'title': 'Кредит дүн',   
                'width': '150',
                'align': 'left'
            },{
                'field': 'enddebitamount',   
                'title': 'Эцсийн үлдэгдэл Дт',   
                'width': '150',
                'align': 'left'
            },{
                'field': 'endcreditamount',   
                'title': 'Эцсийн үлдэгдэл Кт',   
                'width': '150',
                'align': 'left'
            }
            ];

            $("#finRepGuilgeeDataGrid", finFiscalPeriodMainWin).datagrid({
                url: 'mdobject/dataViewDataGrid',
                queryParams: {
                    metaDataId: '1452486811771331',
                    rows: '50',
                    defaultCriteriaData: $("#finFiscalPeriodCloseForm_<?php echo $this->uniqId; ?>").serialize()
                },
                fit: false,
                fitColumns: false,
                rownumbers: true,
                singleSelect: true,
                showFooter: true,
                pagination: true,
                pageSize: 50,
                columns: [dgHeaderData],
                onLoadSuccess: function(data) {
                    Core.unblockUI();
                }
            });
    });
        
        $("body").on("keydown", 'input.lookup-code-autocomplete-salary:not(disabled, readonly)', function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            var _this = $(this);
            if (code === 13) {
                if (_this.data("ui-autocomplete")) {
                    _this.autocomplete("destroy");
                }
                return false;
            } else {
                if (!_this.data("ui-autocomplete")) {
                    lookupAutoCompleteSalary(_this, 'code');
                }
            }
        });  

        $("body").on("keydown", 'input.meta-autocomplete-salary:not([readonly="readonly"], [readonly], readonly, [disabled="disabled"], [disabled], disabled)', function (e) {
            if (e.which === 13) {
                var _this = $(this);
                var _value = _this.val();
                var _metaDataId = _this.attr("data-metadataid");
                var _processId = _this.attr("data-processid");
                var _lookupId = _this.attr("data-lookupid");
                var _lookupTypeId = _this.attr("data-lookuptypeid");
                var _metaDataCode = _this.attr("data-field-name");
                var bpElem = _this.parent().find("input[type='hidden']");
                var _paramRealPath = bpElem.attr("data-path");
                var _parent = _this.closest("div.meta-autocomplete-wrap");
                var _isName = false;
                var params = '';

                if (typeof bpElem.attr("data-in-param") !== 'undefined' && typeof bpElem.attr('data-in-lookup-param') !== 'undefined') {
                    var mainSelector = _this.closest('form');
                    var paramsPathArr = bpElem.attr('data-in-param').split('|');
                    var lookupPathArr = bpElem.attr('data-in-lookup-param').split('|');
                    for (var i = 0; i < paramsPathArr.length; i++) {
                        var fieldPath = paramsPathArr[i];
                        var inputPath = lookupPathArr[i];
                        var fieldValue = "";

                        if ($("[data-path='"+fieldPath+"']", mainSelector).length > 0) {
                            fieldValue = getBpRowParamNum(mainSelector, bpElem, fieldPath);
                        } else {
                            fieldValue = fieldPath;
                        }

                        params += inputPath + "=" + fieldValue + "&";
                    }
                }

                if (typeof _this.attr('data-ac-id') !== 'undefined') {
                    _isName = 'idselect';
                    _value = _this.attr('data-ac-id');
                }

                $.ajax({
                    type: 'post',
                    url: 'mdobject/autoCompleteById',
                    data: {
                        processMetaDataId: _processId,
                        metaDataId: _metaDataId,
                        lookupId: _lookupId, 
                        lookupMetaTypeId: _lookupTypeId, 
                        paramRealPath: _paramRealPath,
                        code: _value,
                        isName: _isName, 
                        params: encodeURIComponent(params) 
                    },
                    dataType: 'json',
                    async: false,
                    beforeSend: function () {
                        _this.addClass("spinner2");
                    },
                    success: function (data) {

                        _this.removeAttr('data-ac-id');

                        var controlsData;
                        var rowData;

                        if (typeof (data.controlsData) !== 'undefined') {
                            controlsData = data.controlsData;
                        }
                        if (typeof (data.rowData) !== 'undefined') {
                            rowData = data.rowData;
                        }

                        if (_parent.closest("div.bp-param-cell").length > 0) {
                            var parentCell = _parent.closest("div.bp-param-cell");
                            var parentTable = _parent.closest("div.xs-form");
                        } else if (_parent.closest("div.form-md-line-input").length > 0) {
                            var parentCell = _parent.closest("div.form-md-line-input");
                            var parentTable = _parent.closest("div.xs-form");
                        } else {
                            if (_parent.closest("div.meta-autocomplete-wrap").length > 0) {
                                var parentCell = _parent.closest("div.meta-autocomplete-wrap");
                            } else {
                                var parentCell = _parent.closest("td");
                            }

                            if (_parent.closest("table.bprocess-table-dtl").length > 0) {
                                var parentTable = _parent.closest("tr");
                            } else {
                                var parentTable = _parent.closest("form");
                            }
                        }

                        if (controlsData !== undefined) {
                            $.each(controlsData, function (i, v) {
                                if (typeof rowData[v.FIELD_NAME] !== 'undefined' && _metaDataCode !== v.META_DATA_CODE) {
                                    var getPathElement = parentTable.find("[data-field-name='" + v.META_DATA_CODE + "']");
                                    if (getPathElement.length > 0) {
                                        if (getPathElement.prop("tagName").toLowerCase() == 'select') {
                                            if (getPathElement.hasClass('select2')) {
                                                getPathElement.trigger("select2-opening", 'notdisabled');
                                                getPathElement.select2('val', rowData[v.FIELD_NAME]);
                                            } else {                                                
                                                getPathElement.trigger("focus");
                                                getPathElement.val(rowData[v.FIELD_NAME]);
                                            }
                                        } else if (getPathElement.hasClass('dateInit')) {
                                            getPathElement.datepicker('update', date('Y-m-d', strtotime(rowData[v.FIELD_NAME])));
                                        } else if (getPathElement.hasClass('bigdecimalInit')) {
                                            getPathElement.next("input[type=hidden]").val(setNumberToFixed(rowData[v.FIELD_NAME]));
                                            getPathElement.val(rowData[v.FIELD_NAME]).trigger('change');
                                        } else {
                                            $.getScript('assets/custom/addon/plugins/phpjs/strings/get_html_translation_table.js', function() {
                                                $.getScript('assets/custom/addon/plugins/phpjs/strings/html_entity_decode.js', function() {
                                                    getPathElement.val(html_entity_decode(rowData[v.FIELD_NAME])).trigger('change');
                                                });
                                            });
                                        }
                                    }
                                }
                            });
                        }

                        if (data.META_VALUE_ID !== '') {
                            _parent.find("input[id*='_valueField']").attr('data-row-data', JSON.stringify(rowData).replace(/&quot;/g, '\\&quot;'));
                            _parent.find("input[id*='_valueField']").val(data.META_VALUE_ID).trigger("change");
                            _parent.find("input[id*='_displayField']").val(data.META_VALUE_CODE).attr('title', data.META_VALUE_CODE);
                            _parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                            _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(data.META_VALUE_NAME);
                        } else {
                            _parent.find("input[id*='_valueField']").val('').trigger("change");
                            _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text('');
                        }

                        /**
                         * 
                         * @description Sidebar үед ашиглаж байгаа
                         * @author  Ulaankhuu Ts
                         */
                        var selectedTR = $('table.bprocess-table-dtl tbody').find('tr.currentTarget');
                        var fieldPath = _parent.attr('data-section-path');
                        if (selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").length > 0) {
                            _parent.find("input").removeClass("spinner2");
                            selectedTR.find("td:last-child").find("i.input_html").find("div[data-section-path='" + fieldPath + "']").empty().append(_parent.html());
                        }
                        _this.removeClass("spinner2");
                    },
                    error: function () {
                        alert("Error");
                    }
                });
            }
        });    
    });
    
    function lookupAutoCompleteSalary(elem, type) {
        var _this = elem;
        var _lookupId = _this.attr("data-lookupid");
        var _metaDataId = _this.attr("data-metadataid");
        var _processId = _this.attr("data-processid");
        var bpElem = _this.parent().parent().find("input[type='hidden']");
        var _paramRealPath = bpElem.attr("data-path");
        var _parent = _this.closest("div.meta-autocomplete-wrap");
        var mainSelector = $("#bp-window-"+_processId+":visible");
        var params = '';
        var isHoverSelect = false;

        if (typeof bpElem.attr("data-criteria-param") !== 'undefined') {
            var paramsPathArr = bpElem.attr("data-criteria-param").split("|");
            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPathArr = paramsPathArr[i].split("@");
                var fieldPath = fieldPathArr[0];
                var inputPath = fieldPathArr[1];
                var fieldValue = '';

                if ($("[data-path='"+fieldPath+"']", mainSelector).length > 0) {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                } else {
                    fieldValue = fieldPath;
                }

                params += inputPath + "=" + fieldValue + "&";
            }
        }

        if (typeof bpElem.attr("data-in-param") !== 'undefined' && typeof bpElem.attr('data-in-lookup-param') !== 'undefined') {
            var paramsPathArr = bpElem.attr('data-in-param').split('|');
            var lookupPathArr = bpElem.attr('data-in-lookup-param').split('|');
            for (var i = 0; i < paramsPathArr.length; i++) {
                var fieldPath = paramsPathArr[i];
                var inputPath = lookupPathArr[i];
                var fieldValue = '';

                if ($("[data-path='"+fieldPath+"']", mainSelector).length > 0) {
                    fieldValue = getBpRowParamNum(mainSelector, elem, fieldPath);
                } else {
                    fieldValue = fieldPath;
                }

                params += inputPath + "=" + fieldValue + "&";
            }
        }

        _this.autocomplete({
            minLength: 1,
            maxShowItems: 30,
            delay: 500,
            highlightClass: "lookup-ac-highlight", 
            appendTo: "body",
            position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
            autoSelect: false,
            source: function(request, response) {

                if (lookupAutoCompleteRequest != null) {
                    lookupAutoCompleteRequest.abort();
                    lookupAutoCompleteRequest = null;
                }

                lookupAutoCompleteRequest = $.ajax({
                    type: 'post',
                    url: 'mdwebservice/lookupAutoComplete',
                    dataType: 'json',
                    data: {
                        lookupId: _lookupId, 
                        metaDataId: _metaDataId, 
                        processId: _processId, 
                        paramRealPath: _paramRealPath, 
                        q: request.term, 
                        type: type, 
                        criteriaParams: encodeURIComponent(params) 
                    },
                    success: function(data) {
                        if (type == 'code') {
                            response($.map(data, function(item) {
                                var code = item.split("|");
                                return {
                                    value: code[1], 
                                    label: code[1],
                                    name: code[2], 
                                    id: code[0]
                                };
                            }));
                        } else {
                            response($.map(data, function(item) {
                                var code = item.split("|");
                                return {
                                    value: code[2], 
                                    label: code[1],
                                    name: code[2], 
                                    id: code[0]
                                };
                            }));
                        }
                    }
                });
            },
            focus: function(event, ui) {
                if (typeof event.keyCode === 'undefined' || event.keyCode == 0) {
                    isHoverSelect = false;
                } else {
                    if (event.keyCode == 38 || event.keyCode == 40) {
                        isHoverSelect = true;
                    }
                }
                return false;
            },
            open: function() {
                $(this).autocomplete('widget').zIndex(99999999999999);
                return false;
            },
            close: function() {
                $(this).autocomplete("option","appendTo","body"); 
            }, 
            select: function(event, ui) {
                var origEvent = event;	

                if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                    if (type === 'code') {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                        _parent.find("input[id*='_displayField']").attr('data-ac-id', ui.item.id);
                    } else {
                        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(ui.item.name);
                    }
                } else {
                    if (type === 'code') {
                        if (ui.item.label === _this.val()) {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                            _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(ui.item.name);
                        } else {
                            _parent.find("input[id*='_displayField']").val(_this.val());
                            event.preventDefault();
                        }
                    } else {
                        if (ui.item.name === _this.val()) {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                            _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(ui.item.name);
                        } else {
                            _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(_this.val());
                            event.preventDefault();
                        }
                    }
                }

                while (origEvent.originalEvent !== undefined){
                    origEvent = origEvent.originalEvent;
                }

                if (origEvent.type === 'click') {
                    var e = jQuery.Event("keydown");
                    e.keyCode = e.which = 13;
                    _this.trigger(e);
                }
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            ul.addClass('lookup-ac-render');

            if (type === 'code') {
                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            } else {
                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    name = item.name.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+item.label+'</div><div class="lookup-ac-render-name">'+name+'</div>').appendTo(ul);
            }
        };
    }
    
    function chooseRowGrid_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        var _parent = $(elem).closest("div.meta-autocomplete-wrap");
        _parent.find("input[id*='_valueField']").val(row.id);
        _parent.find("input[id*='_displayField']").val(row.code);
        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(row.departmentname);
    }
    
    function chooseRowGridPeriod_<?php echo $this->uniqId; ?>(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        var _parent = $(elem).closest("div.meta-autocomplete-wrap");
        _parent.find("input[id*='_valueField']").val(row.id);
        _parent.find("input[id*='_displayField']").val(row.periodcode);
        _parent.closest('.next-generation-input-wrap').find(".next-generation-input-body").text(row.periodname);        
        
        $('#filterStartDate', finFiscalPeriodMainWin).val(row.startdate);
        $('#filterEndDate', finFiscalPeriodMainWin).val(row.enddate);
    }    
    
</script>