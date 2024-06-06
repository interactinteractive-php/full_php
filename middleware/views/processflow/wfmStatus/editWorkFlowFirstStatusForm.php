<div class="col-md-12">
    <?php echo Form::create(array('class' => 'form-horizontal xs-form', 'id' => 'updateWfmTransition-from', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
    <table class="table table-sm table-no-bordered" style="table-layout: fixed !important">
        <tbody>
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmStatusName" data-label-path="title" required="required">Ажлын урсгалын нэр:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="wfmStatusName">
                        <input type="text" id="wfmStatusName" name="wfmStatusName" placeholder="Ажлын урсгалын нэр" class="form-control form-control-sm" required="required" value="<?php echo $this->data['DESCRIPTION'] ?>">
                    </div>
                </td>
            </tr>  
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmStatusId" data-label-path="title" required="required">Эхлэх төлөв:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="wfmStatusId">
                        <?php echo Form::select(array('name' => 'wfmStatusId', 'id' => 'wfmStatusId', 'class' => 'form-control form-control-sm select2me', 'data' => $this->statusList, 'op_value' => 'ID', 'op_text' => 'WFM_STATUS_NAME', 'value' => $this->data['NEXT_WFM_STATUS_ID'], 'required' => 'required', 'text' => 'notext')); ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="clearfix w-100"></div>

    <div class="row mt6" id="p-exp-<?php echo $this->uniqId; ?>">
        <div class="col-md-2 pr0" style="width: 13.667%; height: 400px;">
            
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, 'if ');">if</button> 
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' && ');">and</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' || ');">or</button>
            
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
            
            <button class="btn p-exp-operator p-exp-operator-big" type="button" onclick="insertPayrollOperator(this, ' == ');">=</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' > ');">&gt;</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' < ');">&lt;</button><br />
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' != ');">&ne;</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' <= ');">&le;</button>
            <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, ' >= ');">&ge;</button>
            
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
        
        <div class="col-md-7 pl0 pr0" style="width: 61.333%;">
            <div class="p-exp-area" contenteditable="true">
                <?php echo $this->data['CRITERIA']; ?>&nbsp;
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="input-icon input-icon-sm p-exp-search">
                <i class="fa fa-search"></i>
                <input type="text" class="form-control form-control-sm pl16" placeholder="код болон нэрээр хайх">
            </div>
            <div style="height: 366px; max-height: 366px; overflow: auto; margin-top: 5px;">
                <ul class="p-exp-metas">
                    <?php echo $this->metaList; ?>
                </ul>   
            </div>    
        </div>    
    </div>    
    <?php echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); ?>
    <?php echo Form::hidden(array('name' => 'transitionId', 'value' => $this->transitionId)); ?>
    <?php echo Form::hidden(array('name' => 'bpCriteria', 'value' => '')); ?>
    <?php echo Form::close(); ?>  
</div>
<script type="text/javascript">
$(function() {
    Core.initUniform($('#createWfmStatus-from'));

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
        insertPayrollOperator(elem, content);
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
function insertPayrollOperator(elem, content) {
    var $this = $(elem);
    var $editor = $this.closest('.row').find('.p-exp-area');
    $editor.focus(); 
    pasteHtmlAtCaret(content); 
}    
</script>