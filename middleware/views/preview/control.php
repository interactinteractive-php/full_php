<?php echo Mdcommon::addCustomFonts('linkUrl'); ?>
<div class="report-preview ctrl-print-container" data-type="statement">
    <div class="report-preview-toolbar">
        <div class="row">
            <div class="col">
                <?php
                echo Form::select(
                    array(
                        'class' => 'form-control form-control-sm float-left mr5',
                        'style' => 'width: 85px; height: 22px; line-height: 22px;',
                        'name' => 'statementPageOrientation',
                        'data' => array(
                            array(
                                'val' => 'landscape',
                                'text' => Lang::lineDefault('PRINT_0007', 'Хэвтээ')
                            ),
                            array(
                                'val' => 'portrait',
                                'text' => Lang::lineDefault('PRINT_0006', 'Босоо')
                            )
                        ),
                        'op_value' => 'val',
                        'op_text' => 'text',
                        'text' => 'notext',
                        'value' => $this->pageProperty['pageOrientation']
                    )
                );
                
                echo html_tag('span', array('class' => 'st-splitter'), '');
                
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary report-preview-printpreview ctrl-print-btn',
                        'value' => '<i class="fa fa-print"></i> '.$this->lang->line('print_btn')
                    ), $this->pageProperty['pagePrint']
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary report-preview-excel',
                        'value' => '<i class="fa fa-file-excel-o"></i> '.$this->lang->line('excel_btn')
                    ), $this->pageProperty['pageExcel']
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary report-preview-pdf',
                        'value' => '<i class="fa fa-file-pdf-o"></i> '.$this->lang->line('pdf_btn')
                    ), $this->pageProperty['pagePdf']
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary report-pdf-preview font-weight-bold',
                        'value' => '<i class="fa fa-file-pdf-o font-red"></i> PDF харах'
                    ), $this->pageProperty['pagePdfView']
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary report-preview-word',
                        'value' => '<i class="fa fa-file-word-o"></i> '.Lang::lineDefault('word_export_btn', 'Word татах')
                    ), $this->pageProperty['pageWord']
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary',
                        'onclick' => 'toArchiveStatement(this, \'' . $this->pageProperty['reportName'] . '\', \'' . getUID() . '\', \'orientation=' . $this->pageProperty['pageOrientation'] . '&size=' . $this->pageProperty['pageSize'] . '&top=' . $this->pageProperty['pageMarginTop'] . '&left=' . $this->pageProperty['pageMarginLeft'] . '&bottom=' . $this->pageProperty['pageMarginBottom'] . '&right=' . $this->pageProperty['pageMarginRight'] . '&width=' . $this->pageProperty['pageWidth'] . '&height=' . $this->pageProperty['pageHeight'] . '\');',
                        'value' => '<i class="fa fa-archive"></i> Архив'
                    ), $this->pageProperty['pageArchive']
                );
                
                echo html_tag('span', array('class' => 'st-splitter'), '');
                
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary report-preview-zoomin',
                        'value' => '<i class="icon-plus3 font-size-12"></i> Zoom in'
                    ), true
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary report-preview-zoomout',
                        'value' => '<i class="fa fa-minus"></i> Zoom out'
                    ), true
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary report-preview-zoomreset',
                        'value' => '<i class="fa fa-history"></i> Zoom reset'
                    ), true
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary report-preview-fullscreen',
                        'title' => 'Full screen',
                        'value' => '<i class="fa fa-expand"></i>'
                    )
                );
                ?>
            </div>
            <div class="col-auto">
                <?php
                if ($this->pageProperty['pageSearch']) {
                    echo Form::text(
                        array(
                            'class' => 'form-control form-control-sm float-right search-preview-text',
                            'placeholder' => Lang::lineDefault('btn_search', 'Хайх')
                        ), $this->pageProperty['pageSearch']
                    );
                }
                ?>
            </div>
            <div class="clearfix w-100"></div>
        </div>
    </div>
    <div class="report-preview-container">
        <div class="report-preview-<?php echo $this->pageProperty['pageOrientation']; ?>-<?php echo $this->pageProperty['pageSize']; ?>"<?php
        echo ($this->pageProperty['pageSize'] == 'custom') ? ' style="min-height: ' . preg_replace('/\D/', '', $this->pageProperty['pageHeight']) . 'px; width: ' . preg_replace('/\D/', '', $this->pageProperty['pageWidth']) . 'px;"' : '';
        ?>>
            <div class="report-preview-print" style="<?php echo $this->style; ?>">
                <?php echo $this->contentHtml; ?>
                <div class="print-width-dpi"></div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
