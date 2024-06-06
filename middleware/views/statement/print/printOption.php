<div class="report-preview">
    <div class="report-preview-toolbar">
        <div class="row">
            <div class="col-md-7">
                <?php
                echo Form::select(
                    array(
                        'class' => 'form-control form-control-sm float-left mr5',
                        'style' => 'width: 100px; height: 22px; line-height: 22px;',
                        'name' => 'statementPageOrientation',
                        'data' => array(
                            array(
                                'val' => 'landscape',
                                'text' => Lang::lineDefault('statement_horizontal', 'Хэвтээ')
                            ),
                            array(
                                'val' => 'portrait',
                                'text' => Lang::lineDefault('statement_vertical', 'Босоо')
                            )
                        ),
                        'op_value' => 'val',
                        'op_text' => 'text',
                        'text' => 'notext'
                    )
                );                
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary', 
                        'onclick' => 'printTemplate(this);',
                        'value' => '<i class="fa fa-print"></i> Хэвлэх'
                    ), $this->pageProperty['pagePrint']
                ); 
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary', 
                        'onclick' => 'excelTemplate(this);',
                        'value' => '<i class="fa fa-file-excel-o"></i> Эксель гаргах'
                    ), $this->pageProperty['pageExcel'] 
                ); 
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary', 
                        'onclick' => 'pdfTemplate(this);',
                        'value' => '<i class="fa fa-file-pdf-o"></i> PDF гаргах'
                    ), $this->pageProperty['pagePdf'] 
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary', 
                        'onclick' => 'wordTemplate(this);',
                        'value' => '<i class="fa fa-file-word-o"></i> Word гаргах'
                    ), $this->pageProperty['pageWord'] 
                );
                ?>
            </div>
            <div class="clearfix w-100"></div>
        </div>
    </div>
    <div class="report-preview-container">
        <div class="report-preview-<?php echo $this->pageProperty['pageOrientation']; ?>-<?php echo $this->pageProperty['pageSize']; ?>"<?php echo ($this->pageProperty['pageSize'] == 'custom') ? ' style="min-height: '.preg_replace('/\D/', '', $this->pageProperty['pageHeight']).'px; width: '.preg_replace('/\D/', '', $this->pageProperty['pageWidth']).'px;"' : ''; ?>>
            <div class="report-preview-print" style="<?php echo $this->style; ?>">
                <page>
                    <div id="externalContent">
                        <?php echo $this->contentHtml; ?>
                    </div>
                </page>
            </div>
        </div>

        <div id="contentRepeat" class="hide"></div>
    </div>
</div>

<script type="text/javascript">
var copies = '<?php echo $this->numberOfCopies;?>';
var isNewPage = '<?php echo $this->isPrintNewPage;?>';

$(function() {
    
    $('.report-preview-print').find('table:has(thead)').each(function(){
        var $table = $(this);
        var $thead = $table.find('thead');
        
        if ($thead.find('tr').length === 2) {
            
            $table.find('colgroup').remove();
            
            var _colgroup = '<colgroup>\n';
            var regex = /width:(.*?)\;/g;
            var _colspan = 0;                                   

            $thead.find('tr:first-child').find('th, td').each(function(){
                var $td = $(this);                                       

                if (typeof $td.attr('colspan') !== 'undefined') {
                    if ($td[0].style.cssText.match(regex) !== null) {
                        var strWidth = $td[0].style.cssText.match(regex);
                        var strToNum = strWidth[0].match(/\d/g), colsWidtSum = 0;
                        strToNum = Number(strToNum.join(''));

                        var _colspanStart = _colspan, currentColspan = Number($td.attr('colspan'));
                        _colspan += currentColspan;
                        var secondtr = $thead.find('tr:last-child').find('th, td');

                        for (var i = _colspanStart; i < _colspan; i++) {
                            if (typeof secondtr[i] !== 'undefined') {
                                var getWidth = secondtr[i].style.cssText.match(regex);

                                if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                    var strToChildNum = getWidth[0].match(/\d/g);
                                    colsWidtSum += Number(strToChildNum.join(''));                                                            
                                    currentColspan--;
                                }
                            }
                        }

                        var equalWidth = (strToNum - colsWidtSum) / currentColspan;

                        for (var i = _colspanStart; i < _colspan; i++) {
                            if (typeof secondtr[i] !== 'undefined') {
                                var getWidth = secondtr[i].style.cssText.match(regex);

                                if (getWidth !== null && typeof getWidth[0] !== 'undefined')                                                        
                                    _colgroup += '<col style="' + getWidth[0] + '">\n';
                                else
                                    _colgroup += '<col style="width:' + equalWidth + 'px">\n';
                            }
                        }

                    } else {

                        var _colspanStart = _colspan;
                        _colspan += Number($td.attr('colspan'));
                        var secondtr = $thead.find('tr:last-child').find('th, td');

                        for (var i = _colspanStart; i < _colspan; i++) {
                            if (typeof secondtr[i] !== 'undefined') {
                                var getWidth = secondtr[i].style.cssText.match(regex);
                                if (getWidth !== null && typeof getWidth[0] !== 'undefined')
                                    _colgroup += '<col style="' + getWidth[0] + '">\n';
                            }
                        }
                    }
                } else {

                    if ($td) {
                        var getWidth = $td.attr('style').match(regex);
                        if (getWidth !== null && typeof getWidth[0] !== 'undefined')
                            _colgroup += '<col style="' + getWidth[0] + '">\n';
                    }
                }

            });
            _colgroup += '</colgroup>';
            $thead.closest('table').prepend(_colgroup);
        }
    });
    
    $("div#contentRepeat").empty();
    $("page").each(function(j) {
        for (var i = 0; i < copies; i++) {
            if (parseFloat(isNewPage) == 1) {
                $("#contentRepeat").append($("#externalContent").get(0).outerHTML);
                $("#contentRepeat").find("#externalContent").attr('style', 'page-break-after: always;');
            } else {
                $("#contentRepeat").append($(this).find("#externalContent").get(0).outerHTML);
            }
        }
        $("#contentRepeat").find("#externalContent").last().attr('style', 'page-break-after: always;');
    });
    $("div#contentRepeat").find("#externalContent").last().removeAttr('style');

    $('select[name="statementPageOrientation"]:visible:last').on('change', function(){
        var $this=$(this);
        var pageOrientation=$this.val();
        var $parent=$this.closest('.report-preview');

        if (pageOrientation == 'landscape') {
            var $pageA4=$parent.find('.report-preview-portrait-a4');
            if ($pageA4.length) {
                $pageA4.removeClass('report-preview-portrait-a4').addClass('report-preview-landscape-a4');
            }
            var $pageA3=$parent.find('.report-preview-portrait-a3');
            if ($pageA3.length) {
                $pageA3.removeClass('report-preview-portrait-a3').addClass('report-preview-landscape-a3');
            }
        } else {
            var $pageA4=$parent.find('.report-preview-landscape-a4');
            if ($pageA4.length) {
                $pageA4.removeClass('report-preview-landscape-a4').addClass('report-preview-portrait-a4');
            }
            var $pageA3=$parent.find('.report-preview-landscape-a3');
            if ($pageA3.length) {
                $pageA3.removeClass('report-preview-landscape-a3').addClass('report-preview-portrait-a3');
            }
        }
    });    
});

