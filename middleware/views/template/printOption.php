<div class="report-preview ctrl-print-container" data-type="report">
    <div class="report-preview-toolbar">
        <div class="row">
            <div class="col-md-12">
                <?php
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary ctrl-print-btn', 
                        'onclick' => 'printTemplate(this);',
                        'value' => '<i class="fa fa-print"></i> '.$this->lang->line('print_btn')
                    ), 
                    $this->isPrintBtn
                ); 
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary',  
                        'onclick' => 'excelTemplate(this);',
                        'value' => '<i class="fa fa-file-excel-o"></i> '.$this->lang->line('excel_export_btn')
                    ), 
                    $this->isExcelBtn    
                ); 
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary', 
                        'onclick' => 'pdfTemplate(this);',
                        'value' => '<i class="fa fa-file-pdf-o"></i> '.$this->lang->line('pdf_export_btn')
                    ), 
                    $this->isPdfBtn        
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary', 
                        'onclick' => 'wordTemplate(this);',
                        'value' => '<i class="fa fa-file-word-o"></i> '.$this->lang->line('word_export_btn')
                    ), 
                    $this->isWordBtn    
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary', 
                        'onclick' => 'toArchiveReport(this, \''.$this->isArchiveName.'\', \''.getUID().'\');',
                        'value' => '<i class="fa fa-archive"></i> Архив' 
                    ), 
                    $this->isArchiveBtn     
                );
                echo Form::button(
                    array(
                        'class' => 'btn btn-sm btn-secondary', 
                        'onclick' => 'sendMailReportTemplate(this, \''.$this->metaDataId.'\', \''.$this->emailTo.'\', \''.$this->emailSubject.'\', \''.$this->emailFileName.'\', \''.$this->emailSentParams.'\');',
                        'value' => '<i class="fa fa-envelope-o"></i> '.$this->lang->line('sendmail')
                    ), 
                    $this->isEmailBtn     
                );
                
                if (isset($this->secondPrintTemplateList)) {
                    
                    if (count($this->secondPrintTemplateList) > 1) {
                        echo Form::select(
                            array(
                                'class' => 'form-control form-control-sm d-inline rt-second-print-tmp', 
                                'data' => $this->secondPrintTemplateList, 
                                'op_value' => 'id', 
                                'op_text' => 'name', 
                                'text' => 'notext', 
                                'style' => 'width: 200px;border: 1px solid #ccc;height: 26px;border-radius: 3px;margin-top: 0;margin-right: 5px;'
                            )
                        );
                    } 
                    echo Form::button(
                        array(
                            'class'   => 'btn btn-sm btn-secondary', 
                            'onclick' => 'secondPrintTemplate(this, \''.$this->metaDataId.'\', \''.$this->secondPrintTemplateList[0]['id'].'\');', 
                            'value'   => '<i class="fa fa-print"></i> '.$this->lang->line('rt_second_print_btn'), 
                            'data-rowdata' => htmlentities(json_encode($this->rowData), ENT_QUOTES, 'UTF-8')
                        )
                    );
                }
                
                if ($this->wfmArchiveBtnArr) {
                    
                    $nextStatusList = $this->wfmArchiveBtnArr['nextStatus'];
                    unset($this->wfmArchiveBtnArr['nextStatus']);
                    
                    foreach ($nextStatusList as $nextStatus) {
                        
                        $wfmArchiveBtnArr = array_merge($this->wfmArchiveBtnArr, $nextStatus);
                        
                        if (isset($wfmArchiveBtnArr['signature_print']) && $wfmArchiveBtnArr['signature_print'] == '1' && $wfmArchiveBtnArr['wfmisneedsign'] == '5') {
                            $nextStatus['wfmstatusicon'] = 'fa-key';
                        }
                        
                        echo Form::button(
                            array(
                                'class' => 'btn btn-sm btn-secondary', 
                                'style' => 'color: #fff; background-color: '.$nextStatus['wfmstatuscolor'], 
                                'onclick' => 'toArchiveReportByWfm(this, '.str_replace('"', "'", json_encode($wfmArchiveBtnArr, JSON_UNESCAPED_UNICODE)).');',
                                'value' => '<i class="far '.$nextStatus['wfmstatusicon'].'"></i> '.$nextStatus['wfmstatusname'] 
                            ), 
                            true 
                        );
                    }
                }
                
                if ($this->isSettingsDialog == '1') {
                    echo html_tag('div', array('class' => 'pull-right'), '<label><input type="checkbox" id="uncheckUserPrintOption"> Хэвлэх тохиргоог харуулах эсэх</label>');
                }
                ?>
            </div>
            <div class="clearfix w-100"></div>
        </div>
    </div>
    <div class="report-preview-container" id="report-preview-container-id" data-report-metadataid="<?php echo $this->reportMetaDataId; ?>">
        <?php require BASEPATH . 'middleware/views/template/previewTemplate.php'; ?>
        <div id="contentRepeat" class="hide"></div>
    </div>
    <div id="download-area"></div>
    
    <script type="text/template" data-template="templateHeader"><?php echo count($this->contentHeaderHtml) > 0 ? htmlentities($this->contentHeaderHtml[0], ENT_QUOTES, 'UTF-8') : '' ?></script>
    <script type="text/template" data-template="templateFooter"><?php echo count($this->contentFooterHtml) > 0 ? htmlentities($this->contentFooterHtml[0], ENT_QUOTES, 'UTF-8') : '' ?></script>
