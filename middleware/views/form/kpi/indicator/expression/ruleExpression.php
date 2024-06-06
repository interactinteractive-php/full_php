<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<form>
    <div class="row" id="p-exp-<?php echo $this->uniqId; ?>">

        <?php
        if ($this->metaName) {
        ?>
        <div class="col-md-12">
            <h3 class="p-exp-title"><?php echo ($this->metaCode ? $this->metaCode.' - ' : '') . $this->metaName; ?></h3>
        </div>
        <div class="clearfix w-100"></div>
        <?php
        }
        ?>

        <div class="col-md-2 pr0" style="width: 13.667%;min-height: 400px;flex: 0 0 13.667%;max-width: 13.667%;">

            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' and ');">and</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' or ');">or</button>

            <div class="clearfix w-100 mt5"></div>

            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '( &nbsp;)');">(⋯)</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '( ');">(</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ') ');">)</button>

            <div class="clearfix w-100 mt5"></div>

            <button class="btn p-exp-operator p-exp-operator-big" type="button" onclick="insertPayrollOperator(this, ' + ');">+</button>
            <button class="btn p-exp-operator p-exp-operator-big" type="button" onclick="insertPayrollOperator(this, ' - ');">&minus;</button>
            <button class="btn p-exp-operator p-exp-operator-big" type="button" onclick="insertPayrollOperator(this, ' * ');">&lowast;</button><br />
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' / ');">/</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' % ');">&percnt;</button>
            
            <div class="clearfix w-100 mt5"></div>

            <button class="btn p-exp-operator p-exp-operator-big" type="button" onclick="insertPayrollOperator(this, ' = ');">=</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' > ');"><i class="far fa-greater-than"></i></button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' < ');"><i class="far fa-less-than"></i></button><br />
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' != ');">&ne;</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' <= ');"><i class="far fa-less-than-equal"></i></button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' >= ');"><i class="far fa-greater-than-equal"></i></button>

            <div class="clearfix w-100 mt5"></div>

            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '7');">7</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '8');">8</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '9');">9</button><br />
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '4');">4</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '5');">5</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '6');">6</button><br />
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '1');">1</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '2');">2</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '3');">3</button><br />
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '0');">0</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, '.');">.</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ',');">,</button>

        </div>

        <div class="col-md-7 pl0 pr0" style="width: 61.333%;flex: 0 0 61.333%;max-width: 61.333%;">
            <div class="p-exp-area" contenteditable="true" spellcheck="false">
                <?php echo $this->expression; ?>&nbsp;
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group quick-item" style="width: 253px;">
                <div class="form-group-feedback form-group-feedback-left p-exp-search">
                    <input type="text" class="form-control form-control-sm" placeholder="код болон нэрээр хайх">
                    <div class="form-control-feedback form-control-feedback-lg">
                        <i class="fa fa-search"></i>
                    </div>
                </div>
            </div>
            <div style="height: 366px; max-height: 366px; overflow: auto; margin-top: 5px;">
                <ul class="p-exp-metas">
                    <?php echo $this->metaList; ?>
                </ul>   
            </div>    
        </div>
    </div>
</form>

