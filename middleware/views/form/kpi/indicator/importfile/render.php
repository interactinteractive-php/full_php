<form id="createmvstructurefromfile-form" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($this->isCreateIndicator)) {
            ?>
            <div class="form-group row">
                <?php 
                echo Form::label(array(
                    'text' => $this->lang->line('info_name'),  
                    'class' => 'col-form-label col-md-2 text-right pr0 pt5', 
                    'required' => 'required'
                )); 
                ?>
                <div class="col-md-6">
                    <?php 
                    echo Form::text(array(
                        'class' => 'form-control form-control-sm stringInit', 
                        'name' => 'name', 
                        'required' => 'required', 
                        'placeholder' => $this->lang->line('info_name')
                    ));
                    ?> 
                </div>
            </div>
            <?php
            }
            ?>
            <div class="form-group row">
                <?php 
                echo Form::label(array(
                    'text' => $this->lang->line('file').' /txt, xls, xlsx, csv/',  
                    'class' => 'col-form-label col-md-2 text-right pr0 pt5', 
                    'required' => 'required'
                )); 
                ?>
                <div class="col-md-6">
                    <?php 
                    echo Form::file(array(
                        'class' => 'form-control form-control-sm fileInit', 
                        'name' => 'importStructureFile', 
                        'required' => 'required', 
                        'data-valid-extension' => 'xls, xlsx, csv, txt', 
                        'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, text/csv, application/csv, text/comma-separated-values, text/plain'
                    ));
                    ?> 
                </div>
            </div>
        </div>  
        <div class="col-md-12">
            
            <fieldset class="collapsible border-fieldset mt-2 mb-3">
                <legend>File format</legend>
                <div class="row">
                    <div class="col-md-3">
                        
                        <div class="form-group row">
                            <?php 
                            $headerCheckBox = Form::checkbox(array('class' => 'form-control form-control-sm', 'id' => 'headerCheckBox', 'value' => '1', 'saved_val' => '1'));
                            echo Form::label(array(
                                'text' => $headerCheckBox . ' Header',  
                                'class' => 'col-form-label col-md-4 text-right pr0 pt5', 
                                'for' => 'headerCheckBox'
                            )); 
                            ?>
                            <div class="col-md-6">
                                <?php 
                                echo Form::select(array(
                                    'class' => 'form-control form-control-sm', 
                                    'name' => 'headerSkip', 
                                    'data' => array(
                                        array(
                                            'id' => 'before_skip', 
                                            'name' => 'Before skip'
                                        ), 
                                        array(
                                            'id' => 'after_skip', 
                                            'name' => 'After skip'
                                        )
                                    ), 
                                    'op_value' => 'id', 
                                    'op_text' => 'name', 
                                    'value' => 'before_skip', 
                                    'text' => 'notext'
                                ));
                                ?> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group row">
                            <?php 
                            echo Form::label(array('text' => 'Skip rows', 'class' => 'col-form-label col-md-7 text-right pr0 pt5')); 
                            ?>
                            <div class="col-md-5">
                                <?php 
                                echo Form::number(array('class' => 'form-control form-control-sm longInit', 'name' => 'skipRows', 'value' => 0, 'min' => 0));
                                ?> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group row">
                            <?php 
                            echo Form::label(array('text' => 'Skip columns', 'class' => 'col-form-label col-md-7 text-right pr0 pt5')); 
                            ?>
                            <div class="col-md-5">
                                <?php 
                                echo Form::number(array('class' => 'form-control form-control-sm longInit', 'name' => 'skipColumns', 'value' => 0, 'min' => 0));
                                ?> 
                            </div>
                        </div>
                    </div>
                </div>    
                
                <div class="row mt10">
                    <div class="col-md-3">
                        
                        <div class="form-group row" id="mv-file-import-delimiter">
                            <?php 
                            echo Form::label(array(
                                'text' => 'Delimiter',  
                                'class' => 'col-form-label col-md-4 text-right pr0 pt5'
                            )); 
                            ?>
                            <div class="col-md-6">
                                <?php 
                                echo Form::select(array(
                                    'class' => 'form-control form-control-sm', 
                                    'name' => 'delimiter', 
                                    'data' => array(
                                        array(
                                            'id' => ','
                                        ), 
                                        array(
                                            'id' => '|'
                                        ), 
                                        array(
                                            'id' => ';'
                                        ), 
                                        array(
                                            'id' => ':'
                                        ), 
                                        array(
                                            'id' => 'tab'
                                        )
                                    ), 
                                    'op_value' => 'id', 
                                    'op_text' => 'id', 
                                    'value' => '|', 
                                    'text' => 'notext'
                                ));
                                ?> 
                            </div>
                        </div>
                        <div class="form-group row" id="mv-file-import-sheetnames" style="display: none">
                            <?php 
                            echo Form::label(array(
                                'text' => 'Sheet',  
                                'class' => 'col-form-label col-md-4 text-right pr0 pt5'
                            )); 
                            ?>
                            <div class="col-md-6">
                                <?php 
                                echo Form::select(array(
                                    'class' => 'form-control form-control-sm', 
                                    'name' => 'sheetNames', 
                                    'data' => array(),
                                    'text' => 'notext'
                                ));
                                ?> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group row">
                            <?php 
                            echo Form::label(array('text' => 'Preview row limit', 'class' => 'col-form-label col-md-7 text-right pr0 pt5')); 
                            ?>
                            <div class="col-md-5">
                                <?php 
                                echo Form::number(array('class' => 'form-control form-control-sm longInit', 'name' => 'previewRowLimit', 'value' => 100));
                                ?> 
                            </div>
                        </div>
                    </div>
                </div>    
            </fieldset>
            
            <fieldset class="collapsible border-fieldset mt-2 mb-3">
                <legend>File contents</legend>
                <div id="mv-importfile-preview-rows" class="bp-overflow-xy-auto" style="max-height: 500px">
                </div>
            </fieldset>
        </div>
    </div>     
