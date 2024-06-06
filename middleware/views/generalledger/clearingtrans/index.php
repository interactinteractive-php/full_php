<?php
if (!$this->isAjax) {
?>
<div class="col-md-12" id="clearingTrans">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="caption buttons">
                <span class="caption-subject font-weight-bold uppercase card-subject-blue">
                    <?php echo $this->title; ?>
                </span>
                <?php echo Form::button(array('class' => 'btn btn-circle btn-sm btn-success saveClearingTrans', 'value' => '<i class="fa fa-save"></i> ' . $this->lang->line('save_btn'))); ?>
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
<div id="clearingTrans">           
<?php
}
?>
    <form class="form-horizontal xs-form p-0" method="post" id="saveClearingTrans-form">
        <div class="row">
            <div class="col-md-12">
                <fieldset class="collapsible">
                    <legend><?php echo Lang::lineDefault('FIN_01516', 'Ерөнхий мэдээлэл'); ?></legend>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group row">
                                <?php echo Form::label(array('text' => Lang::lineDefault('PL_0176', 'Огноо'), 'for' => 'clearingTransDate', 'class' => 'col-form-label col-md-4 col-sm-6 pt0', 'required' => 'required')); ?>
                                <div class="col-md-8 col-sm-6">
                                    <div class="dateElement input-group">
                                        <?php echo Form::text(array('name' => 'clearingTransDate', 'id' => 'clearingTransDate', 'class' => 'form-control form-control-sm dateInit', 'value' => Date::currentDate('Y-m-d'), 'required' => 'required', 'style' => 'width: 132px')); ?>
                                        <span class="input-group-btn"><button onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (Config::getFromCache('IS_USE_CLOSE_TRX_JAPAN')) {
                            ?>
                            <div class="form-group row">
                                <?php echo Form::label(array('text' => 'Төрөл', 'for' => 'filterBookTypeId', 'class' => 'col-form-label col-md-4 col-sm-6 pt0')); ?>
                                <div class="col-md-5 col-sm-5">
                                    <select class="form-control form-control-sm" id="filterBookTypeId" name="filterBookTypeId">
                                        <option value="17" selected="selected">ХААЛТЫН ГҮЙЛГЭЭ</option>
                                        <option value="170">ЯПОН ХААЛТЫН ГҮЙЛГЭЭ</option>
                                    </select>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                            <div class="form-group row">
                                <?php echo Form::label(array('text' => Lang::lineDefault('FIN_00613', 'Баримтын дугаар'), 'for' => 'clearing_bookNumber', 'class' => 'col-form-label col-md-4 col-sm-6 pt0')); ?>
                                <div class="col-md-8 col-sm-6">
                                    <?php echo Form::text(array('id' => 'clearingbookNumber', 'name' => 'clearingbookNumber', 'class' => 'form-control', 'required' => 'required')); ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <?php echo Form::label(array('text' => Lang::lineDefault('PL_0317', 'Хэлтэс'), 'for' => 'customerCodeName', 'class' => 'col-form-label col-md-4 pt0')); ?>
                                <div class="col-md-8">
                                    <div class="meta-autocomplete-wrap" data-section-path="filterDepartmentId">
                                        <div class="input-group double-between-input" data-section-path="<?php echo Mdgl::$customerListDataViewCode; ?>">
                                            <?php echo Form::hidden(array('name' => 'filterDepartmentId', 'id' => 'filterDepartmentId_valueField', 'value' => isset($this->fillPath['filterdepartmentid']) ? $this->fillPath['filterdepartmentid'] : '')); ?>
                                            <?php echo Form::text(array('name' => '', 'data-lookupid' => '1457081813808', 'data-processid' => '1528858041095420', 'id' => 'filterDepartmentId_displayField', 'class' => 'form-control form-control-sm meta-autocomplete lookup-code-autocomplete', 'placeholder' => Lang::line('code_search'), 'value' => isset($this->fillPath['filterdepartmentid_displayfield']) ? $this->fillPath['filterdepartmentid_displayfield'] : '')); ?>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('Department11', 'single', 'unitedDepartmentSelectabled', '', this);"><i class="fa fa-search"></i></button>
                                            </span>     
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => '', 'data-lookupid' => '1457081813808', 'data-processid' => '1528858041095420', 'id' => 'filterDepartmentId_nameField', 'class' => 'form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete', 'placeholder' => Lang::line('name_search'), 'value' => isset($this->fillPath['filterdepartmentid_namefield']) ? $this->fillPath['filterdepartmentid_namefield'] : '')); ?>
                                            </span>     
                                        </div>
                                    </div>
                                </div>
                            </div>                              
                        </div>
                        <div class="col-md-7">
                            <?php
                            if (Config::getFromCache('CONFIG_CT_ECONOMIC_SRC')) {
                            ?>
                            <div class="form-group row">
                                <?php echo Form::label(array('text' => 'Эх үүсвэр', 'for' => 'economicSourceId', 'class' => 'col-form-label col-md-4 pt0', 'required' => 'required')); ?>
                                <div class="col-md-4">
                                    <?php
                                    echo Form::select(
                                        array(
                                            'class' => 'form-control form-control-sm', 
                                            'name' => 'clEconomicSourceId', 
                                            'required' => 'required', 
                                            'data' => array(
                                                array(
                                                    'id' => '1001', 
                                                    'text' => 'Улсын төсөв'
                                                ),
                                                array(
                                                    'id' => '1002', 
                                                    'text' => 'Орон нутгийн төсөв'
                                                )
                                            ), 
                                            'op_value' => 'id', 
                                            'op_text' => 'text', 
                                            'value' => '1001'
                                        )
                                    );
                                    ?>
                                </div>
                            </div> 
                            <?php
                            }
                            ?>
                            <div class="form-group row">
                                <?php echo Form::label(array('text' => Lang::lineDefault('FIN_00209', 'Орлого зарлагын нэгдсэн данс'), 'for' => 'customerCodeName', 'class' => 'col-form-label col-md-4 pt0', 'required' => 'required')); ?>
                                <div class="col-md-8">
                                    <?php echo Form::hidden(array('name' => 'incomeOutcomeCurrencyId', 'id' => 'incomeOutcomeCurrencyId')); ?>
                                    <div class="meta-autocomplete-wrap" data-section-path="incomeOutcomeAccountId">
                                        <div class="input-group double-between-input" data-section-path="<?php echo Mdgl::$customerListDataViewCode; ?>">
                                            <?php echo Form::hidden(array('name' => 'incomeOutcomeAccountId', 'id' => 'incomeOutcomeAccountId_valueField', 'value' => isset($this->defaultAccountId1['id']) ? $this->defaultAccountId1['id'] : '')); ?>
                                            <?php echo Form::text(array('name' => '', 'id' => 'incomeOutcomeAccountId_displayField', 'data-lookupid' => '1459138813444931', 'data-processid' => '1528858041095420', 'value' => isset($this->defaultAccountId1['code']) ? $this->defaultAccountId1['code'] : '', 'class' => 'form-control form-control-sm accountCodeMask meta-autocomplete lookup-code-autocomplete', 'placeholder' => Lang::line('code_search'), 'required' => 'required')); ?>
                                            <span class="input-group-btn">
                                                <script>
                                                    var isAccessCriteriDepartmentId = '<?php echo Config::getFromCache('FIN_CLOSING_TRANSACTION'); ?>';
                                                </script>
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('ERS_ACCOUNTS', 'single', 'unitedAccountSelectabled', ((($('select[name=clEconomicSourceId]').length > 0 && $('select[name=clEconomicSourceId]').val() != '') ? 'param[economicSourceId]='+$('select[name=clEconomicSourceId]').val() : '')+(isAccessCriteriDepartmentId == '1' ? '&param[departmentId]='+$('input[name=filterDepartmentId]').val() : '')), this);"><i class="fa fa-search"></i></button>
                                            </span>     
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => '', 'id' => 'incomeOutcomeAccountId_nameField', 'data-lookupid' => '1459138813444931', 'data-processid' => '1528858041095420', 'value' => isset($this->defaultAccountId1['name']) ? $this->defaultAccountId1['name'] : '', 'class' => 'form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete', 'placeholder' => Lang::line('code_search'), 'required' => 'required')); ?>    
                                            </span>     
                                        </div>
                                    </div>    
                                </div>
                            </div>     
                            <div class="form-group row">
                                <?php echo Form::label(array('text' => Lang::lineDefault('FIN_1018', 'Тайлант үеийн ашиг алдагдлын данс'), 'for' => 'clearingTransDate', 'class' => 'col-form-label col-md-4 col-sm-6 pt0', 'required' => 'required')); ?>
                                <div class="col-md-8 col-sm-6">
                                    <div class="meta-autocomplete-wrap" data-section-path="extAccountId">
                                        <div class="input-group double-between-input">
                                            <?php echo Form::hidden(array('name' => 'extAccountId', 'id' => 'extAccountId_valueField', 'value' => isset($this->defaultAccountId2['id']) ? $this->defaultAccountId2['id'] : '')); ?>
                                            <?php echo Form::text(array('name' => '', 'id' => 'extAccountId_displayField', 'data-lookupid' => '1479113500351', 'data-processid' => '1528858041095420', 'value' => isset($this->defaultAccountId2['code']) ? $this->defaultAccountId2['code'] : '', 'class' => 'form-control form-control-sm accountCodeMask meta-autocomplete lookup-code-autocomplete', 'placeholder' => Lang::line('code_search'), 'required' => 'required')); ?>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('reocp_accounts', 'single', 'extAccountSelectabled', (isAccessCriteriDepartmentId == '1' ? 'param[departmentId]='+$('input[name=filterDepartmentId]').val() : ''), this);"><i class="fa fa-search"></i></button>
                                            </span>
                                            <span class="input-group-btn">
                                                <?php echo Form::text(array('name' => '', 'id' => 'extAccountId_nameField', 'data-lookupid' => '1479113500351', 'data-processid' => '1528858041095420', 'value' => isset($this->defaultAccountId2['name']) ? $this->defaultAccountId2['name'] : '', 'class' => 'form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete', 'placeholder' => Lang::line('name_search'), 'required' => 'required')); ?>
                                            </span>
                                        </div>
                                    </div>    
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-sm btn-circle green-meadow" onclick="clearingTransSearch();"><i class="fa fa-search"></i> <?php echo $this->lang->line('search_btn'); ?></button>                        
                                </div>
                            </div>    
                        </div>                     
                        <div class="clearfix w-100"></div>
                    </div>
                </fieldset>
            </div>    
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-bordered table-hover" id="clearingTransGrid" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="trNumber text-center"><?php echo Lang::lineDefault('PL_0045', 'Багц'); ?></th>
                            <th class="text-center"><?php echo Lang::lineDefault('MET_330575', 'Дансны код'); ?></th>
                            <th><?php echo Lang::lineDefault('PL_20104', 'Дансны нэр'); ?></th>
                            <th class="text-center amounts"><?php echo Lang::lineDefault('FIN_1007', 'Дебит дүн'); ?></th>
                            <th class="text-center amounts"><?php echo Lang::lineDefault('FIN_1008', 'Кредит дүн'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td></td>
                            <td class="text-right"><div class="numberInit totalDebitFoot"></div><?php echo Form::hidden(array('id' => 'totalDebitFoot', 'class' => 'numberInit')); ?></td>
                            <td class="text-right"><div class="numberInit totalCreditFoot"></div><?php echo Form::hidden(array('id' => 'totalCreditFoot', 'class' => 'numberInit')); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </form>
    
    <div class="form-actions mt10">
        <div class="row">
            <div class="col-md-12 text-right">
                <?php 
                echo Mdcommon::redirectHelpContentButton('FIN_GL_CLEARING_HELP_CONTENT');
                echo Form::button(array('class' => 'btn btn-circle green-meadow bp-btn-save saveClearingTrans', 'value' => '<i class="icon-checkmark-circle2"></i> ' . $this->lang->line('save_btn'))); 
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
    .dataTables_wrapper {
        clear: both;
        overflow: hidden;
        position: relative;
    }
    #clearingTrans .dataTables_wrapper table.dataTable thead th {
        height: 25px;
    }
    #clearingTrans .dataTables_wrapper table.dataTable tbody td {
        white-space: normal;
        word-break: break-word;
        vertical-align: top;
    }
    .double-between-input input.form-control.incomeOutcomeName-autocomplete {
        width: 100% !important;
    }
