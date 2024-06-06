<div class="col-md-12">
    <div id="bankRangeBillRate-<?php echo $this->metaDataId ?>">            
        <form class="form-horizontal xs-form" role="form" method="post" id="saveBillRate-form-<?php echo $this->metaDataId ?>">
            <div class="form-body">
                <div class="row">
                    <fieldset class="collapsible">
                        <legend>Ерөнхий мэдээлэл</legend>
                        <div class="row"> 
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group row">
                                    <div>
                                        <?php echo Form::label(array('text' => 'Эхлэх огноо', 'for' => 'fromDate', 'class' => 'col-form-label col-md-3 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                        <div class="col-md-2">
                                            <div class="dateElement input-group">
                                                <?php echo Form::text(array('name' => 'fromDate', 'id' => 'fromDate', 'class' => 'form-control form-control-sm dateInit', 'value' => Ue::sessionFiscalPeriodStartDate(), 'required' => 'required', 'style' => 'width: 132px')); ?>
                                                <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group row">
                                    <?php echo Form::label(array('text' => 'Харилцагч', 'for' => 'customer', 'class' => 'col-form-label col-md-3 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-8">
                                        <div class="input-group double-between-input" data-section-path="<?php echo Mdgl::$customerListDataViewCode; ?>">
                                            <?php echo Form::hidden(array('name' => 'customerId_valueField', 'id' => 'customerId_valueField', 'value' => $this->selectedRow['customerid'])) ?>
                                            <?php echo Form::text(array('name' => 'customerCode_displayField', 'id' => 'customerCode_displayField', 'class' => 'form-control form-control-sm meta-autocomplete glCode-autocomplete', 'placeholder' => 'кодоор хайх', 'value' => $this->selectedRow['customercode'])); ?>
                                            <span class="input-group-btn">
                                                <button type="button" disabled="disabled" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('<?php echo Mdgl::$customerListDataViewCode; ?>', 'single', 'customerSelectableGrid', '', this);"><i class="fa fa-search"></i></button>
                                            </span>     
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => 'customerName_nameField', 'id' => 'customerName_nameField', 'class' => 'form-control form-control-sm meta-autocomplete glName-autocomplete', 'placeholder' => 'нэрээр хайх', 'value' => $this->selectedRow['customername'])); ?>    
                                            </span>     
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group row">
                                    <div>
                                        <?php echo Form::label(array('text' => 'Дуусах огноо', 'for' => 'toDate', 'class' => 'col-form-label col-md-3 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                        <div class="col-md-2">
                                            <div class="dateElement input-group">
                                                <?php echo Form::text(array('name' => 'toDate', 'id' => 'toDate', 'class' => 'form-control form-control-sm dateInit', 'value' => Ue::sessionFiscalPeriodEndDate(), 'required' => 'required', 'style' => 'width: 132px')); ?>
                                                <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group row">
                                    <?php echo Form::label(array('text' => 'Данс', 'for' => 'account', 'class' => 'col-form-label col-md-3 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                    <div class="col-md-8">
                                        <div class="input-group double-between-input" data-section-path="fin_account_list">
                                            <?php echo Form::hidden(array('name' => 'accountId_valueField', 'id' => 'accountId_valueField', 'value' => $this->selectedRow['accountid'])) ?>
                                            <?php echo Form::text(array('name' => 'accountCode_displayField', 'id' => 'accountCode_displayField', 'class' => 'form-control form-control-sm meta-autocomplete glCode-autocomplete accountCodeMask', 'placeholder' => 'кодоор хайх', 'value' => $this->selectedRow['accountcode'])); ?>
                                            <span class="input-group-btn">
                                              <button type="button" disabled="disabled" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('fin_account_list', 'single', 'accountSelectableGrid', '', this);"><i class="fa fa-search"></i></button>
                                            </span>     
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => 'accountName_nameField', 'id' => 'accountName_nameField', 'class' => 'form-control form-control-sm meta-autocomplete glName-autocomplete', 'placeholder' => 'нэрээр хайх', 'value' => $this->selectedRow['accountname'])); ?>    
                                            </span>     
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php echo Form::hidden(array('name' => 'customerId', 'id' => 'customerId', 'value' => $this->selectedRow['customerid'])) ?>
                        <?php echo Form::hidden(array('name' => 'accountId', 'id' => 'accountId', 'value' => $this->selectedRow['accountid'])) ?>
                        <div class="clearfix w-100"></div>
                        <div class="col-md-12 text-right" style="margin-bottom: 10px;">
                            <button type="button" class="btn btn-sm btn-circle green-meadow" onclick="calcSearchBillRate();"><i class="fa fa-search"></i> Шүүх</button>                        
                        </div>
                    </fieldset>
                    <div class="panel-body-billContent jeasyuiTheme3 mt10">
                        <table id="bankChargeCustomerBillGrid"></table> 
                    </div>
                </div>                              
            </div>
        </form>
    </div>
</div>

<style type="text/css">
    .cashrate-label {
        font-weight: 400;
        font-size: 14px;
        color: #5e5e5e;
    }
    .panel-title {
        font-size: 14px;
        color: #5e5e5e;
        font-weight: bold;
    }
    .xs-form .dataTables_wrapper table.dataTable thead th {
        height: 25px;
    }
    .xs-form .dataTables_wrapper table th:not(.trNumber) {
        min-width: 20px !important;
    }  
    table.table tbody tr.selected td {
        background-color: #619cfe !important;
        color: #000 !important;
    }
</style>
<script type="text/javascript">
    var bankChargeBillWindowId = "#bankRangeBillRate-<?php echo $this->metaDataId ?>";
    var tableId = "#bankChargeCustomerBillGrid";
    var _billRateSelectedRows = [];
    var _billRateSelectedRowIndex = [];
    
    $(function() {
        $(bankChargeBillWindowId+" #bankChargeCustomerBillGrid").datagrid({
            view: horizonscrollview,
            url: 'mdgl/bankRangeCustomerBill',
            queryParams: {
                fromDate: $("#fromDate").val(),
                toDate: $("#toDate").val(),
                currencyId: $("#currencyId").val(),
                customerId: $("#customerId").val(),
                accountId: $("#accountId").val()
            }, 
            resizeHandle: 'right',
            fitColumns: true, 
            autoRowHeight: true, 
            striped: false, 
            method: 'post', 
            nowrap: true, 
            pagination: false, 
            rownumbers: true, 
            singleSelect: false, 
            checkOnSelect: true, 
            selectOnCheck: true, 
            pagePosition: 'bottom', 
            pageNumber: 1, 
            pageSize: 100, 
            pageList: [50,100,200,300], 
            sortName: 'BOOK_DATE', 
            sortOrder: 'ASC', 
            multiSort: false, 
            remoteSort: true, 
            showHeader: true, 
            showFooter: true, 
            height:$(window).height()-250,
            scrollbarSize: 18,
            remoteFilter: true,
            filterDelay: 10000000000,
            frozenColumns: [[
                {field: 'ck', checkbox: true},
                {field:'BOOK_NUMBER',title:'Баримтын №',sortable:true,fixed: true, halign: 'left',align: 'left',},
                {field:'ACCOUNT_CODE',title:'Харьцсан данс',sortable:true, width: '110px',fixed: true, halign: 'left',align: 'left',},
                {field:'DESCRIPTION',title:'Баримтын утга',sortable:true, width: '250px', fixed: true, halign: 'left',align: 'left',},
                {field:'BOOK_DATE',title:'Огноо',sortable:true,fixed: true, halign: 'left',align: 'left', formatter: function(v, r, i) {return dateFormatter('Y-m-d', v);},},
            ]],
            columns: [[
                {field:'RATE',title:'Ханш',sortable:true,fixed: true, halign: 'left',align: 'right',formatter: gridAmountField,},
                {field:'BEGIN_AMOUNT_BASE',title:'Эхний үлдэгдэл /вал/',sortable:true,fixed: true,  halign: 'left',align: 'right',formatter: gridAmountField,},
                {field:'DEBIT_AMOUNT_BASE',title:'Гүйлгээ ДТ /вал/',sortable:true,fixed: true, halign: 'left',align: 'right',formatter: gridAmountField,},
                {field:'DEBIT_AMOUNT',title:'Гүйлгээ ДТ /төг/',sortable:true,fixed: true, halign: 'left',align: 'right',formatter: gridAmountField,},
                {field:'CREDIT_AMOUNT_BASE',title:'Гүйлгээ КТ /вал/',sortable:true,fixed: true, halign: 'left',align: 'right',formatter: gridAmountField,},
                {field:'CREDIT_AMOUNT',title:'Гүйлгээ КТ /төг/',sortable:true,fixed: true, halign: 'left',align: 'right',formatter: gridAmountField,},
                {field:'END_AMOUNT_BASE',title:'Эцсийн үлдэгдэл /вал/',sortable:true,fixed: true, halign: 'left',align: 'right',formatter: gridAmountField,},
            ]],
            onCheckAll: function() {
                $.uniform.update();
            },
            onUncheckAll: function() {
                $.uniform.update();
            },
            onClickRow: function(index, row) {
                $.uniform.update();
            },
            onLoadSuccess: function(data) {
                if (data['log'].status === 'warning') {
                    PNotify.removeAll();
                    new PNotify({
                        title: data['log'].title,
                        text: data['log'].message,
                        type: data['log'].status,
                        sticker: false
                    });
                }
                var _thisGrid = $(this);
                showGridMessage(_thisGrid);
                _thisGrid.datagrid('resize'); 
                var panelView = _thisGrid.datagrid("getPanel").children("div.datagrid-view");

                if (data.total == 0) {
                    var tr = panelView.find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table").find("tbody tr");
                    $(tr).find('td').find('div').find('span').each(function () {
                        this.remove();
                    });
                }

                Core.initInputType(panelView);   
            }
        });
        $(window).bind('resize', function () {
            $(bankChargeBillWindowId+" #bankChargeCustomerBillGrid").datagrid('resize');
        });
    });
    
    
    function calcSearchBillRate() {
        $("#saveBillRate-form-<?php echo $this->metaDataId ?>", bankChargeBillWindowId).validate({errorPlacement: function() {}});          
        if ($("#saveBillRate-form-<?php echo $this->metaDataId ?>", bankChargeBillWindowId).valid()) {
            $(bankChargeBillWindowId+" #bankChargeCustomerBillGrid").datagrid('load', {
                fromDate: $("#fromDate").val(),
                toDate: $("#toDate").val(),
                currencyId: $("#currencyId").val(),
                customerId: $("#customerId").val(),
                accountId: $("#accountId").val()
            });
        }
    }
</script>