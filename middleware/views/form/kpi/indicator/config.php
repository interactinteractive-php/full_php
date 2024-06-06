<div class="kpi-ind-tmplt-section" id="kpi-<?php echo $this->uniqId; ?>">
    
    <div class="kpi-ind-tmplt-form"><?php echo $this->form; ?></div>
    
    <?php 
    echo Form::hidden(array('name' => 'templateTableName', 'value' => $this->templateTableName));
    echo Form::hidden(array('name' => 'kpiMainIndicatorId', 'value' => $this->kpiMainIndicatorId));
    echo Form::hidden(array('name' => 'kpiIndicatorIndicatorMapId', 'value' => $this->kpiIndicatorIndicatorMapId));
    ?>
</div>

<script type="text/javascript">
var dataMartLookupId = '16424911273171';
var mvHeaderFields = $.ajax({
    type: 'post',
    url: 'mdform/getIndicatorInputFields', 
    data: {indicatorId: '<?php echo $this->kpiMainIndicatorId; ?>'},
    dataType: 'json',
    async: false
});
mvHeaderFields = mvHeaderFields.responseJSON;
mvHeaderFields = mvHeaderFields.input;

var mvHeaderFieldsCombo = '<select name class="form-control form-control-sm">';
mvHeaderFieldsCombo += '<option value="">- Сонгох -</option>';

for (var h in mvHeaderFields) {
    mvHeaderFieldsCombo += '<option value="'+(mvHeaderFields[h]['COLUMN_NAME']).toLowerCase()+'">'+mvHeaderFields[h]['COLUMN_NAME']+' - '+mvHeaderFields[h]['LABEL_NAME']+'</option>';
}
mvHeaderFieldsCombo += '</select>';

