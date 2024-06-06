<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row" id="p-exp-<?php echo $this->uniqId; ?>">
    <div class="col-md-12">
        <h3 class="p-exp-title"><?php echo $this->metaCode.' - '.$this->metaName; ?></h3>
    </div>
    
    <div class="clearfix w-100"></div>
    
    <div class="col-md-2 pr0" style="width: 13.667%; height: 400px;">
        
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, 'if ');">if</button> 
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' and ');">and</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' or ');">or</button>
        
        <div class="clearfix w-100 mt5"></div>
        
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '( &nbsp;)');">(⋯)</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '( ');">(</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ') ');">)</button>
        
        <div class="clearfix w-100 mt5"></div>
        
        <button class="btn p-exp-operator p-exp-operator-big" type="button" onclick="insertMglOperator(this, ' + ');">+</button>
        <button class="btn p-exp-operator p-exp-operator-big" type="button" onclick="insertMglOperator(this, ' - ');">&minus;</button>
        <button class="btn p-exp-operator p-exp-operator-big" type="button" onclick="insertMglOperator(this, ' * ');">&lowast;</button><br />
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' / ');">/</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' % ');">&percnt;</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' case ');">case</button>
        
        <div class="clearfix w-100 mt5"></div>
        
        <button class="btn p-exp-operator p-exp-operator-big" type="button" onclick="insertMglOperator(this, ' = ');">=</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' > ');">&gt;</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' < ');">&lt;</button><br />
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' != ');">&ne;</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' <= ');">&le;</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ' >= ');">&ge;</button>
        
        <div class="clearfix w-100 mt5"></div>
        
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '7');">7</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '8');">8</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '9');">9</button><br />
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '4');">4</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '5');">5</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '6');">6</button><br />
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '1');">1</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '2');">2</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '3');">3</button><br />
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '0');">0</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, '.');">.</button>
        <button class="btn p-exp-operator" type="button" onclick="insertMglOperator(this, ',');">,</button>
        
    </div>
    
    <div class="col-md-7 pl0 pr0" style="width: 61.333%;">
        <div class="p-exp-area" contenteditable="true">
            <?php echo $this->expression; ?>&nbsp;
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="input-icon input-icon-sm p-exp-search">
            <i class="fa fa-search"></i>
            <input type="text" class="form-control form-control-sm" placeholder="код болон нэрээр хайх">
        </div>
        <div style="height: 366px; max-height: 366px; overflow: auto; margin-top: 5px;">
            <ul class="p-exp-metas">
                <?php echo $this->metaList; ?>
            </ul>   
        </div>    
    </div>
</div>

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
        $(this).parent().remove();
    });
    
    $('#p-exp-<?php echo $this->uniqId; ?>').on('dblclick', 'ul.p-exp-metas > li', function(){
        var elem = this;
        var $this = $(elem);
        var metaCode = $this.attr('data-code');
        var metaName = $this.text();
        var content = ' <span class="p-exp-meta" contenteditable="false" data-code="'+metaCode+'">'+metaName+'<span class="p-exp-meta-remove" contenteditable="false">x</span></span>&nbsp;';
        insertMglOperator(elem, content);
    });    
    
    /*$('#p-exp-<?php echo $this->uniqId; ?> .p-exp-area').on('keydown', function(e){
        e = e || window.event;
        if ((e.which == 90 || e.keyCode == 90) && e.ctrlKey) {
            alert('undo');
            return e.preventDefault();
        }
    });*/    
    
    /*$('#p-exp-<?php echo $this->uniqId; ?> .p-exp-area').on('focus', function(){
        var focus = $(document.activeElement);
    });*/
    
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
            var code = $rowElem.attr('data-code').toLowerCase();
            var value = $rowElem.text().toLowerCase() + code;
            return value.indexOf(inputVal) === -1;
        });

        $rows.show();
        $filteredRows.hide();
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
            while ( (node = el.firstChild) ) {
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
    } else if (document.selection && document.selection.type != "Control") {
        // IE < 9
        document.selection.createRange().pasteHTML(html);
    }
}
function insertMglOperator(elem, content) {
    var $this = $(elem);
    var $editor = $this.closest('.row').find('.p-exp-area');
    $editor.focus(); 
    
    if (content == ' case ') {
        content = ' case when then else end ';
    }
    
    pasteHtmlAtCaret(content); 
}
</script>

