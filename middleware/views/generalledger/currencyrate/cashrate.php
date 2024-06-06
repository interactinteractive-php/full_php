<?php
if (!$this->isAjax) {
?>
<div class="col-md-12" id="cashRate">
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
        <div class="card-body">
<?php
} else {
?>
<div id="cashRate">    
<?php
}
?>  
        <form class="form-horizontal xs-form p-0" role="form" method="post" id="saveCashrate-form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="collapsible">
                            <legend><?php echo Lang::lineDefault('FIN_01516', 'Ерөнхий мэдээлэл'); ?></legend>
                            <div class="row">

                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text' => Lang::lineDefault('PL_0176', 'Огноо'), 'for' => 'currencyRateDate', 'class' => 'col-form-label col-md-3 col-sm-6 cashrate-label', 'required' => 'required')); ?>
                                        <div class="col-md-6 col-sm-5">                                 
                                            <div class="dateElement input-group">
                                                <?php echo Form::text(array('name' => 'currencyRateDate', 'id' => 'currencyRateDate', 'class' => 'form-control form-control-sm dateInit', 'value' => Date::currentDate('Y-m-d'), 'required' => 'required')); ?>
                                                <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fal fa-calendar"></i></button></span>
                                            </div>
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text' => Lang::lineDefault('FIN_00846', 'Салбар нэгж'), 'for' => 'filterDepartmentId_displayField', 'class' => 'col-form-label col-md-3 col-sm-6 cashrate-label', 'required' => 'required')); ?>
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
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text' => Lang::lineDefault('FIN_1010', 'Валют'), 'for' => 'currency', 'class' => 'col-form-label col-md-3 col-sm-6 cashrate-label', 'required' => 'required')); ?>
                                        <div class="col-md-6">
                                            <?php
                                            echo Form::select(array(
                                                'name' => 'currencyId',
                                                'id' => 'currencyId',
                                                'class' => 'form-control select form-control-sm',
                                                'data' => $this->currencyList,
                                                'op_value' => 'CURRENCY_ID',
                                                'op_text' => 'CURRENCY_CODE| |-| |CURRENCY_NAME',
                                                'data-placeholder' => 'Валют сонгох',
                                                'required' => 'required'
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                 </div>    
                                
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text' => Lang::lineDefault('FIN_01168', 'Тайлбар'), 'for' => 'cashgldescription', 'class' => 'col-form-label col-md-3 col-sm-6')); ?>
                                        <div class="col-md-9">
                                            <?php
                                            echo Form::textArea(array(
                                                'name' => 'cashgldescription',
                                                'id' => 'cashgldescription',
                                                'class' => 'form-control select form-control-sm'
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                 </div>     
                            </div>
                            <div class="row">
                                 <div class="col-md-5">
                                    <div class="form-group row">
                                        <?php echo Form::label(array('text' => Lang::lineDefault('FIN_01542', 'Шинэ ханш'), 'for' => 'rate', 'class' => 'col-form-label col-md-3 col-sm-6 col-xs-12 cashrate-label', 'required' => 'required')); ?>
                                        <div class="col-sm-6">
                                            <?php echo Form::text(array('name' => 'hdrRate', 'id' => 'hdrRate', 'class' => 'form-control numberInit form-control-sm', 'required' => 'required')); ?>
                                        </div>
                                    </div>
                                 </div>
                                 <div class="col-md-7">
                                    <div class="float-right">
                                        <?php echo Form::button(array('class' => 'btn btn-sm blue-madison btn-search', 'value' => '<i class="fa fa-search"></i> '.Lang::line('btn_search'), 'onclick' => 'calcSearchCashRate()', 'disabled' => true)); ?>
                                        <?php echo Form::button(array('class' => 'btn btn-sm green-meadow btn-rate-calc', 'value' => '<i class="fa fa-bars"></i> '.Lang::lineDefault('fin_3345323234234', 'Ханш тэгшитгэх'), 'onclick' => 'calcCurrencyCashRate()', 'disabled' => true)); ?>
                                    </div>
                                 </div>    
                            </div>
                        </fieldset>
                    </div>
                </div>    
                <div class="row">    
                    <div class="col-md-12">
                        
                        <div class="card-accordion card-group-control card-group-control-left" id="accordion1">
                            <div class="card">
                                <div class="card-header card-header-no-padding bg-primary">
                                    <h6 class="card-title">
                                        <a data-toggle="collapse" class="text-white" href="#accordion-styled-group1"><?php echo Lang::lineDefault('FIN_1011', 'Мөнгөн хөрөнгийн дансны жагсаалт'); ?></a>
                                    </h6>
                                </div>
                                <div id="accordion-styled-group1" class="collapse show">
                                    <div class="card-body">
                                        <table class="table table-sm table-bordered table-hover" id="cashRateGrid" cellspacing="0" width="100%">
                                            <thead>
                                                <tr style='width: 100%;'>
                                                    <th style='width: 5%;' class="trNumber" rowspan="2">№</th>
                                                    <th style='width: 10%;' rowspan="2"><?php echo Lang::lineDefault('FIN_1012', 'Нярав/данс код'); ?></th>
                                                    <th style='width: 20%;' rowspan="2"><?php echo Lang::lineDefault('FIN_1013', 'Нярав/данс нэр'); ?></th>
                                                    <th rowspan="2">Дансны код</th>
                                                    <th rowspan="2">Дансны нэр</th>
                                                    <th style='width: 15%;' rowspan="2"><?php echo Lang::lineDefault('FIN_1014', 'Сүүлд тэгшитгэсэн ханш'); ?></th>
                                                    <th style='width: 10%;' rowspan="2"><?php echo Lang::lineDefault('FIN_00936', 'Үлдэгдэл /валют/'); ?></th>
                                                    <th style='width: 10%;' rowspan="2"><?php echo Lang::lineDefault('FIN_1015', 'Үлдэгдэл /төгрөг/'); ?></th>
                                                    <th style='width: 10%;' rowspan="2"><?php echo Lang::lineDefault('FIN_1016', 'Тэгшитгэл төгрөг'); ?></th>
                                                    <th class="text-center" colspan="2"><?php echo Lang::lineDefault('PL_20256', 'Зөрүү'); ?></th>
                                                    <th class="equalise text-center" rowspan="2">
                                                        <div class="mb5"><?php echo Lang::lineDefault('FIN_00882', 'Тэгшитгэх эсэх'); ?></div>
                                                        <input type="checkbox" name="equaliseAllCheck" class="equalise" value="0" onclick="equaliseAll(this, '#cashRateGrid');">
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th><?php echo Lang::lineDefault('FIN_00505', 'Ашиг'); ?></th>
                                                    <th><?php echo Lang::lineDefault('FIN_00506', 'Алдагдал'); ?></th>
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
                                                    <td class="SUM text-right"><span id="footBaseAmount" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'footBaseAmount')); ?></td>
                                                    <td class="SUM text-right"><span id="footAmount" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'footAmount')); ?></td>
                                                    <td class="SUM text-right"><span id="footEquation" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'footEquation')); ?></td>
                                                    <td class="SUM text-right"><span id="footVarianceDebit" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'footVarianceDebit')); ?></td>
                                                    <td class="SUM text-right"><span id="footVarianceCredit" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'footVarianceCredit')); ?></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table> 
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header card-header-no-padding bg-primary">
                                    <h6 class="card-title">
                                        <a class="collapsed text-white" data-toggle="collapse" href="#accordion-styled-group2"><?php echo Lang::lineDefault('FIN_1017', 'Тооцооны дансны жагсаалт'); ?></a>
                                    </h6>
                                </div>
                                <div id="accordion-styled-group2" class="collapse show">
                                    <div class="card-body">
                                        <table class="table table-sm table-bordered table-hover" id="billRateGrid" cellspacing="0" width="100%">
                                            <thead>
                                                <tr style='width: 100%;'>
                                                    <th style='width: 5%;' class="trNumber" rowspan="2">№</th>
                                                    <th style='width: 10%;' rowspan="2"><?php echo Lang::lineDefault('MET_330575', 'Дансны код'); ?></th>
                                                    <th style='width: 20%;' rowspan="2"><?php echo Lang::lineDefault('PL_20104', 'Дансны нэр'); ?></th>
                                                    <th rowspan="2">Харилцагчийн код</th>
                                                    <th rowspan="2">Харилцагчийн нэр</th>
                                                    <th rowspan="2" style='width: 15%;'><?php echo Lang::lineDefault('FIN_1014', 'Сүүлд тэгшитгэсэн ханш'); ?></th>
                                                    <th style='width: 10%;' rowspan="2"><?php echo Lang::lineDefault('FIN_00936', 'Үлдэгдэл /валют/'); ?></th>
                                                    <th style='width: 10%;' rowspan="2"><?php echo Lang::lineDefault('FIN_1015', 'Үлдэгдэл /төгрөг/'); ?></th>
                                                    <th style='width: 10%;' rowspan="2"><?php echo Lang::lineDefault('FIN_1016', 'Тэгшитгэл төгрөг'); ?></th>
                                                    <th class="text-center" colspan="2"><?php echo Lang::lineDefault('PL_20256', 'Зөрүү'); ?></th>
                                                    <th style='width: 5%;' class="equalise text-center" rowspan="2">
                                                        <div class="mb5"><?php echo Lang::lineDefault('FIN_00882', 'Тэгшитгэх эсэх'); ?></div>
                                                        <input type="checkbox" name="equaliseAllCheck" class="equalise" value="0" onclick="equaliseAll(this, '#billRateGrid');">
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th><?php echo Lang::lineDefault('FIN_00505', 'Ашиг'); ?></th>
                                                    <th><?php echo Lang::lineDefault('FIN_00506', 'Алдагдал'); ?></th>
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
                                                    <td class="SUM text-right"><span id="billfootBaseAmount" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'billfootBaseAmount')); ?></td>
                                                    <td class="SUM text-right"><span id="billfootAmount" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'billfootAmount')); ?></td>
                                                    <td class="SUM text-right"><span id="billfootEquation" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'billfootEquation')); ?></td>
                                                    <td class="SUM text-right"><span id="billfootVarianceDebit" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'billfootVarianceDebit')); ?></td>
                                                    <td class="SUM text-right"><span id="billfootVarianceCredit" class="numberInit">0</span><?php echo Form::hidden(array('name' => 'billfootVarianceCredit')); ?></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>                              
            </div>
        </form>    
        <div class="form-actions">
            <div class="row">
                <div class="col-md-12 text-right">
                    <?php 
                    echo Mdcommon::redirectHelpContentButton('FIN_CASH_RATE_HELP_CONTENT');
                    echo Form::button(array('class' => 'btn btn-circle green-meadow bp-btn-save saveCashrate', 'value' => '<i class="icon-checkmark-circle2"></i> ' . $this->lang->line('save_btn'))); 
                    echo Form::button(array('class' => 'btn btn-circle blue-madison cancelCashrate', 'value' => $this->lang->line('cancel_btn'))); 
                    ?>
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
} else {
?>      
</div>
<?php
}
?>

