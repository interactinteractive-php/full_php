<div class="row" id="ndd-book-<?php echo $this->uniqId; ?>">
    <div class="col-md-3 pr0" style="max-height: 500px;height: 500px;">
        <form id="nddBookPrintForm" method="post">
            <div class="form-body xs-form">
                <?php echo Form::label(array('text' => $this->lang->line('employee'), 'style'=> 'float:left;', 'required' => 'required')); ?>
                <div class="clearfix w-100"></div>
                
                <div class="meta-autocomplete-wrap" data-section-path="nddEmployeeKeyId">
                    <div class="input-group double-between-input">
                        <input id="nddEmployeeKeyId_valueField" name="nddEmployeeKeyId" class="popupInit" data-path="nddEmployeeKeyId" type="hidden">
                        <input id="nddEmployeeKeyId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" placeholder="кодоор хайх" data-processid="1469942750893" data-lookupid="1482213710825357" data-lookuptypeid="200101010000016" data-field-name="nddEmployeeKeyId" data-isclear="0" type="text" required="required">
                        <span class="input-group-btn">
                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('prlEmployeeKeyList', 'single', 'nddSelectabledGrid', '', this);"><i class="fa fa-search"></i></button>
                        </span>     
                        <span class="input-group-btn">
                            <input id="nddEmployeeKeyId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" placeholder="нэрээр хайх" data-processid="1469942750893" data-lookupid="1482213710825357" data-lookuptypeid="200101010000016" data-field-name="nddEmployeeKeyId" data-isclear="0" type="text" required="required">      
                        </span>     
                    </div>
                </div>
                
                <table class="table table-sm table-no-bordered mt10 ndd-emp-info mb0" style="display: none; border-top: 1px #D9D9D9 solid; border-bottom: 1px #D9D9D9 solid;">
                    <tr>
                        <td class="middle" style="width: 35%"></td>
                        <td id="nddEmpPicture" style="width: 65%"></td>
                    </tr>
                    <tr>
                        <td>Овог:</td>
                        <td id="nddEmpLastName"></td>
                    </tr>
                    <tr>
                        <td>Нэр:</td>
                        <td id="nddEmpFirstName"></td>
                    </tr>
                    <tr>
                        <td>Регистр:</td>
                        <td id="nddEmpRegister"></td>
                    </tr>
                    <tr>
                        <td>Салбар нэгж:</td>
                        <td id="nddEmpDepartment"></td>
                    </tr>
                    <tr>
                        <td>Албан тушаал:</td>
                        <td id="nddEmpPosition"></td>
                    </tr>
                </table>
                
                <div class="clearfix w-100 mt10"></div>
                
                <?php echo Form::label(array('text' => 'Дэвтэрийн төрөл', 'style'=> 'float:left;', 'required' => 'required')); ?>
                <div class="clearfix w-100"></div>
                <?php 
                echo Form::select(
                    array(
                        'name' => 'bookTypeId',
                        'id' => 'bookTypeId',
                        'class' => 'form-control form-control-sm',
                        'data' => $this->getNDDBookType,
                        'op_value' => 'ID',
                        'op_text' => 'CODE| |-| |DESCRIPTION', 
                        'required' => 'required'
                    )
                );
                ?>
                
                <div class="clearfix w-100"></div>
                
                <table class="table table-sm table-no-bordered mt10">
                    <tr>
                        <td class="pl0" style="width: 46%">
                            <span class="required">*</span> Жил:
                            <?php 
                            echo Form::select(
                                array(
                                    'name' => 'nddYearCode',
                                    'id' => 'nddYearCode',
                                    'class' => 'form-control form-control-sm',
                                    'data'=> $this->getNDDprintYear,
                                    'op_value' => 'id',
                                    'op_text' => 'code',
                                    'value' => Date::currentDate('Y'), 
                                    'required' => 'required'
                                )
                            );
                            ?>
                        </td>
                        <td class="pr0" style="width: 54%">
                            <span class="required">*</span> Хуудасны төрөл: 
                            <div class="radio-list">
                                <label class="radio-inline">
                                    <input type="radio" name="nddPageNum" value="2" id="nddPageEven" checked="checked"> Тэгш
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="nddPageNum" value="3" id="nddPageOdd"> Сондгой
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="pl0 pt10">
                            <span class="required">*</span> Эхлэх сар:
                            <?php
                            $months = array();
                            $monthIndex = 1;
                            for ($mon = $monthIndex; $mon <= 12; $mon++)
                                array_push($months, array('MONTH' => $mon));
                        
                            echo Form::select(
                                array(
                                    'name' => 'nddMonthPrev',
                                    'id' => 'nddMonthPrev',
                                    'class' => 'form-control form-control-sm', 
                                    'data'=> $months,
                                    'op_value' => 'MONTH',
                                    'op_text' => 'MONTH', 
                                    'required' => 'required'
                                )
                            );
                            ?>
                        </td>
                        <td class="pr0 pt10">
                            <span class="required">*</span> Дуусах сар:
                            <?php
                            echo Form::select(
                                array(
                                    'name' => 'nddMonthNext',
                                    'id' => 'nddMonthNext',
                                    'class' => 'form-control form-control-sm', 
                                    'required' => 'required'
                                )
                            );
                            ?>
                        </td>
                    </tr>
                </table>
            </div>    
            <div class="form-actions">
                <?php echo Form::button(array('class' => 'btn blue btn-sm nddBookFilter', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('do_filter'))); ?>
                <?php echo Form::button(array('class' => 'btn grey-cascade btn-sm float-right nddBookPrint', 'value' => '<i class="fa fa-print"></i> ' . $this->lang->line('print_btn'))); ?>
            </div>
            <?php 
            echo Form::hidden(array('name' => 'nddEmployeeId')); 
            echo Form::hidden(array('name' => 'type', 'value' => $this->type));
            ?>
        </form>
    </div>
    <div class="col-md-9">
        <div class="report-preview">
            <div class="report-preview-container" style="height: 540px;">
                <div id="nddContentsPre" style="display: block; position: absolute; width: 790px; height: 511px;">
                    <?php echo $this->nddPreview; ?>
                </div>
                <div class="clearfix w-100"></div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    #nddContentsPre {
        background: white;
        width: 23cm;
        height: 13.5cm;
        padding: 0;
        margin: 0;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
    }
    .nddColPre {
        display: inline-block;
        position: absolute;
        font-size: 11px;
        color: #000;
        padding: 0;
    }    
    .nddPrintTempTable {
        font-size: 10px;
        border-collapse: collapse;
    }
    #nddContentsPrintPrev {
        border: 1px transparent solid;
        cursor: move;
    }
    #nddContentsPrintPrev:hover {
        background-color: rgba(0, 0, 0, 0.2);
        border: 1px #eee solid;
    }
    table.nddPrintTempTable, .nddPrintTempTable td, .nddPrintTempTable th {
        border: 1px solid black;
    }    
    .bosooText {
      -webkit-transform:rotate(-90deg); 
      -moz-transform:rotate(-90deg); 
      -o-transform: rotate(-90deg);
    }        
    #nddBookPrintForm .radio-inline + .radio-inline {
        margin-left: -13px !important;
    }
    .ndd-emp-info td {
        font-size: 12px;
        line-height: 14px !important;
    }
    .ndd-emp-info tr td:nth-child(1) {
        text-align: right;
    }
    .ndd-emp-info tr td:nth-child(2) {
        font-weight: 700;
    }