function addRowKpiIndicatorTemplateConfig(elem, rowElem, commandCode) {
    var $this = $(elem), 
        $parent = $this.closest('div'), 
        $nextDiv = $parent.next('div'), 
        $script = $nextDiv.nextAll('script[data-template="templateConfig"]'), 
        $tbody = $nextDiv.find('table.table:eq(0) > tbody'), 
        $tbodyCells = $tbody.find('[data-col-path]'), 
        $parentForm = $this.closest('.kpi-ind-tmplt-section'), 
        templateTableName = $parentForm.find('input[name="templateTableName"]').val(), 
        tbodyCellsLength = $tbodyCells.length, 
        jsonConfig = JSON.parse($script.text()), 
        htmlTbl = [], rowId = '', nextId = '', parentId = '', 
        cellObj = {}, rowDataObj = {}, isEdit = false; 
        
    if (tbodyCellsLength) {
        var i = tbodyCellsLength - 1;
        
        for (i; i >= 0; i--) { /*for (i; i < tbodyCellsLength; i++) {*/ 
            
            var $cellInput = $($tbodyCells[i]), 
                $parentCell = $cellInput.closest('td'), 
                $parentRow = $cellInput.closest('tr'), 
                colName = $cellInput.attr('data-col-path'), 
                cellRowId = $parentRow.attr('data-id'), 
                cellRowIndex = $parentRow.attr('data-row-index'), 
                alphaCode = $parentCell.attr('data-alpha-code');
            
            var trans = {'A': 'A|', 'B': 'B|', 'C': 'C|', 'D': 'D|', 'E': 'E|', 'F': 'F|', 'G': 'G|', 'H': 'H|', 'I': 'I|', 'J': 'J|', 'K': 'K|'};
            colName = strtr(colName, trans);
            
            cellObj[alphaCode + cellRowIndex] = colName + '.' + cellRowId;
        }
    }

    if (typeof commandCode != 'undefined') {
        
        rowId = rowElem.attr('data-id');

        if (commandCode == 'nextRow') {
            nextId = rowId;
        } else if (commandCode == 'childRow') {
            parentId = rowId;
        } else if (commandCode == 'editRow') {
            
            Core.blockUI({message: 'Loading...', boxed: true});
            
            var rowData = $.ajax({
                type: 'post',
                url: 'mdform/getKpiIndicatorTemplateRow', 
                data: {templateTableName: templateTableName, rowId: rowId},
                dataType: 'json',
                async: false
            });
            
            rowData = rowData.responseJSON;
            
            if (rowData.hasOwnProperty('rowData') && Object.keys(rowData.rowData).length) {
                rowDataObj = rowData.rowData;
                isEdit = true;
            }
        }
    } 
    
    htmlTbl.push('<form>');
        htmlTbl.push('<table class="table table-bordered" style="table-layout: fixed;">');
            htmlTbl.push('<thead>');
                htmlTbl.push('<tr>');
                    htmlTbl.push('<th class="font-weight-bold" style="width: 160px">Үзүүлэлт</th>');
                    htmlTbl.push('<th class="font-weight-bold">Утга</th>');
                htmlTbl.push('</tr>');
            htmlTbl.push('</thead>');
            htmlTbl.push('<tbody>');

            for (var i in jsonConfig) {
                
                var columnName = jsonConfig[i]['COLUMN_NAME'];
                
                htmlTbl.push('<tr>');
                    htmlTbl.push('<td class="text-right">'+jsonConfig[i]['NAME']+'</td>');
                    htmlTbl.push('<td>');

                    if (jsonConfig[i]['SEMANTIC_TYPE_NAME'] != 'Багана') {
                        
                        var control = jsonConfig[i]['control'];
                        
                        htmlTbl.push(control);
                        
                        if (jsonConfig[i]['SHOW_TYPE'] == 'combo') {
                            htmlTbl.push('<input type="text" name="descName['+columnName+']" class="form-control form-control-sm mt-1">');    
                        } else {
                            htmlTbl.push('<input type="hidden" name="descName['+columnName+']">');
                        }
                        
                    } else {
                        
                        htmlTbl.push('<input type="hidden" name="cellJson['+columnName+']">');
                        
                        htmlTbl.push(cellJsonKpiIndicatorTemplateConfig(columnName, isEdit));
                    }

                    htmlTbl.push('</td>');
                htmlTbl.push('</tr>');
            }
            
                htmlTbl.push('<tr>');
                    htmlTbl.push('<td>Мөрний харагдац</td>');
                    htmlTbl.push('<td>');
                        htmlTbl.push('<select name="kpiTbl[ROW_STYLE]" data-col-path="ROW_STYLE" class="form-control form-control-sm">');
                            htmlTbl.push('<option value="">---</option>');
                            htmlTbl.push('<option value="yellowbold">Yellow bold</option>');
                            htmlTbl.push('<option value="orangebold">Orange bold</option>');
                            htmlTbl.push('<option value="bluebold">Blue bold</option>');
                            htmlTbl.push('<option value="orangebottom">Orange bottom</option>');
                        htmlTbl.push('</select>');
                    htmlTbl.push('</td>');
                htmlTbl.push('</tr>');    

            htmlTbl.push('</tbody>');
        htmlTbl.push('</table>');
        
        htmlTbl.push('<input type="hidden" name="rowId" value="'+rowId+'">');
        htmlTbl.push('<input type="hidden" name="parentId" value="'+parentId+'">');
        htmlTbl.push('<input type="hidden" name="nextId" value="'+nextId+'">');
        
    htmlTbl.push('</form>');
    
    var $dialogName = 'dialog-kpiindicatortemplateaddrow';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);

    $dialog.empty().append(htmlTbl.join(''));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'KPI Indicator template - add row',
        width: 950,
        height: 'auto',
        maxHeight: $(window).height() - 10,
        position: {my: 'top', at: 'top+0'},
        modal: true, 
        open: function() {
            
            if (isEdit) {
                
                var $nameControls = $dialog.find('[data-col-path]');
                var $expControls = $dialog.find('[name*="cellJsonComment"]');
                var $getValFromMart = $dialog.find('[data-getval-frommart]');
                
                $nameControls.each(function() {
                    var $nameControl = $(this), columnName = $nameControl.attr('data-col-path');
                    
                    if (rowDataObj.hasOwnProperty(columnName)) {
                        
                        var savedVal = rowDataObj[columnName];

                        if (rowDataObj.hasOwnProperty(columnName + '_DESC')) {

                            $dialog.find('input[name="descName['+columnName+']"]').val(rowDataObj[columnName + '_DESC']);

                            if (savedVal === null || savedVal == '') {
                                savedVal = rowDataObj[columnName + '_DESC'];
                            }
                        }
                        
                        $nameControl.val(savedVal);
                    }
                });
                
                $expControls.each(function() {
                    var $expControl = $(this), columnName = $expControl.attr('data-colname');
                    
                    if (rowDataObj.hasOwnProperty(columnName) && rowDataObj[columnName] && (rowDataObj[columnName]).indexOf('{"') !== -1) {
                        
                        var cellJson = JSON.parse(rowDataObj[columnName]);
                        
                        if (cellJson.hasOwnProperty('comment')) {
                            $expControl.val(cellJson.comment);
                        }
                        
                        if (cellJson.hasOwnProperty('expression') && cellJson.expression) {
                            
                            var convertExpression = cellJson.expression;
                            
                            for (var c in cellObj) {
                                
                                var trans = {'A|': 'A', 'B|': 'B', 'C|': 'C', 'D|': 'D', 'E|': 'E', 'F|': 'F', 'G|': 'G', 'H|': 'H', 'I|': 'I'};
                                var colName = strtr(cellObj[c], trans);
                                
                                convertExpression = str_replace('[' + colName + ']', c, convertExpression);
                            }
                
                            $dialog.find('input[data-cell-expression="'+columnName+'"]').val(convertExpression);
                            $dialog.find('input[name="cellJsonExpression['+columnName+']"]').val(cellJson.expression);
                        }
                        
                        if (cellJson.hasOwnProperty('defaultvalue') && cellJson.defaultvalue != '') {
                            $dialog.find('input[name="cellJsonDefaultValue['+columnName+']"]').val(cellJson.defaultvalue);
                        }
                        
                        if (cellJson.hasOwnProperty('style')) {
                            $dialog.find('select[name="cellJsonStyle['+columnName+']"]').val(cellJson.style);
                        }
                    }
                });
                
                $getValFromMart.each(function() {
                    var $martControl = $(this), columnName = $martControl.attr('data-getval-frommart');
                    var controlHtml = [], getValFromMartExpression = '', isGetValFromMartExpression = false;
                    
                    controlHtml.push('<button type="button" class="btn btn-xs green-meadow" onclick="addRowGetValIndicatorConfig(this, \''+columnName+'\');"><i class="far fa-plus"></i> Нэмэх</button>');
                    
                    controlHtml.push('<div data-dtl-config="1">');
                    
                        if (rowDataObj.hasOwnProperty(columnName) && rowDataObj[columnName] && (rowDataObj[columnName]).indexOf('{"') !== -1) {

                            var cellJson = JSON.parse(rowDataObj[columnName]);

                            if (cellJson.hasOwnProperty('getValFromMart') && cellJson.getValFromMart) {
                                var getValFromMart = cellJson.getValFromMart;
                                
                                for (var indicatorId in getValFromMart) {
                                    
                                    if (indicatorId == 'expression') {
                                        getValFromMartExpression = getValFromMart['expression'];
                                        isGetValFromMartExpression = true;
                                        continue;
                                    }
                                    
                                    var itemConfig = {indicatorId: indicatorId};
                                    
                                    var response = $.ajax({
                                        type: 'post',
                                        url: 'mdform/getIndicatorRow',
                                        data: {indicatorId: indicatorId},
                                        dataType: 'json',
                                        async: false
                                    });
                                    var responseObj = response.responseJSON;
                                    
                                    itemConfig.indicatorCode = responseObj.CODE;
                                    itemConfig.indicatorName = responseObj.NAME;
                                    
                                    var response = $.ajax({
                                        type: 'post',
                                        url: 'mdform/getIndicatorInputOutputFields',
                                        data: {indicatorId: indicatorId},
                                        dataType: 'json',
                                        async: false
                                    });
                                    var responseObj = response.responseJSON;

                                    itemConfig.input = responseObj.input;
                                    itemConfig.output = responseObj.output;
                                    
                                    itemConfig.inputSaved = getValFromMart[indicatorId]['input'];
                                    itemConfig.outputSaved = getValFromMart[indicatorId]['output'];
                                    
                                    controlHtml.push(cellGetValueFromDataMart($martControl, columnName, itemConfig));
                                }
                            }
                        }
                    
                    controlHtml.push('</div>');
                    
                    controlHtml.push('<textarea name="cellJsonGetValFromMartExpression['+columnName+']" class="form-control form-control-sm mt-2" rows="3" placeholder="Томъёо бичих" data-getvalmart="expression" style="'+(isGetValFromMartExpression ? '' : 'display: none')+'">'+getValFromMartExpression+'</textarea>');
                    
                    $martControl.html(controlHtml.join(''));
                });
                
                Core.unblockUI();
            }
            
            Core.initSelect2($dialog);
        },
        close: function() {
            $dialog.empty().dialog('destroy').remove();
        },
        buttons: [
            {text: plang.get('save_btn_add'), class: 'btn btn-sm green-meadow bp-btn-saveadd', click: function () {
                saveKpiIndicatorTemplateConfig($dialog, $parentForm, templateTableName, true);
            }}, 
            {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-save', click: function () {
                saveKpiIndicatorTemplateConfig($dialog, $parentForm, templateTableName, false);
            }},
            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
    
    var previousTxt = '';
    
    $dialog.on('focusin', 'select.select2', function() {
        
        var $this = $(this);
        
        if ($this.val() != '') {
            if ($this.hasAttr('data-name')) {
                var $option = $this.find('option:selected');
                var rowData = $option.data('row-data');
                previousTxt = rowData[$this.attr('data-name')];
            } else {
                previousTxt = $this.find('option:selected').text();
            }
        }
        
    }).on('change', 'select.select2', function() {
        
        var $this = $(this), $row = $this.closest('tr'), $descName = $row.find('input[name*="descName["]');
        
        if ($descName.length) {
            
            var descName = '';
            
            if ($this.val() != '') {
                
                if ($this.hasAttr('data-name')) {
                    var $option = $this.find('option:selected');
                    var rowData = $option.data('row-data');
                    descName = rowData[$this.attr('data-name')];
                } else {
                    descName = $this.find('option:selected').text();
                }
                
                if ($descName.val() != '' && previousTxt != $descName.val()) {
                    return false;
                }
                
                previousTxt = descName;
            }
            
            $descName.val(descName);
        }
    });
    
    $dialog.on('change', 'input.form-control[data-col-path]', function() {
        var $this = $(this), $row = $this.closest('tr'), $descName = $row.find('input[name*="descName["]');
        if ($descName.length) {
            var descName = '';
            if ($this.val() != '') {
                descName = $this.val();
            }
            $descName.val(descName);
        }
    });
    
    $dialog.on('change', 'input[data-cell-expression]', function() {
        var $this = $(this), thisVal = $this.val(), convertExpression = '';
        
        if (thisVal != '') {
            
            convertExpression = thisVal;
            
            if (Object.keys(cellObj).length) {
                convertExpression = convertExpression.toUpperCase();
                for (var c in cellObj) {
                    var regex = new RegExp('\\b' + c + '\\b', 'g');
                    convertExpression = convertExpression.replace(regex, '[' + cellObj[c] + ']');
                }
                
                var trans = {'A|': 'A', 'B|': 'B', 'C|': 'C', 'D|': 'D', 'E|': 'E', 'F|': 'F', 'G|': 'G', 'H|': 'H', 'I|': 'I'};
                convertExpression = strtr(convertExpression, trans);
            }
        } 
        
        $this.next('input').val(convertExpression);
    });
    
    $dialog.on('change', 'input.popupInit', function() {
        var $this = $(this), indicatorId = $this.val(), $cell = $this.closest('td'), 
            $dtlConfig = $cell.find('div[data-config-item]');
        
        if (indicatorId) {
            var columnName = $this.attr('data-path');
            var config = {indicatorId: indicatorId};
            var response = $.ajax({
                type: 'post',
                url: 'mdform/getIndicatorInputOutputFields',
                data: {indicatorId: indicatorId},
                dataType: 'json',
                async: false
            });
            var responseObj = response.responseJSON;
            
            config.input = responseObj.input;
            config.output = responseObj.output;
            
            $dtlConfig.find('div[data-config-input="1"]').empty().append(inputGetValueFromDataMart($cell, columnName, config));
            $dtlConfig.find('div[data-config-output="1"]').empty().append(outputGetValueFromDataMart($cell, columnName, config));
        } else {
            $dtlConfig.empty();
        }
        
        expressionToggleGetValueFromDataMart($cell);
    });
}  
function expressionToggleGetValueFromDataMart($cell) {
    if ($cell.find('div[data-config-item="1"]').length) {
        $cell.find('textarea[data-getvalmart="expression"]').show();
    } else {
        $cell.find('textarea[data-getvalmart="expression"]').hide();
    }
}