</div>

<style type="text/css" media="all">
<?php
if (isset($isMarginConfig)) {
    $top = ($this->pageMargin['top']) ? $this->pageMargin['top'] : '0.26cm';
    $left = ($this->pageMargin['left']) ? $this->pageMargin['left'] : '0.26cm';
    $right = ($this->pageMargin['right']) ? $this->pageMargin['right'] : '0.26cm';
    $bottom = ($this->pageMargin['bottom']) ? $this->pageMargin['bottom'] : '0.26cm';
?>
page[id="portrait"] {
    background: white;
    width: 21cm;
    height: 33.7cm;
    display: table;
    margin: 0px auto;
    padding: <?php echo $top.' '.$right.' '.$bottom.' '.$left; ?>;
    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
page[id="landscape"] {
    background: white;
    width: 33.7cm;
    height: 21cm;
    display: table;
    margin: 0px auto;
    padding: <?php echo $top.' '.$right.' '.$bottom.' '.$left; ?>;
    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
<?php
} else {
    $top = $left = $right = $bottom = '';
?>
page[id="portrait"] {
    background: white;
    width: 21cm;
    height: 33.7cm;
    display: table;
    margin: 0px auto;
    padding-top: 50px;
    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
page[id="landscape"] {
    background: white;
    width: 33.7cm;
    height: 21cm;
    display: table;
    margin: 0px auto;
    padding-top: 70px;
    box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}
<?php
}
?>
#externalContent {
    margin: 0px;
}
.report-preview-container table {
    border-collapse: collapse;
    border-color: grey;
}
.report-preview-container thead {
    display: table-header-group; 
}
.report-preview-container tbody {
   display: table-row-group;
}
.report-preview-container tfoot {
    display: table-footer-group;
}
.report-preview-container table thead th, 
.report-preview-container table thead td, 
.report-preview-container table tbody td, 
.report-preview-container table tfoot td {
    padding: 2px 3px;
    position: relative;
}
.pf-rt-col-sorting > thead > tr > th, 
.pf-rt-col-sorting > thead > tr > td {
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -o-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.pf-rt-col-sorting > thead > tr > th:hover, 
.pf-rt-col-sorting > thead > tr > td:hover {
    cursor: n-resize;
    background-color: #cdc7c7;
}
.pf-rt-col-sorting > thead > tr > th.pf-rt-sort-asc, 
.pf-rt-col-sorting > thead > tr > td.pf-rt-sort-asc {
    background-image: url('assets/core/global/img/rt-up.gif');
    background-repeat: no-repeat;
    background-position: right center;
}
.pf-rt-col-sorting > thead > tr > th.pf-rt-sort-desc, 
.pf-rt-col-sorting > thead > tr > td.pf-rt-sort-desc {
    background-image: url('assets/core/global/img/rt-down.gif');
    background-repeat: no-repeat;
    background-position: right center;
}
</style>

<style type="text/css">
    page[id="portrait"], page[id="landscape"] {
        margin-bottom: 10px;
    }
    page[id="portrait"]:last-of-type, page[id="landscape"]:last-of-type {
        margin-bottom: 0;
    }
</style>

<script type="text/javascript">
    var copies = '<?php echo $this->numberOfCopies; ?>', 
        isNewPage = '<?php echo $this->isPrintNewPage; ?>', 
        isPrintPageBottom = '<?php echo issetVar($this->isPrintPageBottom); ?>', 
        pageOrientation = '<?php echo $this->pageOrientation; ?>', 
        paperInput = '<?php echo $this->paperInput; ?>', 
        pageSize = '<?php echo $this->pageSize; ?>', 
        pageType = '<?php echo $this->pt; ?>', 
        pageRtTop = '<?php echo $top; ?>',
        pageRtLeft = '<?php echo $left; ?>',
        pageRtBottom = '<?php echo $bottom; ?>',
        pageRtRight = '<?php echo $right; ?>', 
        configBottomSizePort = 0, 
        configBottomSizeLand = 0, 
        dataCss = '<?php if ($this->isPrintNewPage == '1') { echo 'tr { -webkit-column-break-inside: avoid; page-break-inside: avoid; break-inside: avoid; -webkit-column-break-after: auto;page-break-after:auto;break-after:auto;}'; } ?>';
        
    var chartCategoryAxisFontSize = 11,
        chartValueAxesFontSize = 11,
        chartValueFontSize = 11;           
    
    $(function() {
        
        /**
         * A4 standart page height size - padding top = 1223
         */
        if (isPrintPageBottom == '1') {
            if ($('#portrait').length) {
                configBottomSizePort = 1223 - $("#externalContent").height();
                $('#externalContent').css('margin-top', configBottomSizePort + 'px');
                
            } else if ($('#landscape').length) {
                configBottomSizeLand = 723 - $("#externalContent").height();
                configBottomSizeLand = configBottomSizeLand < 0 ? 0 : configBottomSizeLand;
                $('#externalContent').css('margin-top', configBottomSizeLand + 'px');
            }
        }
        
        if ($("table > tbody > tr > td[data-merge-cell='true']:eq(0)", '.report-preview').length > 0) {
            $("table > tbody:has(td[data-merge-cell='true'])", '.report-preview').each(function(){
                var $thisTbody = $(this), $hasMergeCell = $thisTbody.find('> tr > td[data-merge-cell="true"]:eq(0)');
                if ($hasMergeCell.length) {
                    $thisTbody.TableSpan('verticalstatement').TableSpan('horizontalstatement');
                }
            });
        }    
        
        if ($("table > tbody > tr > td[data-merge-mode='column']:eq(0)", '.report-preview').length > 0) {
            $("table > tbody:has(td[data-merge-mode='column'])", '.report-preview').each(function(){
                var $thisTbody = $(this), $hasMergeCell = $thisTbody.find('> tr > td[data-merge-mode="column"]:eq(0)');
                if ($hasMergeCell.length) {
                    $thisTbody.TableSpan('verticalcolumn');
                }
            });
        }
        
        $('.report-preview-container:visible:last').find('[data-count-remove="0"]').remove();
        
        $('.pf-rt-col-sorting').on('click', 'thead > tr > td, thead > tr > th', function(){
            var $this = $(this), $table = $this.closest('table'), $tbody = $table.find('tbody[data-sort-body="1"]');
            
            if ($tbody.eq(0).find('tr').length) {
                
                var $colIndex = $this.index();
                
                if ($tbody.eq(0).find('> tr > td').eq($colIndex).find('span[data-nosort-col="1"]').length == 0) {
                    this.asc = !this.asc;
                    var isAsc = this.asc;

                    $table.find('thead > tr > th, thead > tr > td').removeClass('pf-rt-sort-asc pf-rt-sort-desc');

                    $tbody.each(function(){
                        var $thidBody = $(this), 
                            rows = $thidBody.find('> tr').toArray().sort(rtComparer($colIndex));

                        if (!isAsc) { 
                            rows = rows.reverse(); 
                        }

                        for (var i = 0; i < rows.length; i++) {
                            $thidBody.append(rows[i]);
                        }

                        var $el = $thidBody.find('> tr'), len = $el.length, i = 0;
                        for (i; i < len; i++) { 
                            $($el[i]).find('td:eq(0) span[data-nosort-col="1"]').text(i + 1);
                        }
                    });

                    if (!isAsc) { 
                        $this.removeClass('pf-rt-sort-asc').addClass('pf-rt-sort-desc');
                    } else {
                        $this.removeClass('pf-rt-sort-desc').addClass('pf-rt-sort-asc');
                    }
                }
            }
        });
        
        $('.report-preview-container:visible:last').on('keyup', 'span[contenteditable="true"]', function(e){ 
        
            var keyCode = (e.keyCode ? e.keyCode : e.which);

            if (keyCode != 37 && keyCode != 38 && keyCode != 39 && keyCode != 40) {

                var $this = this;
                var $elem = $($this);

                setTimeout(function(){

                    var contentHtml = $elem.html();
                    var cursorStart = getEditableCursorPosition($this);
                    
                    contentHtml = contentHtml.replace(/<\/?nobr>/g, '');

                    contentHtml = contentHtml.replace(/(;| )\/([_\-.,A-Za-zА-Яа-яӨҮөүх0-9]+)\//g, '$1<nobr>/$2/</nobr>');
                    contentHtml = contentHtml.replace(/(;| )\/([_\-.,A-Za-zА-Яа-яӨҮөүх0-9]+)(&nb| )/g, '$1<nobr>/$2</nobr>$3');
                    contentHtml = contentHtml.replace(/(;| )([_\-.,A-Za-zА-Яа-яӨҮөүх0-9]+)\/(&nb| )/g, '$1<nobr>$2/</nobr>$3');

                    $elem.html(contentHtml);

                    setEditableCursorPos($this, cursorStart);
                }, 10);
            }
        });
        
        $('#uncheckUserPrintOption').on('click', function(){
            $(this).closest('.pull-right').remove();
            
            $.ajax({
                type: 'post',
                url: 'mdtemplate/changeUserPrintOption',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>'}
            });
        });
        
        var reportPreviewContainerHtml = $('.report-preview-container').html();
        
        if (reportPreviewContainerHtml.indexOf('Next_MuseoSansCyrl') !== -1 && $("link[href='assets/custom/webfonts/nextmuseo/font.css']").length == 0) {
            $('head').append('<link rel="stylesheet" type="text/css" href="assets/custom/webfonts/nextmuseo/font.css"/>');
        }
        
        setTimeout(function() {
            $('.report-preview-container:not(.rt-set-autoheight)').css('height', ($(window).height() - 136)+'px');
        }, 5);
     
        var $chartContainers = $('.report-preview-container:visible:last').find('[data-chartmetaid]');        
        if ($chartContainers && $chartContainers.length) {
            $chartContainers.each(function(){
                var chartMetaId = $(this).data("chartmetaid");
                $.ajax({
                    type: 'post',
                    url: 'mddashboard/diagramRenderByPost',
                    data: {
                        metaDataId: chartMetaId,
                        executeType: 'chart',
                        defaultCriteriaData: $(this).data("criteria")
                    },
                    dataType: "json",
                    async: false,
                    beforeSend: function() {
                    },
                    success: function(data) {
                        $("div.reporttemplate-chart-" + chartMetaId).html('<div style="width:100%;height:100%">' + data.Html + '</div>');
                        $("div.reporttemplate-chart-" + chartMetaId).find(".mddashboard-card-title").remove();
                        $("div.reporttemplate-chart-" + chartMetaId).find(".mddashboard-card").removeClass("bordered");
                    },
                    error: function(){
                    alert("Error");
                    }
                }).done(function(){
                    Core.initAjax($("div.reporttemplate-chart-" + chartMetaId));
                });    
            });            
        }
    });
    
    function rtComparer(index) {
        return function(a, b) {
            var valA = getRTCellValue(a, index), valB = getRTCellValue(b, index);
            return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
        };
    }
    function getRTCellValue(row, index) {
        return $(row).find('> td').eq(index).text();
    }
    
    function printTemplate(elem) {
        $(elem).attr('data-clicked', '1');
        <?php if (!empty($this->contentHeaderHtml[0]) || !empty($this->contentFooterHtml[0])) { ?>
            printTemplateNew(elem);  
        <?php } else { ?>
        
        var _parent = $(elem).closest(".report-preview");
        $("div#contentRepeat", _parent).empty();
        var divide = Math.ceil(copies / 2);
        
        if (copies >= 1) {
            $("div#contentRepeat", _parent).promise().done(function() {
                
                var isHelveticaNeueFont = false, isNextMuseoFont = false;
                
                $("page", _parent).each(function(j) {
                    
                    var $thisPage = $(this);
                    
                    $thisPage.find('input:checkbox').each(function() {
                        var $thisCheck = $(this);
                        if ($thisCheck.is(':checked')) {
                            $thisCheck.attr('checked', 'checked');
                        } else {
                            $thisCheck.removeAttr('checked');
                        }
                    });
                    
                    if (pageType == '2col') {
                        
                        var content = $thisPage.find('#exContent').clone();
                        $('#contentRepeat', _parent).append(content);
                        $('#contentRepeat', _parent).append('<div style="page-break-after: always;"></div>');
                        
                    } else {
                        
                        var templateOuterHTML = $thisPage.get(0).outerHTML;
                        
                        /*if (pageSize == 'a5') {
                            templateOuterHTML = templateOuterHTML.replace(/12pt/g, '13.5pt');
                        }*/
                        
                        for (var i = 0; i < divide; i++) {
                            $('#contentRepeat', _parent).append(templateOuterHTML + '<div style="page-break-after: always;"></div>');
                        }
                        
                        if (templateOuterHTML.indexOf('HelveticaNeue') !== -1) {
                            isHelveticaNeueFont = true;
                        }
                        if (templateOuterHTML.indexOf('Next_MuseoSansCyrl') !== -1) {
                            isNextMuseoFont = true;
                        }
                    }
                });
                
                var css = ["<?php echo URL; ?>assets/custom/css/print/reportPrint.v1513527547.css"], customFontCss = [];
                
                if (pageSize == 'a5') {
                    if (paperInput == 'landscape') {
                        css = ["<?php echo URL; ?>assets/custom/css/print/print.v1513527547.css"];
                    } else if (paperInput == '' && pageOrientation == 'landscape') {
                        css = ["<?php echo URL; ?>assets/custom/css/print/print.v1513527547.css"];
                    }
                } else if (pageOrientation == 'landscape') {
                    css = ["<?php echo URL; ?>assets/custom/css/print/print.v1513527547.css"];
                }
                
                if (isHelveticaNeueFont) {                    
                    var fontCss = ['<?php echo URL; ?>assets/custom/webfonts/xacfont/font.css'];
                    css.push(fontCss);
                    customFontCss.push(fontCss);
                }
                if (isNextMuseoFont) {
                    var fontCss = ['<?php echo URL; ?>assets/custom/webfonts/nextmuseo/font.css'];
                    css.push(fontCss);
                    customFontCss.push(fontCss);
                }
                
                if (isPrintPageBottom == '1') {
                
                    setTimeout(function () {
                        $("div#contentRepeat", _parent).find('#externalContent').css({'position': 'absolute', 'bottom': 0, 'margin-top': ''});
                        $("div#contentRepeat", _parent).printThis({
                            debug: false,             
                            importCSS: false,           
                            printContainer: false,      
                            loadCSS: css,
                            removeInline: false        
                        });
                    }, 50);
                    
                } else {
                    
                    <?php
                    if (isset($isMarginConfig)) {
                    ?>
                    $.ajax({
                        type: 'post',
                        url: 'mdtemplate/printCss',
                        data: {
                            orientation: pageOrientation,
                            isPrintNewPage: '<?php echo $this->isPrintNewPage; ?>', 
                            size: '<?php echo $this->pageSize; ?>',
                            top: '<?php echo $this->pageMargin['top']; ?>',
                            left: '<?php echo $this->pageMargin['left']; ?>',
                            bottom: '<?php echo $this->pageMargin['bottom']; ?>',
                            right: '<?php echo $this->pageMargin['right']; ?>'
                        },
                        beforeSend: function(){
                            Core.blockUI({boxed: true, message: 'Printing...'});
                        },
                        success: function(dataReportCss){
                    
                            $("#contentRepeat", _parent).printThis({
                                debug: false,
                                importCSS: false,
                                printContainer: false,
                                loadCSS: customFontCss,
                                dataCSS: dataReportCss,
                                removeInline: false
                            });
                        },
                        error: function(){
                            alert('Error');
                        }

                    }).done(function(){
                        Core.unblockUI();
                    });
                    <?php
                    } else {
                    ?>
                    $("#contentRepeat", _parent).printThis({
                        debug: false,             
                        importCSS: false,           
                        printContainer: false,      
                        loadCSS: css,
                        dataCSS: dataCss,
                        removeInline: false        
                    });
                    <?php
                    }
                    ?>
                }
                
                <?php
                if ($this->isAutoArchiveBtn) {
                    echo "toAutoArchiveReport(elem, '".$this->metaDataId."', '".$this->recordId."', '".$this->isArchiveName."', '".$this->defaultDirectoryId."');";
                }
                ?>
            });
        }
        
        <?php } ?>
    }
    function printTemplateNew(elem) {
        var $parent = $(elem).closest(".report-preview");
        $("div#contentRepeat", $parent).empty();

        if (copies >= 1) {
            
            $("page", $parent).each(function (j) {
                
                var $thisPage = $(this);
                
                $thisPage.find('input:checkbox').each(function() {
                    var $thisCheck = $(this);
                    if ($thisCheck.is(':checked')) {
                        $thisCheck.attr('checked', 'checked');
                    } else {
                        $thisCheck.removeAttr('checked');
                    }
                });
                    
                if (pageType == '2col') {
                    $("#contentRepeat", $parent).append($thisPage.find("#exContent").get(0).outerHTML + '<div style="page-break-after: always;"></div>');
                } else {
                    for (var i = 0; i < copies; i++) {
                        if (isNewPage == '1') {
                            $("#contentRepeat", $parent).append($thisPage.find("#externalContent").get(0).outerHTML + '<div style="page-break-after: always;"></div>');
                        } else {
                            if (pageType == '2col') {
                                $("#contentRepeat", $parent).append($thisPage.find("#exContent").html() + '<div style="page-break-after: always;"></div>');
                            } else {
                                $("#contentRepeat", $parent).append($thisPage.find("#externalContent").get(0).outerHTML + '<div style="page-break-after: always;"></div>');
                            }
                        }
                    }
                }
            });
            $("div#contentRepeat", $parent).find("#externalContent").last().removeAttr('style');
        }
        
        $.when(
            $.getStylesheet(URL_APP+'assets/custom/addon/plugins/printjs/print.min.css'),
            $.getScript(URL_APP+'assets/custom/addon/plugins/printjs/print.min.js') 
        ).then(function () {
            
            $.ajax({
                type: 'post',
                url: 'mdtemplate/pdfToTemp',
                data: {
                    content: $("div#contentRepeat", $parent).html(),
                    orientation: pageOrientation,
                    size: '<?php echo $this->pageSize; ?>',
                    headerHtml: $parent.find('script[data-template="templateHeader"]').text(),
                    footerHtml: $parent.find('script[data-template="templateFooter"]').text(),
                    top: '<?php echo $top; ?>',
                    left: '<?php echo $left; ?>',
                    bottom: '<?php echo $bottom; ?>',
                    right: '<?php echo $right; ?>',
                    isSmartShrinking: '<?php echo issetDefaultVal($this->isSmartShrinking, 0); ?>'
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({boxed: true, message: 'Printing...'});
                },            
                success: function (data) {
                    PNotify.removeAll();
                    if (data.status === 'error') {
                        new PNotify({
                            title: 'Error',
                            text: data.message,
                            type: 'error',
                            sticker: false
                        });
                    } else {
                        printJS(URL_APP + data.message);
                    }
                    Core.unblockUI();
                }
            });
            
            <?php
            if ($this->isAutoArchiveBtn) {
                echo "toAutoArchiveReport(elem, '".$this->metaDataId."', '".$this->recordId."', '".$this->isArchiveName."', '".$this->defaultDirectoryId."');";
            }
            ?>            
            
        }, function () {
            console.log('an error occurred somewhere');
        });        
    }
    function pdfTemplate(elem) {   
    
        Core.blockUI({message: 'Exporting...', boxed: true});
        
        var $parent = $(elem).closest(".report-preview");
        $("div#contentRepeat", $parent).empty();

        if (copies >= 1) {
            $("page", $parent).each(function(j) {
                var $thisPage = $(this);
                
                $thisPage.find('input:checkbox').each(function() {
                    var $thisCheck = $(this);
                    if ($thisCheck.is(':checked')) {
                        $thisCheck.attr('checked', 'checked');
                    } else {
                        $thisCheck.removeAttr('checked');
                    }
                });
                
                if (pageType == '2col') {
                    $("#contentRepeat", $parent).append($thisPage.find("#exContent").get(0).outerHTML+'<div style="page-break-after: always;"></div>');
                } else {
                    for (var i = 0; i < copies; i++) {
                        $("#contentRepeat", $parent).append($thisPage.find("#externalContent").get(0).outerHTML+'<div style="page-break-after: always;"></div>');
                    }
                }
            });
            $("div#contentRepeat", $parent).find("#externalContent").last().removeAttr('style');
        }

        /**
         * Resize image
         */
        $("div#contentRepeat", $parent).find('img').css('max-width', '100%');

        var htmlContent = $parent.find('div#contentRepeat').html();
        
        <?php
        if (isset($isMarginConfig)) {
        ?>
        $.fileDownload(URL_APP + 'mdtemplate/reportPdfExport', {
            httpMethod: 'POST',
            data: {
                reportName: '<?php echo $this->pageHeaderTitle; ?>',
                downloadFileName: '<?php echo $this->downloadFileName; ?>',
                htmlContent: htmlContent,
                orientation: pageOrientation, 
                isPrintNewPage: '<?php echo $this->isPrintNewPage; ?>', 
                size: '<?php echo $this->pageSize; ?>',
                top: '<?php echo $this->pageMargin['top']; ?>',
                left: '<?php echo $this->pageMargin['left']; ?>',
                bottom: '<?php echo $this->pageMargin['bottom']; ?>',
                right: '<?php echo $this->pageMargin['right']; ?>',
                headerHtml: $parent.find('script[data-template="templateHeader"]').text(),
                footerHtml: $parent.find('script[data-template="templateFooter"]').text(),                
                isSmartShrinking: '<?php echo $this->isSmartShrinking; ?>', 
                isBlockChainVerify: '<?php echo issetParam($this->isBlockChainVerify); ?>'
            }
        }).done(function(){
            Core.unblockUI();
        }).fail(function(){
            alert("File download failed!");
            Core.unblockUI();
        });
        <?php
        } else {
        ?>
        $.fileDownload(URL_APP + 'mdtemplate/reportPdfExport', { 
            httpMethod: 'POST',
            data: {
                reportName: '<?php echo $this->pageHeaderTitle; ?>',
                downloadFileName: '<?php echo $this->downloadFileName; ?>',
                htmlContent: htmlContent,
                orientation: pageOrientation,
                size: pageSize,
                headerHtml: $parent.find('script[data-template="templateHeader"]').text(),
                footerHtml: $parent.find('script[data-template="templateFooter"]').text(),
                isSmartShrinking: '<?php echo $this->isSmartShrinking ?>'
            }
        }).done(function(){
            Core.unblockUI();
        }).fail(function(){
            alert("File download failed!");
            Core.unblockUI();
        });        
        <?php
        }
        ?>        
        return;
    }
    function wordTemplate(elem) {
        Core.blockUI({
            message: 'Exporting...',
            boxed: true
        });  
        
        var $parent = $(elem).closest(".report-preview");
        $("div#contentRepeat", $parent).empty();
        
        if (copies >= 1) {
            $("page", $parent).each(function(j) {
                var $thisPage = $(this);
                $thisPage.find('input:checkbox').each(function() {
                    var $thisCheck = $(this);
                    if ($thisCheck.is(':checked')) {
                        $thisCheck.attr('checked', 'checked');
                    } else {
                        $thisCheck.removeAttr('checked');
                    }
                });
                    
                if (pageType == '2col') {
                    $("#contentRepeat", $parent).append($thisPage.find("#exContent").html()+'<div style="page-break-after: always;"></div>');
                } else {
                    for (var i = 0; i < copies; i++) {
                        $("#contentRepeat", $parent).append($thisPage.find("#externalContent").get(0).outerHTML+'<div style="page-break-after: always;"></div>');
                        $("#contentRepeat", $parent).find("#externalContent").attr('style', 'page-break-after: always;');
                    }
                    $("#contentRepeat", $parent).find("#externalContent").last().attr('style', 'page-break-after: always;');
                }
            });
            $("div#contentRepeat", $parent).find("#externalContent").last().removeAttr('style');
        }

        if (/<img src="data:image\/png;base64,/.test($("div#contentRepeat", $parent).html())) {
            $.when(
                $.getScript(URL_APP+'assets/custom/addon/plugins/html-docx/html-docx.js')
            ).then(function () {

                $.getScript(URL_APP+'assets/custom/addon/plugins/html-docx/FileSaver.js').done(function() {

                    /**
                     * Resize image
                     */

                    var width, $this, height;
                    $("div#contentRepeat", $parent).find('img').each(function(){
                        $this = $(this);
                        width = this.width;
                        if (width > 700) {
                            height = (this.height / width) * 700;
                            $this.attr({'width': 700, 'height': height});        
                        }
                    });
                    
                    var contentHtml = $("div#contentRepeat", $parent).html();
                    contentHtml = contentHtml.replace(/font-size:[^;]+/g, '').replace(/font-family:[^;]+/g, '');
                    
                    var contentStr = '<!DOCTYPE html>'
                        +'<style type="text/css">'
                        +'body {font-family: Gill Sans; font-size:11pt;}' 
                        +'table {table-layout: fixed;clear: both;border-collapse: collapse;word-wrap: break-word;} '
                        +'table thead th, table thead td, table tbody td, table tfoot td{overflow: hidden;word-wrap: break-word;padding: 2px 3px}'
                        +'</style>' 
                        + contentHtml;
                
                    var orientation = 'portrait';
                    var converted = htmlDocx.asBlob(contentStr, {
                        orientation: orientation, 
                        margins: {
                            top: 720,
                            right: 720,
                            bottom: 720,
                            left: 720,
                            header: 520,
                            footer: 520,
                            gutter: 0
                        }
                    });

                    saveAs(converted, 'Tемплейт - ' + getUniqueId('no') + '.docx');

                    var link = document.createElement('a');
                    link.href = URL_FN.createObjectURL(converted);
                    link.download = 'document.docx';
                    link.appendChild(document.createTextNode('Click here if your download has not started automatically'));
                    var downloadArea = document.getElementById('download-area');
                    downloadArea.innerHTML = '';
                    downloadArea.appendChild(link);            
                    Core.unblockUI();
                });
                
            }, function () {
                console.log('an error occurred somewhere');
            });        
            return;            
        }  
        
        <?php
        if (isset($isMarginConfig)) {
        ?>
        $.fileDownload(URL_APP + 'mdtemplate/reportWordExport', {
            httpMethod: "POST",
            data: {
                reportName: '<?php echo $this->pageHeaderTitle; ?>',
                downloadFileName: '<?php echo $this->downloadFileName; ?>',
                htmlContent: $("div#contentRepeat", $parent).html(),
                orientation: pageOrientation, 
                isPrintNewPage: '<?php echo $this->isPrintNewPage; ?>', 
                size: '<?php echo $this->pageSize; ?>',
                top: '<?php echo $this->pageMargin['top']; ?>',
                left: '<?php echo $this->pageMargin['left']; ?>',
                bottom: '<?php echo $this->pageMargin['bottom']; ?>',
                right: '<?php echo $this->pageMargin['right']; ?>'
            }
        }).done(function(){
            Core.unblockUI();
        }).fail(function(){
            alert("File download failed!");
            Core.unblockUI();
        });
        <?php
        } else {
        ?>
        $.fileDownload(URL_APP + 'mdtemplate/reportWordExport', {
            httpMethod: "POST",
            data: {
                reportName: '<?php echo $this->pageHeaderTitle; ?>',
                downloadFileName: '<?php echo $this->downloadFileName; ?>',
                htmlContent: $("div#contentRepeat", $parent).html(),
                orientation: pageOrientation
            }
        }).done(function(){
            Core.unblockUI();
        }).fail(function(){
            alert("File download failed!");
            Core.unblockUI();
        });
        <?php
        }
        ?>
        return false;
    }
    function excelTemplate(elem) {
        
        Core.blockUI({message: 'Exporting...', boxed: true});
        
        var $parent = $(elem).closest(".report-preview");
        $("div#contentRepeat", $parent).empty();
        
        if (copies >= 1) {
            $("page", $parent).each(function(j) {
                var $thisPage = $(this);
                $thisPage.find('input:checkbox').each(function() {
                    var $thisCheck = $(this);
                    if ($thisCheck.is(':checked')) {
                        $thisCheck.attr('checked', 'checked');
                    } else {
                        $thisCheck.removeAttr('checked');
                    }
                });
                if (pageType == '2col') {
                    $("#contentRepeat", $parent).append($thisPage.find("#exContent").html());
                } else {
                    for (var i = 0; i < copies; i++) {
                        $("#contentRepeat", $parent).append($thisPage.find("#externalContent").get(0).outerHTML);
                        $("#contentRepeat", $parent).find("#externalContent").attr('style', 'page-break-after: always;');
                    }
                    $("#contentRepeat", $parent).find("#externalContent").last().attr('style', 'page-break-after: always;');
                }
            });
            $("div#contentRepeat", $parent).find("#externalContent").last().removeAttr('style');
        }
        
        $.fileDownload(URL_APP + 'mdtemplate/reportExcelExport', {
            httpMethod: "POST",
            data: {
                reportName: '<?php echo $this->pageHeaderTitle; ?>',
                downloadFileName: '<?php echo $this->downloadFileName; ?>',
                htmlContent: $("div#contentRepeat", $parent).html()
            }
        }).done(function(){
            Core.unblockUI();
        }).fail(function(){
            alert("File download failed!");
            Core.unblockUI();
        });
        return false;
    }
    
    function secondPrintTemplate(elem, dataViewId, rtMetaId) {
        PNotify.removeAll();
        var $this = $(elem);
        var $tmpCombo = $this.prev('select.rt-second-print-tmp');
        if ($tmpCombo.length) {
            rtMetaId = $tmpCombo.val();
        }
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/printTemplateByPost',
            data: {
                dataViewId: dataViewId, 
                rtMetaId: rtMetaId, 
                rowData: dataViewSelectedRowsResolver(JSON.parse($this.attr('data-rowdata')))
            }, 
            beforeSend: function(){
                Core.blockUI({boxed: true, message: 'Printing...'});
            },
            success: function(secondTemp){
                
                if (secondTemp.status == 'success') {
                    
                    var $contentRepeat = $("#contentRepeat");

                    $contentRepeat.empty().append(secondTemp.html).promise().done(function(){

                        if ($contentRepeat.find("table > tbody > tr > td[data-merge-cell='true']:eq(0)").length > 0) {
                            $contentRepeat.find("table > tbody:has(td[data-merge-cell='true'])").each(function(){
                                $(this).TableSpan('verticalstatement');
                            });
                        }   

                        $contentRepeat.find('[data-count-remove="0"]').remove();

                        $contentRepeat.printThis({
                            debug: false,
                            importCSS: false,
                            printContainer: false,
                            dataCSS: secondTemp.css,
                            removeInline: false
                        });

                        Core.unblockUI();
                    }); 
                    
                } else {
                    new PNotify({
                        title: secondTemp.status,
                        text: secondTemp.message,
                        type: secondTemp.status,
                        sticker: false, 
                        addclass: pnotifyPosition
                    });
                    Core.unblockUI();
                }
            },
            error: function(){ alert('Error'); Core.unblockUI(); }
        });
    }
    
    function getEditableCursorPosition(element) {
        var ie = (typeof document.selection != "undefined" && document.selection.type != "Control") && true;
        var w3 = (typeof window.getSelection != "undefined") && true;
        var caretOffset = 0;

        if (w3) {

            var range = window.getSelection().getRangeAt(0);
            var preCaretRange = range.cloneRange();
            preCaretRange.selectNodeContents(element);
            preCaretRange.setEnd(range.endContainer, range.endOffset);
            caretOffset = preCaretRange.toString().length;

        } else if (ie) {

            var textRange = document.selection.createRange();
            var preCaretTextRange = document.body.createTextRange();
            preCaretTextRange.expand(element);
            preCaretTextRange.setEndPoint("EndToEnd", textRange);
            caretOffset = preCaretTextRange.text.length;
        }

        return caretOffset;
    }
    function setEditableCursorPos(el, sPos) {
        var charIndex = 0, range = document.createRange();
        range.setStart(el, 0);
        range.collapse(true);
        var nodeStack = [el], node, foundStart = false, stop = false;

        while (!stop && (node = nodeStack.pop())) {
            if (node.nodeType == 3) {
                var nextCharIndex = charIndex + node.length;
                if (!foundStart && sPos >= charIndex && sPos <= nextCharIndex) {
                    range.setStart(node, sPos - charIndex);
                    foundStart = true;
                }
                if (foundStart && sPos >= charIndex && sPos <= nextCharIndex) {
                    range.setEnd(node, sPos - charIndex);
                    stop = true;
                }
                charIndex = nextCharIndex;
            } else {
                var i = node.childNodes.length;
                while (i--) {
                    nodeStack.push(node.childNodes[i]);
                }
            }
        }

        selection = window.getSelection();                 
        selection.removeAllRanges();                       
        selection.addRange(range);
    }      
</script>