</form> 

<script type="text/javascript">
var $form = $('#createmvstructurefromfile-form');
var $formDialog = $('#dialog-mvrows-createstructure');
var mvFileReader = new FileReader();
var mvFileReaderExtention = '';
var mvFileWorkbook;
var mvFileRowsData;
var mvFileFirstRow;
var mvFileFirstData;
var mvFileIsImportManage = <?php echo $this->isImportManage; ?>;
    
$(function() {
    
    $form.on('change', 'input[name="importStructureFile"]', function() {
        
        var $fileInput = $(this);
        var file = $fileInput[0].files[0];
        var fileName = file.name;
        
        $form.find('input[name="name"]').val(fileName);
        
        mvFileReaderExtention = (fileName.split('.').pop()).toLowerCase();
        
        if (mvFileReaderExtention == 'txt') {
            mvFileReader.readAsText(file, 'UTF-8');
        }

        mvFileReader.onload = function () {
            mvFileToHtmlTable(false);
        };

        mvFileReader.onerror = function (err) {
            var errorMsg = err.target.error.message;

            if (errorMsg.indexOf('file could not be read') !== -1 || errorMsg.indexOf('ERR_UPLOAD_FILE_CHANGED') !== -1) {

                $fileInput.val('');

                PNotify.removeAll();
                new PNotify({
                    title: 'Info',
                    text: 'Таны сонгосон файл дээр өөрчлөлт орсон тул та файлаа дахин сонгоно уу.',
                    type: 'info',
                    sticker: false, 
                    delay: 1000000000, 
                    addclass: 'pnotify-center'
                });
                Core.unblockUI();
            }
        };
        
        if (mvFileReaderExtention == 'xls' || mvFileReaderExtention == 'xlsx') {
            mvFileReader.readAsBinaryString(file);
        }
    });
    
    $form.on('change', 'select[name="delimiter"], select[name="sheetNames"], input[name="previewRowLimit"], input#headerCheckBox, input[name="skipRows"], input[name="skipColumns"]', function() {
        mvFileToHtmlTable(true);
    });
});    