.st-splitter {
    display: inline;
    border-left: 1px #b1b1b1 solid;
    height: 23px;
    width: 0px;
    margin-left: 5px;
    margin-right: 10px;
    margin-top: 0;
    margin-bottom: 0;
    padding-top: 4px;
    padding-bottom: 5px;
}
td.sticky_cell {
    z-index:3;
    background-color: #fff;
    border: 1px #777 solid !important;
    color: #000;
    padding: 2px 3px;
    line-height: 12px;
    overflow: hidden;
    word-wrap: break-word;
    position: relative;    
}
th.sticky_cell {
    z-index:3;
    background-color: rgba(101, 101, 101, 0.75);
    color: #000;
    padding: 2px 3px;
    line-height: 12px;
    overflow: hidden;
    word-wrap: break-word;
    position: relative;       
    border: 1px #777 solid !important;
}
.pivot-datatable-wrapper table thead tr th:nth-child(1),
.pivot-datatable-wrapper table thead tr th:nth-child(2),
.pivot-datatable-wrapper table thead tr th:nth-child(3),
.pivot-datatable-wrapper table tbody tr td:nth-child(1),
.pivot-datatable-wrapper table tbody tr td:nth-child(2),
.pivot-datatable-wrapper table tbody tr td:nth-child(3) {
    transition: all .1s linear;
}
</style>