function inputGetValueFromDataMart(elem, columnName, config) {
    var htmlTbl = [], indicatorId = config.indicatorId, inputs = config.input, inputSaved = {};
    var buttonAttr = '', iconName = 'fa-minus-square', tableStyle = '';
    
    if (config.hasOwnProperty('inputSaved')) {
        inputSaved = config.inputSaved;
        buttonAttr = ' data-expand="1"'; 
        iconName = 'fa-plus-square'; 
        tableStyle = ' style="display: none;"';
    }
    
    htmlTbl.push('<a href="javascript:;" class="d-block mt-2 mb-1 font-size-16" onclick="nextTblToggleGetValueFromDataMart(this);"'+buttonAttr+'>Оролт <i class="far '+iconName+'"></i></a>');
    htmlTbl.push('<table class="table table-bordered table-hover mb-2"'+tableStyle+'>');
        htmlTbl.push('<thead>');
            htmlTbl.push('<tr>');
                htmlTbl.push('<th class="font-weight-bold" style="width: 28px">№</th>');
                htmlTbl.push('<th class="font-weight-bold">Src</th>');
                htmlTbl.push('<th class="font-weight-bold">Trg</th>');
                htmlTbl.push('<th class="font-weight-bold" style="width: 140px">Default value</th>');
            htmlTbl.push('</tr>');
        htmlTbl.push('</thead>');
        htmlTbl.push('<tbody>');
        
        for (var i in inputs) {
            
            var setTrg = '', setDefaultVal = '';
            
            if (inputSaved && inputSaved.hasOwnProperty(inputs[i]['COLUMN_NAME'])) {
                setTrg = (inputSaved[inputs[i]['COLUMN_NAME']]['trg']).toLowerCase();
                setDefaultVal = inputSaved[inputs[i]['COLUMN_NAME']]['defaultVal'];
            }
            
            var inputTrgCombo = mvHeaderFieldsCombo.replace('name', 'name="cellJsonGetValFromMartInputTrg['+columnName+']['+indicatorId+'][]"');
            inputTrgCombo = inputTrgCombo.replace('value="'+setTrg+'"', 'value="'+setTrg+'" selected');
            
            htmlTbl.push('<tr>');
                htmlTbl.push('<td>'+(Number(i)+1)+'</td>');
                htmlTbl.push('<td>');
                    htmlTbl.push(inputs[i]['COLUMN_NAME']+' - '+inputs[i]['LABEL_NAME']);
                    htmlTbl.push('<input type="hidden" name="cellJsonGetValFromMartInputSrc['+columnName+']['+indicatorId+'][]" value="'+inputs[i]['COLUMN_NAME']+'">');
                htmlTbl.push('</td>');
                htmlTbl.push('<td>'+inputTrgCombo+'</td>');
                htmlTbl.push('<td><input type="text" name="cellJsonGetValFromMartInputDefaultVal['+columnName+']['+indicatorId+'][]" value="'+setDefaultVal+'" class="form-control form-control-sm"></td>');
            htmlTbl.push('</tr>');
        }
        
        htmlTbl.push('</tbody>');
    htmlTbl.push('</table>');
    
    return htmlTbl.join('');
}
function outputGetValueFromDataMart(elem, columnName, config) {
    var htmlTbl = [], indicatorId = config.indicatorId, outputs = config.output;
    var outputSaved = {};
    var buttonAttr = '', iconName = 'fa-minus-square', tableStyle = '';
    
    if (config.hasOwnProperty('outputSaved')) {
        outputSaved = config.outputSaved;
        buttonAttr = ' data-expand="1"'; 
        iconName = 'fa-plus-square'; 
        tableStyle = ' style="display: none;"';
    }
    
    htmlTbl.push('<a href="javascript:;" class="d-block mt-2 mb-1 font-size-16" onclick="nextTblToggleGetValueFromDataMart(this);"'+buttonAttr+'>Гаралт <i class="far '+iconName+'"></i></a>');
    htmlTbl.push('<table class="table table-bordered table-hover"'+tableStyle+'>');
        htmlTbl.push('<thead>');
            htmlTbl.push('<tr>');
                htmlTbl.push('<th class="font-weight-bold" style="width: 28px">№</th>');
                htmlTbl.push('<th class="font-weight-bold">Name</th>');
                htmlTbl.push('<th class="font-weight-bold" style="width: 98px">Aggregate</th>');
                htmlTbl.push('<th class="font-weight-bold">Path</th>');
            htmlTbl.push('</tr>');
        htmlTbl.push('</thead>');
        htmlTbl.push('<tbody>');
        
        for (var o in outputs) {
            
            var setAggregate = 'sum';
            
            if (Object.keys(outputSaved).length && outputSaved.hasOwnProperty(outputs[o]['COLUMN_NAME'])) {
                setAggregate = outputSaved[outputs[o]['COLUMN_NAME']]['aggregate'];
            }
            
            htmlTbl.push('<tr>');
                htmlTbl.push('<td>'+(Number(o)+1)+'</td>');
                htmlTbl.push('<td>');
                    htmlTbl.push(outputs[o]['LABEL_NAME']);
                    htmlTbl.push('<input type="hidden" name="cellJsonGetValFromMartOutputSrc['+columnName+']['+indicatorId+'][]" value="'+outputs[o]['COLUMN_NAME']+'">');
                htmlTbl.push('</td>');
                htmlTbl.push('<td>'+aggregateComboGetValueFromDataMart(columnName, indicatorId, setAggregate)+'</td>');
                htmlTbl.push('<td>['+indicatorId+'.'+outputs[o]['COLUMN_NAME']+']</td>');
            htmlTbl.push('</tr>');
        }
        
        htmlTbl.push('</tbody>');
    htmlTbl.push('</table>');
    
    return htmlTbl.join('');
}
function aggregateComboGetValueFromDataMart(columnName, indicatorId, aggrCode) {
    var combo = [];
    
    combo.push('<select class="form-control form-control-sm" name="cellJsonGetValFromMartOutputAggregate['+columnName+']['+indicatorId+'][]">');
        combo.push('<option value="sum">SUM</option>');
        combo.push('<option value="avg">AVG</option>');
        combo.push('<option value="count">COUNT</option>');
        combo.push('<option value="min">MIN</option>');
        combo.push('<option value="max">MAX</option>');
    combo.push('</select>');
    
    return (combo.join('')).replace('value="'+aggrCode+'"', 'value="'+aggrCode+'" selected');
}
function addRowGetValIndicatorConfig(elem, columnName) {
    var $this = $(elem), $next = $this.next('div[data-dtl-config="1"]');
    $next.append(cellGetValueFromDataMart(elem, columnName));
}

