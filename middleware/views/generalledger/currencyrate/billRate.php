<?php
if (!$this->isAjax) {
?>
<div class="col-md-12" id="billRate-<?php echo $this->metaDataId; ?>">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="caption buttons"> 
                <span class="caption-subject font-weight-bold uppercase card-subject-blue">
                    <?php echo $this->title; ?>
                </span>
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body xs-form">
<?php } else { ?>
<div id="billRate-<?php echo $this->metaDataId; ?>">            
<?php } ?>
            <form class="form-horizontal xs-form p-0" method="post" id="saveBillRate-form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="collapsible">
                                <legend><?php echo Lang::lineDefault('FIN_01516', 'Ерөнхий мэдээлэл'); ?></legend>
                                <div class="row"> 
                                    <?php 
                                    if (isset($this->booknumber)) {
                                        echo Form::hidden(array('name' => 'hidden_glbookNumber', 'value' => $this->booknumber));
                                    } 
                                    $hideClass = '';
                                    $endDateLabel = Lang::lineDefault('PL_0103', 'Дуусах огноо');
                                    $filterRowStyle = 'margin-top: -40px';

                                    if (Config::getFromCache('CONFIG_GL_BILLRATE_HDR_RATE')) {
                                        $hideClass = ' hide';
                                        $endDateLabel = Lang::lineDefault('date', 'Огноо');
                                        $filterRowStyle = '';
                                    }
                                    ?>
                                    <div class="col-md-5 col-sm-5">
                                        <div class="form-group row<?php echo $hideClass; ?>">
                                            <?php echo Form::label(array('text' => Lang::lineDefault('PL_2036', 'Эхлэх огноо'), 'for' => 'fromDate', 'class' => 'col-form-label col-md-4 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                            <div class="col-md-3">
                                                <div class="dateElement input-group">
                                                    <?php echo Form::text(array('name' => 'fromDate', 'id' => 'fromDate', 'class' => 'form-control form-control-sm dateInit', 'value' => $this->startDate, 'required' => 'required', 'style' => 'width: 104px')); ?>
                                                    <span class="input-group-btn input-group-append"><button onclick="return false;" class="btn"><i class="fal fa-calendar"></i></button></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <?php echo Form::label(array('text' => $endDateLabel, 'for' => 'toDate', 'class' => 'col-form-label col-md-4 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                            <div class="col-md-3">
                                                <div class="dateElement input-group">
                                                    <?php echo Form::text(array('name' => 'toDate', 'id' => 'toDate', 'class' => 'form-control form-control-sm dateInit', 'value' => $this->endDate, 'required' => 'required', 'style' => 'width: 104px')); ?>
                                                    <span class="input-group-btn input-group-append"><button onclick="return false;" class="btn"><i class="fal fa-calendar"></i></button></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <?php echo Form::label(array('text' => Lang::lineDefault('FIN_1010', 'Валют'), 'for' => 'currencyId', 'class' => 'col-form-label col-md-4 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo Form::select(array(
                                                    'name' => 'currencyId',
                                                    'id' => 'currencyId',
                                                    'class' => 'form-control form-control-sm float-left',
                                                    'data' => $this->currencyList,
                                                    'op_value' => 'CURRENCY_ID',
                                                    'op_text' => 'CURRENCY_CODE| |-| |CURRENCY_NAME',
                                                    'required' => 'required',
                                                    'style' => 'width:162px',
                                                    'value' => $this->currencyId ? $this->currencyId : '200101010000002'
                                                ));
                                                echo Form::text(array('name' => 'headerNewRate', 'id' => 'headerNewRate', 'class' => 'form-control form-control-sm bigdecimalInit', 'placeholder' => 'Ханш', 'style' => 'width: 125px')); 
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <?php echo Form::label(array('text' => Lang::lineDefault('description', 'Тайлбар'), 'for' => 'gldescription_billrate', 'class' => 'col-form-label col-md-4 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                            <div class="col-md-8">
                                                <?php
                                                echo Form::textArea(array(
                                                    'name' => 'gldescription',
                                                    'id' => 'gldescription_billrate',
                                                    'class' => 'form-control form-control-sm'
                                                ));
                                                ?>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-7 col-sm-7">
                                        <div class="form-group row">
                                            <?php echo Form::label(array('text' => Lang::lineDefault('FIN_00846', 'Салбар нэгж'), 'for' => 'filterDepartmentId_displayField', 'class' => 'col-form-label col-md-3 col-sm-6 cashrate-label')); ?>
                                            <div class="col-md-9 col-sm-9">                                 
                                                <div class="meta-autocomplete-wrap" data-section-path="filterDepartmentId">
                                                    <div class="input-group double-between-input">
                                                        <input type="hidden" name="filterDepartmentId" id="filterDepartmentId_valueField" data-path="filterDepartmentId" class="popupInit" value="<?php echo issetParam($this->depInfo['DEPARTMENT_ID']); ?>">
                                                        <input type="text" name="filterDepartmentId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="filterDepartmentId" id="filterDepartmentId_displayField" data-processid="1454315883636" data-lookupid="1457081813808" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off" value="<?php echo issetParam($this->depInfo['DEPARTMENT_CODE']); ?>">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('filterDepartmentId', '1454315883636', '1457081813808', 'single', 'serviceCustomerId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                                        </span>  
                                                        <span class="input-group-btn">
                                                            <input type="text" name="filterDepartmentId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="filterDepartmentId" id="filterDepartmentId_nameField" data-processid="1454315883636" data-lookupid="1457081813808" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off" value="<?php echo issetParam($this->depInfo['DEPARTMENT_NAME']); ?>">
                                                        </span>   
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <?php echo Form::label(array('text' => Lang::lineDefault('FIN_1005', 'Харилцагч'), 'for' => 'customer', 'class' => 'col-form-label col-md-3 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                            <div class="col-md-9">
                                                
                                                <div class="meta-autocomplete-wrap" data-section-path="customerId">
                                                    <div class="input-group double-between-input">
                                                        <input type="hidden" name="customerId_valueField" id="customerId_valueField" data-path="customerId" class="popupInit" value="<?php echo $this->customerId; ?>">
                                                        <input type="text" name="customerId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="customerId" id="customerId_displayField" data-processid="1454315883636" data-lookupid="1456925361630" value="<?php echo $this->customerCode; ?>" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn default btn-bordered mr0" onclick="dataViewSelectableGrid('customerId', '1454315883636', '1456925361630', 'single', 'customerId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                                        </span>  
                                                        <span class="input-group-btn">
                                                            <input type="text" name="customerId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="customerId" id="customerId_nameField" data-processid="1454315883636" data-lookupid="1456925361630" value="<?php echo $this->customerName; ?>" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                                                        </span>   
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>   
                                        <div class="form-group row">
                                            <?php echo Form::label(array('text' => Lang::lineDefault('prl_008', 'Данс'), 'for' => 'account', 'class' => 'col-form-label col-md-3 cashrate-label', 'style' => 'font-size: 12px !important')); ?>
                                            <div class="col-md-9">

                                                <div class="meta-autocomplete-wrap" data-section-path="fin_account_list">
                                                    <div class="input-group double-between-input">
                                                        <input type="hidden" name="accountId_valueField" id="accountId_valueField" data-path="accountId" class="popupInit" value="<?php echo $this->accountId; ?>" data-criteria="currencyCode[]=AUD&currencyCode[]=CAD&currencyCode[]=CHD&currencyCode[]=CHF&currencyCode[]=CNY&currencyCode[]=EUR&currencyCode[]=GBP&currencyCode[]=HKD&currencyCode[]=JPY&currencyCode[]=KRW&currencyCode[]=PLN&currencyCode[]=RUB&currencyCode[]=SDR&currencyCode[]=SEK&currencyCode[]=SGD&currencyCode[]=USD&currencyCode[]=XAG&currencyCode[]=XAU">
                                                        <input type="text" name="accountId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="accountId" id="accountId_displayField" data-processid="1454315883636" data-lookupid="1454379109682" value="<?php echo $this->accountCode; ?>" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('accountId', '1454315883636', '1454379109682', 'single', 'accountId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                                                        </span>  
                                                        <span class="input-group-btn">
                                                            <input type="text" name="accountId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="accountId" id="accountId_nameField" data-processid="1454315883636" data-lookupid="1454379109682" value="<?php echo $this->accountName; ?>" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                                                        </span>   
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row" id="headerParam" style="<?php echo $filterRowStyle; ?>">                              
                                    <div class="col-md-4 ml-auto text-right">
                                        <button type="button" class="btn btn-sm btn-circle green-meadow" function-name="FNC_APAR_REVAL_LIST_KEY||FNC_APAR_REVAL_LIST" onclick="calcSearchCurrencyRate();"><i class="fa fa-search"></i> <?php echo Lang::lineDefault('PL_0367', 'Шүүх'); ?></button>                        
                                        <button type="button" class="btn btn-sm btn-circle blue-madison" onclick="calcCurrencyRate();"><?php echo Lang::lineDefault('FIN_00877', 'Тэгшитгэх'); ?></button>                    
                                    </div>
                                </div>                             
                            </fieldset>
                        </div>       
                    </div>    
                    <div class="row">
                        <div class="col-md-12 panel-body-billContent">
                            <table class="table table-sm table-bordered table-hover" id="customerBillGrid" cellspacing="0" width="100%">
                                <thead>
                                    <tr style='width: 100%;'>
                                        <th class="text-center trNumber" rowspan="2">№</th>
                                        <th class="text-center" rowspan="2">Код</th>
                                        <th class="text-center" rowspan="2">Харилцагчийн нэр</th>
                                        <th class="text-center" rowspan="2">Код</th>
                                        <th class="text-center" rowspan="2">Дансны нэр</th>
                                        <th style="width: 57px" class="text-center" rowspan="2"><?php echo Lang::lineDefault('PL_0176', 'Огноо'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo Lang::lineDefault('FIN_00613', 'Баримтын дугаар'); ?></th>
                                        <th style="width: 10%" class="text-center description-calculate-currency" rowspan="2"><?php echo Lang::lineDefault('FIN_1006', 'Гүйлгээний утга'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo Lang::lineDefault('FIN_00781', 'Ханш'); ?></th>
                                        <th style="width: 20%; border-bottom: 1px rgba(204, 204, 204, 0.33) solid !important;" class="text-center" colspan="4"><?php echo Lang::lineDefault('PL_20103', 'Гүйлгээ'); ?></th>
                                        <th style="width: 15%; border-bottom: 1px rgba(204, 204, 204, 0.33) solid !important;" class="text-center" colspan="2"><?php echo Lang::lineDefault('FIN_00929', 'Эцсийн үлдэгдэл'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo Lang::lineDefault('FIN_01542', 'Шинэ ханш'); ?></th>
                                        <th style="width: 15%; border-bottom: 1px rgba(204, 204, 204, 0.33) solid !important;" class="text-center" colspan="2"><?php echo Lang::lineDefault('PL_20256', 'Зөрүү'); ?></th>
                                        <th class="equalise text-center" rowspan="2" style="border-left: 1px solid rgba(204, 204, 204, 0.33) !important;">
                                            <div class="text-center" class="mb5"><?php echo Lang::lineDefault('FIN_00882', 'Тэгшитгэх эсэх'); ?></div>
                                            <input type="checkbox" name="equaliseAllCheck" value="0" onclick="equaliseAll(this);">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"><?php echo Lang::lineDefault('FIN_T_00882', 'ДТ /валют/'); ?></th>
                                        <th class="text-center"><?php echo Lang::lineDefault('FIN_T_0088214', 'ДТ /төгрөг/'); ?></th>
                                        <th class="text-center"><?php echo Lang::lineDefault('FIN_T_0088215', 'КТ /валют/'); ?></th>
                                        <th class="text-center"><?php echo Lang::lineDefault('FIN_T_0088216', 'КТ /төгрөг/'); ?></th>
                                        <th class="text-center"><?php echo Lang::lineDefault('FIN_T_0088217', 'валют'); ?></th>
                                        <th class="text-center"><?php echo Lang::lineDefault('FIN_T_0088218', 'төгрөг'); ?></th>
                                        <th class="text-center"><?php echo Lang::lineDefault('FIN_T_0088219', 'Ашиг'); ?></th>
                                        <th class="text-center"><?php echo Lang::lineDefault('FIN_T_0088220', 'Алдагдал'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="SUM text-right"><span id="footDebitBase" class="bigDecimalInit">0</span></td>
                                        <td class="SUM text-right"><span id="footDebit" class="bigDecimalInit">0</span></td>
                                        <td class="SUM text-right"><span id="footCreditBase" class="bigDecimalInit">0</span></td>
                                        <td class="SUM text-right"><span id="footCredit" class="bigDecimalInit">0</span></td>
                                        <td></td>
                                        <td class="SUM text-right"><span id="footVarianceDb" class="bigDecimalInit">0</span></td>
                                        <td class="SUM text-right"><span id="footVarianceCr" class="bigDecimalInit">0</span></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table> 
                        </div>
                    </div>                          
                </div>
                <div class="form-actions mt10">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <?php echo Form::button(array('class' => 'btn btn-circle green-meadow bp-btn-save', 'onclick' => 'saveBillRate();', 'value' => '<i class="icon-checkmark-circle2"></i> ' . $this->lang->line('save_btn'))); ?>
                            <?php echo Form::button(array('class' => 'btn btn-circle blue-madison cancelCashrate', 'value' => $this->lang->line('cancel_btn'))); ?>
                        </div>
                    </div>
                </div>
            </form>
<?php if (!$this->isAjax) { ?>  
        </div> 
    </div>
</div>
<?php } else { ?>
</div>
<?php } ?>

<style type="text/css">
    .cashrate-label {
        font-weight: 400;
        font-size: 14px;
        color: #444;
        padding-right: 0;
        padding-top: 0 !important;
    }
    .panel-title {
        font-size: 14px;
        color: #444;
        font-weight: bold;
    }
    .xs-form .dataTables_wrapper table.dataTable thead th {
        height: 12px;
        background-color: #E7E7E7;
        font-size: 12px;
        color: #444;
    }
    .xs-form .dataTables_wrapper table.dataTable tbody td {
        height: 15px;        
        font-size: 11px !important;
    }
    .xs-form .dataTables_wrapper table.dataTable tbody tr.even {
        background-color: #EEEEEE;
    }
    .xs-form .dataTables_wrapper table.dataTable tbody tr.odd .form-control[readonly] {
        background-color: #fff;
    }
    .xs-form .dataTables_wrapper table.dataTable td.stretchInput input[type="text"] {
        height: 22px !important;
        font-size: 11px !important;
    }
    .xs-form .dataTables_wrapper table.dataTable td.stretchInput input[type="text"]:focus {
        border: 1px solid #999 !important;
        box-shadow: none !important;
        -webkit-box-shadow: none !important; 
        -moz-box-shadow: none !important; 
    }
    .xs-form .dataTables_wrapper table th:not(.trNumber) {
        min-width: 20px !important;
    }    
    .xs-form .dataTables_wrapper table.dataTable td.stretchInput .btn-sm {
        padding: 0px 4px 0px 4px !important;
    }    
</style>
<script type="text/javascript">
    var billWindowId = '#billRate-<?php echo $this->metaDataId; ?>';
    var tableId = '#customerBillGrid';
    
    $(function() {
        
        <?php
        if (Config::getFromCache('CONFIG_GL_BILLRATE_HDR_RATE')) {
        ?>
        function setBillRateNewRate_<?php echo $this->metaDataId; ?>() {
            var currencyId = $(billWindowId).find('#currencyId').val(), 
                toDate = $(billWindowId).find('#toDate').val();
            
            if (currencyId && toDate) {
                $.ajax({
                    type: 'post',
                    url: 'mdgl/getOneCurrency',
                    data: {currencyId: currencyId, rateDate: toDate},
                    dataType: 'json',       
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        PNotify.removeAll();
                        if (data.status === 'success') {
                            $(billWindowId).find('#headerNewRate').autoNumeric('set', data.rate);
                        } else {
                            new PNotify({
                                title: 'Error',
                                text: data.message,
                                type: 'error',
                                sticker: false
                            });
                        }
                        Core.unblockUI();
                    }
                });
            } else {
                $(billWindowId).find('#headerNewRate').autoNumeric('set', null);
            }
        }
        
        setBillRateNewRate_<?php echo $this->metaDataId; ?>();
        
        $(billWindowId).on('change', '#currencyId', function(){
            setBillRateNewRate_<?php echo $this->metaDataId; ?>();
        });
        $(billWindowId).on('changeDate', '#toDate', function(){
            setBillRateNewRate_<?php echo $this->metaDataId; ?>();
        });
        $(billWindowId).on('change', '#headerNewRate', function(){
            var $thisRate = $(this).autoNumeric('get');
            $(billWindowId + ' #customerBillGrid').find('input[name="equalizeRate[]"]').autoNumeric('set', $thisRate);
        });
        <?php
        }
        ?>
                
        //billRateAutoComplete();
        customerBillRateDtlTable = $(billWindowId+" #customerBillGrid").dataTable({
            scrollY: 400,
            scrollX: true,
            scrollXInner: "100%",
            scrollCollapse: false,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: false,
            responsive: true,
            language: {
                "emptyTable": "No data"
            },
            "columnDefs": [
                {
                    "searchable": false,
                    "orderable": false,
                    "visible": false,
                    "targets": [1, 2, 3, 4]
                },
                {
                    targets: 0,
                    width: '25px'
                },
                {
                    targets: 5,
                    width: '60px'
                },
                {
                    targets: 6,
                    width: '130px'
                },
                {
                    targets: 7,
                    width: '230px'
                },
                {
                    targets: 8,
                    width: '60px'
                },
                /*{
                    targets: 1,
                    width: '57px'
                }
                {
                    targets: 5,
                    width: '60px'
                },
                {
                    targets: 6,
                    width: '65px'
                },
                {
                    targets: 7,
                    width: '180px'
                },
                {
                    targets: 8,
                    width: '50px'
                },
                {
                    targets: 9,
                    width: '70px'
                },
                {
                    targets: 10,
                    width: '75px'
                },
                {
                    targets: 11,
                    width: '65px'
                },
                {
                    targets: 12,
                    width: '75px'
                },
                {
                    targets: 13,
                    width: '70px'
                },
                {
                    targets: 14,
                    width: '90px'
                },
                {
                    targets: 15,
                    width: '60px'
                },
                {
                    targets: 16,
                    width: '65px'
                },                
                {
                    targets: 17,
                    width: '65px'
                },
                {
                    targets: 18,
                    width: '15px'
                }*/                
            ],
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();
                var last = null;
                var lastCustomer = null, uniqId = '', customerNumbering = 1;
                
                api.column(3, {page: 'current'}).data().each(function(group, i) {
                    if (last !== group) {
                        customerNumbering = 1;
                        var accountName = api.columns(4).data()[0][i];
                        uniqId = Core.getUniqueID('group_' + i);
                        $(rows).eq(i).before(
                            '<tr class="group" id="' + uniqId + '" style="cursor: pointer"><td colspan="17" style="font-weight:bold; text-overflow: ellipse;">' + group + " - " + accountName + '</td>\n\
                            </tr>'
                        );
                        var customerName = api.columns(2).data()[0][i];
                        var customerCode = api.columns(1).data()[0][i];
                        $(rows).eq(i).before(
                            '<tr class="groupCustomer ' + uniqId + '" style="cursor: pointer"><td>' + customerNumbering + '</td><td colspan="16" style="font-weight:bold; text-overflow: ellipse;"><a onclick="customerDetail(this);" href="javascript:;">' + customerCode + '</a> - ' + customerName + '</td>\n\
                            </tr>'
                        );           
                        customerNumbering++;
                        last = group;
                        lastCustomer = customerCode;
                    } else {
                        var customerName = api.columns(2).data()[0][i];
                        var customerCode = api.columns(1).data()[0][i];
                        if (lastCustomer !== customerCode) {
                            $(rows).eq(i).before(
                                '<tr class="groupCustomer ' + uniqId + '" style="cursor: pointer"><td>' + customerNumbering + '</td><td colspan="16" style="font-weight:bold; text-overflow: ellipse;"><a onclick="customerDetail(this);" href="javascript:;">' + customerCode + '</a> - ' + customerName + '</td>\n\
                                </tr>'
                            );
                            lastCustomer = customerCode;
                            customerNumbering++;
                        }                        
                    }
                });
                
                if($("#customerId_valueField", billWindowId).val() == '' || $("#accountId_valueField", billWindowId).val() == '')
                    collapseAccounts();
                    
                Core.initInputType($(billWindowId));
            }
        });
        
        $(window).bind('resize', function () {
            customerBillRateDtlTable.fnAdjustColumnSizing();
        });

        $(billWindowId+' table#customerBillGrid tbody').on('click', 'tr.group', function(e) {
            if (e.target.className === "equalise")
                return;
            
            var row = $(this);
            var rowIndex = row.index() + 1;
            var trSelector = row.parent().children('tr');
            var trSelectorLen = trSelector.length;
            var uniqId = row.attr('id');
            
            while (rowIndex < trSelectorLen) {
                if(row.parent().children('tr:eq('+rowIndex+')').hasClass('group')) {
                    rowIndex = trSelectorLen
                } else {
                    if (row.parent().children('tr:eq('+rowIndex+')').hasClass(uniqId)) {
                        row.parent().children('tr:eq('+rowIndex+')').toggleClass("hide");
                    } else                    
                        row.parent().children('tr:eq('+rowIndex+')').addClass('hide');
                    rowIndex++;
                }
            }    
        });

        $(billWindowId+' table#customerBillGrid  tbody').on('click', 'tr.groupCustomer', function(e) {
            if (e.target.className === "equalise")
                return;

            var row = $(this);
            var rowIndex = row.index() + 1;
            var trSelector = row.parent().children('tr');
            var trSelectorLen = trSelector.length;
            
            while (rowIndex < trSelectorLen) {
                if(row.parent().children('tr:eq('+rowIndex+')').hasClass('groupCustomer') || row.parent().children('tr:eq('+rowIndex+')').hasClass('group')) {
                    rowIndex = trSelectorLen
                } else {
                    row.parent().children('tr:eq('+rowIndex+')').toggleClass('hide');
                    rowIndex++;
                }
            }    
        });
        
        $(billWindowId).on('mouseover', '.qtooltip', function(event) {
            $(this).qtip({
                overwrite: false,
                content: false,
                position: {
                    my: 'top center',
                    at: 'bottom center'
                },                
                show: {
                    event: event.type,
                    ready: true
                }
            }, event);
        })        
        
        if ($("#fromDate", billWindowId).val() != '' && $("#toDate", billWindowId).val() 
            && $("#currencyId", billWindowId).val() != '' && $("#customerId_valueField", billWindowId).val() != '' 
            && $("#accountId_valueField", billWindowId).val() != '') {
            calcSearchCurrencyRate();
        }
//        $(billWindowId+' table#customerBillGrid  tbody').on('click', 'tr.odd, tr.even', function(e) {
//            $(billWindowId+' table#customerBillGrid  tbody').find('tr.odd').css('background-color', '#fff');
//            $(billWindowId+' table#customerBillGrid  tbody').find('tr.even').css('background-color', '#EEEEEE');
//            $(this).css('background-color', 'rgb(80, 187, 255)');
//        });
    });
    
    function nextTRinput(event) {
        event.preventDefault();
        var _this = $(event.target);
        var parent = _this.parent();
        var getIndex = parent.index();

        if(event.which === 13) {
            parent.parent().next()
                .children("td:eq(" + getIndex + ")")
                .children("input[type=text]")
                .select();
            _this.closest("tr").next("tr").trigger("click");                
        }    
    }
    function nextTRTDinput(event) {
        event.preventDefault();
        var $this = $(event.target);
        var parent = $this.parent();

        if (event.which === 13) {
            if (!parent.next().children().hasClass('moreEqualizerVarianceDebit')) {
                parent.next()
                .children()
                .select();
            } else if (parent.next().children().hasClass('moreEqualizerVarianceDebit') && parent.parent().next().length === 0) {
                $this.closest('div.ui-dialog').find('.ui-dialog-buttonset button').focus();
            } else {
                parent.parent().next()
                .children("td:eq(0)")
                .children()
                .select();        
            }
        }    
    }
    function billRateAutoComplete() {
        $(billWindowId).on("focus", 'input.glCode-autocomplete:not(disabled, readonly)', function(e) {
            billlookupAutoComplete($(this), 'code');
        });
        $(billWindowId).on("focus", 'input.glName-autocomplete:not(disabled, readonly)', function(e) {
            billlookupAutoComplete($(this), 'name');
        });
        $(billWindowId).on("keydown", 'input.glCode-autocomplete:not(disabled, readonly)', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code === 13) {
                $(this).autocomplete("destroy");
                return false;
            } else {
                if (!$(this).data("ui-autocomplete")) {
                    billlookupAutoComplete($(this), 'code');
                }
            }
        });
        $(billWindowId).on("keydown", 'input.glName-autocomplete:not(disabled, readonly)', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code === 13) {
                $(this).autocomplete("destroy");
                return false;
            } else {
                if (!$(this).data("ui-autocomplete")) {
                    billlookupAutoComplete($(this), 'code');
                }
            }
        });

        $(billWindowId).on("keydown", 'input.meta-autocomplete:not(disabled, readonly)', function(e) {
            var isName = false;
            if ($(this).hasClass('glName-autocomplete')) {
                isName = true;
            }
            if (e.which === 13) {
                var _this = $(this);
                var _value = _this.val();
                var _parent = _this.closest("div.input-group");
                var _lookupCode = _parent.attr("data-section-path");

                $.ajax({
                    type: 'post',
                    url: 'mdgl/glAutoCompleteById',
                    data: {
                        lookupCode: _lookupCode,
                        code: _value,
                        isName: isName
                    },
                    dataType: "json",
                    async: false,
                    beforeSend: function() {
                        _this.addClass("spinner2");
                    },
                    success: function(data) {
                        if (data.META_VALUE_ID !== '') {
                            _parent.find("input[id*='_valueField']").val(data.META_VALUE_ID).trigger("change");
                            _parent.find("input[id*='_displayField']").val(data.META_VALUE_CODE).attr('title', data.META_VALUE_CODE);
                            _parent.find("input[id*='_nameField']").val(data.META_VALUE_NAME).attr('title', data.META_VALUE_NAME);
                        } else {
                            _parent.find("input[id*='_valueField']").val('').trigger("change");
                            _parent.find("input[id*='_nameField']").val('').attr('title', '');
                        }

                        _this.removeClass("spinner2");
                    },
                    error: function() {
                        alert("Error");
                    }
                });
            }
        });
    }
    function billlookupAutoComplete(elem, type) {
        var _this = elem;
        var isHoverSelect = false;
        var _parent = _this.closest("div.input-group");
        var lookupCode = _parent.attr("data-section-path");

        _this.autocomplete({
            minLength: 1,
            maxShowItems: 10,
            delay: 500,
            highlightClass: "lookup-ac-highlight",
            appendTo: "body",
            position: {my: "left top", at: "left bottom", collision: "flip flip"},
            autoSelect: false,
            source: function(request, response) {
                $.ajax({
                    type: 'post',
                    url: 'mdgl/glLookupAutoComplete',
                    dataType: "json",
                    data: {
                        lookupCode: lookupCode,
                        q: request.term,
                        type: type
                    },
                    success: function(data) {
                        if (type == 'code') {
                            response($.map(data, function(item) {
                                var code = item.codeName.split("|");
                                return {
                                    value: code[1],
                                    label: code[1],
                                    name: code[2],
                                    row: item.row
                                };
                            }));
                        } else {
                            response($.map(data, function(item) {
                                var code = item.codeName.split("|");
                                return {
                                    value: code[2],
                                    label: code[1],
                                    name: code[2],
                                    row: item.row
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
                $(this).autocomplete("option", "appendTo", "body");
            },
            select: function(event, ui) {
                var origEvent = event;

                if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
                    if (type === 'code') {
                        _parent.find("input[id*='_displayField']").val(ui.item.label);
                    } else {
                        _parent.find("input[id*='_nameField']").val(ui.item.name);
                    }
                } else {
                    if (type === 'code') {
                        if (ui.item.label === _this.val()) {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                            _parent.find("input[id*='_nameField']").val(ui.item.name);
                        } else {
                            _parent.find("input[id*='_displayField']").val(_this.val());
                            event.preventDefault();
                        }
                    } else {
                        if (ui.item.name === _this.val()) {
                            _parent.find("input[id*='_displayField']").val(ui.item.label);
                            _parent.find("input[id*='_nameField']").val(ui.item.name);
                        } else {
                            _parent.find("input[id*='_nameField']").val(_this.val());
                            event.preventDefault();
                        }
                    }
                }

                while (origEvent.originalEvent !== undefined) {
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

                return $('<li>').append('<div class="lookup-ac-render-code">' + label + '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(ul);
            } else {
                var re = new RegExp("(" + this.term + ")", "gi"),
                        cls = this.options.highlightClass,
                        template = "<span class='" + cls + "'>$1</span>",
                        name = item.name.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">' + item.label + '</div><div class="lookup-ac-render-name">' + name + '</div>').appendTo(ul);
            }
        };
    }

    /*function customerSelectableGrid(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        $("#customerId_valueField", billWindowId).val(row.id);
        $("#customerCode_displayField", billWindowId).val(row.customercode);
        $("#customerName_nameField", billWindowId).val(row.customername);
    }
    function accountSelectableGrid(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        $("#accountId_valueField", billWindowId).val(row.id);
        $("#accountCode_displayField", billWindowId).val(row.accountcode);
        $("#accountName_nameField", billWindowId).val(row.accountname);
    }*/
    
    function calcSearchCurrencyRate() {
        $("#saveBillRate-form", billWindowId).validate({errorPlacement: function() {}});          
        if ($("#saveBillRate-form", billWindowId).valid()) {
            Core.blockUI({
                boxed: true,
                message: 'Уншиж байна...'
            });
            var getCurrnecy = 0;

            setTimeout(function(){
                $.ajax({
                    type: 'post',
                    url: 'mdgl/getOneCurrency',
                    async: false,
                    data: {
                        currencyId: $("#saveBillRate-form", billWindowId).find('#currencyId').val(),
                        rateDate: $("#saveBillRate-form", billWindowId).find('#toDate').val()
                    },
                    dataType: 'json',        
                    success: function (data) {
                        if (data.status === 'success') {
                            getCurrnecy = parseFloat(data.rate);
                        } else {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Error',
                                text: data.message,
                                type: 'error',
                                sticker: false
                            });
                        }
                    }
                });   

                $.ajax({
                type: 'POST',
                url: 'mdgl/customerBill',
                async: false,
                data: {
                    fromDate: $('#fromDate', billWindowId).val(),
                    toDate: $('#toDate', billWindowId).val(),
                    currencyId: $('#currencyId', billWindowId).val(),
                    customerId: $("#customerId_valueField", billWindowId).val(),
                    accountId: $("#accountId_valueField", billWindowId).val(), 
                    departmentId: $("#filterDepartmentId_valueField", billWindowId).val()
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.status !== 'success') {
                        $('.btn-rate-calc').attr('disabled', true);
                        PNotify.removeAll();
                        new PNotify({
                            type: resp.status,
                            title: resp.title,
                            text: resp.text,
                            sticker: false
                        });
                    } else {
                        $('input[name="equaliseAllCheck"]').attr('checked', false);
                        $('input[name="equaliseAllCheck"]').parent().removeClass('checked');
                        $('input[name="equaliseAllCheck"]').attr('checked', false);
                        $('input[name="equaliseAllCheck"]').parent().removeClass('checked');

                        customerBillRateDtlTable.fnDeleteRow();
                        $('.btn-rate-calc').attr('disabled', false);
                        var billarr = [], newRate = '', headerNewRate = $(billWindowId).find('#headerNewRate').autoNumeric('get');
                        $.each(resp.getRows, function(i, val) {
                            /*if ($.cookie && typeof $.cookie('BILL_RATE_' + val.KEY_ID + '_' + val.BOOK_NUMBER) !== 'undefined') {
                                newRate = $.cookie('BILL_RATE_' + val.KEY_ID + '_' + val.BOOK_NUMBER);
                                newRate = newRate == 0 ? '' : newRate;
                            }*/
                            <?php 
                            if (Config::getFromCache('CONFIG_GL_BILLRATE_HDR_RATE')) {
                            ?>
                            newRate = headerNewRate;
                            <?php
                            } else {
                            ?>
                            newRate = '';  
                            if (typeof val.LAST_RATE !== 'undefined' && val.LAST_RATE > 1) {
                                newRate = val.LAST_RATE;
                            }
                            <?php
                            }
                            ?>
                            
                            var desc = val.DESCRIPTION;
                            if (val.DESCRIPTION && val.DESCRIPTION.length > 29) {
                                desc = val.DESCRIPTION.substr(0, 29) + '...';
                            }
                            
                            billarr.push([
                                '',
                                '<span id="'+val.KEY_ID+'">' + val.CUSTOMER_CODE + '</span>',
                                val.CUSTOMER_NAME,
                                val.ACCOUNT_CODE + '<input type="hidden" name="groupAccountCode[]" value="' + val.ACCOUNT_CODE + '">',
                                val.ACCOUNT_NAME,
                                '<input type="text" name="date[]" class="form-control dateInit text-center" value="' + val.BOOK_DATE + '" readonly="true" data-m-dec="2"><input type="hidden" class="isAp" value="' + val.IS_AP + '"><input type="hidden" name="keyId[]" value="' + val.KEY_ID + '"><input type="hidden" name="accountId[]" value="' + val.ACCOUNT_ID + '"><input type="hidden" name="accountCode[]" value="' + val.ACCOUNT_CODE + '"><input type="hidden" name="customerId[]" value="' + val.CUSTOMER_ID + '">',
                                '<input type="text" name="bookNumberRow[]" class="form-control" value="' + val.BOOK_NUMBER + '" readonly="true" data-m-dec="2">',
                                '<span title="' + val.DESCRIPTION + '" class="qtooltip">' + desc + '</span>',
                                '<input type="text" name="rate[]" class="form-control bigdecimalInit" value="' + val.RATE + '" readonly="true" data-m-dec="2">',
                                /*'<input type="text" name="beginbaseamount[]" class="form-control bigdecimalInit" value="' + val.BEGIN_AMOUNT_BASE + '" readonly="true" data-m-dec="2">',
                                '<input type="text" name="beginamount[]" class="form-control bigdecimalInit" value="' + val.BEGIN_AMOUNT + '" readonly="true" data-m-dec="2">',*/
                                '<input type="text" name="debitbaseamount[]" class="form-control bigdecimalInit" value="' + val.DEBIT_AMOUNT_BASE + '" readonly="true" data-m-dec="2">',
                                '<input type="text" name="debitamount[]" class="form-control bigdecimalInit" value="' + val.DEBIT_AMOUNT + '" readonly="true" data-m-dec="2">',
                                '<input type="text" name="creditbaseamount[]" class="form-control bigdecimalInit" value="' + val.CREDIT_AMOUNT_BASE + '" readonly="true" data-m-dec="2">',
                                '<input type="text" name="creditamount[]" class="form-control bigdecimalInit" value="' + val.CREDIT_AMOUNT + '" readonly="true" data-m-dec="2">',
                                '<input type="text" name="endAmountBase[]" class="form-control bigdecimalInit" value="' + val.END_AMOUNT_BASE + '" readonly="true" data-m-dec="2">',
                                '<input type="text" name="endAmount[]" class="form-control bigdecimalInit" value="' + (val.END_AMOUNT >= -0.5 && val.END_AMOUNT < 0.5 ? 0 : val.END_AMOUNT) + '" readonly="true" data-m-dec="2">',
                                <?php
                                if (Config::getFromCache('CONFIG_GL_BILLRATE_HDR_RATE')) {
                                ?>
                                '<input type="text" name="equalizeRate[]" onkeypress="nextTRinput(event)" onchange="inputEqualizer(this)" class="form-control bigdecimalInit" value="' + newRate + '" data-lastrate="' + newRate + '" data-m-dec="2" style="width: 67px;">',
                                <?php
                                } else {
                                ?>
                                '<input type="text" name="equalizeRate[]" onkeypress="nextTRinput(event)" onchange="inputEqualizer(this)" class="form-control bigdecimalInit" value="' + newRate + '" data-lastrate="' + newRate + '" data-m-dec="2" style="width: 56px;"><button class="btn btn-sm blue" onclick="moreEqualizer(this); return false;" style="margin-bottom: 1px;margin-left: 3px;"><i class="fa fa-ellipsis-h"></i></button>',                    
                                <?php
                                }
                                ?>                                    
                                '<input type="text" name="varianceDebitDisplay[]" class="form-control bigdecimalInit" value="0" readonly="true" data-m-dec="2"><input type="hidden" name="varianceDebit[]" class="" value="">',
                                '<input type="text" name="varianceCreditDisplay[]" class="form-control bigdecimalInit" value="0" readonly="true" data-m-dec="2"><input type="hidden" name="varianceCredit[]" class="" value="">',
                                '<input type="checkbox" class="form-control booleanInit text-center" name="isCheck[]" class="equalise" onclick="equaliseItem(this)">'
                            ]);
                        });
                        customerBillRateDtlTable.fnAddData(billarr);                    
                        
                        var $tbody = $(billWindowId).find(".dataTables_scroll").find(".dataTables_scrollBody").find("table.dataTable tbody");
                            
                        $tbody.find("input[type=text]").closest('td').addClass('stretchInput');
                        $tbody.find("input[type=checkbox]").closest('td').addClass('text-center');
                        
                        $(billWindowId).find(".dataTables_scrollFoot")
                                .find("table tfoot tr").find("td.SUM")
                                .find("span").autoNumeric('init', {aPad: false, mDec: 2, vMin: '-99999999999999999999.99', vMax: '99999999999999999999.99'});

                        rowNumeringByGroup();
                        calcFoot();
                        if ($("#glTemplateSectionStatic", billWindowId).length > 0) {
                            $("#glTemplateSectionStatic", billWindowId).remove();
                        }
                        customerBillRateDtlTable.fnAdjustColumnSizing();

                        <?php if ($this->isDefaultCalc == '1' || $this->isDefaultCalc == '') { ?>
                            $(billWindowId+" #customerBillGrid > tbody > tr").each(function(){
                                var _this = $(this), currTr = _this;

                                if(typeof _this.attr('role') !== 'undefined') {
                                    var dHtml = '<div class="more-equalizer-dialog" style="display:none">';
                                    dHtml += '<table class="table table-sm table-bordered table-hover" style="border-top: 1px solid #ddd;">' +
                                        '<thead>'+
                                            '<tr style="background-color: #EDEDED; font-weight: bold;">'+
                                                '<td class="text-center" rowspan="2">ДҮН</td>'+
                                                '<td class="text-center" rowspan="2">ХАНШ</td>'+
                                                '<td class="text-center" colspan="2">ЗӨРҮҮ</td>'+
                                            '</tr>'+
                                            '<tr style="background-color: #EDEDED; font-weight: bold;">'+
                                                '<td class="text-center">АШИГ</td>'+
                                                '<td class="text-center">АЛДАГДАЛ</td>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                            '<tr>'+
                                                '<td><input type="text" name="moreEqualizerAmount" onchange="fnMoreEqualizerAmount(this);" onkeypress="nextTRTDinput(event);" class="form-control bigdecimalInit text-right moreEqualizerAmount" value="' + (Number(_this.find(' > td:eq(7) > input').autoNumeric("get")) + Number(_this.find(' > td:eq(5) > input').autoNumeric("get"))) + '" style="border: 1px solid #ddd;"/></td>'+
                                                '<td><input type="text" name="moreEqualizerRate" onchange="calculateVarianceDialog(this);" onkeypress="nextTRTDinput(event);" class="form-control bigdecimalInit text-right moreEqualizerRate" value="' + getCurrnecy + '" style="border: 1px solid #ddd;"/></td>'+
                                                '<td><input type="text" disabled class="form-control bigdecimalInit text-right moreEqualizerVarianceDebit" style="border: 1px solid #ddd;"/></td>'+
                                                '<td><input type="text" disabled class="form-control bigdecimalInit text-right moreEqualizerVarianceCredit" style="border: 1px solid #ddd;"/></td>'+
                                            '</tr>'+
                                        '</tbody>'+
                                        '<tfoot>'+
                                            '<tr style="background-color: #EDEDED; font-weight: 600;">'+
                                                '<td class="text-right">&nbsp;</td>'+
                                                '<td>&nbsp;</td>'+
                                                '<td class="text-right">&nbsp;</td>'+
                                                '<td class="text-right">&nbsp;</td>'+
                                            '</tr>'+
                                        '</tfoot>'+
                                    '</table>';
                                    dHtml += '<div>';                                         
                                    _this.find(' > td:eq(11)').append(dHtml);

                                    var moreAmounSum = 0;
                                    var moreVarianceDebitSum = 0;
                                    var moreVarianceCreditSum = 0;
                                    var moreRateCheck = true;
                                    var moreRateNumberCheck = true;
                                    var moreAmountCheck = true;

                                    Core.initNumberInput($('.more-equalizer-dialog > table > tbody > tr', _this.find(' > td:eq(11)')));
                                    $('.more-equalizer-dialog > table > tbody > tr', _this.find(' > td:eq(11)')).each(function(){
                                        var _thisChild = $(this);
                                        var tr = _thisChild;
                                        var isAp = currTr.find('input.isAp').val();        
                                        var newAmount = Number(tr.find('input.moreEqualizerAmount').autoNumeric("get"));
                                        var oldRate = Number(currTr.find('input[name="rate[]"]').autoNumeric("get"));
                                        var rate = Number(tr.find('input.moreEqualizerRate').autoNumeric("get"));
                                        var variance = 0;

                                        if(rate == 0)
                                            return;

                                        if(oldRate <= rate) {    
                                            if(isAp === '1'){
                                                variance = (newAmount * oldRate) - (newAmount * rate);
                                            } else {
                                                variance = (newAmount * rate) - (newAmount * oldRate);
                                            }

                                            if (variance > 0) {
                                                tr.find('input.moreEqualizerVarianceDebit').autoNumeric("set", 0);
                                                tr.find('input.moreEqualizerVarianceCredit').autoNumeric("set", variance);
                                            } else {
                                                variance = variance * -1;
                                                tr.find('input.moreEqualizerVarianceDebit').autoNumeric("set", variance);
                                                tr.find('input.moreEqualizerVarianceCredit').autoNumeric("set", 0);
                                            }      
                                            
                                        } else {
                                            
                                            if (isAp === '1') {
                                                variance = (newAmount * oldRate) - (newAmount * rate);
                                            } else {
                                                variance = (newAmount * rate) - (newAmount * oldRate);
                                            }

                                            if (variance > 0) {
                                                tr.find('input.moreEqualizerVarianceDebit').autoNumeric("set", 0);
                                                tr.find('input.moreEqualizerVarianceCredit').autoNumeric("set", variance);
                                            } else {
                                                variance = variance * -1;
                                                tr.find('input.moreEqualizerVarianceDebit').autoNumeric("set", variance);
                                                tr.find('input.moreEqualizerVarianceCredit').autoNumeric("set", 0);
                                            }                                            
                                        }

                                        var morevdebit = 0;
                                        var morevcredit = 0;
                                        tr.closest('tbody').find('tr').each(function(){
                                            morevdebit += Number($(this).find('input.moreEqualizerVarianceDebit').autoNumeric("get"));
                                            morevcredit += Number($(this).find('input.moreEqualizerVarianceCredit').autoNumeric("get"));
                                        });
                                        $('div.more-equalizer-dialog > table > tfoot > tr > td:eq(2)', currTr).text(pureNumberFormat(morevdebit));
                                        $('div.more-equalizer-dialog > table > tfoot > tr > td:eq(3)', currTr).text(pureNumberFormat(morevcredit));

                                        var mr = Number(_thisChild.find('input.moreEqualizerRate').autoNumeric("get"));
                                        var ma = Number(_thisChild.find('input.moreEqualizerAmount').autoNumeric("get"));
                                        var mvd = Number(_thisChild.find('input.moreEqualizerVarianceDebit').autoNumeric("get"));
                                        var mvc = Number(_thisChild.find('input.moreEqualizerVarianceCredit').autoNumeric("get"));

                                        if(mr < 0 && ma > 0) {
                                            moreRateNumberCheck = false;
                                        }
                                        if(mr === 0 && ma > 0) {
                                            moreRateCheck = false;
                                        }
                                        if(ma === 0 && mr > 0) {
                                            moreAmountCheck = false;
                                        }

                                        moreAmounSum += mr * ma;
                                        moreVarianceDebitSum += mvd;
                                        moreVarianceCreditSum += mvc;
                                    }).promise().done(function(){
                                        if(moreAmounSum !== 0) {
                                            var baseAmount = currTr.find('input[name="debitbaseamount[]"]').autoNumeric("get") === '0' ? currTr.find('input[name="creditbaseamount[]"]').autoNumeric("get") : currTr.find('input[name="debitbaseamount[]"]').autoNumeric("get"),
                                                moreNewRate = moreAmounSum / Number(baseAmount);

                                            currTr.find('input[name="equalizeRate[]"]').autoNumeric("set", moreNewRate);
                                            currTr.find('input[name="equalizeRate[]"]').parent().append("<input type='hidden' name='equalizeRateHidden[]' value='"+(moreAmounSum / Number(baseAmount)).toFixed(6)+"'>");

                                            if (moreVarianceDebitSum <= moreVarianceCreditSum) {
                                                currTr.find('input[name="varianceDebitDisplay[]"]').autoNumeric("set", (moreVarianceDebitSum - moreVarianceCreditSum) * (-1));
                                                currTr.find('input[name="varianceDebit[]"]').val((moreVarianceDebitSum - moreVarianceCreditSum) * (-1));
                                                currTr.find('input[name="varianceCreditDisplay[]"]').autoNumeric("set", 0);
                                                currTr.find('input[name="varianceCredit[]"]').val(0);
                                            } else {
                                                currTr.find('input[name="varianceCreditDisplay[]"]').autoNumeric("set", (moreVarianceCreditSum - moreVarianceDebitSum) * (-1));
                                                currTr.find('input[name="varianceCredit[]"]').val((moreVarianceCreditSum - moreVarianceDebitSum) * (-1));
                                                currTr.find('input[name="varianceDebitDisplay[]"]').autoNumeric("set", 0);
                                                currTr.find('input[name="varianceDebit[]"]').val(0);
                                            }
                                        }
                                    });                                        
                                }
                            });                        
                        <?php } ?>
                    }                    
                    Core.unblockUI();
                }
            });
            }, 200);
        }
    }
    function equaliseItem(elem) {
        if ($("#glTemplateSectionStatic").length > 0) {
            $("#glTemplateSectionStatic").remove();
        }
        var equalAll = true;

        if ($(elem).is(':checked')) {
            if($(elem).closest('tr').find('input[name="equalizeRate[]"]').val() != ""){
                $(elem).attr('checked', true);
                $(elem).val('1');
                $(elem).parent().addClass('checked');
            }else{
                alert("Шинэ ханш хоосон байна");
                $(elem).val('0');
                $(elem).attr('checked', false);
            }
        } else {
            $(elem).attr('checked', false);
            $(elem).val('0');
            $(elem).parent().removeClass('checked');
        }
        $('input[name="isCheck[]"]', tableId).each(function() {
            if ($(this).is(':checked') === false) {
                equalAll = false;
            }
        });
        if (equalAll) {
            $(tableId + "_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").attr('checked', true);
            $(tableId + "_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").parent().addClass('checked');
        } else {
            $(tableId + "_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").attr('checked', false);
            $(tableId + "_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").parent().removeClass('checked');
        }
    }
    function equaliseAll(elem) {
        var $checkBoxs = $(billWindowId + ' #customerBillGrid').find('input[name="isCheck[]"]');
        if ($(elem).is(':checked')) {
            $checkBoxs.val('1');
            $checkBoxs.prop('checked', true);
        } else {
            $checkBoxs.val('0');
            $checkBoxs.prop('checked', false);
        }
        $.uniform.update($checkBoxs);
        
        /*if ($("#glTemplateSectionStatic").length > 0) {
            $("#glTemplateSectionStatic").remove();
        }
        if ($(elem).is(':checked')) {
            var equalAll = true;
            $('input[name="equalizeRate[]"]', tableId).each(function(index) {
                if($(this).val() == ''){
                    equalAll = false;
                }
            });

            if (equalAll) {
                $('.equalise', tableId).attr('checked', true);
                $('.equalise', tableId).parent().addClass('checked');
            } else {
                $(tableId + "_wrapper")
                        .find(".dataTables_scroll")
                        .find(".dataTables_scrollHead")
                        .find(".dataTables_scrollHeadInner")
                        .find("table.dataTable thead")
                        .find("input[name=equaliseAllCheck]").attr('checked', false);
                $(tableId + "_wrapper")
                        .find(".dataTables_scroll")
                        .find(".dataTables_scrollHead")
                        .find(".dataTables_scrollHeadInner")
                        .find("table.dataTable thead")
                        .find("input[name=equaliseAllCheck]").parent().removeClass('checked');
            }
        } else {
            $('.equalise', tableId).attr('checked', false);
            $('.equalise', tableId).parent().removeClass('checked');
            $('input[name="isCheck[]"]', tableId).each(function() {
                $(this).val('0');
            });
        }*/
    }
    function calcCurrencyRate() {
        setVariaceAmount();
        var ratedAccountRow = [];
        var isCheck = 0;
        $('#customerBillGrid > tbody > tr', billWindowId).each(function() {
            var $this = $(this);
            if ($this.find('input[name="isCheck[]"]').val() == '1') {
                isCheck = 1;
                var selectedRow = {
                    'accountId': $this.find('input[name="accountId[]"]').val(),
                    'customerId': $this.find('input[name="customerId[]"]').val(),
                    'keyId': $this.find('input[name="keyId[]"]').val(),
                    bookdate: $this.find('input[name="date[]"]').val(),
                    //bookdate: $("#saveBillRate-form", billWindowId).find('#toDate').val(),
                    'rate': $this.find('input[name="equalizeRate[]"]').autoNumeric('get'),
                    'debitAmount': $this.find('input[name="varianceDebit[]"]').val(),
                    'creditAmount': $this.find('input[name="varianceCredit[]"]').val(),
                    'bookNumber': $this.find('input[name="bookNumberRow[]"]').val()
                };
                ratedAccountRow.push(selectedRow);
            }
        });
        if (isCheck === 0) {
            PNotify.removeAll();
            new PNotify({
                title: 'Анхааруулга',
                text: 'Ханш тэгшитгэх гүйлгээгээ сонгоно уу',
                type: 'warning',
                sticker: false
            });
        } else {
            $.ajax({
                type: 'post',
                url: 'mdgl/getCurrencyRatedRow',
                data: {ratedRow: ratedAccountRow, type: 'billRate', 'bookdate': $("#saveBillRate-form", billWindowId).find('#toDate').val()},
                dataType: "json",
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(data) {
                    if (data.status == 'success') {
                        if (data.Html != '') {
                            if ($("#glTemplateSectionStatic", billWindowId).length > 0) {
                                $("#glTemplateSectionStatic", billWindowId).remove();
                            }
                            $('.panel-body-billContent', billWindowId).after(data.Html);
                            $("table#glDtl > tbody > tr", billWindowId).find('input[name="gl_rate_currency[]"]').val("MNT");
                            $("table#glDtl", billWindowId).find("thead#header2").addClass("hide").hide();
                            $("table#glDtl", billWindowId).find("thead#header1").show();
                            $("table#glDtl", billWindowId).find("th.usebase, td[data-usebase='usebase']").hide();
                        }
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.message,
                            type: 'error',
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
    }
    function alertRate() {
        var footProfit = $('input[name="footVarianceCredit"]', billWindowId).val();
        var footLoss = $('input[name="totalLoss"]', billWindowId).val();
        var footDebit = $('input[name="hdrCredit"]', billWindowId).val();
        var footCredit = $('input[name="hdrDebit"]', billWindowId).val();
        if (footProfit == '0' && footLoss == '0' && footDebit == '0' && footCredit == '0') {
            new PNotify({
                title: 'Анхааруулга',
                text: 'Шинэ ханшаар тэгшитгэл хийгдсэн байна',
                type: 'error',
                sticker: false
            });
        }
    }
    function calcFoot() {
        var sumbeginbaseamount = 0, sumbeginamount = 0, sumdebitamount = 0, sumdebitamountbase = 0, sumcreditamount = 0, sumcreditamountbase = 0, sumvariancedebit = 0, sumvariancecredit = 0;

        $('#customerBillGrid > tbody > tr', billWindowId).each(function() {
            var beginbaseamount = $(this).find('input[name="beginbaseamount[]"]', billWindowId).autoNumeric('get');
            var beginamount = $(this).find('input[name="beginamount[]"]', billWindowId).autoNumeric('get');
            var debitbaseamount = $(this).find('input[name="debitbaseamount[]"]', billWindowId).autoNumeric('get');
            var debitamount = $(this).find('input[name="debitamount[]"]', billWindowId).autoNumeric('get');
            var creditbaseamount = $(this).find('input[name="creditbaseamount[]"]', billWindowId).autoNumeric('get');
            var creditamount = $(this).find('input[name="creditamount[]"]', billWindowId).autoNumeric('get');
            var varianceDebit = $(this).find('input[name="varianceDebit[]"]', billWindowId).val();
            var varianceCredit = $(this).find('input[name="varianceCredit[]"]', billWindowId).val();
            
            if (typeof debitbaseamount != 'undefined'){
                sumbeginbaseamount = parseFloat(sumbeginbaseamount) + parseFloat(beginbaseamount);
                sumbeginamount = parseFloat(sumbeginamount) + parseFloat(beginamount);
                sumdebitamount = parseFloat(sumdebitamount) + parseFloat(debitamount);
                sumdebitamountbase = parseFloat(sumdebitamountbase) + parseFloat(debitbaseamount);
                sumcreditamount = parseFloat(sumcreditamount) + parseFloat(creditamount);
                sumcreditamountbase = parseFloat(sumcreditamountbase) + parseFloat(creditbaseamount);
                sumvariancedebit = parseFloat(sumvariancedebit) + parseFloat(varianceDebit);
                sumvariancecredit = parseFloat(sumvariancecredit) + parseFloat(varianceCredit);
            }
        });
//        $('#footBeginBase', billWindowId).autoNumeric('set', sumbeginbaseamount);
//        $('#footBegin', billWindowId).autoNumeric('set', sumbeginamount);
        $('#footDebit', billWindowId).autoNumeric('set', sumdebitamount);
        $('#footDebitBase', billWindowId).autoNumeric('set', sumdebitamountbase);
        $('#footCredit', billWindowId).autoNumeric('set', sumcreditamount);
        $('#footCreditBase', billWindowId).autoNumeric('set', sumcreditamountbase);
        $('#footVarianceDb', billWindowId).autoNumeric('set', sumvariancedebit);
        $('#footVarianceCr', billWindowId).autoNumeric('set', sumvariancecredit);
    }
    function saveBillRate() {
        PNotify.removeAll();
        $("#saveBillRate-form", billWindowId).validate({errorPlacement: function() {}});
        if ($("#saveBillRate-form", billWindowId).valid()) {
            var validGl = validateGlBook($('#glTemplateSectionStatic', billWindowId));
            if (validGl.status == 'success') {
                Core.blockUI({
                    boxed: true, 
                    message: 'Loading...'
                });
                $("#saveBillRate-form", billWindowId).ajaxSubmit({
                    type: 'post',
                    url: 'mdgl/saveBillRate',
                    dataType: "json",
                    success: function(data) {
                        if (data.status === 'success') {
                            new PNotify({
                                title: 'Success',
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
                            clearForm();
                        } else {
                            new PNotify({
                                title: 'Error',
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
                        }
                        Core.unblockUI();
                    }
                });
            } else {
                new PNotify({
                    title: 'Error',
                    text: validGl.text,
                    type: 'error',
                    sticker: false
                });
            }
        } else {
            $('html, body').animate({
                scrollTop: 0
            }, 0);
        }
    }
    function clearForm() {
        customerBillRateDtlTable.fnDeleteRow();
        if ($("#glTemplateSectionStatic", billWindowId).length > 0) {
            $("#glTemplateSectionStatic", billWindowId).remove();
        }
        $(".SUM span", billWindowId).empty();
//        $("#headerParam").find('input, select').val("");
//        $("input[name='equaliseAllCheck']").attr('checked', false);
//        $("input[name='equaliseAllCheck']").parent().removeClass('checked');
    }
    function calculateVariance(tr) {
        var debitbaseamount = tr.find('input[name="debitbaseamount[]"]').autoNumeric('get');
        var creditbaseamount = tr.find('input[name="creditbaseamount[]"]').autoNumeric('get');
        var debitamount = tr.find('input[name="debitamount[]"]').autoNumeric('get');
        var creditamount = tr.find('input[name="creditamount[]"]').autoNumeric('get');
        var rate = tr.find('input[name="equalizeRate[]"]').autoNumeric('get');
        var endAmount = 0, newAmount = debitamount - creditamount, newBaseAmount = debitbaseamount - creditbaseamount;
        
        if (debitbaseamount > creditbaseamount) {
            endAmount = newBaseAmount * rate - newAmount; 
        } else {
            endAmount = newBaseAmount * rate - newAmount; 
        }
        
        if (endAmount > 0) {
            tr.find('input[name="varianceDebitDisplay[]"]').autoNumeric('set', endAmount);
            tr.find('input[name="varianceDebit[]"]').val(endAmount);
            tr.find('input[name="varianceCreditDisplay[]"]').autoNumeric('set', 0);
            tr.find('input[name="varianceCredit[]"]').val(0);
        } else {
            endAmount = endAmount * -1;
            tr.find('input[name="varianceDebitDisplay[]"]').autoNumeric('set', 0);
            tr.find('input[name="varianceDebit[]"]').val(0);
            tr.find('input[name="varianceCreditDisplay[]"]').autoNumeric('set', endAmount);
            tr.find('input[name="varianceCredit[]"]').val(endAmount);
        }   
    }
    function collapseAccounts() {
        $('table#customerBillGrid > tbody > tr', billWindowId).each(function() {
            var _thisRow = $(this);
            if (!_thisRow.is('.group')) {
                _thisRow.addClass("hide");
            }
        });
    }
    function checkBalance(tableId, groupId) {
        var isChecked = true;
        $(tableId + ' > tbody > tr:not(.group)').each(function() {
            if ($(this).find('input[name="accountCode[]"]').val() == groupId) {
                var amount = $(this).find('input[name="amount[]"]').autoNumeric('get');
                if (amount < 0) {
                    isChecked = false;
                }
            }
        });
        return isChecked;
    }
    function setVariaceAmount() {
        Core.blockUI({
            boxed: true,
            message: 'Ханш тэгшитгэж байна...'
        });
        $('#customerBillGrid > tbody', billWindowId).find("tr:not(.group)").each(function() {
            var $this = $(this);
            <?php
            if (!Config::getFromCache('CONFIG_GL_BILLRATE_IGNORE_CALC')) {
                if (Config::getFromCache('CONFIG_GL_BILLRATE_HDR_RATE')) {
            ?>
                if ($this.find("input[name='isCheck[]']").val() == '1') {
                    calculateVariance($this);
                }
            <?php
                } else {
            ?>
                if ($this.find("input[name='isCheck[]']").val() == '1' && $this.find("input[name='equalizeRate[]']").attr('data-lastrate') == '') {
                    calculateVariance($this);
                }            
            <?php
                }
            }
            ?>
            
            /*if ($this.find("input[name='isCheck[]']").val() == '1' && $this.find("input[name='equalizeRate[]']").attr('data-lastrate') != '') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: 'Ханш тэгшитгэсэн тохиолдолд дахиж тэгшитгэл хийх боломжгүй.<br>Устгаад дахин тэгшитгэл хийнэ үү',
                    type: 'warning',
                    sticker: false
                });                 
                return;                
            }*/
        });
        calcFoot();
        Core.unblockUI();
    }
    function rowNumeringByGroup() {
        var j = 1;
        if ($('#customerBillGrid > tbody > tr').length > 1) {
            $('#customerBillGrid > tbody > tr:not(.group)').each(function() {
                var row = $(this);
                if(row.hasClass('groupCustomer'))
                    j = 1;
                else {
                    row.find('td:first-child').text(j);
                    j++;
                }
            });
        }
    }
    function customerDetail(elem){
        var keyVal = $(elem).children().attr('id');
        var $dialogName = 'dialog-customerDtl';

        if (!$("#" + $dialogName, billWindowId).length) {
            $("#" + $dialogName, billWindowId).dialog('destroy').remove();
        }
        if (!$("#" + $dialogName, billWindowId).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo(billWindowId);
        }    
        
        $.ajax({
            type: 'post',
            url: 'mdgl/customerBillDetail',
            data: {
                keyId: keyVal
            },
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                Core.unblockUI();
                $("#" + $dialogName, billWindowId).empty().html(data.html);
                $("#" + $dialogName, billWindowId).dialog({
                        appendTo: billWindowId,
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 1000,
                        height: "auto",
                        modal: false,
                        buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $("#" + $dialogName, billWindowId).empty().dialog('close');
                            $("#" + $dialogName, billWindowId).dialog('destroy').remove();
                        }}
                        ]
                }).dialogExtend({
                        "closable": true,
                        "maximizable": true,
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize", "minimizeLocation": "left",
                        "icons": {
                        "close": "ui-icon-circle-close",
                                "maximize": "ui-icon-extlink",
                                "minimize": "ui-icon-minus",
                                "collapse": "ui-icon-triangle-1-s",
                                "restore": "ui-icon-newwin"
                        }
                });
                $("#" + $dialogName, billWindowId).dialog('open');
                $("#" + $dialogName).dialogExtend("maximize");
            },
            error: function() {
                alert("Error");
            }
        });
    }
    function calculateVarianceDialog(tr) {
        var tr = $(tr).closest('tr');
        var currTr = tr.closest('table').closest('tr');
        var isAp = currTr.find('input.isAp').val();        
        var newAmount = Number(tr.find('input.moreEqualizerAmount').autoNumeric("get"));
        var oldRate = Number(currTr.find('input[name="rate[]"]').autoNumeric("get"));
        var debitAmount = Number(currTr.find('input[name="debitamount[]"]').autoNumeric("get"));
        var creditAmount = Number(currTr.find('input[name="creditamount[]"]').autoNumeric("get"));
        var rate = Number(tr.find('input.moreEqualizerRate').autoNumeric("get"));
        var endAmount = 0;
        
        if (rate == 0)
            return; 
        
        if (debitAmount > creditAmount) {
            endAmount = newAmount * rate - newAmount * oldRate; 
        } else {
            endAmount = (newAmount * rate - newAmount * oldRate) * -1; 
        }
        
        if (endAmount > 0) {
            tr.find('input.moreEqualizerVarianceDebit').autoNumeric("set", endAmount);
            tr.find('input.moreEqualizerVarianceCredit').autoNumeric("set", 0);
        } else {
            endAmount = endAmount * -1;
            tr.find('input.moreEqualizerVarianceDebit').autoNumeric("set", 0);
            tr.find('input.moreEqualizerVarianceCredit').autoNumeric("set", endAmount);
        }   
 
        var morevdebit = 0;
        var morevcredit = 0;
        tr.closest('tbody').find('tr').each(function(){
            morevdebit += Number($(this).find('input.moreEqualizerVarianceDebit').autoNumeric("get"));
            morevcredit += Number($(this).find('input.moreEqualizerVarianceCredit').autoNumeric("get"));
        });
        $('div.more-equalizer-dialog > table > tfoot > tr > td:eq(2)', currTr).text(pureNumberFormat(morevdebit));
        $('div.more-equalizer-dialog > table > tfoot > tr > td:eq(3)', currTr).text(pureNumberFormat(morevcredit));
    }    
    function moreEqualizer(target) {
        var currTr = $(target).closest('tr');
        var currTd = $(target).closest('td');        
        var $dialogName = 'div.more-equalizer-dialog';	
        if ($($dialogName, currTd).length === 0) {
            var dHtml = '<div class="more-equalizer-dialog">';
            dHtml += '<table class="table table-sm table-bordered table-hover" style="border-top: 1px solid #ddd;">' +
                '<thead>'+
                    '<tr style="background-color: #EDEDED; font-weight: bold;">'+
                        '<td class="text-center" rowspan="2">ДҮН</td>'+
                        '<td class="text-center" rowspan="2">ХАНШ</td>'+
                        '<td class="text-center" colspan="2">ЗӨРҮҮ</td>'+
                    '</tr>'+
                    '<tr style="background-color: #EDEDED; font-weight: bold;">'+
                        '<td class="text-center">АШИГ</td>'+
                        '<td class="text-center">АЛДАГДАЛ</td>'+
                    '</tr>'+
                '</thead>'+
                '<tbody>'+
                    '<tr>'+
                        '<td><input type="text" name="moreEqualizerAmount" onchange="fnMoreEqualizerAmount(this);" onkeypress="nextTRTDinput(event);" class="form-control bigdecimalInit text-right moreEqualizerAmount" style="border: 1px solid #ddd;"/></td>'+
                        '<td><input type="text" name="moreEqualizerRate" onchange="calculateVarianceDialog(this);" onkeypress="nextTRTDinput(event);" class="form-control bigdecimalInit text-right moreEqualizerRate" style="border: 1px solid #ddd;"/></td>'+
                        '<td><input type="text" disabled class="form-control bigdecimalInit text-right moreEqualizerVarianceDebit" style="border: 1px solid #ddd;"/></td>'+
                        '<td><input type="text" disabled class="form-control bigdecimalInit text-right moreEqualizerVarianceCredit" style="border: 1px solid #ddd;"/></td>'+
                    '</tr>'+
                '</tbody>'+
                '<tfoot>'+
                    '<tr style="background-color: #EDEDED; font-weight: 600;">'+
                        '<td class="text-right">&nbsp;</td>'+
                        '<td>&nbsp;</td>'+
                        '<td class="text-right">&nbsp;</td>'+
                        '<td class="text-right">&nbsp;</td>'+
                    '</tr>'+
                '</tfoot>'+
            '</table>';
            dHtml += '<div>';
            currTd.append(dHtml);
        } else {
            $($dialogName, currTd).show();
        }

        $($dialogName, currTd).dialog({
            cache: false,
            resizable: true,
            appendTo: currTd,
            bgiframe: true,
            autoOpen: false,
            title: 'Олон ханш тэгшитгэх',
            width: 500,
            height: "auto",
            modal: false,
            draggable: false,
            closeOnEscape: false,
            buttons: [
                {text: 'Хаах', class: 'btn blue-madison btn-sm', click: function () {
                        
                    var moreAmounSum = 0;
                    var moreVarianceDebitSum = 0;
                    var moreVarianceCreditSum = 0;
                    var moreRateCheck = true;
                    var moreRateNumberCheck = true;
                    var moreAmountCheck = true;
                    
                    $($dialogName + ' table > tbody > tr', currTd).each(function(){
                        
                        var _this = $(this);
                        var mr = Number(_this.find('input.moreEqualizerRate').autoNumeric("get"));
                        var ma = Number(_this.find('input.moreEqualizerAmount').autoNumeric("get"));
                        var mvd = Number(_this.find('input.moreEqualizerVarianceDebit').autoNumeric("get"));
                        var mvc = Number(_this.find('input.moreEqualizerVarianceCredit').autoNumeric("get"));
                        
                        if (mr < 0 && ma > 0) {
                            moreRateNumberCheck = false;
                        }
                        if (mr === 0 && ma > 0) {
                            moreRateCheck = false;
                        }
                        if (ma === 0 && mr > 0) {
                            moreAmountCheck = false;
                        }
                        
                        moreAmounSum += mr * ma;
                        moreVarianceDebitSum += mvd;
                        moreVarianceCreditSum += mvc;
                        
                    }).promise().done(function(){
                        
                        if (moreRateNumberCheck === false) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Анхааруулга',
                                text: 'Ханш буруу оруулсан байна!',
                                type: 'warning',
                                sticker: false
                            });                 
                            return;                  
                        }
                        if (moreAmountCheck === false) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Анхааруулга',
                                text: 'Дүн оруулна уу!',
                                type: 'warning',
                                sticker: false
                            });                 
                            return;                  
                        }
                        if (moreRateCheck === false) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Анхааруулга',
                                text: 'Ханш оруулна уу!',
                                type: 'warning',
                                sticker: false
                            });                 
                            return;                  
                        }
                        
                        if (moreAmounSum !== 0) {
                            
                            var baseAmount = currTr.find('input[name="debitbaseamount[]"]').autoNumeric("get") === '0' ? currTr.find('input[name="creditbaseamount[]"]').autoNumeric("get") : currTr.find('input[name="debitbaseamount[]"]').autoNumeric("get"),
                                moreNewRate = moreAmounSum / Number(baseAmount);      
                            
                            if (moreVarianceDebitSum == moreVarianceCreditSum) {
                                return;
                            }
                            
                            currTr.find('input[name="equalizeRate[]"]').autoNumeric('set', moreNewRate);
                            currTr.find('input[name="equalizeRate[]"]').parent().find("input[name='equalizeRateHidden[]']").remove();
                            currTr.find('input[name="equalizeRate[]"]').parent().append("<input type='hidden' name='equalizeRateHidden[]' value='"+moreNewRate.toFixed(6)+"'>");
                            currTr.find('input[name="isCheck[]"]').attr('checked', 'checked').val('1').parent().addClass('checked');
                            
                            if (moreVarianceDebitSum < moreVarianceCreditSum) {
                                currTr.find('input[name="varianceCreditDisplay[]"]').autoNumeric("set", (moreVarianceCreditSum - moreVarianceDebitSum));
                                currTr.find('input[name="varianceCredit[]"]').val((moreVarianceCreditSum - moreVarianceDebitSum));
                                currTr.find('input[name="varianceDebitDisplay[]"]').autoNumeric("set", 0);
                                currTr.find('input[name="varianceDebit[]"]').val(0);
                            } else {
                                currTr.find('input[name="varianceDebitDisplay[]"]').autoNumeric("set", (moreVarianceDebitSum - moreVarianceCreditSum));
                                currTr.find('input[name="varianceDebit[]"]').val((moreVarianceDebitSum - moreVarianceCreditSum));
                                currTr.find('input[name="varianceCreditDisplay[]"]').autoNumeric("set", 0);
                                currTr.find('input[name="varianceCredit[]"]').val(0);
                            }
                        }
                        
                        $($dialogName, currTd).dialog('close');
                    });                        
                }}
            ]
        }).dialogExtend({
            "closable": false,
            "maximizable": true,
            "minimizable": true,
            "collapsable": true,
            "dblclick": "maximize",
            "minimizeLocation": "left",
            "icons": {
                "maximize": "ui-icon-extlink",
                "minimize": "ui-icon-minus",
                "collapse": "ui-icon-triangle-1-s",
                "restore": "ui-icon-newwin"
            }
        });
        $($dialogName, currTd).dialog('open');
        currTd.find(".ui-dialog-titlebar").css("text-align", "left");
        Core.initInputType(currTd);
        $($dialogName, currTd).parent().draggable();
    }
    function fnMoreEqualizerAmount(elem) {
        var currTr = $(elem).closest('table').closest('tr');
        var baseAmount = currTr.find('input[name="debitbaseamount[]"]').autoNumeric("get") === '0' ? currTr.find('input[name="creditbaseamount[]"]').autoNumeric("get") : currTr.find('input[name="debitbaseamount[]"]').autoNumeric("get");
        var moreAmount = 0;
        $(elem).closest('tbody').find('tr').each(function(){
            moreAmount += Number($(this).find('input.moreEqualizerAmount').autoNumeric("get"));
        });
        var amount = Number(baseAmount) - moreAmount;
        
        if(amount > 0 && moreAmount > 0) {
            $('div.more-equalizer-dialog > table > tbody', currTr).append(
                '<tr>'+
                    '<td><input type="text" name="moreEqualizerAmount" onchange="fnMoreEqualizerAmount(this)" onkeypress="nextTRTDinput(event);" class="form-control bigdecimalInit text-right moreEqualizerAmount" style="border: 1px solid #ddd;" value="'+amount+'"/></td>'+
                    '<td><input type="text" name="moreEqualizerRate" onchange="calculateVarianceDialog(this);" onkeypress="nextTRTDinput(event);" class="form-control bigdecimalInit text-right moreEqualizerRate" style="border: 1px solid #ddd;"/></td>'+
                    '<td><input type="text" disabled class="form-control bigdecimalInit text-right moreEqualizerVarianceDebit" style="border: 1px solid #ddd;"/></td>'+
                    '<td><input type="text" disabled class="form-control bigdecimalInit text-right moreEqualizerVarianceCredit" style="border: 1px solid #ddd;"/></td>'+                    
                '</tr>'                    
            );
            $('div.more-equalizer-dialog > table > tfoot > tr > td:first-child', currTr).text(pureNumberFormat(baseAmount));
            Core.initInputType($('div.more-equalizer-dialog > table > tbody', currTr));
        } else if($(elem).closest('tbody').find('tr').find('input.moreEqualizerAmount:first-child').autoNumeric("get") === baseAmount) {
            $('div.more-equalizer-dialog > table > tbody > tr', currTr).not(':first').remove();
        }
        
        calculateVarianceDialog(elem);
    }
    function inputEqualizer(target) {
        var _this = $(target);
        
        /*if ($.cookie) {
            var keyId = _this.closest('tr').find('input[name="keyId[]"]').val();
            var bookNumber = _this.closest('tr').find('input[name="bookNumberRow[]"]').val();
            $.cookie('BILL_RATE_' + keyId + '_' + bookNumber, pureNumber(_this.val()));
        }*/
        
        var currTd = _this.closest('td');        
        var $dialogName = 'div.more-equalizer-dialog';	
        if($($dialogName, currTd).length > 0) {
            $($dialogName, currTd).remove();
        }
    }
    function getAutoNumber() {
        $.ajax({
            type: 'post',
            url: 'mdgl/getAutoNumber',
            data: {bookTypeId: 22},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },            
            success: function (data) {
                if (data.status === 'success')
                    $("#billRateBookNumber", billWindowId).val(data.result.result);
                else
                    new PNotify({
                        title: 'Error',
                        text: data.text, /*'Баримтын дугаар үүсгэхэд алдаа гарлаа!',*/
                        type: 'error',
                        sticker: false
                    });
                Core.unblockUI();
            }
        });
    }    
</script>