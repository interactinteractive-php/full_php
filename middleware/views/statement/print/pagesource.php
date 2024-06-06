<html>
<head>
<meta charset="utf-8" />
<title><?php echo $this->title; ?></title>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<base href="<?php echo URL; ?>">
<style type="text/css">
    <?php echo $this->style; ?>
    /*body {
        padding-top: <?php echo checkDefaultVal($this->paddingTop, '20px'); ?>;
        padding-left: <?php echo checkDefaultVal($this->paddingLeft, '20px'); ?>;
        padding-right: <?php echo checkDefaultVal($this->paddingRight, '20px'); ?>;
        padding-bottom: <?php echo checkDefaultVal($this->paddingBottom, '20px'); ?>;
    }*/
    body {
        padding-top: 20px!important;
        padding-left: 20px!important;
        padding-right: 20px!important;
        padding-bottom: 20px!important;
    }
</style>
</head>
<body>
<div id="statement-wrap">    
    <?php echo $this->contentHtml; ?>
</div>
<script src="<?php echo autoVersion('assets/core/js/main/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/jquery-migrate.min.js'); ?>" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>
<script src="<?php echo autoVersion('assets/custom/js/plugins.min.js'); ?>" type="text/javascript"></script>    
<script type="text/javascript">
$(function() {
    
    var $statementWindow = $('div#statement-wrap');

    if ($statementWindow.find("table > tbody > tr > td[data-merge-cell='true']:eq(0)").length > 0) {
        $statementWindow.find("table > tbody:has(td[data-merge-cell='true'])").each(function(){
            $(this).TableSpan('verticalstatement').TableSpan('horizontalstatement');
        });
    }
    
    if ($statementWindow.find("table > tbody > tr > td[data-vertical-merge-cell='true']:eq(0)").length > 0) {
        $statementWindow.find("table > tbody:has(td[data-vertical-merge-cell='true'])").each(function(){
            $(this).TableSpan('verticalstatement');
        });
    }

    $statementWindow.find('table:has(thead)').each(function() {

        var $table = $(this);
        var $thead = $table.find('thead');
        var headRowsLength = $thead.find('> tr').length;
        
        if (headRowsLength === 2) {
            
            $table.find('colgroup').remove();
            
            var _colgroup = '<colgroup>\n';
            var regex = /width:(.*?)\;/g;
            var _colspan = 0;                                   

            $thead.find('tr:first-child').find('th, td').each(function(){
                var $td = $(this);                                       

                if (typeof $td.attr('colspan') !== 'undefined') {
                    if ($td.attr('style').match(regex) !== null) {
                        var strWidth = $td.attr('style').match(regex);
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

                                if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                    _colgroup += '<col style="' + getWidth[0] + '">\n';
                                } else {
                                    equalWidth = equalWidth > 500 ? 100 : equalWidth;
                                    _colgroup += '<col style="width:' + equalWidth + 'px">\n';
                                }
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
                        try {
                            var getWidth = $td.attr('style').match(regex);
                            if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                _colgroup += '<col style="' + getWidth[0] + '">\n';
                            }
                        } catch(e) { }
                    }
                }

            });
            _colgroup += '</colgroup>';
            $thead.closest('table').prepend(_colgroup);
            
        } else if (headRowsLength === 3) {
            
            $table.find('colgroup').remove();
            
            var _colgroup = '<colgroup>\n';
            var regex = /width:(.*?)\;/g;
            var _colspan = 0, _colspan_level2 = 0;
        
            var firsttr = $thead.find('tr:first-child').find('th, td'),
                secondtr = $thead.find('tr:nth-child(2)').find('th, td'),
                thirdtr = $thead.find('tr:last-child').find('th, td');

            firsttr.each(function(){
                var $td = $(this);

                if (typeof $td.attr('colspan') !== 'undefined') {
                    var _colspanStart2 = _colspan_level2, currentColspan2 = Number($td.attr('colspan'));
                    _colspan_level2 += currentColspan2;

                    for (var ii = _colspanStart2; ii < _colspan_level2; ii++) {
                        var $td2 = $(secondtr[ii]);

                        if ($td2.length && (typeof $td2.attr('colspan') !== 'undefined' || typeof $td2.attr('rowspan') === 'undefined')) {                            
                            
                            var td2ColsResolver = typeof $td2.attr('colspan') === 'undefined' ? 1 : Number($td2.attr('colspan'));
                            
                            if ($td2[0].style.cssText.match(regex) !== null) {
                            
                                var strWidth = $td2[0].style.cssText.match(regex);
                                var strToNum = strWidth[0].match(/\d/g), colsWidtSum = 0;
                                strToNum = Number(strToNum.join(''));

                                var _colspanStart = _colspan, currentColspan = td2ColsResolver;
                                _colspan += currentColspan;                                

                                for (var i = _colspanStart; i < _colspan; i++) {
                                    if (typeof thirdtr[i] !== 'undefined') {
                                        var getWidth = thirdtr[i].style.cssText.match(regex);

                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                            var strToChildNum = getWidth[0].match(/\d/g);
                                            if (strToChildNum) {
                                                colsWidtSum += Number(strToChildNum.join(''));
                                                currentColspan--;
                                            }
                                        }
                                    }
                                }

                                var equalWidth = (strToNum - colsWidtSum) / currentColspan;

                                for (var i = _colspanStart; i < _colspan; i++) {
                                    if (typeof thirdtr[i] !== 'undefined') {
                                        var getWidth = thirdtr[i].style.cssText.match(regex);

                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                            _colgroup += '<col style="' + getWidth[0] + '">\n';
                                        } else {
                                            _colgroup += '<col style="width:' + equalWidth + 'px">\n';
                                        }
                                    }
                                }

                            } else {
                                
                                var _colspanStart = _colspan;
                                
                                if (td2ColsResolver > 1) {
                                    _colspan += td2ColsResolver - 1;
                                } else {
                                    _colspan += td2ColsResolver;
                                }

                                for (var i = _colspanStart; i < _colspan; i++) {
                                    if (typeof thirdtr[i] !== 'undefined') {
                                        var getWidth = thirdtr[i].style.cssText.match(regex);
                                        
                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                            _colgroup += '<col style="' + getWidth[0] + '">\n';
                                        } else {
                                            _colgroup += '<col style="width:' + $(thirdtr[i]).width() + 'px;">\n';
                                        }
                                    }
                                }
                            }

                        } else if (typeof $td2.attr('rowspan') !== 'undefined') {
                        
                            var getWidth = $td2.attr('style').match(regex);
                                
                            if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                _colgroup += '<col style="' + getWidth[0] + '">\n';         
                            } else {
                                _colgroup += '<col style="width:' + $td2.width() + 'px;">\n';
                            }
                        }
                    }

                } else {

                    if ($td) {
                        try {
                            var getWidth = $td.attr('style').match(regex);
                            if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                _colgroup += '<col style="' + getWidth[0] + '">\n';
                            }
                        } catch(e) {}
                    }
                }

            });
            _colgroup += '</colgroup>';
            $thead.closest('table').prepend(_colgroup);
        }
    });
    
    if ($statementWindow.find("table > thead > tr > th[data-merge-cell='true']:eq(0)").length > 0) {
        $statementWindow.find("table > thead:has(th[data-merge-cell='true'])").each(function(){
            $(this).TableSpan('horizontalstatementhead');
        });
    }
                        
    if ($statementWindow.find('.right-rotate').length > 0) {
        $statementWindow.find('.right-rotate').each(function(){
            var $this = $(this), wspace = $this.html().replace('&nbsp;', ' '),
            characterSplit = wspace.replace(/\s/g, '#').match(/.{1,1}/g), 
            tdHeigth = $this.closest('td').height() || $this.closest('th').height();
            var charWidth = 0, $parent = $this.parent();

            for (var i = 0; i < characterSplit.length; i++) {
                $parent.append('<span class="hide characterSplit">'+characterSplit[i]+'</span>');
                charWidth += $parent.find('span.characterSplit').width();
                $parent.find('span.characterSplit').remove();

                if (tdHeigth <= charWidth) {
                    var splitCharArr = wspace.match(new RegExp('.{1,' + (++i) + '}', 'g'));
                    $this.empty();
                    for (var ii = 0; ii < splitCharArr.length; ii++) {
                        $this.append('<span>' + splitCharArr[ii] + '</span>');
                    }
                    break;
                }
            }                                 
        });
    }
    if ($statementWindow.find('.left-rotate').length > 0) {
        $statementWindow.find('.left-rotate').each(function() {
            var $this = $(this), wspace = $this.html().replace('&nbsp;', ' '),
                characterSplit = wspace.replace(/\s/g, '#').match(/.{1,1}/g),
                tdHeigth = $this.closest('td').height() || $this.closest('th').height();
            var charWidth = 0, $parent = $this.parent();

            for (var i = 0; i < characterSplit.length; i++) {
                $parent.append('<span class="hide characterSplit">'+characterSplit[i]+'</span>');
                charWidth += $parent.find('span.characterSplit').width();
                $parent.find('span.characterSplit').remove();

                if (tdHeigth <= charWidth) {
                    var splitCharArr = wspace.match(new RegExp('.{1,' + (++i) + '}', 'g'));
                    $this.empty();
                    for (var ii = 0; ii < splitCharArr.length; ii++) {
                        $this.append('<span>' + splitCharArr[ii] + '</span>');
                    }
                    break;
                }
            }       
        });
    }
});      
</script>
</body>
</html>