<script type="text/javascript">
$(function(){
    
    setTimeout(function() {
        $('#p-exp-<?php echo $this->uniqId; ?> .p-exp-area').focus();
    }, 0);    
    
    $('#p-exp-<?php echo $this->uniqId; ?>').on('click', '.p-exp-meta', function(){
        var $this = $(this);
        var $parent = $this.closest('.p-exp-area');
        $parent.find('.p-exp-meta-selected').removeClass('p-exp-meta-selected');
        $this.addClass('p-exp-meta-selected');
    });
    
    $('#p-exp-<?php echo $this->uniqId; ?>').on('click', '.p-exp-meta-remove', function(){
        var $parent = $(this).parent();
        
        if ($parent.hasAttr('class')) {
            
            if ($parent.attr('class') == 'p-exp-kpifield-tmp') {
                
                $parent.nextAll('span:not(.p-exp-meta-remove)').remove();
                $parent.nextAll('input').remove();
                $parent.after('<input type="text" placeholder="template" class="form-control form-control-sm d-inline exp-kpi-field-ac" style="width: 150px" data-type="template" required="required" name="kpi-field-exp-tmp">'+
                    '<input type="text" placeholder="indicator" class="form-control form-control-sm d-inline rounded-0 exp-kpi-field-ac" style="width: 120px" data-type="indicator" required="required" name="kpi-field-exp-ind">'+
                    '<input type="text" placeholder="fact" class="form-control form-control-sm d-inline exp-kpi-field-ac" style="width: 90px" data-type="fact" required="required" name="kpi-field-exp-fact">');
                $parent.next('input').focus();
                
            } else if ($parent.attr('class') == 'p-exp-kpifield-ind') {
                
                $parent.nextAll('span:not(.p-exp-meta-remove)').remove();
                $parent.nextAll('input').remove();
                $parent.after('<input type="text" placeholder="indicator" class="form-control form-control-sm d-inline rounded-0 exp-kpi-field-ac" style="width: 120px" data-type="indicator" required="required" name="kpi-field-exp-ind">'+
                    '<input type="text" placeholder="fact" class="form-control form-control-sm d-inline exp-kpi-field-ac" style="width: 90px" data-type="fact" required="required" name="kpi-field-exp-fact">');
                $parent.next('input').focus();
                
            } else if ($parent.attr('class') == 'p-exp-kpifield-fact') {
                
                $parent.after('<input type="text" placeholder="fact" class="form-control form-control-sm d-inline exp-kpi-field-ac" style="width: 90px" data-type="fact" required="required" name="kpi-field-exp-fact">');
                $parent.next('input').focus();
            }
        }
        
        $parent.remove();
    });
    
    $('#p-exp-<?php echo $this->uniqId; ?>').on('dblclick', 'ul.p-exp-metas > li', function(){
        var elem = this;
        var $this = $(elem);
        var metaCode = $this.attr('data-code');
        var metaName = $this.text();
        var content = ' <span class="p-exp-meta" contenteditable="false" data-code="'+metaCode+'">'+metaName+'<span class="p-exp-meta-remove" contenteditable="false">x</span></span>&nbsp;';
        insertPayrollOperator(elem, content);
    });     
    
    $('#p-exp-<?php echo $this->uniqId; ?> .p-exp-area').on('keydown', function(event){
        if (window.getSelection && event.which == 8) { // backspace

            var selection = window.getSelection();
            if (!selection.isCollapsed || !selection.rangeCount) {
                return;
            }

            var curRange = selection.getRangeAt(selection.rangeCount - 1);
            if (curRange.commonAncestorContainer.nodeType == 3 && curRange.startOffset > 0) {
                // we are in child selection. The characters of the text node is being deleted
                return;
            }

            var range = document.createRange();
            if (selection.anchorNode != this) {
                // selection is in character mode. expand it to the whole editable field
                range.selectNodeContents(this);
                range.setEndBefore(selection.anchorNode);
            } else if (selection.anchorOffset > 0) {
                range.setEnd(this, selection.anchorOffset);
            } else {
                // reached the beginning of editable field
                return;
            }
            range.setStart(this, range.endOffset - 1);

            var previousNode = range.cloneContents().lastChild;
            
            if (previousNode && previousNode.contentEditable == 'false') {
                // this is some rich content, e.g. smile. We should help the user to delete it
                range.deleteContents();
                event.preventDefault();
            }
        }
    });
    
    $('#p-exp-<?php echo $this->uniqId; ?> .p-exp-search > input').on('keyup', function(e){
        
        var code = e.keyCode || e.which;
        if (code == '9') return;
        
        var inputVal = $(this).val().toLowerCase(), 
            $table = $('#p-exp-<?php echo $this->uniqId; ?> .p-exp-metas'), 
            $rows = $table.find('li');

        var $filteredRows = $rows.filter(function(){
            var $rowElem = $(this);
            var value = $rowElem.text().toLowerCase();
            return value.indexOf(inputVal) === -1;
        });

        $rows.show();
        $filteredRows.hide();
    });
    
    $(document.body).on('focus', 'input.exp-kpi-field-ac', function(){
        expKpiFieldAutoComplete($(this));
    });
    $(document.body).on('keydown', 'input.exp-kpi-field-ac', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        var $this = $(this);
        if (code === 13) {
            if ($this.data('ui-autocomplete')) {
                $this.autocomplete('destroy');
            }
            return false;
        } else {
            if (!$this.data('ui-autocomplete')) {
                expKpiFieldAutoComplete($this);
            }
        }
    });
    
});    