function printTemplate(elem) {
    var $this = $(elem); 
    var _parent = $this.closest(".report-preview");

    if (parseFloat(copies) >= 1) {
        $("div#contentRepeat", _parent).promise().done(function() {
            
            $("div#contentRepeat").empty();
            $("page").each(function(j) {
                for (var i = 0; i < copies; i++) {
                    if (parseFloat(isNewPage) == 1) {
                        $("#contentRepeat").append($("#externalContent").get(0).outerHTML);
                        $("#contentRepeat").find("#externalContent").attr('style', 'page-break-after: always;');
                    } else {
                        $("#contentRepeat").append($(this).find("#externalContent").get(0).outerHTML);
                    }
                }
                $("#contentRepeat").find("#externalContent").last().attr('style', 'page-break-after: always;');
            });
            $("div#contentRepeat").find("#externalContent").last().removeAttr('style');

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
                    Core.blockUI({
                        boxed: true,
                        message: 'Printing...'
                    });
                },
                success: function(dataCss){
                    
                    $('#contentRepeat', _parent).printThis({
                        debug: false,
                        importCSS: false,
                        printContainer: false,
                        dataCSS: dataCss,
                        removeInline: false
                    });
                },
                error: function(){
                    alert('Error');
                }

            }).done(function(){
                Core.unblockUI();
            });
        });
    }
}
function pdfTemplate(elem) {   
    var _parent = $(elem).closest(".report-preview");
    Core.blockUI({
        animate: true
    });
    $.fileDownload(URL_APP + 'mdtemplate/reportPdfExport', {
        httpMethod: "POST",
        data: {
            reportName: '<?php echo $this->pageHeaderTitle; ?>',
            orientation: $(elem).parent().find('select[name="statementPageOrientation"]').val(),
            htmlContent: $("div#externalContent", _parent).html()
        }
    }).done(function(){
        Core.unblockUI();
    }).fail(function(){
        alert("File download failed!");
        Core.unblockUI();
    });
}
function wordTemplate(elem) {
    var _parent = $(elem).closest(".report-preview");
    Core.blockUI({
        animate: true
    });
    $.fileDownload(URL_APP + 'mdtemplate/reportWordExport', {
        httpMethod: "POST",
        data: {
            reportName: '<?php echo $this->pageHeaderTitle; ?>',
            htmlContent: $("div#externalContent", _parent).html(),
            orientation: $(elem).parent().find('select[name="statementPageOrientation"]').val()
        }
    }).done(function(){
        Core.unblockUI();
    }).fail(function(){
        alert("File download failed!");
        Core.unblockUI();
    });
}
function excelTemplate(elem){
    var _parent = $(elem).closest(".report-preview");
    Core.blockUI({
        animate: true
    });
    $.fileDownload(URL_APP + 'mdtemplate/reportExcelExport', {
        httpMethod: "POST",
        data: {
            reportName: '<?php echo $this->pageHeaderTitle; ?>',
            htmlContent: $("div#externalContent", _parent).html()
        }
    }).done(function(){
        Core.unblockUI();
    }).fail(function(){
        alert("File download failed!");
        Core.unblockUI();
    });
}
</script>