function cellGetValueFromDataMart(elem, columnName, config) {
    var html = [], indicatorId = '', indicatorCode = '', indicatorName = '';
    
    if (config && config.hasOwnProperty('indicatorId')) {
        indicatorId = config.indicatorId;
        indicatorCode = config.indicatorCode;
        indicatorName = config.indicatorName;
    }
    
    html.push('<div data-config-item="1" class="mt-2" style="position: relative;border: 1px #e9a22f solid;padding: 10px;">');
    
        html.push('<button type="button" class="btn btn-xs red" style="position: absolute;right: 10px;top: -23px;" onclick="removeCellGetValueFromDataMart(this);"><i class="far fa-trash"></i></button>');
    
        html.push('<div class="meta-autocomplete-wrap" data-section-path="'+columnName+'">');
            html.push('<div class="input-group double-between-input">');
                html.push('<input type="hidden" name="param['+columnName+']" value="'+indicatorId+'" id="'+columnName+'_valueField" data-path="'+columnName+'" class="popupInit" placeholder="Утга авах нэр март">');
                html.push('<input type="text" name="'+columnName+'_displayField" value="'+indicatorCode+'" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="'+columnName+'" id="'+columnName+'_displayField" data-processid="16424366405551" data-lookupid="'+dataMartLookupId+'" placeholder="кодоор хайх" autocomplete="off">');
                html.push('<span class="input-group-btn">');
                    html.push('<button type="button" class="btn default btn-bordered btn-xs mr-0" onclick="dataViewSelectableGrid(\''+columnName+'\', \'16424366405551\', \''+dataMartLookupId+'\', \'single\', \''+columnName+'\', this);" tabindex="-1"><i class="far fa-search"></i></button>');
                html.push('</span>');
                html.push('<span class="input-group-btn">');
                    html.push('<input type="text" name="'+columnName+'_nameField" value="'+indicatorName+'" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="'+columnName+'" id="'+columnName+'_nameField" data-processid="16424366405551" data-lookupid="'+dataMartLookupId+'" placeholder="нэрээр хайх">');
                html.push('</span>');
            html.push('</div>');
        html.push('</div>');
        
        html.push('<div data-config-input="1">');
            if (config && config.hasOwnProperty('input')) {
                html.push(inputGetValueFromDataMart(elem, columnName, config));
            }
        html.push('</div>');
        
        html.push('<div data-config-output="1">');
            if (config && config.hasOwnProperty('output')) {
                html.push(outputGetValueFromDataMart(elem, columnName, config));
            }
        html.push('</div>');
        
    html.push('</div>');
    
    return html.join('');
}