function pasteHtmlAtCaret(html) {
    var sel, range;
    if (window.getSelection) {
        // IE9 and non-IE
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            range = sel.getRangeAt(0);
            range.deleteContents();

            // Range.createContextualFragment() would be useful here but is
            // non-standard and not supported in all browsers (IE9, for one)
            var el = document.createElement("div");
            el.innerHTML = html;
            var frag = document.createDocumentFragment(), node, lastNode;
            while ((node = el.firstChild)) {
                lastNode = frag.appendChild(node);
            }
            range.insertNode(frag);
            
            // Preserve the selection
            if (lastNode) {
                range = range.cloneRange();
                range.setStartAfter(lastNode);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    } else if (document.selection && document.selection.type != 'Control') {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}
function insertPayrollOperator(elem, content) {
    var $this = $(elem);
    var $editor = $this.closest('.row').find('.p-exp-area');
    $editor.focus(); 
    
    if (content == 'kpiField') {
        content = '<span class="p-exp-kpifield" contenteditable="false">';
            content += '<input type="text" placeholder="template" class="form-control form-control-sm d-inline exp-kpi-field-ac" style="width: 150px" data-type="template" required="required" name="kpi-field-exp-tmp">';
            content += '<input type="text" placeholder="indicator" class="form-control form-control-sm d-inline rounded-0 exp-kpi-field-ac" style="width: 120px" data-type="indicator" required="required" name="kpi-field-exp-ind">';
            content += '<input type="text" placeholder="fact" class="form-control form-control-sm d-inline exp-kpi-field-ac" style="width: 90px" data-type="fact" required="required" name="kpi-field-exp-fact">';
        content += '<span class="p-exp-meta-remove" contenteditable="false">x</span></span>';
    }
    
    pasteHtmlAtCaret(content); 
}
function expKpiFieldAutoComplete(elem) {
    var $this = elem, $parent = $this.parent(), type = $this.attr('data-type');

    $this.autocomplete({
        minLength: 1,
        maxShowItems: 30,
        delay: 500,
        highlightClass: 'lookup-ac-highlight', 
        appendTo: 'body',
        position: {my: 'left top', at: 'left bottom', collision: 'flip flip'}, 
        autoSelect: false,
        source: function(request, response) {
            
            if (lookupAutoCompleteRequest != null) {
                lookupAutoCompleteRequest.abort();
                lookupAutoCompleteRequest = null;
            }
            
            var postData = {q: request.term, type: type};

            if (type == 'indicator') {
                postData['templateId'] = $parent.attr('data-template-id');
            } else if (type == 'fact') {
                postData['templateId'] = $parent.attr('data-template-id');
                postData['indicatorId'] = $parent.attr('data-indicator-id');
            }
        
            lookupAutoCompleteRequest = $.ajax({
                type: 'post',
                url: 'mdform/kpiExpAutoComplete',
                dataType: 'json',
                data: postData,
                success: function(data) {
                    response($.map(data, function(item) {
                        return {id: item.ID, code: item.CODE, name: item.NAME};
                    }));
                }
            });
        },
        focus: function(event, ui) {
            return false;
        },
        open: function() {
            var $this = $(this);
            var $onTopElem = $this.closest('.ui-front');
            if ($onTopElem.length > 0) {
                var $widget = $this.autocomplete('widget');
                $widget.css('z-index', $onTopElem.css('z-index') + 1);
            }
            return false;
        },
        close: function() {
            $(this).autocomplete('option', 'appendTo', 'body'); 
        }, 
        select: function(event, ui) {	
            
            if (type == 'template') {
                
                $parent.attr({'data-template-id': ui.item.id, 'data-template-code': ui.item.code});
                $this.after('<span class="p-exp-kpifield-tmp" contenteditable="false"><span class="p-exp-kpifield-title" title="'+ui.item.name+'" contenteditable="false">'+ui.item.name+'</span> <span class="p-exp-meta-remove" contenteditable="false">x</span></span>');
                $this.nextAll('input:eq(0)').focus();
                $this.remove();
                
            } else if (type == 'indicator') {
                
                $parent.attr({'data-indicator-id': ui.item.id, 'data-indicator-code': ui.item.code});
                $this.after('<span class="p-exp-kpifield-ind" contenteditable="false"><span class="p-exp-kpifield-title" title="'+ui.item.name+'" contenteditable="false">'+ui.item.name+'</span> <span class="p-exp-meta-remove" contenteditable="false">x</span></span>');
                $this.nextAll('input:eq(0)').focus();
                $this.remove();
                
            } else {
                
                $parent.attr({'data-fact-id': ui.item.id, 'data-fact-code': ui.item.code});
                $this.after('<span class="p-exp-kpifield-fact" contenteditable="false"><span class="p-exp-kpifield-title" title="'+ui.item.name+'" contenteditable="false">'+ui.item.name+'</span> <span class="p-exp-meta-remove" contenteditable="false">x</span></span>');
                $this.remove();
            }
        }
    }).autocomplete('instance')._renderItem = function(ul, item) {
        ul.addClass('lookup-ac-render');
        
        var $qTerm = this.term;
        
        if ($qTerm.indexOf('*') !== -1) {
            var $leftSubstr = $qTerm.substring(0, 1);
            var $rightSubstr = $qTerm.substring(-1);
            
            if ($leftSubstr == '*' && $rightSubstr == '*') {
                $qTerm = $qTerm.substring(0, -1).substring(1);
            } else if ($leftSubstr == '*') {
                $qTerm = $qTerm.substring(1);
            } else if ($rightSubstr == '*') {
                $qTerm = $qTerm.substring(0, -1);
            }
        }
        
        var re = new RegExp("(" + $qTerm + ")", "gi"),
            cls = this.options.highlightClass,
            template = "<span class='" + cls + "'>$1</span>",
            label = item.name.replace(re, template);

        return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div>').appendTo(ul);
    };
}
</script>