</style>
<script type="text/javascript">
    var clearingTransTable,
        clearingTransWindowId = "#clearingTrans",
        clearingForm = "#saveClearingTrans-form",
        clearingTransTableId = "#clearingTransGrid";

    $(function () {

        $(".saveClearingTrans", clearingTransWindowId).on('click', function () {
            if ($('#extAccountId_valueField', clearingTransWindowId).val() == '') {
                alert('Тайлант үеийн ашиг алдагдлын данс сонгоно уу!');
                return;
            }
            
            $(clearingForm).validate({errorPlacement: function () {}});
            if ($(clearingForm).valid()) {
                $.ajax({
                    type: 'post',
                    url: 'mdgl/saveClearingTrans',
                    data: $("#saveClearingTrans-form", clearingTransWindowId).serialize(),
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function (data) {
                        PNotify.removeAll();

                        if (data.status === 'success') {
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
                            /*$("input[type='text']", clearingTransWindowId).val("");
                            $("input[type='hidden']", clearingTransWindowId).val("");
                            $(".numberInit", clearingTransWindowId).html("0");
                            getAutoNumber();
                            $('#clearingTransDate', clearingTransWindowId).datepicker('setDate', new Date());
                            clearingTransTable.fnClearTable();*/
                            multiTabActiveAutoClose();
                        } else {
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
        });

        var dynamicHeight = $(window).height() - $(clearingTransWindowId).offset().top - 350;
        clearingTransTable = $("#clearingTransGrid", clearingTransWindowId).dataTable({
            scrollY: dynamicHeight,
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
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "width": 15,
                "targets": 0
            },
            {
                "searchable": false,
                "orderable": false,
                "width": 150,
                "className": 'text-center',
                "targets": 1
            }]
        });
        
        getAutoNumber();
        //clearingTransSearch();
        
        $(clearingTransWindowId).on("focus", 'input.incomeOutcomeCode-autocomplete:not(disabled, readonly)', function(e) {
            var _this = $(this);
            var isHoverSelect = false;
            var _parent = _this.closest("div.input-group");

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
                        url: 'mdgl/filterAccountCode',
                        dataType: "json",
                        data: {
                            accountCode: _this.val()
                        },
                        success: function(data) {
//                            if (type == 'code') {
                                response($.map(data, function(item) {
                                    return {
                                        value: item.id,
                                        label: item.accountcode,
                                        name: item.accountname,
                                        row: item
                                    };
                                }));
//                            } else {
//                                response($.map(data, function(item) {
//                                    var code = item.codeName.split("|");
//                                    return {
//                                        value: code[2],
//                                        label: code[1],
//                                        name: code[2],
//                                        row: item.row
//                                    };
//                                }));
//                            }
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

//                    if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
//                        if (type === 'code') {
                            _parent.find("#incomeOutcomeAccountId_displayField").val(ui.item.label);
                            _parent.find("#incomeOutcomeAccountId_nameField").val(ui.item.name);
                            _parent.find("#incomeOutcomeAccountId_valueField").val(ui.item.value);
                            _parent.parent().find("#incomeOutcomeCurrencyId").val(ui.item.row.currencyid);
                            event.stopPropagation();
                            event.preventDefault();
//                        } else {
//                            _parent.find("input[id*='_nameField']").val(ui.item.name);
//                        }
//                    } else {
//                        if (type === 'code') {
//                            if (ui.item.label === _this.val()) {
//                                _parent.find("input[id*='_displayField']").val(ui.item.label);
//                                _parent.find("input[id*='_nameField']").val(ui.item.name);
//                            } else {
//                                _parent.find("input[id*='_displayField']").val(_this.val());
//                                event.preventDefault();
//                            }
//                        } else {
//                            if (ui.item.name === _this.val()) {
//                                _parent.find("input[id*='_displayField']").val(ui.item.label);
//                                _parent.find("input[id*='_nameField']").val(ui.item.name);
//                            } else {
//                                _parent.find("input[id*='_nameField']").val(_this.val());
//                                event.preventDefault();
//                            }
//                        }
//                    }

                    while (origEvent.originalEvent !== undefined) {
                        origEvent = origEvent.originalEvent;
                    }

//                    if (origEvent.type === 'click') {
//                        var e = jQuery.Event("keydown");
//                        e.keyCode = e.which = 13;
//                        _this.trigger(e);
//                    }
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

//                if (type === 'code') {
                    var re = new RegExp("(" + this.term + ")", "gi"),
                            cls = this.options.highlightClass,
                            template = "<span class='" + cls + "'>$1</span>",
                            label = item.label.replace(re, template);

                    return $('<li>').append('<div class="lookup-ac-render-code">' + label + '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(ul);
//                } else {
//                    var re = new RegExp("(" + this.term + ")", "gi"),
//                            cls = this.options.highlightClass,
//                            template = "<span class='" + cls + "'>$1</span>",
//                            name = item.name.replace(re, template);
//
//                    return $('<li>').append('<div class="lookup-ac-render-code">' + item.label + '</div><div class="lookup-ac-render-name">' + name + '</div>').appendTo(ul);
//                }
            };
        });        
        
        $(clearingTransWindowId).on("focus", 'input.extIncomeOutcomeCode-autocomplete:not(disabled, readonly)', function(e) {
            var _this = $(this);
            var isHoverSelect = false;
            var _parent = _this.closest("div.input-group");

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
                        url: 'mdgl/filterAccountCode',
                        dataType: "json",
                        data: {
                            accountCode: _this.val()
                        },
                        success: function(data) {
//                            if (type == 'code') {
                                response($.map(data, function(item) {
                                    return {
                                        value: item.id,
                                        label: item.accountcode,
                                        name: item.accountname,
                                        row: item
                                    };
                                }));
//                            } else {
//                                response($.map(data, function(item) {
//                                    var code = item.codeName.split("|");
//                                    return {
//                                        value: code[2],
//                                        label: code[1],
//                                        name: code[2],
//                                        row: item.row
//                                    };
//                                }));
//                            }
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

//                    if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
//                        if (type === 'code') {
                            _parent.find("#extAccountId_displayField").val(ui.item.label);
                            _parent.find("#extAccountId_nameField").val(ui.item.name);
                            _parent.find("#extAccountId_valueField").val(ui.item.value);
                            event.stopPropagation();
                            event.preventDefault();
//                        } else {
//                            _parent.find("input[id*='_nameField']").val(ui.item.name);
//                        }
//                    } else {
//                        if (type === 'code') {
//                            if (ui.item.label === _this.val()) {
//                                _parent.find("input[id*='_displayField']").val(ui.item.label);
//                                _parent.find("input[id*='_nameField']").val(ui.item.name);
//                            } else {
//                                _parent.find("input[id*='_displayField']").val(_this.val());
//                                event.preventDefault();
//                            }
//                        } else {
//                            if (ui.item.name === _this.val()) {
//                                _parent.find("input[id*='_displayField']").val(ui.item.label);
//                                _parent.find("input[id*='_nameField']").val(ui.item.name);
//                            } else {
//                                _parent.find("input[id*='_nameField']").val(_this.val());
//                                event.preventDefault();
//                            }
//                        }
//                    }

                    while (origEvent.originalEvent !== undefined) {
                        origEvent = origEvent.originalEvent;
                    }

//                    if (origEvent.type === 'click') {
//                        var e = jQuery.Event("keydown");
//                        e.keyCode = e.which = 13;
//                        _this.trigger(e);
//                    }
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

//                if (type === 'code') {
                    var re = new RegExp("(" + this.term + ")", "gi"),
                            cls = this.options.highlightClass,
                            template = "<span class='" + cls + "'>$1</span>",
                            label = item.label.replace(re, template);

                    return $('<li>').append('<div class="lookup-ac-render-code">' + label + '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(ul);
//                } else {
//                    var re = new RegExp("(" + this.term + ")", "gi"),
//                            cls = this.options.highlightClass,
//                            template = "<span class='" + cls + "'>$1</span>",
//                            name = item.name.replace(re, template);
//
//                    return $('<li>').append('<div class="lookup-ac-render-code">' + item.label + '</div><div class="lookup-ac-render-name">' + name + '</div>').appendTo(ul);
//                }
            };
        });        
        
        $(clearingTransWindowId).on("keydown", 'input.filterDepartmentId-autocomplete:not(disabled, readonly)', function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            var _this = $(this);
            var _parent = _this.closest("div.input-group");
            
            if (code === 13) {
                if (_this.data("ui-autocomplete")) {
                    _this.autocomplete("destroy");
                }                     
                $.ajax({
                    type: 'post',
                    url: 'api/callDataview',
                    data: {dataviewId: '1457081813808', criteriaData: {code: [{operator: '=', operand: _this.val()}]}}, 
                    dataType: 'json',
                    success: function(data) {                            
                        if (data.status === 'success' && data.result[0]) {
                            _parent.find("#filterDepartmentId_displayField").val(data.result[0]['code']);
                            _parent.find("#filterDepartmentId_nameField").val(data.result[0]['departmentname']);
                            _parent.find("#filterDepartmentId_valueField").val(data.result[0]['id']).trigger('change');
                        } else {
                            _parent.find("#filterDepartmentId_displayField").val('');
                            _parent.find("#filterDepartmentId_nameField").val('');
                            _parent.find("#filterDepartmentId_valueField").val('').trigger('change');                                 
                        }
                    }
                });             
                return false;
            } else {
                if (!_this.data("ui-autocomplete")) {
                    var _this = $(this);
                    var isHoverSelect = false;
                    var _parent = _this.closest("div.input-group");

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
                            url: 'mdgl/filterDepartmentCode',
                            dataType: "json",
                            data: {
                                department: _this.val()
                            },
                            success: function(data) {
                                response($.map(data, function(item) {
                                    return {
                                        value: item.department_id,
                                        label: item.department_code,
                                        name: item.department_name,
                                        row: item
                                    };
                                }));
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

    //                    if (isHoverSelect || event.originalEvent.originalEvent.type == 'click') {
    //                        if (type === 'code') {
                                _parent.find("#filterDepartmentId_displayField").val(ui.item.label);
                                _parent.find("#filterDepartmentId_nameField").val(ui.item.name);
                                _parent.find("#filterDepartmentId_valueField").val(ui.item.value);
                                event.preventDefault();
    //                        } else {
    //                            _parent.find("input[id*='_nameField']").val(ui.item.name);
    //                        }
    //                    } else {
    //                        if (type === 'code') {
    //                            if (ui.item.label === _this.val()) {
    //                                _parent.find("input[id*='_displayField']").val(ui.item.label);
    //                                _parent.find("input[id*='_nameField']").val(ui.item.name);
    //                            } else {
    //                                _parent.find("input[id*='_displayField']").val(_this.val());
    //                                event.preventDefault();
    //                            }
    //                        } else {
    //                            if (ui.item.name === _this.val()) {
    //                                _parent.find("input[id*='_displayField']").val(ui.item.label);
    //                                _parent.find("input[id*='_nameField']").val(ui.item.name);
    //                            } else {
    //                                _parent.find("input[id*='_nameField']").val(_this.val());
    //                                event.preventDefault();
    //                            }
    //                        }
    //                    }

                        while (origEvent.originalEvent !== undefined) {
                            origEvent = origEvent.originalEvent;
                        }

    //                    if (origEvent.type === 'click') {
    //                        var e = jQuery.Event("keydown");
    //                        e.keyCode = e.which = 13;
    //                        _this.trigger(e);
    //                    }
                    }
                }).autocomplete("instance")._renderItem = function(ul, item) {
                    ul.addClass('lookup-ac-render');

    //                if (type === 'code') {
                        var re = new RegExp("(" + this.term + ")", "gi"),
                                cls = this.options.highlightClass,
                                template = "<span class='" + cls + "'>$1</span>",
                                label = item.label.replace(re, template);

                        return $('<li>').append('<div class="lookup-ac-render-code">' + label + '</div><div class="lookup-ac-render-name">' + item.name + '</div>').appendTo(ul);
    //                } else {
    //                    var re = new RegExp("(" + this.term + ")", "gi"),
    //                            cls = this.options.highlightClass,
    //                            template = "<span class='" + cls + "'>$1</span>",
    //                            name = item.name.replace(re, template);
    //
    //                    return $('<li>').append('<div class="lookup-ac-render-code">' + item.label + '</div><div class="lookup-ac-render-name">' + name + '</div>').appendTo(ul);
    //                }
                };
                }
            }                
        });        
        
        <?php if (Config::getFromCache('FIN_CLOSING_TRANSACTION') == '1') { ?>
            $(clearingTransWindowId).on("change", '#filterDepartmentId_valueField', function(){
                $.ajax({
                    type: 'post',
                    url: 'api/callDataview',
                    data: {dataviewId: '1459138813444931', criteriaData: {departmentid: [{operator: '=', operand: $(this).val()}]}}, 
                    dataType: 'json',
                    success: function(data) {                            
                        if (data.status === 'success' && data.result[0]) {
                            $('input[name="incomeOutcomeAccountId"]').val(data.result[0]['id']);
                            $('input[id="incomeOutcomeAccountId_displayField"]').val(data.result[0]['accountcode']);
                            $('input[id="incomeOutcomeAccountId_nameField"]').val(data.result[0]['accountname']);                                                    
                            $('input[name="incomeOutcomeAccountId"]').attr('data-row-data', JSON.stringify(data.result[0]));
                        } else {
                            $('input[name="incomeOutcomeAccountId"]').val('');
                            $('input[id="incomeOutcomeAccountId_displayField"]').val('');
                            $('input[id="incomeOutcomeAccountId_nameField"]').val('');                                                    
                            $('input[name="incomeOutcomeAccountId"]').attr('data-row-data', '');                           
                        }
                    }
                });
                $.ajax({
                    type: 'post',
                    url: 'api/callDataview',
                    data: {dataviewId: '1479113500351', criteriaData: {departmentid: [{operator: '=', operand: $(this).val()}]}}, 
                    dataType: 'json',
                    success: function(data) {                            
                        if (data.status === 'success' && data.result[0]) {
                            $('input[name="extAccountId"]').val(data.result[0]['id']);
                            $('input[id="extAccountId_displayField"]').val(data.result[0]['accountcode']);
                            $('input[id="extAccountId_nameField"]').val(data.result[0]['accountname']);                                                    
                            $('input[name="extAccountId"]').attr('data-row-data', JSON.stringify(data.result[0]));
                        } else {
                            $('input[name="extAccountId"]').val('');
                            $('input[id="extAccountId_displayField"]').val('');
                            $('input[id="extAccountId_nameField"]').val('');                                                    
                            $('input[name="extAccountId"]').attr('data-row-data', '');                           
                        }
                    }
                });
            })
        <?php } ?>
    });


    function clearingTransSearch() {
        $(clearingForm).validate({errorPlacement: function () {}});
        
        if ($(clearingForm).valid()) {   
            
            if ($('#extAccountId_valueField', clearingTransWindowId).val() == '') {
                alert('Тайлант үеийн ашиг алдагдлын данс сонгоно уу!');
                return;
            }            
            
            PNotify.removeAll();
            Core.blockUI({boxed: true, message: 'Loading...'});

            $.ajax({
                type: 'post',
                url: 'mdgl/clearingTransList',
                data: $('#saveClearingTrans-form', clearingTransWindowId).serialize(),
                dataType: 'json',
                success: function (data) {
                    if (data.status === 'error') {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                        Core.unblockUI();
                        return;
                    }
                    clearingTransTable.fnClearTable();

                    var i = 0, dataLength = Object.keys(data.rows).length;
                    
                    for (i; i < dataLength; i++) {
                        clearingTransTable.fnAddData([
                                data.rows[i].subid + '<input type="hidden" name="subId[]" value="' + data.rows[i].subid + '">',
                                data.rows[i].accountcode + '<input type="hidden" name="accountId[]" value="' + data.rows[i].accountid + '"> <input type="hidden" name="accountCode[]" value="' + data.rows[i].accountcode + '">',
                                data.rows[i].accountname,
                                '<div class="text-right">' + pureNumberFormat(data.rows[i].debitamount) + '</div><input type="hidden" name="debit[]" value="' + data.rows[i].debitamount + '">',
                                '<div class="text-right">' + pureNumberFormat(data.rows[i].creditamount) + '</div><input type="hidden" name="credit[]" value="' + data.rows[i].creditamount + '">'
                            ],
                            false
                        );
                    }
                    
                    clearingTransTable.fnDraw();
                    Core.initInputType($("#saveClearingTrans-form", clearingTransWindowId));
                    Core.unblockUI();
                }
            }).done(function(){
                Core.unblockUI();
            });
        }
    }

    function clearingTransSegmentValueCommonSelectableGrid() {
        commonMetaDataGrid('single', 'metaSegmentValue', '');
    }
    function clearingTransAccountSegmentCommonSelectableGrid() {
        commonMetaDataGrid('single', 'metaSegment', '');
    }

    function clearingTransAccountCommonSelectableGrid() {
        //var accountType = 'EXPENSE_AND_REVENUE_SUMMARY_ACCOUNTS';
        dataViewCustomSelectableGrid('ERS_ACCOUNTS', 'single', 'unitedAccountSelectabled', '', this);
        //commonSelectableGrid('account', 'single', 'account', 'ACCOUNT_TYPE_ID=' + accountType + '&TYPE_CODE_ACCOUNT_TYPE_ID=table&autoSearch=1');
    }
    function unitedAccountSelectabled(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        $("#incomeOutcomeAccountId_displayField", clearingTransWindowId).val(row.accountcode);
        $("#incomeOutcomeAccountId_nameField", clearingTransWindowId).val(row.accountname);
        $("#incomeOutcomeAccountId_valueField", clearingTransWindowId).val(row.id);
        $("#incomeOutcomeCurrencyId", clearingTransWindowId).val(row.currencyid);
    }
    
    function extAccountSelectabled(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        $("#extAccountId_displayField", clearingTransWindowId).val(row.accountcode);
        $("#extAccountId_nameField", clearingTransWindowId).val(row.accountname);
        $("#extAccountId_valueField", clearingTransWindowId).val(row.id);
    }
    
    function unitedDepartmentSelectabled(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        $("#filterDepartmentId_displayField", clearingTransWindowId).val(row.code);
        $("#filterDepartmentId_nameField", clearingTransWindowId).val(row.departmentname);
        $("#filterDepartmentId_valueField", clearingTransWindowId).val(row.id).trigger('change');
    }

    function selectableCommonMetaDataGrid(chooseType, elem, params) {
        if (elem === 'metaSegment') {
            var rows = $('#commonBasketMetaDataGrid', clearingTransWindowId).datagrid('getRows');
            var row = rows[0];
            $("input#accountSegmentId", clearingTransWindowId).val(row.META_DATA_ID);
            $("input#accountSegmentName", clearingTransWindowId).val(row.META_DATA_CODE + " | " + row.META_DATA_NAME);
            $("#accountValueName", clearingTransWindowId).removeAttr('disabled');
            $("#accountValueButton", clearingTransWindowId).removeAttr('disabled');
        }
        if (elem === 'metaSegmentValue') {
            var rows = $('#commonBasketMetaDataGrid', clearingTransWindowId).datagrid('getRows');
            var row = rows[0];
            $("input#accountValueId", clearingTransWindowId).val(row.META_DATA_ID);
            $("input#accountValueName", clearingTransWindowId).val(row.META_DATA_CODE + " | " + row.META_DATA_NAME);
        }
    }

    function getAutoNumber() {
        $.ajax({
            type: 'post',
            url: 'mdgl/getAutoNumber',
            data: {bookTypeId: 17},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                if (data.status === 'success')
                    $("#clearingbookNumber", clearingTransWindowId).val(data.result.result);
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