function removeCellGetValueFromDataMart(elem) {
    var dialogName = '#dialog-getvalfrommartcell-remove-confirm';
    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName);

    $dialog.html(plang.get('msg_delete_confirm'));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('msg_title_confirm'), 
        width: 300,
        height: 'auto',
        modal: true,
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                var $cell = $(elem).closest('td');
                $(elem).closest('div[data-config-item="1"]').remove();
                expressionToggleGetValueFromDataMart($cell);   
                $dialog.dialog('close');
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}

function nextTblToggleGetValueFromDataMart(elem) {
    var $this = $(elem), $tbl = $this.next('table');
    if ($this.hasAttr('data-expand')) {
        $tbl.show();
        $this.find('i').removeClass('fa-plus-square').addClass('fa-minus-square');
        $this.removeAttr('data-expand');
    } else {
        $tbl.hide();
        $this.find('i').removeClass('fa-minus-square').addClass('fa-plus-square');
        $this.attr('data-expand', 1);
    }
}

function saveKpiIndicatorTemplateConfig($dialog, $parentForm, templateTableName, isSaveAdd) {
    var $form = $dialog.find('form');    
    $form.validate({errorPlacement: function () {}});

    if ($form.valid()) {

        $form.ajaxSubmit({
            type: 'post',
            url: 'mdform/addRowKpiIndicatorTemplate',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            beforeSubmit: function(formData, jqForm, options) {
                formData.push(
                    { name: 'templateTableName', value: templateTableName }, 
                    { name: 'kpiMainIndicatorId', value: $parentForm.find('input[name="kpiMainIndicatorId"]').val() }, 
                    { name: 'kpiIndicatorIndicatorMapId', value: $parentForm.find('input[name="kpiIndicatorIndicatorMapId"]').val() }
                );
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
                    
                    if (isSaveAdd == false) {
                        $dialog.dialog('close');
                    }
                
                    $parentForm.find('input[name="templateTableName"]').val(data.templateTableName);
                    $parentForm.find('.kpi-ind-tmplt-form').empty().append(data.html);
                } 

                Core.unblockUI();
            }
        });
    }
}