function mvFileToHtmlTable(isInputRead) {
    Core.blockUI({message: 'Loading...', boxed: true});
            
    setTimeout(function() {
        
        var skipColumns = Number($form.find('input[name="skipColumns"]').val());
        var skipRows = Number($form.find('input[name="skipRows"]').val());
        
        if (mvFileReaderExtention == 'txt') {
            
            $form.find('#mv-file-import-sheetnames').hide();
            $form.find('#mv-file-import-delimiter').show();
                
            mvFileRowsData = (mvFileReader.result).split("\r\n");
            
        } else {
            
            if (isInputRead == false) {
                
                $.cachedScript('assets/custom/addon/plugins/sheetjs/xlsx.full.min.js?v=1', {async: false});

                mvFileWorkbook = XLSX.read(mvFileReader.result, {type: 'binary', cellDates: true, raw: true, cellText: true, dense: true, dateNF: 'YYYY-MM-DD'});
                var sheetNames = mvFileWorkbook.SheetNames;
                var $sheetCombo = $form.find('select[name="sheetNames"]');

                $sheetCombo.empty();

                $form.find('#mv-file-import-delimiter').hide();
                $form.find('#mv-file-import-sheetnames').show();

                for (var s in sheetNames) {
                    $sheetCombo.append('<option value="'+sheetNames[s]+'">'+sheetNames[s]+'</option>');
                }
            }
            
            var getSheet = mvFileWorkbook.Sheets[$form.find('select[name="sheetNames"]').val()];
            var sheetRange = XLSX.utils.decode_range(getSheet['!ref']);
            
            mvFileFirstData = getSheet['!data'][skipRows + 1];
            
            sheetRange.s.r = 0; //start row
            sheetRange.s.c = skipColumns; //start column
            getSheet['!ref'] = XLSX.utils.encode_range(sheetRange);
            
            mvFileRowsData = XLSX.utils.sheet_to_json(getSheet, {header: 1, raw: false, blankrows: false, defval: '', dateNF: 'YYYY-MM-DD'});
        }
        
        var rowsLength = mvFileRowsData.length;

        if (rowsLength > 0) {

            var delimiter = $form.find('select[name="delimiter"]').val(), 
                previewRowLimit = Number($form.find('input[name="previewRowLimit"]').val()),
                isHeader = $form.find('#headerCheckBox').is(':checked'), 
                rowData, n = 1, htmlTbl = [], firstKey = 0;
                
            if (delimiter == 'tab') {
                delimiter = "　";
            }    
            
            if (skipRows > 0) {
                mvFileRowsData = mvFileRowsData.slice(skipRows);
            }
            
            if (mvFileReaderExtention == 'txt') {
                mvFileFirstRow = (mvFileRowsData[firstKey]).split(delimiter);
            } else {
                mvFileFirstRow = mvFileRowsData[firstKey];
            }

            htmlTbl.push('<table class="table table-bordered table-hover">');
                htmlTbl.push('<thead>');
                    htmlTbl.push('<tr class="table-border-solid">');
                
                    if (isHeader) {
                        
                        for (var f in mvFileFirstRow) {
                            htmlTbl.push('<th>'+mvFileFirstRow[f]+'</th>');
                        }
                        
                        mvFileRowsData.splice(firstKey, 1);
                        
                    } else {
                        for (var f in mvFileFirstRow) {
                            htmlTbl.push('<th>Column'+(Number(f) + 1)+'</th>');
                        }
                    }
                
                    htmlTbl.push('</tr>');
                htmlTbl.push('</thead>');

                htmlTbl.push('<tbody>');

                for (var r in mvFileRowsData) {
                    
                    if (mvFileReaderExtention == 'txt') {
                        rowData = (mvFileRowsData[r]).split(delimiter);
                    } else {
                        rowData = mvFileRowsData[r];
                    }

                    htmlTbl.push('<tr>');
                        for (var v in rowData) {
                            htmlTbl.push('<td>'+rowData[v]+'</td>');
                        }
                    htmlTbl.push('</tr>');

                    if (n == previewRowLimit) {
                        break;
                    }
                    n++;
                }

                htmlTbl.push('</tbody>');
            htmlTbl.push('</table>');

            $form.find('#mv-importfile-preview-rows').empty().append(htmlTbl.join(''));

            $formDialog.dialog('option', 'position', {my: 'center', at: 'center', of: window});
        }

        Core.unblockUI();
    }, 100);
}
</script>