<style type="text/css">
    .cashrate-label {
        
        font-weight: 400;
        font-size: 14px;
        color: #5e5e5e;
        padding-top: 0 !important;
    }
    .panel-title {
        
        font-size: 14px;
        color: #5e5e5e;
        font-weight: bold;
    }
    .xs-form .dataTables_wrapper table.dataTable thead th {
        height: 25px;
    }    
/*    .dataTables_wrapper {
        clear: both;
        overflow-y: scroll;
        overflow: auto;
        min-height: 100px;
        max-height: 300px;
        position: relative;
    }*/
</style>
<script type="text/javascript">
    var CashRateDtlTable;
    var BillRateDtlTable;
    var cashRateWindowId = "#cashRate";
    var cashRateTableId = "#cashRateGrid";
    var billRateTableId = "#billRateGrid";
    var keeperData = '<?php echo $this->keeperData; ?>';
    
    $(function () {

        CashRateDtlTable = $("#cashRateGrid", cashRateWindowId).dataTable({
            scrollY: 400,
            scrollX: true,
            scrollXInner: "100%",
            scrollCollapse: false,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: false,
            responsive : true,
            language: {
                "emptyTable": "No data"
            },
            "columnDefs": [
                {
                "searchable": false,
                "orderable": false,
                "visible":false,
                "targets": [3, 4]
                },
                {
                    targets: 9,
                    width: '150px'
                },
                {
                    targets: 10,
                    width: '150px'
                },
                {
                    targets: 11,
                    width: '15px'
                }
            ],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({page:'current'}).nodes();
                var last= null;
                api.column(3, {page:'current'}).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        var accountName = api.columns(4).data()[0][i];                        
                        $(rows).eq(i).before(
                            '<tr class="group" style="width:100%;"><td colspan="3" style="font-weight:bold; text-overflow: ellipse; width:100px;">'+group+" - "+accountName+'</td>\n\
                             <td class="bigdecimalInit" id="grouprate" style="font-weight:bold; font-size:11px;"></td>\n\
                             <td class="stretchInput"><input type="text" id="groupamountbase" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="stretchInput"><input type="text" id="groupamount" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="stretchInput"><input type="text" id="groupequation" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="stretchInput"><input type="text" id="groupvariancedebit" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="stretchInput"><input type="text" id="groupvariancecredit" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="text-center"><div class="checker"><span><input type="checkbox" name="equalise[]" class="equalise" onclick="equaliseItem(this, \'#cashRateGrid\')"></span></div></td>\n\
                            </tr>'
                        );
                        last = group;
                    }
                });
                Core.initInputType($(cashRateWindowId));
            }
        });
        
        BillRateDtlTable = $("#billRateGrid", cashRateWindowId).dataTable({
            scrollY: 400,
            scrollX: true,
            scrollXInner: "100%",
            scrollCollapse: false,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            autoWidth: false,
            language: {
                "emptyTable": "No data"
            },
            "columnDefs": [
                {
                "searchable": false,
                "orderable": false,
                "visible":false,
                "targets": [3, 4] 
                },
                {
                    targets: 9,
                    width: '150px'
                },
                {
                    targets: 10,
                    width: '150px'
                },
                {
                    targets: 11,
                    width: '15px'
                }                
            ],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({page:'current'}).nodes();
                var last= null;
                api.column(3, {page:'current'}).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        var accountName = api.columns(4).data()[0][i];                        
                        $(rows).eq(i).before(
                            '<tr class="group" style="width:100%;"><td colspan="3" style="font-weight:bold; text-overflow: ellipsis; width:100px;">'+group+" - "+accountName+'</td>\n\
                             <td class="bigdecimalInit" id="grouprate" style="font-weight:bold; font-size:11px;"></td>\n\
                             <td class="stretchInput"><input type="text" id="groupamountbase" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="stretchInput"><input type="text" id="groupamount" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="stretchInput"><input type="text" id="groupequation" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="stretchInput"><input type="text" id="groupvariancedebit" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="stretchInput"><input type="text" id="groupvariancecredit" class="form-control bigdecimalInit text-right" data-m-dec="2" value="0" style="font-weight:bold; font-size:11px;"/></td>\n\
                             <td class="text-center"><div class="checker"><span><input type="checkbox" name="equalise[]" class="equalise" onclick="equaliseItem(this, \'#billRateGrid\')"></span></div></td>\n\
                            </tr>'
                        );
                        last = group;
                    }
                });
                Core.initInputType($(cashRateWindowId));
            }
        });
        
        $('.collapse').on('shown.bs.collapse', function(){
            $(this).parent().find(".fa-chevron-right").removeClass("fa-chevron-right").addClass("fa-chevron-down");
            
            CashRateDtlTable.fnAdjustColumnSizing();
            BillRateDtlTable.fnAdjustColumnSizing();
            
        }).on('hidden.bs.collapse', function(){
            $(this).parent().find(".fa-chevron-down").removeClass("fa-chevron-down").addClass("fa-chevron-right");
        });
        
        $(window).bind('resize', function () {
            CashRateDtlTable.fnAdjustColumnSizing();
            BillRateDtlTable.fnAdjustColumnSizing();
            calcGroupTotal();
        });
        
        $(cashRateWindowId).on('change', '#currencyId, #currencyRateDate', function() {
            CashRateDtlTable.fnClearTable();
            BillRateDtlTable.fnClearTable();
            
            if ($('#currencyId', cashRateWindowId).val() != '') {
                var _this = $(this);
                
                $.ajax({
                    type: 'post',
                    url: 'mdgl/getOneCurrency',
                    data: {
                        currencyId: _this.closest('form').find('#currencyId').val(),
                        rateDate: _this.closest('form').find('#currencyRateDate').val()
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },            
                    success: function (data) {
                        if (data.status === 'success') {
                            $('#hdrRate', cashRateWindowId).autoNumeric('set', parseFloat(data.rate));
                        } else {
                            PNotify.removeAll();
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
                
                $('.btn-search', cashRateWindowId).attr('disabled', false);
            } else {
                $('.btn-search', cashRateWindowId).attr('disabled', true); 
            }
        });    
        
        $(cashRateWindowId).on('keydown', 'input[name="hdrRate"]', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);

            if (code === 13) {
                return false;
            }
        });
        $(cashRateWindowId).on('keydown', 'input[name="currencyRateDate"]', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);

            if (code === 13) {
                return false;
            }
        });
        $('table#cashRateGrid  tbody', cashRateWindowId).on('click', 'tr.group', function(e) {
            if(e.target.className === "equalise")
                return;
            
            var row = $(this);
            var accountcode = row.find('td:first').find('input[name="groupAccountCode[]"]').val();
            $('table#cashRateGrid > tbody > tr', cashRateWindowId).each(function () {
                var _thisRow = $(this);
                if(_thisRow.find('input[name="accountCode[]"]').val() == accountcode){
                    if(_thisRow.hasClass("hide")){
                        _thisRow.removeClass("hide");
                    }else{
                       _thisRow.addClass("hide"); 
                    }
                }
            });
        });
        $('table#billRateGrid  tbody', cashRateWindowId).on('click', 'tr.group', function(e) {
            if(e.target.className === "equalise")
                return;
            
            var row = $(this);
            var accountcode = row.find('td:first').find('input[name="groupAccountCode[]"]').val();
            $('table#billRateGrid > tbody > tr', cashRateWindowId).each(function () {
                var _thisRow = $(this);
                if(_thisRow.find('input[name="accountCode[]"]').val() == accountcode){
                    if(_thisRow.hasClass("hide")){
                        _thisRow.removeClass("hide");
                    }else{
                       _thisRow.addClass("hide"); 
                    }
                }
            });
        });
        $(".saveCashrate", cashRateWindowId).on("click", function () {
            if ($("#glTemplateSectionStatic").length > 0) {
                saveCashRate();
            } else {
                new PNotify({
                    title: 'Анхааруулга',
                    text: 'Ханшийн тэгшитгэл хийгдээгүй байна',
                    type: 'error',
                    sticker: false
                });
            }
        });
        $('.cancelCashrate', cashRateWindowId).on('click', function () {
            clearForm();
        });    
        
        $(cashRateWindowId).on('change', 'input[name="isCheck[]"]',  function () {
            if($(this).is(':checked')) {
                $(this).val('1');
            } else
                $(this).val('');
        });
    });
    function setRate(currencyId, rateDate) {
        $('#totalCurrency').autoNumeric('set', 0);
        $('input[name="totalCurrency"]').val('');
        $('#footAmount').autoNumeric('set', 0);
        $('input[name="footAmount"]').val('');
        $('#footVarianceCredit').autoNumeric('set', 0);
        $('input[name="footVarianceCredit"]').val('');
        $('#totalLoss').autoNumeric('set', 0);
        $('input[name="totalLoss"]').val('');
        $('#totalDebit').autoNumeric('set', 0);
        $('input[name="totalDebit"]').val('');
        $('#totalCredit').autoNumeric('set', 0);
        $('input[name="totalCredit"]').val('');

        $('#glDebit').autoNumeric('set', 0);
        $('#glCredit').autoNumeric('set', 0);

        $('.btn-search', cashRateWindowId).attr('disabled', false);        
        $("#hdrRate", cashRateWindowId).val('');
        
        $.ajax({
            type: 'post',
            url: 'mdgl/getOneCurrency',
            data: {currencyId: currencyId, rateDate: rateDate},
            dataType: 'json',
            success: function (res) {
                PNotify.removeAll();
                $("#hdrRate", cashRateWindowId).val('');

                if (res.status === 'success') {
                    
                    $('#totalCurrency').autoNumeric('set', 0);
                    $('input[name="totalCurrency"]').val('');
                    $('#footAmount').autoNumeric('set', 0);
                    $('input[name="footAmount"]').val('');
                    $('#footVarianceCredit').autoNumeric('set', 0);
                    $('input[name="footVarianceCredit"]').val('');
                    $('#totalLoss').autoNumeric('set', 0);
                    $('input[name="totalLoss"]').val('');
                    $('#totalDebit').autoNumeric('set', 0);
                    $('input[name="totalDebit"]').val('');
                    $('#totalCredit').autoNumeric('set', 0);
                    $('input[name="totalCredit"]').val('');
                    
                    $('#glDebit').autoNumeric('set', 0);
                    $('#glCredit').autoNumeric('set', 0);
                    
                    $('.btn-search', cashRateWindowId).attr('disabled', false);
                    $('#hdrRate', cashRateWindowId).autoNumeric('set', parseFloat(res.rate));
                } else {
                    $('.btn-search', cashRateWindowId).attr('disabled', true);
                    new PNotify({
                        title: 'Анхааруулга',
                        text: res.message,
                        type: 'error',
                        sticker: false
                    });
                }
            }
        });
    }
    function equaliseItem(elem, tableId) {
        if($("#glTemplateSectionStatic").length > 0){
            $("#glTemplateSectionStatic").remove();
        }
        var equalAll = true;
        var isChecked = false;
        var groupId = $(elem).closest('tr').find('input[name="groupAccountCode[]"]').val();
        
        if ($(elem).is(':checked')) {
//            var checkedBalance = checkBalance(tableId, groupId);
//            if(checkedBalance){
                $(elem).attr('checked', true);
                $(elem).parent().addClass('checked');
                isChecked = true;
//            }else{
//                alert("Хасах үлдэгдэлтэй дүн байна");
//                $(elem).attr('checked', false);
//            }
        } else {
            $(elem).attr('checked', false);
            $(elem).parent().removeClass('checked');
        }
        
        if (isChecked) {
            $(tableId+' tbody > tr').each(function() {
                var _thisRow = $(this);
                if(_thisRow.find('input[name="accountCode[]"]').val() == groupId){
                        _thisRow.find("input[name='isCheck[]']").val('1'); 
                        _thisRow.find("input[name='isCheck[]']").attr('checked', true);
                        _thisRow.find("input[name='isCheck[]']").parent().addClass('checked');                        
                }
            });
        } else{
            $(tableId+' tbody > tr').each(function() {
                var _thisRow = $(this);
                if(_thisRow.find('input[name="accountCode[]"]').val() == groupId){
                        _thisRow.find("input[name='isCheck[]']").val(''); 
                        _thisRow.find("input[name='isCheck[]']").attr('checked', false);
                        _thisRow.find("input[name='isCheck[]']").parent().removeClass('checked');                   
                }
            });
        }
        
        $('input[name="equalise[]"]', tableId).each(function () {
            if ($(this).is(':checked') === false) {
                equalAll = false;
            }
        });
        
        if (equalAll) {
            $(tableId+"_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").attr('checked', true);
            $(tableId+"_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").parent().addClass('checked');
        } else {
            $(tableId+"_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").attr('checked', false);
            $(tableId+"_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").parent().removeClass('checked');
        }
    }
    function equaliseAll(elem, tableId) {    
        if($("#glTemplateSectionStatic").length > 0){
            $("#glTemplateSectionStatic").remove();
        }
        if ($(elem).is(':checked')) {
            var equalAll = true;
            
            if(equalAll){
                $('input[name="isCheck[]"]', tableId).each(function (index) {   
                    $(this).val('1');
                    $(this).attr('checked', true);
                    $(this).parent().addClass('checked');                    
                });
                $('.equalise', tableId).attr('checked', true);
                $('.equalise', tableId).parent().addClass('checked');
            }else{
                $(tableId+"_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").attr('checked', false);
                $(tableId+"_wrapper")
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollHead")
                    .find(".dataTables_scrollHeadInner")
                    .find("table.dataTable thead")
                    .find("input[name=equaliseAllCheck]").parent().removeClass('checked');
            }
        } else {
            $('.equalise', tableId).attr('checked', false);
            $('.equalise', tableId).parent().removeClass('checked');
            $('input[name="isCheck[]"]', tableId).each(function () {
                $(this).val('');
                $(this).attr('checked', false);
                $(this).parent().removeClass('checked');                 
            });
        }
    }
    function calcCurrencyCashRate() {
        var ratedAccountRow = [];
        if ($('#hdrRate', cashRateWindowId).val().length > 0) {
            
            setVariaceAmount();
            var isCheck = 0;    
            var isBalancedAmount = true;
            var hdrRate = $("#hdrRate", cashRateWindowId).autoNumeric('get');
            
            $('#cashRateGrid > tbody > tr', cashRateWindowId).each(function(){
                var $this = $(this);
                
                if ($this.find('input[name="isCheck[]"]').val() == '1') {
                    
                    isCheck = 1;
                    var amount = $this.find('input[name="baseamount[]"]').autoNumeric('get');
                    if (amount < -0.05) {
                        isBalancedAmount = false;
                    }
                    
                    var selectedRow = {
                        'accountId' : $this.find('input[name="accountId[]"]').val(),
                        'customerId' : $this.find('input[name="customerId[]"]').val(),
                        'keyId' :  $this.find('input[name="keyId[]"]').val(),
                        'rate' :  hdrRate,
                        'debitAmount' : $this.find('input[name="varianceDebit[]"]').val(),
                        'creditAmount' : $this.find('input[name="varianceCredit[]"]').val()
                    };
                    ratedAccountRow.push(selectedRow);
                }
            });
            
            $('#billRateGrid > tbody > tr', cashRateWindowId).each(function(){
                var $this = $(this);
                
                if ($this.find('input[name="isCheck[]"]').val() == '1') {
                    
                    isCheck = 1;
                    var amount = $this.find('input[name="baseamount[]"]').autoNumeric('get');
                    
                    if (amount < -0.05) {
                        isBalancedAmount = false;
                    }
                    
                    var selectedRow = {
                        'accountId' : $this.find('input[name="accountId[]"]').val(),
                        'customerId' : $this.find('input[name="customerId[]"]').val(),
                        'keyId' :  $this.find('input[name="keyId[]"]').val(),
                        'rate' :  hdrRate,
                        'debitAmount' : $this.find('input[name="varianceDebit[]"]').val(),
                        'creditAmount' : $this.find('input[name="varianceCredit[]"]').val()
                    };
                    ratedAccountRow.push(selectedRow);
                }
            });
            
            if (isCheck === 0) {
                
                new PNotify({
                    title: 'Анхааруулга',
                    text: 'Ханш тэгшитгэх дансаа сонгоно уу',
                    type: 'warning',
                    sticker: false
                });
                
            } else {
                
                //if (isBalancedAmount) {
                    $.ajax({
                        type: 'post',
                        url: 'mdgl/getCurrencyRatedRow',
                        data: {
                            ratedRow: ratedAccountRow, 
                            bookdate: $('#currencyRateDate', cashRateWindowId).val(), 
                            cashgldescription: $('#cashgldescription', cashRateWindowId).val(), 
                            bpTabLength: 0, 
                            /*isCashFlowSubCategoryId: 1,*/
                            isNotAddAccount: 1
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({
                                message: 'Loading...',
                                boxed: true
                            });
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                if (data.Html != '') {
                                    if ($("#glTemplateSectionStatic").length > 0) {
                                        $("#glTemplateSectionStatic").remove();
                                    }
                                    $('#accordion1').after(data.Html); 
                                    //$("table#glDtl > tbody > tr").find('input[name="gl_rate_currency[]"]').val("MNT");
                                    $("table#glDtl").find("thead#header2").addClass("hide").hide();
                                    $("table#glDtl").find("thead#header1").show();
                                    $("table#glDtl").find("th[data-usebase='usebase'], td[data-usebase='usebase']").hide();
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
                /*} else {
                    alert("Хасах үлдэгдэлтэй дүн байна");
                }*/
            }
        } else {
            new PNotify({
                title: 'Анхааруулга',
                text: 'Ханш сонгоогүй байна',
                type: 'error',
                sticker: false
            });
        }
    }
    function alertRate() {
        var footProfit = $('input[name="footVarianceCredit"]', cashRateWindowId).val();
        var footLoss = $('input[name="totalLoss"]', cashRateWindowId).val();
        var footDebit = $('input[name="hdrCredit"]', cashRateWindowId).val();
        var footCredit = $('input[name="hdrDebit"]', cashRateWindowId).val();
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
        var footAmount = 0, footBaseAmount = 0, footVarianceDebit = 0, footEquation = 0, footVarianceCredit = 0;
        var bfootAmount = 0, bfootBaseAmount = 0, bfootVarianceDebit = 0, bfootEquation = 0, bfootVarianceCredit = 0;
        
        $('#cashRateGrid > tbody > tr', cashRateWindowId).each(function () {
            var rowAmount = $(this).find('input[name="amount[]"]', cashRateWindowId).autoNumeric('get');
            var baseamount = $(this).find('input[name="baseamount[]"]', cashRateWindowId).autoNumeric('get');
            var rowvarianceDebit = $(this).find('input[name="varianceDebit[]"]', cashRateWindowId).val();
            var rowequation = $(this).find('input[name="equation[]"]', cashRateWindowId).autoNumeric('get');
            var rowVarianceCredit = $(this).find('input[name="varianceCredit[]"]', cashRateWindowId).val();
            
            if (typeof rowAmount != 'undefined'){
                footAmount = parseFloat(footAmount) + parseFloat(rowAmount);
                footBaseAmount = parseFloat(footBaseAmount) + parseFloat(baseamount);
                footVarianceDebit = parseFloat(footVarianceDebit) + parseFloat(rowvarianceDebit);
                footEquation = parseFloat(footEquation) + parseFloat(rowequation);
                footVarianceCredit = parseFloat(footVarianceCredit) + parseFloat(rowVarianceCredit);
            }   
        });
        $('#billRateGrid > tbody > tr', cashRateWindowId).each(function () {
            var rowAmount = $(this).find('input[name="amount[]"]', cashRateWindowId).autoNumeric('get');
            var baseamount = $(this).find('input[name="baseamount[]"]', cashRateWindowId).autoNumeric('get');
            var rowvarianceDebit = $(this).find('input[name="varianceDebit[]"]', cashRateWindowId).val();
            var rowequation = $(this).find('input[name="equation[]"]', cashRateWindowId).autoNumeric('get');
            var rowVarianceCredit = $(this).find('input[name="varianceCredit[]"]', cashRateWindowId).val();
            
            if (typeof rowAmount != 'undefined'){
                bfootAmount = parseFloat(bfootAmount) + parseFloat(rowAmount);
                bfootBaseAmount = parseFloat(bfootBaseAmount) + parseFloat(baseamount);
                bfootVarianceDebit = parseFloat(bfootVarianceDebit) + parseFloat(rowvarianceDebit);
                bfootEquation = parseFloat(bfootEquation) + parseFloat(rowequation);
                bfootVarianceCredit = parseFloat(bfootVarianceCredit) + parseFloat(rowVarianceCredit);
            }   
        });

        $('#footAmount', cashRateWindowId).autoNumeric('set', footAmount);
        $('input[name="footAmount"]', cashRateWindowId).val(footAmount);
        $('#footBaseAmount', cashRateWindowId).autoNumeric('set', footBaseAmount);
        $('input[name="footBaseAmount"]', cashRateWindowId).val(footBaseAmount);
        $('#footVarianceDebit', cashRateWindowId).autoNumeric('set', footVarianceDebit);
        $('input[name="footVarianceDebit"]', cashRateWindowId).val(footVarianceDebit);
        $('#footEquation', cashRateWindowId).autoNumeric('set', footEquation);
        $('input[name="footEquation"]', cashRateWindowId).val(footEquation);
        $('#footVarianceCredit', cashRateWindowId).autoNumeric('set', footVarianceCredit);
        $('input[name="footVarianceCredit"]', cashRateWindowId).val(footVarianceCredit);
        $('#billfootAmount', cashRateWindowId).autoNumeric('set', bfootAmount);
        $('input[name="billfootAmount"]', cashRateWindowId).val(bfootAmount);
        $('#billfootBaseAmount', cashRateWindowId).autoNumeric('set', bfootBaseAmount);
        $('input[name="billfootBaseAmount"]', cashRateWindowId).val(bfootBaseAmount);
        $('#billfootVarianceDebit', cashRateWindowId).autoNumeric('set', bfootVarianceDebit);
        $('input[name="billfootVarianceDebit"]', cashRateWindowId).val(bfootVarianceDebit);
        $('#billfootEquation', cashRateWindowId).autoNumeric('set', bfootEquation);
        $('input[name="billfootEquation"]', cashRateWindowId).val(bfootEquation);
        $('#billfootVarianceCredit', cashRateWindowId).autoNumeric('set', bfootVarianceCredit);
        $('input[name="billfootVarianceCredit"]', cashRateWindowId).val(bfootVarianceCredit);
    }
    function calcSearchCashRate() {
        Core.blockUI({
            boxed : true,
            message: 'Уншиж байна...'
        });        
        $.ajax({
            type: 'POST',
            url: 'mdgl/cashSorting',
            data: {
                metaDataId: keeperData, 
                defaultCriteriaData: 'param[filterDepartmentId]=' + $("#filterDepartmentId_valueField", cashRateWindowId).val() + '&param[currencyid]=' + $("#currencyId", cashRateWindowId).val() + '&param[bookdate]=' + $("#currencyRateDate", cashRateWindowId).val()
            },
            dataType: 'json',
            success: function (data) {
                if (data.status === 'error') {
                    $('.btn-rate-calc').attr('disabled', true);
                    new PNotify({
                        title: 'Анхааруулга',
                        text: data.message,
                        type: 'error',
                        sticker: false
                    });
                } else {
                    $('input[name="equaliseAllCheck"]').attr('checked', false);
                    $('input[name="equaliseAllCheck"]').parent().removeClass('checked');
                    $('input[name="equaliseAllCheck"]').attr('checked', false);
                    $('input[name="equaliseAllCheck"]').parent().removeClass('checked');

                    CashRateDtlTable.fnDeleteRow();
                    BillRateDtlTable.fnDeleteRow();
                    if (data.rows.length > 0) {
                        $('.btn-rate-calc').attr('disabled', false);
                        var billarr = [];
                        var casharr = [];
//                        var j = 0, k=0;
                        $.each(data.rows, function (i, val) {
                            if(val.objectid == '20003' || val.objectid == '20004'){
                                //k=k+1;
                                casharr.push([
                                    '',
                                    val.storekeepercode + '<input type="hidden" name="keyId[]" value="' + val.keyid + '"><input type="hidden" name="accountId[]" value="' + val.accountid + '"><input type="hidden" name="accountCode[]" value="' + val.accountcode + '"><input type="hidden" name="customerId[]" value="' + val.customerid + '">',
                                    val.keepername,
                                    val.accountcode + '<input type="hidden" name="groupAccountCode[]" value="' + val.accountcode + '">',
                                    val.accountname,
                                    '<input type="hidden" name="rate[]" class="form-control" value="' + (val.actiondate ? val.actiondate : '') + '" readonly="true">',
                                    '<input type="text" name="baseamount[]" class="form-control numberInit" value="' + val.endamountbase + '" readonly="true" data-m-dec="2">',
                                    '<input type="text" name="amount[]" class="form-control numberInit" value="' + val.endamount + '" readonly="true" data-m-dec="2">',
                                    '<input type="text" name="equation[]" class="form-control numberInit" value="0" readonly="true" data-m-dec="2">',
                                    '<input type="text" name="varianceDebitDisplay[]" class="form-control numberInit" value="0" readonly="true" data-m-dec="2"><input type="hidden" name="varianceDebit[]" class="" value="">',
                                    '<input type="text" name="varianceCreditDisplay[]" class="form-control numberInit" value="0" readonly="true" data-m-dec="2"><input type="hidden" name="varianceCredit[]" class="" value=""><input type="hidden" name="balanceTypeId[]" class="" value="' + val.balancetypeid + '">',
                                    '<input type="checkbox" name="isCheck[]">'
                                ]);
                            } else if(val.objectid == '20006' || val.objectid == '20007'){
                                //j=j+1;
                                billarr.push([
                                    '',                               
                                    val.customercode + '<input type="hidden" name="keyId[]" value="' + val.keyid + '"><input type="hidden" name="accountId[]" value="' + val.accountid + '"><input type="hidden" name="accountCode[]" value="' + val.accountcode + '"><input type="hidden" name="customerId[]" value="' + val.customerid + '">',
                                    val.customername,
                                    val.accountcode + '<input type="hidden" name="groupAccountCode[]" value="' + val.accountcode + '">',
                                    val.accountname,
                                    '<input type="hidden" name="rate[]" class="form-control" value="' + (val.actiondate ? val.actiondate : '') + '" readonly="true">',
                                    '<input type="text" name="baseamount[]" class="form-control numberInit" value="' + val.endamountbase + '" readonly="true" data-m-dec="2">',
                                    '<input type="text" name="amount[]" class="form-control numberInit" value="' + val.endamount + '" readonly="true" data-m-dec="2">',
                                    '<input type="text" name="equation[]" class="form-control numberInit" value="0" readonly="true" data-m-dec="2">',
                                    '<input type="text" name="varianceDebitDisplay[]" class="form-control numberInit" value="0" readonly="true" data-m-dec="2"><input type="hidden" name="varianceDebit[]" class="" value="">',
                                    '<input type="text" name="varianceCreditDisplay[]" class="form-control numberInit" value="0" readonly="true" data-m-dec="2"><input type="hidden" name="varianceCredit[]" class="" value=""><input type="hidden" name="balanceTypeId[]" class="" value="' + val.balancetypeid + '">',
                                    '<input type="checkbox" name="isCheck[]">'
                                ]);
                            }
                        });
                        if (casharr.length > 0) {
                            CashRateDtlTable.fnAddData(casharr);
                            CashRateDtlTable.fnAdjustColumnSizing();
                        }
                        if (billarr.length > 0) {
                            BillRateDtlTable.fnAddData(billarr);
                            BillRateDtlTable.fnAdjustColumnSizing();
                        }
                    }
                }
                
                Core.unblockUI();
            }
        }).done(function(data){
            $(cashRateWindowId)
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollBody")
                    .find("table.dataTable tbody")
                    .find("input[type=text]").parent("td").addClass("stretchInput");
            $(cashRateWindowId)
                    .find(".dataTables_scroll")
                    .find(".dataTables_scrollBody")
                    .find("table.dataTable tbody")
                    .find("input[type=checkbox]").closest("td").addClass("text-center");
            $(cashRateWindowId).find(".dataTables_scrollFoot")
                    .find("table tfoot tr").find("td.SUM")
                    .find("span").autoNumeric('init', {aPad: false, mDec: 2, vMin: '-99999999999999999999.99', vMax: '99999999999999999999.99'});
            Core.initInputType($(cashRateWindowId));  
            collapseAccounts();
            rowNumeringByGroup();
            calcFoot();             
            calcGroupTotal();
            if ($("#glTemplateSectionStatic").length > 0) {
                $("#glTemplateSectionStatic").remove();
            }
        });
    }
    function saveCashRate() {
        PNotify.removeAll();
        $("#saveCashrate-form").validate({errorPlacement: function() {}});
        if ($("#saveCashrate-form").valid()) {
            var validGl = validateGlBook($('#glTemplateSectionStatic', "#saveCashrate-form"));
            if (validGl.status == 'success') {
                Core.blockUI({
                    boxed: true, 
                    message: 'Loading...'
                });
                $("#saveCashrate-form").ajaxSubmit({
                    type: 'post',
                    url: 'mdgl/saveCashRate',
                    dataType: 'json',
                    success: function(data) {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        if (data.status === 'success') {
                            clearForm();
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
    function calcGroupTotal(){
        var j = 0, k = 0;
        $('table#cashRateGrid >  tbody > tr', cashRateWindowId).each(function () {
            if ($(this).hasClass("group")) {
                calcGroupTotalByAccount($(this).find('input[name="groupAccountCode[]"]').val(), j, '#cashRateGrid');
            }
            j = j+1;
        });
        $('table#billRateGrid > tbody > tr', cashRateWindowId).each(function () {
            if ($(this).hasClass("group")) {
                calcGroupTotalByAccount($(this).find('input[name="groupAccountCode[]"]').val(), k, '#billRateGrid');
            }
            k = k+1;
        });
    }
    function calcGroupTotalByAccount(accountcode, groupIndex, tableId){
        var groupamount = 0;
        var groupamountbase = 0;
        var groupequation = 0;
        var groupvariancedebit = 0;
        var groupvariancecredit = 0;
        var grouprate = '';
        $('table'+tableId+' > tbody', cashRateWindowId).find("tr:not(.group)").each(function () {
            var _thisRow = $(this);
            if (_thisRow.find('input[name="accountCode[]"]').val() == accountcode) { 
                groupamount = groupamount + parseFloat(_thisRow.find('input[name="amount[]"]').autoNumeric('get'));
                groupamountbase = groupamountbase + parseFloat(_thisRow.find('input[name="baseamount[]"]').autoNumeric('get'));
                groupvariancedebit = groupvariancedebit + parseFloat(_thisRow.find('input[name="varianceDebit[]"]').val());
                groupvariancecredit = groupvariancecredit + parseFloat(_thisRow.find('input[name="varianceCredit[]"]').val());
                groupequation = groupequation + parseFloat(_thisRow.find('input[name="equation[]"]').autoNumeric('get'));
                grouprate = _thisRow.find('input[name="rate[]"]').val();
            }
        });
        $('table'+tableId+' tbody', cashRateWindowId).find("tr:eq("+groupIndex+")").find("td#grouprate").text(grouprate);
        $('table'+tableId+' tbody', cashRateWindowId).find("tr:eq("+groupIndex+")").find("input#groupamount").autoNumeric("set", groupamount);
        $('table'+tableId+' tbody', cashRateWindowId).find("tr:eq("+groupIndex+")").find("input#groupamountbase").autoNumeric("set", groupamountbase);
        $('table'+tableId+' tbody', cashRateWindowId).find("tr:eq("+groupIndex+")").find("input#groupequation").autoNumeric("set", groupequation);
        $('table'+tableId+' tbody', cashRateWindowId).find("tr:eq("+groupIndex+")").find("input#groupvariancedebit").autoNumeric("set", groupvariancedebit);
        $('table'+tableId+' tbody', cashRateWindowId).find("tr:eq("+groupIndex+")").find("input#groupvariancecredit").autoNumeric("set", groupvariancecredit);
    }
    function clearForm(){
        CashRateDtlTable.fnDeleteRow();
        BillRateDtlTable.fnDeleteRow();
        if ($("#glTemplateSectionStatic").length > 0) {
            $("#glTemplateSectionStatic").remove();
        }
        $("#currencyRateDate").val("");
        $("#currencyId").val("");
        $("#hdrRate").val("");
        $("input[name='equaliseAllCheck']").attr('checked', false);
        $("input[name='equaliseAllCheck']").parent().removeClass('checked');
        $(".SUM span", cashRateWindowId).empty();
        $(".SUM span", cashRateWindowId).empty();
        $(".SUM input", cashRateWindowId).val('');
        if ($("#glTemplateSectionStatic").length > 0) {
            $("#glTemplateSectionStatic").remove();
        }
    }
    function calculateVariance(tr){
        var hdrRate = $("#hdrRate").autoNumeric('get');
        var amount = $(tr).find('input[name="baseamount[]"]').autoNumeric("get");
        var baseamount = $(tr).find('input[name="amount[]"]').autoNumeric("get");
        var equalMNT = amount * hdrRate;
        var debitORcredit = equalMNT - baseamount;
        var debitORcredit1 = 0;       
        var debitORcredit11 = 0;       
        var debitORcredit2 = 0;

        if (debitORcredit > 0) {
            debitORcredit1 = debitORcredit;
            debitORcredit11 = debitORcredit1;
        } else {
            debitORcredit2 = debitORcredit * -1;
        }
        
        if ($(tr).find('input[name="balanceTypeId[]"]').val() != 1) {
            debitORcredit1 = debitORcredit2;
            debitORcredit2 = debitORcredit11;
        }
        
        $(tr).find('input[name="equation[]"]').autoNumeric("set", equalMNT);
        $(tr).find('input[name="varianceDebitDisplay[]"]').autoNumeric("set", debitORcredit1);
        $(tr).find('input[name="varianceDebit[]"]').val(Number(debitORcredit1).toFixed(2));
        $(tr).find('input[name="varianceCreditDisplay[]"]').autoNumeric("set", debitORcredit2);
        $(tr).find('input[name="varianceCredit[]"]').val(Number(debitORcredit2).toFixed(2));
    }
    function collapseAccounts(){
        $('table#cashRateGrid > tbody > tr', cashRateWindowId).each(function () {
            var _thisRow = $(this);
            if (_thisRow.find('input[name="accountCode[]"]').length > 0) {
                if (_thisRow.hasClass("hide")) {
                    _thisRow.removeClass("hide");
                } else {
                   _thisRow.addClass("hide"); 
                }
            }
        });
        $('table#billRateGrid > tbody > tr', cashRateWindowId).each(function () {
            var _thisRow = $(this);
            if (_thisRow.find('input[name="accountCode[]"]').length > 0) {
                if (_thisRow.hasClass("hide")) {
                    _thisRow.removeClass("hide");
                } else {
                   _thisRow.addClass("hide"); 
                }
            }
        });
    }
    function checkBalance(tableId, groupId){
        var isChecked = true;
        $(tableId+' > tbody > tr:not(.group)').each(function() {
            if ($(this).find('input[name="accountCode[]"]').val() == groupId) {
                var amount = $(this).find('input[name="amount[]"]').autoNumeric('get');
                if (amount < 0) {
                    isChecked = false;
                }
            }
        });
        return isChecked;
    }
    function setVariaceAmount(){
        Core.blockUI({
            boxed : true,
            message: 'Ханш тэгшитгэж байна...'
        }); 
        $('#cashRateGrid > tbody', cashRateWindowId).find("tr:not(.group)").each(function() {
            if ($(this).find("input[name='isCheck[]']").val() == "1") {
                calculateVariance($(this));
            }
        });
        $('#cashRateGrid > tbody', cashRateWindowId).find("tr.group").each(function() {
            var thisrow = $(this);
            var groupId = thisrow.find('input[name="groupAccountCode[]"]').val();
            var groupIndex = thisrow.index();
            calcGroupTotalByAccount(groupId, groupIndex, cashRateTableId);
        });
        $('#billRateGrid > tbody', cashRateWindowId).find("tr:not(.group)").each(function() {
            if ($(this).find("input[name='isCheck[]']").val() == "1") {
                calculateVariance($(this));
            }
        });
        $('#billRateGrid > tbody', cashRateWindowId).find("tr.group").each(function() {
            var thisrow = $(this);
            var groupId = thisrow.find('input[name="groupAccountCode[]"]').val();
            var groupIndex = thisrow.index();
            calcGroupTotalByAccount(groupId, groupIndex, billRateTableId);
        });
        calcFoot();
        Core.unblockUI();
    }
    function rowNumeringByGroup(){
        var cashgroupId = $('#cashRateGrid > tbody > tr:first-child').find('input[name="groupAccountCode[]"]').val();
        var billgroupId = $('#billRateGrid > tbody > tr:first-child').find('input[name="groupAccountCode[]"]').val();
        var k = 1, j = 1;
        if ($('#cashRateGrid > tbody > tr').length > 1) {
            $('#cashRateGrid > tbody > tr:not(.group)').each(function() {
                if ($(this).find('input[name="accountCode[]"]').val() != cashgroupId) {
                    k = 1;
                    cashgroupId = $(this).find('input[name="accountCode[]"]').val();
                }
                $(this).find('td:first-child').text(k);
                k++;
            }); 
        }
        if ($('#billRateGrid > tbody > tr').length > 1) {
            $('#billRateGrid > tbody > tr:not(.group)').each(function() {
                if ($(this).find('input[name="accountCode[]"]').val() != billgroupId) {
                    j = 1;
                    billgroupId = $(this).find('input[name="accountCode[]"]').val();
                }
                $(this).find('td:first-child').text(j);
                j++;
            }); 
        }
    }
</script>