function cellJsonKpiIndicatorTemplateConfig(colName, isEdit) {
    var htmlTbl = [];
    var readonlyAttr = isEdit ? '' : ' readonly="readonly"';
    
    htmlTbl.push('<table class="table table-bordered">');
        htmlTbl.push('<tbody>');
        
            htmlTbl.push('<tr>');
                htmlTbl.push('<td style="width: 112px">Cell comment</td>');
                htmlTbl.push('<td><input type="text" name="cellJsonComment['+colName+']" data-colname="'+colName+'" class="form-control form-control-sm"></td>');
            htmlTbl.push('</tr>');
            
            htmlTbl.push('<tr>');
                htmlTbl.push('<td>Cell expression</td>');
                htmlTbl.push('<td>');
                    htmlTbl.push('<input type="text" data-cell-expression="'+colName+'" class="form-control form-control-sm"'+readonlyAttr+'>');
                    htmlTbl.push('<input type="hidden" name="cellJsonExpression['+colName+']">');
                htmlTbl.push('</td>');
            htmlTbl.push('</tr>');
            
            htmlTbl.push('<tr>');
                htmlTbl.push('<td>Cell default value</td>');
                htmlTbl.push('<td>');
                    htmlTbl.push('<input type="text" name="cellJsonDefaultValue['+colName+']" class="form-control form-control-sm">');
                htmlTbl.push('</td>');
            htmlTbl.push('</tr>');
            
            htmlTbl.push('<tr>');
                htmlTbl.push('<td>Мартаас утга татах</td>');
                htmlTbl.push('<td data-getval-frommart="'+colName+'">');
                    
                htmlTbl.push('</td>');
            htmlTbl.push('</tr>');
            
            htmlTbl.push('<tr>');
                htmlTbl.push('<td>Cell style</td>');
                htmlTbl.push('<td>');
                    htmlTbl.push('<select name="cellJsonStyle['+colName+']" class="form-control form-control-sm">');
                        htmlTbl.push('<option value="">---</option>');
                        htmlTbl.push('<option value="yellowbold">Yellow bold</option>');
                        htmlTbl.push('<option value="orangebold">Orange bold</option>');
                        htmlTbl.push('<option value="bluebold">Blue bold</option>');
                    htmlTbl.push('</select>');
                htmlTbl.push('</td>');
            htmlTbl.push('</tr>');
            
        htmlTbl.push('</tbody>');
    htmlTbl.push('</table>');
        
    return htmlTbl.join('');
}
function removeRowKpiIndicatorTemplateConfig(elem) {
    var $row = $(elem);
    
    if ($row.prop('tagName').toLowerCase() == 'button') {
        $row = $row.closest('tr');
    }
    
    var rowId = $row.attr('data-id');
    var dialogName = '#dialog-kpiindicatortmp-confirm';

    if (!$(dialogName).length) {
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
    }
    var $dialog = $(dialogName);

    $dialog.html(plang.get('msg_delete_confirm'));
    $dialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: plang.get('msg_title_confirm'), 
        width: 300,
        height: 'auto',
        modal: true,
        buttons: [
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                PNotify.removeAll();
                
                var $parent = $row.closest('.kpi-ind-tmplt-section');
                var postData = {
                    rowId: rowId, 
                    templateTableName: $parent.find('input[name="templateTableName"]').val(), 
                    kpiMainIndicatorId: $parent.find('input[name="kpiMainIndicatorId"]').val(), 
                    kpiIndicatorIndicatorMapId: $parent.find('input[name="kpiIndicatorIndicatorMapId"]').val()
                };
                
                $.ajax({
                    type: 'post',
                    url: 'mdform/removeRowKpiDynamicTemplate',
                    data: postData, 
                    dataType: 'json',
                    beforeSend: function () {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function (data) {

                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false, 
                            addclass: pnotifyPosition
                        });

                        if (data.status == 'success') {
                            $dialog.dialog('close');
                            $parent.find('.kpi-ind-tmplt-form').empty().append(data.html);
                        }

                        Core.unblockUI();
                    }
                });
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $dialog.dialog('close');
            }}
        ]
    });
    $dialog.dialog('open');
}
function directionKpiIndicatorTemplateConfig(elem, direction) {
    var $this = $(elem), 
        $row = $this.closest('tr'),
        postData = {};
    
    if (direction == 'down') {
        
        var $nextRow = $row.next('tr');
        
        if ($nextRow.length) {
            
            var rowId = $row.attr('data-id');
            var nextRowId = $nextRow.attr('data-id');
            var $parent = $row.closest('.kpi-ind-tmplt-section');
            postData = {
                rowId: rowId, 
                nextRowId: nextRowId,
                direction: direction, 
                templateTableName: $parent.find('input[name="templateTableName"]').val(), 
                kpiMainIndicatorId: $parent.find('input[name="kpiMainIndicatorId"]').val(), 
                kpiIndicatorIndicatorMapId: $parent.find('input[name="kpiIndicatorIndicatorMapId"]').val()
            };
        }
        
    } else if (direction == 'up') {
        
        var $prevRow = $row.prev('tr');
        
        if ($prevRow.length) {
            
            var rowId = $row.attr('data-id');
            var prevRowId = $prevRow.attr('data-id');
            var $parent = $row.closest('.kpi-ind-tmplt-section');
            postData = {
                rowId: rowId, 
                prevRowId: prevRowId, 
                direction: direction, 
                templateTableName: $parent.find('input[name="templateTableName"]').val(), 
                kpiMainIndicatorId: $parent.find('input[name="kpiMainIndicatorId"]').val(), 
                kpiIndicatorIndicatorMapId: $parent.find('input[name="kpiIndicatorIndicatorMapId"]').val()
            };
        }
        
    } else if (direction == 'left' || direction == 'right') {
        
        var $parent = $row.closest('.kpi-ind-tmplt-section');
        var rowId = $row.attr('data-id');
        
        postData = {
            rowId: rowId, 
            direction: direction, 
            templateTableName: $parent.find('input[name="templateTableName"]').val(), 
            kpiMainIndicatorId: $parent.find('input[name="kpiMainIndicatorId"]').val(), 
            kpiIndicatorIndicatorMapId: $parent.find('input[name="kpiIndicatorIndicatorMapId"]').val()
        };
            
        var $prevRow = $row.prev('tr');
        var $nextRow = $row.next('tr');
        
        if ($prevRow.length) {
            
            var prevRowId = $prevRow.attr('data-id');
            postData.prevRowId = prevRowId;
        }
        
        if ($nextRow.length) {
            
            var nextRowId = $nextRow.attr('data-id');
            postData.nextRowId = nextRowId;
        }
    }

    if (Object.keys(postData).length) {
        
        $.ajax({
            type: 'post',
            url: 'mdform/directionRowKpiDynamicTemplate',
            data: postData, 
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {

                if (data.status == 'success') {
                    $parent.find('.kpi-ind-tmplt-form').empty().append(data.html);
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: pnotifyPosition
                    });
                }

                Core.unblockUI();
            }
        });
    }
}

$.contextMenu({
    selector: '#kpi-<?php echo $this->uniqId; ?> tr[data-row-index]',
    callback: function(key, opt) {
        var $this = opt.$trigger, $parent = $this.closest('.kpi-ind-tmplt-form'), 
            $addBtn = $parent.find('button[onclick*="addRowKpiIndicatorTemplateConfig("]');
        
        if (key == 'removeRow') {
            removeRowKpiIndicatorTemplateConfig($this);
        } else {
            addRowKpiIndicatorTemplateConfig($addBtn, $this, key);
        }
    },
    items: {
        "nextRow": {name: "Мөрний доор", icon: "plus"}, 
        "childRow": {name: "Харъяалагдах мөр", icon: "sitemap"}, 
        "editRow": {name: "Засах", icon: "edit"},
        "removeRow": {name: "Устгах", icon: "trash"}
    }
});
</script>