<script type="text/javascript">
    function bpEventCompleteHint(cm, option) {
        
        var $focusedElem = $(document.activeElement);
        var $parent = $focusedElem.closest('.tab-pane');
        var $textarea = $parent.find('.ace-textarea');
        var editorName = $textarea.attr('data-editor');
        var editor;
        
        if (editorName == 'event') {
            editor = fullExpressionEditor;
        } else if (editorName == 'load') {
            editor = fullExpressionOpenEditor;
        } else if (editorName == 'varfnc') {
            editor = fullExpressionVarFncEditor;
        } else if (editorName == 'beforesave') {
            editor = fullExpressionSaveEditor;
        } else if (editorName == 'aftersave') {
            editor = fullExpressionAfterSaveEditor;
        }
        
        var cur = cm.getCursor(), token = cm.getTokenAt(cur);
        var orgch = cur.ch;
        var start = token.start, end = token.end;
        var from = CodeMirror.Pos(cur.line, start);
        var to = CodeMirror.Pos(cur.line, end);
        
        var doc = editor.getDoc();
        var lastChar = token.string;
        var fullLine = doc.getLine(cur.line);
        var lastTwoChar = fullLine.slice(-2);
        
        var hintList = [];
        
        cur.ch = cur.ch - 1;
        token = cm.getTokenAt(cur);
        token.end = orgch;
        cur.ch = orgch;
        to = CodeMirror.Pos(cur.line, token.end);
        from = CodeMirror.Pos(cur.line, token.end);
        
        if (lastChar == '[' || lastChar == ',') {
            
            $("#fullExpressionPathList > tbody > tr > td:first-child").each(function(){
                hintList.push($(this).text().trim());
            });
            
            var aCompletions = {list: hintList, from: from, to: to};
            
            CodeMirror.on(aCompletions, 'pick', eventPathCompletePick);
            
        } else if (lastTwoChar == '].') {
            
            if (editorName == 'event') {
                hintList = [
                    'change', 'click', 'keyup', 'keydown', 'dblclick', 'focus', 'remove', 
                    'sidebarchange', 'rowsButtonClick', 'kpikeyup', 'kpichange', 'kpiclick', 
                    'kpiColumnChange', 'kpiRowChange', 'kpiCellChange', 'hide', 'show', 
                    'disable', 'enable', 'required', 'nonrequired', 'label', 'control', 'reset'
                ];
            } else {
                hintList = ['hide', 'show', 'disable', 'enable', 'required', 'nonrequired', 'label', 'control', 'reset'];
            }
            
            var aCompletions = {list: hintList, from: from, to: to};
        
            CodeMirror.on(aCompletions, 'pick', eventEventCompletePick);
            
        } else {
            
            hintList = [
                'getLookupFieldValue', 'getProcessParam', 'getDetailRowCount', 'getDate', 'getSessionInfo', 'getOpenParam', 
                'runProcessValue', 'detailActionCriteria', 'changeColumnName', 'changeLabelName', 'message'
            ];
            
            var aCompletions = {list: hintList, from: from, to: to};
        
            CodeMirror.on(aCompletions, 'pick', eventFunctionCompletePick);
        }
        
        return aCompletions;
    }

    function eventEventCompletePick(compc) {  
        
        var $focusedElem = $(document.activeElement);
        var $parent = $focusedElem.closest('.tab-pane');
        var $textarea = $parent.find('.ace-textarea');
        var editorName = $textarea.attr('data-editor');
        var editor;
        
        if (editorName == 'event') {
            editor = fullExpressionEditor;
        } else if (editorName == 'load') {
            editor = fullExpressionOpenEditor;
        } else if (editorName == 'varfnc') {
            editor = fullExpressionVarFncEditor;
        } else if (editorName == 'beforesave') {
            editor = fullExpressionSaveEditor;
        } else if (editorName == 'aftersave') {
            editor = fullExpressionAfterSaveEditor;
        }
        
        var doc = editor.getDoc();
        var cursor = doc.getCursor();
        var currLine = cursor.line;
        var currCh = cursor.ch;

        if (compc == 'hide' || compc == 'show' || compc == 'disable' || compc == 'enable' 
            || compc == 'required' || compc == 'nonrequired' || compc == 'reset') {
            doc.replaceRange(compc + '();', {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'label' || compc == 'control') {
            doc.replaceRange(compc + "('styles');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else {
            doc.replaceRange(compc + "(){\n\t\n};", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine + 1, ch: 1});
        }
    } 
    
    function eventPathCompletePick(compc) {  
        
        var $focusedElem = $(document.activeElement);
        var $parent = $focusedElem.closest('.tab-pane');
        var $textarea = $parent.find('.ace-textarea');
        var editorName = $textarea.attr('data-editor');
        var editor;
        
        if (editorName == 'event') {
            editor = fullExpressionEditor;
        } else if (editorName == 'load') {
            editor = fullExpressionOpenEditor;
        } else if (editorName == 'varfnc') {
            editor = fullExpressionVarFncEditor;
        } else if (editorName == 'beforesave') {
            editor = fullExpressionSaveEditor;
        } else if (editorName == 'aftersave') {
            editor = fullExpressionAfterSaveEditor;
        }
        
        var doc = editor.getDoc();
        var cursor = doc.getCursor();
        var currLine = cursor.line;
        var currCh = cursor.ch;
        
        editor.setCursor({line: currLine, ch: currCh + 1});
    } 
    
    function eventFunctionCompletePick(compc) {  
        
        var $focusedElem = $(document.activeElement);
        var $parent = $focusedElem.closest('.tab-pane');
        var $textarea = $parent.find('.ace-textarea');
        var editorName = $textarea.attr('data-editor');
        var editor;
        
        if (editorName == 'event') {
            editor = fullExpressionEditor;
        } else if (editorName == 'load') {
            editor = fullExpressionOpenEditor;
        } else if (editorName == 'varfnc') {
            editor = fullExpressionVarFncEditor;
        } else if (editorName == 'beforesave') {
            editor = fullExpressionSaveEditor;
        } else if (editorName == 'aftersave') {
            editor = fullExpressionAfterSaveEditor;
        }
        
        var doc = editor.getDoc();
        var cursor = doc.getCursor();
        var currLine = cursor.line;
        var currCh = cursor.ch;

        if (compc == 'getLookupFieldValue') {
            doc.replaceRange(compc + "('lookupField', 'column');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'getProcessParam') {
            doc.replaceRange(compc + "('getProcessCode', 'paramsMap');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'getDetailRowCount') {
            doc.replaceRange(compc + "('groupPath');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'getDate') {
            doc.replaceRange(compc + "('dateType');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'getSessionInfo') {
            doc.replaceRange(compc + "('key');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'getOpenParam') {
            doc.replaceRange(compc + "('code');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'runProcessValue') {
            doc.replaceRange(compc + "('getProcessCode', 'paramsMap', 'responsePath');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'detailActionCriteria') {
            doc.replaceRange(compc + "('groupPath', 'EXAMPLE:autocomplete', 'show OR hide');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'changeColumnName') {
            doc.replaceRange(compc + "('columnPath', 'changeName');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'changeLabelName') {
            doc.replaceRange(compc + "('paramPath', 'changeName');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        } else if (compc == 'message') {
            doc.replaceRange(compc + "(info, 'message');", {line: currLine, ch: currCh - (compc.length)}, {line: currLine, ch: currCh});
            editor.setCursor({line: currLine, ch: currCh + 5});
        }
    } 
</script>