<script type="text/javascript">
    var $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?> = $("div#statement-form-<?php echo $this->metaDataId; ?>");

    $(function(){
        
        $.fn.stickyColumn = function (options) {
            var defaults = { columns: 1 };
            var settings = $.extend({}, defaults, options);
            var tables = this;
            return tables.each(function (tableIndex, table) {
                var test = settings;
                var rows = $(table).find('tbody > tr');
                var rowsThead = $(table).find('thead > tr:first-child');
                var positionOfMainTable = $(table).find('thead tr td:first-child').length ? $(table).find('thead tr td:first-child').offset() : $(table).find('thead tr th:first-child').offset();
                var prevLeft = 20;
                
                if ($statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-landscape-a4').length) {
                    prevLeft = $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-landscape-a4').offset().left;
                } else if ($statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-landscape-a3').length) {
                    prevLeft = $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-landscape-a3').offset().left;
                } else if ($statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-landscape-custom').length) {
                    prevLeft = $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-landscape-custom').offset().left;                    
                }

                var $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?> = $('<table class="alter sticky_column_statetment" style="position: absolute; display: none;">', 'div#statement-form-<?php echo $this->metaDataId; ?>');
                $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-print').append($stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>);
                $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.css('top', positionOfMainTable.top);
                $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.css('left', positionOfMainTable.left - prevLeft);

                $.each(rowsThead, function (rowIndex, rowRunner) {
                    var originalDayCells = $(rowRunner).find('td');

                    var newRow = $('<tr>');
                    var rowCells = $(rowRunner).children();
                    for (var cellIndex = 0; cellIndex < test.columns; cellIndex++) {
                        var clonedDayCell = $(rowCells[cellIndex]).clone();
                        clonedDayCell.css('height', $(rowCells[0]).outerHeight());
                        clonedDayCell.css('width', $(rowCells[cellIndex]).outerWidth());
                        clonedDayCell.addClass('sticky_cell');
                        newRow.append(clonedDayCell);
                    }

                    $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.append('<thead>'+newRow.html()+'</thead><tbody>');
                });

                $.each(rows, function (rowIndex, rowRunner) {
                    var originalDayCells = $(rowRunner).find('td');

                    var newRow = $('<tr>');
                    var rowCells = $(rowRunner).children();
                    for (var cellIndex = 0; cellIndex < test.columns; cellIndex++) {
                        var clonedDayCell = $(rowCells[cellIndex]).clone();
                        clonedDayCell.css('height', $(rowCells[0]).outerHeight());
                        clonedDayCell.css('width', $(rowCells[cellIndex]).outerWidth());
                        clonedDayCell.addClass('sticky_cell');
                        newRow.append(clonedDayCell);
                    }

                    $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.append(newRow);
                }); 
                $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.append('</tbody>');
                
                var positionOfMainTable2 = $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview').offset();
                if ($statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-landscape-custom').length) {
                    $('.content-wrapper').on('scroll', function (e) {
                        var scrollposition = e.currentTarget.scrollLeft;                        
                        
                        if ($('.iconbar').length) {
                            scrollposition += 10;
                        }

                        if (scrollposition > prevLeft) {
                            scrollposition = scrollposition - prevLeft;
                            $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.css('top', positionOfMainTable.top - positionOfMainTable2.top);
                            $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.css('left', scrollposition + prevLeft - 15);

                            $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.show();
                        }
                        else {
                            $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.hide();
                        }
                    });
                } else {
                    $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-container').on('scroll', function (e) {
                        var scrollposition = e.currentTarget.scrollLeft;

                        if (scrollposition > prevLeft) {
                            scrollposition = scrollposition - prevLeft;
                            $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.css('top', positionOfMainTable.top - positionOfMainTable2.top);
                            $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.css('left', 10);

                            $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.show();
                        }
                        else {
                            $stickyColumn_<?php echo $this->metaDataId.$this->dataViewId; ?>.hide();
                        }
                    });
                }
            });
        };            
        
        $.contextMenu({
            selector: '.ctrl-print-container',
            callback: function(key, opt) {
                if (key == 'print') {
                    opt.$trigger.find('.report-preview-printpreview').click();
                } else if (key == 'excel') {
                    opt.$trigger.find('.report-preview-excel').click();
                } else if (key == 'pdf') {
                    opt.$trigger.find('.report-preview-pdf').click();
                } 
            },
            items: {
                "print": {name: "<?php echo $this->lang->line('print_btn'); ?>", icon: "print"}, 
                <?php
                if ($this->pageProperty['pageExcel']) {
                ?>
                "excel": {name: "<?php echo $this->lang->line('excel_btn'); ?>", icon: "file-excel"}, 
                <?php
                }
                if ($this->pageProperty['pagePdf']) {
                ?>        
                "pdf": {name: "<?php echo $this->lang->line('pdf_btn'); ?>", icon: "file-pdf"}
                <?php
                }
                ?>  
            }
        });

        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('change', 'select[name="statementPageOrientation"]:visible:last', function() {
            var $this = $(this), 
                pageOrientation = $this.val(), 
                $parent = $this.closest('.report-preview');

            if (pageOrientation == 'landscape') {
                var $pageA4 = $parent.find('.report-preview-portrait-a4');
                if ($pageA4.length) {
                    $pageA4.removeClass('report-preview-portrait-a4').addClass('report-preview-landscape-a4');
                } else {
                    var $pageA3 = $parent.find('.report-preview-portrait-a3');
                    if ($pageA3.length) {
                        $pageA3.removeClass('report-preview-portrait-a3').addClass('report-preview-landscape-a3');
                    }
                }
            } else {
                var $pageA4 = $parent.find('.report-preview-landscape-a4');
                if ($pageA4.length) {
                    $pageA4.removeClass('report-preview-landscape-a4').addClass('report-preview-portrait-a4');
                } else {
                    var $pageA3 = $parent.find('.report-preview-landscape-a3');
                    if ($pageA3.length) {
                        $pageA3.removeClass('report-preview-landscape-a3').addClass('report-preview-portrait-a3');
                    }
                }
            }
            
            if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                statementHeaderFreezeReflow($parent);
            }
        });
        
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', 'button.report-preview-printpreview:visible:last', function() {

            var $this = $(this);

            $.ajax({
                type: 'post',
                url: 'mdpreview/printCss',
                data: {
                    orientation: $this.parent().find('select[name="statementPageOrientation"]').val(),
                    size: '<?php echo $this->pageProperty['pageSize']; ?>',
                    top: '<?php echo $this->pageProperty['pageMarginTop']; ?>',
                    left: '<?php echo $this->pageProperty['pageMarginLeft']; ?>',
                    bottom: '<?php echo $this->pageProperty['pageMarginBottom']; ?>',
                    right: '<?php echo $this->pageProperty['pageMarginRight']; ?>',
                    width: '<?php echo $this->pageProperty['pageWidth']; ?>',
                    height: '<?php echo $this->pageProperty['pageHeight']; ?>',
                    fontFamily: "<?php echo $this->pageProperty['fontFamily']; ?>", 
                    fontSize: '<?php echo $this->pageProperty['fontSize']; ?>'
                },
                beforeSend: function(){
                    Core.blockUI({boxed: true, message: 'Printing...'});
                },
                success: function(dataCss){
                    
                    var $parent = $this.closest('div.report-preview');
                    
                    if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                        statementHeaderFreezeDestroy($parent);
                    }
                
                    $('div.report-preview-print', $parent).printThis({
                        debug: false,
                        importCSS: false,
                        printContainer: false,
                        dataCSS: dataCss,
                        loadCSS: [<?php echo Mdcommon::addCustomFonts('jsCommaPath'); ?>],
                        removeInline: false
                    });
                    
                    if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                        setTimeout(function() {
                            statementHeaderFreeze($parent);
                        }, 600);
                    }
                },
                error: function(){
                    alert('Error');
                }

            }).done(function(){
                Core.unblockUI();
            });
        });
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', 'button.report-preview-excel:visible:last', function() {
            Core.blockUI({message: 'Exporting...', boxed: true});
            
            var $this = $(this);
            var $parent = $this.closest('div.report-preview');
            
            var $editables = $parent.find('span[contenteditable="true"]');
            var $editablesLen = $editables.length, $n = 0;
            var editableObjs = {};
            var $fileIdElem = $parent.find('div[data-file-id]');
            var fileId = $fileIdElem.attr('data-file-id');
            var statementContent = '';

            for ($n; $n < $editablesLen; $n++) { 
                var $editable = $($editables[$n]);
            
                if ($.trim($editable.html()) !== '') {
                    editableObjs[$n] = $.trim($editable.html());
                }
            }
            
            if (statement_mergecell_<?php echo $this->metaDataId.$this->dataViewId; ?> && $fileIdElem.hasAttr('data-count') && Number($fileIdElem.attr('data-count') < 3001)) {
                
                if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                    statementHeaderFreezeDestroy($parent);
                }
                
                statementContent = htmlentities($parent.find('div.report-preview-print').html().replace(/(\r\n|\n|\r)/gm, ''), 'ENT_QUOTES', 'UTF-8');
                
                if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                    statementHeaderFreeze($parent);
                }
            }
            
            $.fileDownload(URL_APP + 'mdstatement/excelExport', {
                httpMethod: 'POST',
                data: {
                    reportName: '<?php echo $this->pageProperty['reportName']; ?>',
                    fileId: fileId, 
                    editableObjs: editableObjs, 
                    statementContent: statementContent
                }
            }).done(function(){
                Core.unblockUI();
            }).fail(function(){
                alert("File download failed!");
                Core.unblockUI();
            });
            return false;
        });
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', 'button.report-preview-word:visible:last', function() {
            
            Core.blockUI({
                message: 'Exporting...',
                boxed: true
            });
            
            var $this = $(this);
            var $parent = $this.closest("div.report-preview");
            
            var $editables = $parent.find('span[contenteditable="true"]');
            var $editablesLen = $editables.length, $n = 0;
            var editableObjs = {};
            var $fileIdElem = $parent.find('div[data-file-id]');
            var fileId = $fileIdElem.attr('data-file-id');
            var statementContent = '';

            for ($n; $n < $editablesLen; $n++) { 
                var $editable = $($editables[$n]);
            
                if ($.trim($editable.html()) !== '') {
                    editableObjs[$n] = $.trim($editable.html());
                }
            }
            
            if (statement_mergecell_<?php echo $this->metaDataId.$this->dataViewId; ?> && $fileIdElem.hasAttr('data-count') && Number($fileIdElem.attr('data-count') < 3001)) {
                
                if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                    statementHeaderFreezeDestroy($parent);
                }
                
                statementContent = htmlentities($parent.find('div.report-preview-print').html().replace(/(\r\n|\n|\r)/gm, ''), 'ENT_QUOTES', 'UTF-8');
                
                if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                    statementHeaderFreeze($parent);
                }
            }

            $.fileDownload(URL_APP + 'mdstatement/wordExport', {
                httpMethod: 'POST',
                data: {
                    reportName: '<?php echo $this->pageProperty['reportName']; ?>',
                    fileId: fileId,
                    orientation: $this.parent().find('select[name="statementPageOrientation"]').val(), 
                    top: '<?php echo $this->pageProperty['pageMarginTop']; ?>',
                    left: '<?php echo $this->pageProperty['pageMarginLeft']; ?>',
                    bottom: '<?php echo $this->pageProperty['pageMarginBottom']; ?>',
                    right: '<?php echo $this->pageProperty['pageMarginRight']; ?>',
                    editableObjs: editableObjs, 
                    statementContent: statementContent
                }
            }).done(function(){
                Core.unblockUI();
            }).fail(function(){
                alert("File download failed!");
                Core.unblockUI();
            });
            return false;
        });
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', 'button.report-preview-pdf:visible:last', function() {
            
            Core.blockUI({message: 'Exporting...', boxed: true});
            
            var $this = $(this);
            var $parent = $this.closest('div.report-preview');
            
            var $editables = $parent.find('span[contenteditable="true"]');
            var $editablesLen = $editables.length, $n = 0;
            var editableObjs = {};
            var $fileIdElem = $parent.find('div[data-file-id]');
            var fileId = $fileIdElem.attr('data-file-id');
            var statementContent = '';

            for ($n; $n < $editablesLen; $n++) { 
                var $editable = $($editables[$n]);
            
                if ($.trim($editable.html()) !== '') {
                    editableObjs[$n] = $.trim($editable.html());
                }
            }
            
            if (statement_mergecell_<?php echo $this->metaDataId.$this->dataViewId; ?> && $fileIdElem.hasAttr('data-count') && Number($fileIdElem.attr('data-count') < 3001)) {
                
                if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                    statementHeaderFreezeDestroy($parent);
                }
                
                statementContent = htmlentities($parent.find('div.report-preview-print').html().replace(/(\r\n|\n|\r)/gm, ''), 'ENT_QUOTES', 'UTF-8');
                
                if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                    statementHeaderFreeze($parent);
                }
            }
            
            $.fileDownload(URL_APP + 'mdstatement/pdfExport', {
                httpMethod: 'POST',
                data: {
                    reportName: '<?php echo $this->pageProperty['reportName']; ?>',
                    fileId: fileId, 
                    orientation: $this.parent().find('select[name="statementPageOrientation"]').val(),
                    size: '<?php echo $this->pageProperty['pageSize']; ?>',
                    top: '<?php echo $this->pageProperty['pageMarginTop']; ?>',
                    left: '<?php echo $this->pageProperty['pageMarginLeft']; ?>',
                    bottom: '<?php echo $this->pageProperty['pageMarginBottom']; ?>',
                    right: '<?php echo $this->pageProperty['pageMarginRight']; ?>',
                    width: '<?php echo $this->pageProperty['pageWidth']; ?>',
                    height: '<?php echo $this->pageProperty['pageHeight']; ?>',
                    fontFamily: "<?php echo $this->pageProperty['fontFamily']; ?>", 
                    fontSize: '<?php echo $this->pageProperty['fontSize']; ?>', 
                    isIgnoreFooter: '<?php echo $this->pageProperty['isIgnoreFooter']; ?>',
                    statementContent: statementContent, 
                    editableObjs: editableObjs
                }
            }).done(function(){
                Core.unblockUI();
            }).fail(function(){
                alert("File download failed!");
                Core.unblockUI();
            });
            return false;
        });
        
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', 'button.report-pdf-preview:visible:last', function(e) {
            
            e.preventDefault();
            
            var $this = $(this);
            var $parent = $this.closest('div.report-preview');
            
            var redirectWindow = window.open('', '_blank');
            
            var $editables = $parent.find('span[contenteditable="true"]');
            var $editablesLen = $editables.length, $n = 0;
            var editableObjs = {};
            var $fileIdElem = $parent.find('div[data-file-id]');
            var fileId = $fileIdElem.attr('data-file-id');
            var statementContent = '';

            for ($n; $n < $editablesLen; $n++) { 
                var $editable = $($editables[$n]);
            
                if ($.trim($editable.html()) !== '') {
                    editableObjs[$n] = $.trim($editable.html());
                }
            }
            
            if (statement_mergecell_<?php echo $this->metaDataId.$this->dataViewId; ?> && $fileIdElem.hasAttr('data-count') && Number($fileIdElem.attr('data-count') < 3001)) {
                
                if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                    statementHeaderFreezeDestroy($parent);
                }
                
                statementContent = htmlentities($parent.find('div.report-preview-print').html().replace(/(\r\n|\n|\r)/gm, ''), 'ENT_QUOTES', 'UTF-8');
                
                if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                    statementHeaderFreeze($parent);
                }
            }
            
            $.ajax({
                type: 'post',
                url: 'mdstatement/pdfView', 
                data: {
                    reportName: '<?php echo $this->pageProperty['reportName']; ?>',
                    fileId: fileId, 
                    orientation: $this.parent().find('select[name="statementPageOrientation"]').val(),
                    size: '<?php echo $this->pageProperty['pageSize']; ?>',
                    top: '<?php echo $this->pageProperty['pageMarginTop']; ?>',
                    left: '<?php echo $this->pageProperty['pageMarginLeft']; ?>',
                    bottom: '<?php echo $this->pageProperty['pageMarginBottom']; ?>',
                    right: '<?php echo $this->pageProperty['pageMarginRight']; ?>',
                    width: '<?php echo $this->pageProperty['pageWidth']; ?>',
                    height: '<?php echo $this->pageProperty['pageHeight']; ?>',
                    fontFamily: "<?php echo $this->pageProperty['fontFamily']; ?>", 
                    fontSize: '<?php echo $this->pageProperty['fontSize']; ?>', 
                    isIgnoreFooter: '<?php echo $this->pageProperty['isIgnoreFooter']; ?>',
                    statementContent: statementContent, 
                    editableObjs: editableObjs
                },
                dataType: 'json', 
                async: false, 
                beforeSend: function() {
                    Core.blockUI({boxed: true, message: 'Exporting...'});
                },
                success: function(data){
                    if (data.status == 'success') {
                        redirectWindow.location = data.url;
                        Core.unblockUI();
                    }
                },
                error: function(){
                    alert('Error');
                    Core.unblockUI();
                }
            });
            
            return false;
        });
        
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('keyup', 'input.search-preview-text', function(ev) {
            var $this = $(this);
            var $parent = $this.closest('div.report-preview');
            var searchTerm = $this.val();

            $('div.report-preview-print', $parent).removeHighlight();

            if (searchTerm) {
                $('div.report-preview-print', $parent).highlight(searchTerm);
            }
        });
        
        setTimeout(function() {
            
            $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview').after('<div class="st-scroll-to-top">'
                +'<i class="icon-arrow-up52"></i>'
                +'</div>'
                +'<div class="st-scroll-to-bottom">'
                +'<i class="icon-arrow-down52"></i>'
                +'</div>');

            $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', '.st-scroll-to-top:visible:last', function(e){
                e.preventDefault();
                var $thisDialog = $(this).closest('.ui-dialog-content');

                if ($thisDialog.length) {

                    if ($thisDialog.find('.rp-fullscreen').length) {
                        $thisDialog = $thisDialog.find('.report-preview-container');
                    }

                    $thisDialog.animate({
                        scrollTop: 0
                    }, 300);

                } else {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 300);
                }

                return false;
            });

            $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', '.st-scroll-to-bottom:visible:last', function(e){
                e.preventDefault();
                var $thisDialog = $(this).closest('.ui-dialog-content');

                if ($thisDialog.length) {

                    if ($thisDialog.find('.rp-fullscreen').length) {
                        $thisDialog = $thisDialog.find('.report-preview-container');
                    }

                    $thisDialog.animate({
                        scrollTop: 100000
                    }, 300);

                } else {
                    $('html, body').animate({
                        scrollTop: 100000
                    }, 300);
                }

                return false;
            });
        }, 10);
        
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', 'button.report-preview-zoomin:visible:last', function() {
            var $this = $(this), 
                $toolbar = $this.closest('.report-preview-toolbar'), 
                $zoomOut = $toolbar.find('button.report-preview-zoomout'), 
                $parent = $this.closest('.report-preview'), 
                $statementWindow = $parent.find('.report-preview-container'), 
                $panel = $statementWindow.find('> div:eq(0)');     
            var zoomIncrement = .1, currentZoom = 1, maxZoom = 1.5;
            
            if ($this.hasAttr('data-zoom')) {
                currentZoom = parseFloat($this.attr('data-zoom'));
            }
            var newZoom = parseFloat(currentZoom + zoomIncrement);
            
            if (newZoom > maxZoom) { 
                newZoom = maxZoom;
            }
            
            $this.attr('data-zoom', newZoom);
            $zoomOut.attr('data-zoom', newZoom);
            
            $panel.css({
                'zoom': newZoom,
                '-moz-transform': 'scale(' + newZoom + ')',
                '-moz-transform-origin': '0 0'
            });
            
            if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                statementHeaderFreezeDestroy($parent);
            }
        });
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', 'button.report-preview-zoomout:visible:last', function() {
            var $this = $(this), 
                $toolbar = $this.closest('.report-preview-toolbar'), 
                $zoomIn = $toolbar.find('button.report-preview-zoomin'), 
                $parent = $this.closest('.report-preview'), 
                $panel = $parent.find('.report-preview-container > div:eq(0)');    
            var zoomIncrement = .1, currentZoom = 1, minZoom = .5;
            
            if ($this.hasAttr('data-zoom')) {
                currentZoom = parseFloat($this.attr('data-zoom'));
            }
            var newZoom = parseFloat(currentZoom - zoomIncrement);
            
            if (newZoom < minZoom) { 
                newZoom = minZoom;
            }
            
            $this.attr('data-zoom', newZoom);
            $zoomIn.attr('data-zoom', newZoom);
            
            $panel.css({
                'zoom': newZoom,
                '-moz-transform': 'scale(' + newZoom + ')',
                '-moz-transform-origin': '0 0'
            });
            
            if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                statementHeaderFreezeDestroy($parent);
            }
        });
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', 'button.report-preview-zoomreset:visible:last', function() {
            var $this = $(this), 
                $toolbar = $this.closest('.report-preview-toolbar'), 
                $zoomIn = $toolbar.find('button.report-preview-zoomin'), 
                $zoomOut = $toolbar.find('button.report-preview-zoomout'), 
                $parent = $this.closest('.report-preview'), 
                $panel = $parent.find('.report-preview-container > div:eq(0)');    
            
            $zoomIn.removeAttr('data-zoom');
            $zoomOut.removeAttr('data-zoom');
            
            $panel.css({
                'zoom': '',
                '-moz-transform': '',
                '-moz-transform-origin': ''
            });
            
            if (typeof statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?> != 'undefined' && statement_freeze_<?php echo $this->metaDataId.$this->dataViewId; ?>) {
                statementHeaderFreeze($parent);
            }
        });
        $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.on('click', 'button.report-preview-fullscreen:visible:last', function() {
            var $this = $(this), 
                $parent = $this.closest('.report-preview'), 
                $openDialog = $parent.closest('.ui-dialog'), 
                $isDialog = ($openDialog.length) ? true : false, 
                $scrollDiv = $parent.find('.report-preview-container'),
                $pivotWrapperDiv = $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.pivot-datatable-wrapper');
                
            if (!$this.hasAttr('data-fullscreen')) {
                
                var $windowHeight = $(window).height() - 50;
                var $windowWidth = $(window).width() - 22;
                
                if ($isDialog) {
                    $openDialog.css('overflow', 'inherit');
                }

                $this.attr({'data-fullscreen': '1', 'title': 'Restore'}).find('i').removeClass('fa-expand').addClass('fa-compress');
                $parent.addClass('rp-fullscreen');
                
                $scrollDiv.css({'max-height': $windowHeight, 'min-height': $windowHeight});
                
                if ($pivotWrapperDiv.length) {
                    
                    var leftCount = $pivotWrapperDiv.attr('data-left-count');
                    
                    $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-landscape-pivot').css('min-height','0');
                    $pivotWrapperDiv.css(
                        {
                            'max-height': $windowHeight-25, 
                            'max-width': $windowWidth, 
                            'position': 'absolute', 
                            'top': '63px',
                            'left': '15px',
                            'overflow': 'auto',
                            'z-index': 100,
                            'background': '#fff'
                        }
                    );
                    $pivotWrapperDiv.find('table.no-freeze').tableHeadFixer({'head': true, 'left': leftCount, 'z-index': 100, 'foot': true, 'border': true, 'scrollstop': true});
                }
                
            } else {
                
                if ($isDialog) {
                    $openDialog.css('overflow', '');
                }
        
                var leftCount = $pivotWrapperDiv.attr('data-left-count');
                $this.attr('title', 'Fullscreen').removeAttr('data-fullscreen').find('i').removeClass('fa-compress').addClass('fa-expand');
                $parent.removeClass('rp-fullscreen');
                
                $scrollDiv.css({'max-height': '', 'min-height': ''});
                
                if ($pivotWrapperDiv.length) {
                    $pivotWrapperDiv.find('table.no-freeze').tableHeadFixer('destroy');
                    $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-landscape-pivot').css('min-height','90vh');
                    $pivotWrapperDiv.removeAttr('style');
                }
            }
        });

        if ($statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview-print')[0].scrollWidth > $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>[0].scrollWidth) {
            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-horizontal-scroll/jquery.floatingscroll.css"/>');
            $.getScript(URL_APP+'assets/custom/addon/plugins/jquery-horizontal-scroll/jquery.floatingscroll.min.js', 
            function(){
                $statement_form_<?php echo $this->metaDataId.$this->dataViewId; ?>.find('.report-preview').css('display', 'inline-block').floatingScroll('init');
            }); 
        }       
    });
</script>