</style>

<script type="text/javascript">
$(function(){
    
    $('#nddEmployeeKeyId_displayField', '#ndd-book-<?php echo $this->uniqId; ?>').focus();
    
    $('#ndd-book-<?php echo $this->uniqId; ?>').on('change', '#nddMonthPrev', function(){
        var _thisVal = $(this).val();

        if (_thisVal === '') {
            $('#nddMonthNext', '#ndd-book-<?php echo $this->uniqId; ?>').prop('disabled', true);
        } else {
            $('#nddMonthNext', '#ndd-book-<?php echo $this->uniqId; ?>').prop('disabled', false).empty();
            for (var i = _thisVal; i <= 12; i++) {
                $('#nddMonthNext', '#ndd-book-<?php echo $this->uniqId; ?>').append("<option value='"+i+"'>"+i+"</option>");
            }
        }
    });
    $('#nddMonthPrev', '#ndd-book-<?php echo $this->uniqId; ?>').trigger('change');
        
    $('#ndd-book-<?php echo $this->uniqId; ?>').on('change', "input[data-path='nddEmployeeKeyId']", function(e){
        
        var _this = $(this);
        var rowData = JSON.parse(_this.attr('data-row-data'));
        var employeeId = rowData.employeeid;
                        
        $('#nddEmpPicture', '#ndd-book-<?php echo $this->uniqId; ?>').html('<img src="'+rowData.picture+'" data-default-image="assets/core/global/img/grid_layout/profile.png" onerror="onDataViewImgError(this);" class="rounded-circle" style="width: 50px; height: 50px">');
        $('#nddEmpLastName', '#ndd-book-<?php echo $this->uniqId; ?>').text((rowData.lastname != null) ? rowData.lastname : '');
        $('#nddEmpFirstName', '#ndd-book-<?php echo $this->uniqId; ?>').text((rowData.firstname != null) ? rowData.firstname : '');
        $('#nddEmpRegister', '#ndd-book-<?php echo $this->uniqId; ?>').text((rowData.stateregnumber != null) ? rowData.stateregnumber : '');
        $('#nddEmpDepartment', '#ndd-book-<?php echo $this->uniqId; ?>').text((rowData.departmentname != null) ? rowData.departmentname : '');
        $('#nddEmpPosition', '#ndd-book-<?php echo $this->uniqId; ?>').text((rowData.positionname != null) ? rowData.positionname : '');
        
        $('table.ndd-emp-info', '#ndd-book-<?php echo $this->uniqId; ?>').show();
        
        $('input[name="nddEmployeeId"]', '#ndd-book-<?php echo $this->uniqId; ?>').val(employeeId);
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/getEmployeeLastConfig',
            data: {employeeId: employeeId, type: '<?php echo $this->type; ?>'},
            dataType: 'json',
            success: function (data) {
                var rowNumber = Number(data.ROW_NUMBER) + 1;
                var pageNumber = Number(data.PAGE_NUMBER);
                var bookTypeId = data.BOOK_TYPE_ID;
                
                $('#bookTypeId', '#ndd-book-<?php echo $this->uniqId; ?>').val(bookTypeId);
                
                if (rowNumber == 13) {
                    $('#nddMonthPrev', '#ndd-book-<?php echo $this->uniqId; ?>').val('1');
                    
                    if (pageNumber % 2 === 0) {
                        $('#nddPageOdd', '#ndd-book-<?php echo $this->uniqId; ?>').prop("checked", true);
                    }
                } else {
                    $('#nddMonthPrev', '#ndd-book-<?php echo $this->uniqId; ?>').val(rowNumber);
                    
                    if (pageNumber % 2 === 0) {
                        $('#nddPageEven', '#ndd-book-<?php echo $this->uniqId; ?>').prop("checked", true);
                    } else {
                        $('#nddPageOdd', '#ndd-book-<?php echo $this->uniqId; ?>').prop("checked", true);
                    }
                }
                
                Core.updateUniform($("input[name='nddPageNum']", '#ndd-book-<?php echo $this->uniqId; ?>'));
                
                $('#nddMonthPrev', '#ndd-book-<?php echo $this->uniqId; ?>').trigger('change');
            }
        });
    });
    
    $('#ndd-book-<?php echo $this->uniqId; ?>').on('click', '.nddBookFilter', function(){
        $('#nddBookPrintForm', '#ndd-book-<?php echo $this->uniqId; ?>').validate({errorPlacement: function () {}});
                        
        if ($('#nddBookPrintForm', '#ndd-book-<?php echo $this->uniqId; ?>').valid()) {
            $.ajax({
                type: 'post',
                url: 'mdtemplate/renderNDDBook',
                data: $('#nddBookPrintForm', '#ndd-book-<?php echo $this->uniqId; ?>').serialize(),
                dataType: 'json',
                success: function(data){
                    $('#nddContentsPre', '#ndd-book-<?php echo $this->uniqId; ?>').html(data.html);
                }
            }).done(function(){
                $('#nddContentsPrintPrev', '#ndd-book-<?php echo $this->uniqId; ?>').draggable({
                    appendTo: '#ndd-book-<?php echo $this->uniqId; ?> #nddContentsPre',
                    containment: $('#nddContentsPre', '#ndd-book-<?php echo $this->uniqId; ?>'),
                    cursor: 'move',
                    scroll: false
                });
            });
        }
    });
    
    $('#ndd-book-<?php echo $this->uniqId; ?>').on('click', '.nddBookPrint', function(){
        
        if ($.trim($('#nddContentsPrintPrev', '#ndd-book-<?php echo $this->uniqId; ?>').html()) !== '') {
        
            var topSize = Number($('#nddContentsPrintPrev', '#ndd-book-<?php echo $this->uniqId; ?>').css('top').replace('px', ''));
            var leftSize = Number($('#nddContentsPrintPrev', '#ndd-book-<?php echo $this->uniqId; ?>').css('left').replace('px', ''));
            var defaultMarginSize = 8;

            $('body').append("<div class='nddContents'><div style='position: absolute;padding: 0; top: "+(topSize + defaultMarginSize)+"px; left: "+(leftSize + defaultMarginSize)+"px;'>"+$('#nddContentsPrintPrev', '#ndd-book-<?php echo $this->uniqId; ?>').html()+"</div></div>");

            $('.nddContents').promise().done(function() {

                $('.nddContents').printThis({
                    debug: false,             
                    importCSS: false,           
                    printContainer: false,      
                    dataCSS: "@page{size: A4 portrait;margin-top: 0;margin-right: 0;margin-bottom: 0;margin-left: 0;margin: 0;padding: 0;}*{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}*:before,*:after{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}html, body{margin: 0;padding: 0;-webkit-print-color-adjust: exact;font-family: Arial, sans-serif;font-size: 11px;color: #000;font-weight: bold}.nddContents, #nddContentsPrintPrev{margin: 0;padding: 0;display: table}.nddColPre,.nddCol{display: inline-block;position: absolute;padding: 0;}.right-rotate{-webkit-transform: rotate(90deg);-moz-transform: rotate(90deg);-ms-transform: rotate(90deg);-o-transform: rotate(90deg);transform: rotate(90deg)}.left-rotate{-webkit-transform: rotate(270deg);-moz-transform: rotate(270deg);-ms-transform: rotate(270deg);-o-transform: rotate(270deg);transform: rotate(270deg)}table.pf-report-table-none, table.pf-report-table-none td, table.pf-report-table-none th{border: 0px #fff solid}table.pf-report-table-dotted, table.pf-report-table-dotted td, table.pf-report-table-dotted th{border: 1px #000 dotted}table.pf-report-table-dashed, table.pf-report-table-dashed td, table.pf-report-table-dashed th{border: 1px #000 dashed}table.pf-report-table-solid, table.pf-report-table-solid td, table.pf-report-table-solid th{border: 1px #000 solid}#nddContentsPrintPrev{border:0;}",
                    removeInline: false        
                });

                if ($('body').find('.nddContents').length > 0) {
                    $('body').find('.nddContents').remove();
                }                            
            });
            
        } else {
            PNotify.removeAll();
            new PNotify({
                title: 'Info',
                text: 'Хэвлэх дүн татагдаагүй байна!',
                type: 'info',
                sticker: false
            });    
        }
    });
    
});

function nddSelectabledGrid(metaDataCode, chooseType, elem, rows) {
    var row = rows[0];
    var _parent = $(elem).closest("div.meta-autocomplete-wrap");
    _parent.find("input[id*='_valueField']").val(row.id);
    _parent.find("input[id*='_displayField']").val(row.employeecode);
    _parent.find("input[id*='_nameField']").val(row.firstname);
    
    $("input[data-path='nddEmployeeKeyId']", '#ndd-book-<?php echo $this->uniqId; ?>').attr('data-row-data', JSON.stringify(row)).trigger('change');
}  
</script>