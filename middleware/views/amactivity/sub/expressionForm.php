<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row" id="p-exp-<?php echo $this->uniqId; ?>">
    <div class="col-md-12">
        <h3 class="p-exp-title"><?php echo $this->metaCode.' - '.$this->metaName; ?></h3>
    </div>
    
    <div class="clearfix w-100"></div>
    
    <div class="col-md-2 pr0" style="width: 13.667%; height: 400px;">
        
        <button class="btn p-exp-operator" type="button" onclick="insertPayrollOperator(this, 'if ');">if</button> 
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
            <?php echo $this->expression; ?>&nbsp;
        </div>
        <div class="clearfix w-100"></div>
        <div class="col-md-6 pl0">
            <?php
            echo Form::select(
                array(
                    'name' => 'templateList',
                    'id' => 'templateList_'.$this->uniqId,
                    'text' => '- Загвар сонгох -',
                    'class' => 'form-control select2 form-control-sm mt10',
                    'data' => $this->templateList,
                    'op_value' => 'id',
                    'op_text' => 'description',
                    'required' => 'required'
                )
            );
            ?>
        </div>
        <div class="col-md-6 pr0">
            <?php
            echo Form::select(
                array(
                    'name' => 'factList',
                    'id' => 'factList_'.$this->uniqId,
                    'text' => '- Факт сонгох -',
                    'class' => 'form-control select2 form-control-sm mt10',
                    'disabled' => 'disabled'
                )
            );
            ?>
        </div>
        <div class="clearfix w-100"></div>
        <div class="col-md-6 pl0">
            <?php
            echo Form::select(
                array(
                    'name' => 'factRowList',
                    'id' => 'factRowList_'.$this->uniqId,
                    'text' => '- Мөр сонгох -',
                    'class' => 'form-control select2 form-control-sm mt10',
                    'disabled' => 'disabled'
                )
            );
            ?>
        </div>
        <div class="col-md-6 pr0">
            <?php
            echo Form::select(
                array(
                    'name' => 'expressionProcessAmactivityTemplate',
                    'id' => 'expressionProcessAmactivityTemplate',
                    'text' => '- Процесс сонгох -',
                    'class' => 'form-control select2 form-control-sm mt10'
                )
            );
            ?>
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
        var _this = $(this);
        var _parent = _this.closest('.p-exp-area');
        _parent.find('.p-exp-meta-selected').removeClass('p-exp-meta-selected');
        _this.addClass('p-exp-meta-selected');
    });
    
    $('#p-exp-<?php echo $this->uniqId; ?>').on('click', '.p-exp-meta-remove', function(){
        $(this).parent().remove();
    });
    
    $('#p-exp-<?php echo $this->uniqId; ?>').on('dblclick', 'ul.p-exp-metas > li', function(){
        var elem = this;
        var _this = $(elem);
        var metaCode = _this.attr('data-code');
        var metaName = _this.text();
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
        table = $('#p-exp-<?php echo $this->uniqId; ?> .p-exp-metas'), 
        rows = table.find('li');

        var filteredRows = rows.filter(function(){
            var rowElem = $(this);
            var code = rowElem.attr('data-code').toLowerCase();
            var value = rowElem.text().toLowerCase() + code;
            return value.indexOf(inputVal) === -1;
        });

        rows.show();
        filteredRows.hide();
    });
    
    $('#templateList_<?php echo $this->uniqId; ?>').on('change', function(){
        var comboDatas = [];
        var _this = $('#factList_<?php echo $this->uniqId; ?>'), tempId = $(this).val();
        
        Core.blockUI({
            message: 'Loading...', 
            boxed: true 
        });
        
        $.ajax({
            type: 'post',
            url: 'amactivity/factListByTemplateId',
            data: {templateId: tempId},
            dataType: "json",
            async: false,
            success: function(resp) {
                if(resp.status === 'success') {   
                    _this.empty().prop('disabled', false);
                    _this.append($('<option />').val('').text('- Факт сонгох -'));  

                    $.each(resp.rows, function(){
//                        if(savedProcess == this.trgmetadataid) {
//                            _this.append($("<option />")
//                                .val(this.trgmetadataid)
//                                .text(this.metadataname)
//                                .attr("selected", "selected"));
//                        } else {                    
                            _this.append($("<option />")
                                .val(this.id)
                                .text(this.factfieldname)); 
//                        }
                        comboDatas.push({
                            id: this.id,
                            text: this.factfieldname
                        });                     
                    });
                }
                if(resp.status === 'error')
                    new PNotify({
                        type: resp.status,
                        title: resp.status,
                        text: resp.text,
                        sticker: false
                    });                                
            },
            error: function() {
                alert("Error");
            }
        }).done(function(){
            _this.select2({results: comboDatas});
        });
        
        _this = $('#factRowList_<?php echo $this->uniqId; ?>');
        $.ajax({
            type: 'post',
            url: 'amactivity/getAllActivityTemplateCtrl',
            data: {templateId: tempId},
            dataType: "json",
            success: function(resp) {
                if(resp.status === 'success') {   
                    _this.empty().prop('disabled', false);
                    _this.append($('<option />').val('').text('- Мөр сонгох -'));  

                    $.each(resp.getRows.detail, function(){
//                        if(savedProcess == this.trgmetadataid) {
//                            _this.append($("<option />")
//                                .val(this.trgmetadataid)
//                                .text(this.metadataname)
//                                .attr("selected", "selected"));
//                        } else {                    
                            _this.append($("<option />")
                                .val(this.id)
                                .text(this.description)); 
//                        }
                        comboDatas.push({
                            id: this.id,
                            text: this.description
                        });                     
                    });
                }
                if(resp.status === 'error')
                    new PNotify({
                        type: resp.status,
                        title: resp.status,
                        text: resp.text,
                        sticker: false
                    });                                
            },
            error: function() {
                alert("Error");
            }
        }).done(function(){
            _this.select2({results: comboDatas});
            Core.unblockUI();
        });
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
    var _this = $(elem);
    var _editor = _this.closest('.row').find('.p-exp-area');
    _editor.focus(); 
    pasteHtmlAtCaret(content); 
}
</script>

