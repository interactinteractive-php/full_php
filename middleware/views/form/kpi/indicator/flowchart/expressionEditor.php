<div class="row">    
    <div class="flowchart-savebtn-row">
        <button type="button" 
        class="btn btn-sm btn-circle btn-success" 
        style="background-color: #FFF;border-color: #1bbc9b;color: #1bbc9b;border-radius: 100px !important;position: absolute;right: 25px;top: 0px;" 
        onclick="saveBlockExpression(this)">
            <i class="icon-checkmark-circle2"></i> Хадгалах
        </button>
    </div>
    <div class="col-md-12">
        <div id="app" class="joint-app">
            <div class="app-header d-none">
                <div class="toolbar-container"></div>
            </div>
            <div class="app-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#tab_1661422630331128_1642492680355092" class="nav-link active" data-toggle="tab" style="padding-top: 5px !important;padding-bottom: 3px !important;">Shapes</a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab_1661422630331128_1648712300257099" class="nav-link" data-toggle="tab" style="padding-top: 5px !important;padding-bottom: 3px !important;">Тохиргоо</a>
                    </li>
                </ul>            
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1661422630331128_1642492680355092">                
                        <div class="stencil-container"></div>                                
                    </div>
                    <div class="tab-pane" id="tab_1661422630331128_1648712300257099">                
                        <div class="inspector-container"></div>
                    </div>
                </div>
                <div class="paper-container"></div>
                <!-- <div class="navigator-container"></div> -->
            </div>
        </div>
    </div>
</div>   

<style type="text/css">
    .joint-paper-scroller.joint-theme-modern {
        background-color:#ffff;
    }
    div.joint-toolbar.joint-theme-picker {
        display: none;
    }
    [data-name="save"] {
        border-color: #1bbc9b !important;
        color: #1bbc9b !important;
        padding: 10px 10px 10px 10px !important;  
        margin-left: 8px !important;
        margin-right: 8px !important;
    }
    [data-name="png"] {
        padding: 10px 10px 10px 10px !important;  
        margin-left: 8px !important;
    }
    .joint-stencil.joint-theme-modern, .joint-inspector.joint-theme-modern {
        background-color: #fff;
    }
    .joint-inspector.joint-theme-modern input, .joint-inspector.joint-theme-modern .content-editable, .joint-inspector.joint-theme-modern textarea {
        color: #000;
    }
    .joint-inspector.joint-theme-modern .group > .group-label {
        background-color: #fff;
    }
    .joint-inspector.joint-theme-modern .content-editable {
        border: 0;
        border-bottom: 1px solid #ccc;
    }
    /* .joint-theme-modern {
        top: 0px !important;
    } */    
    .object-structure, .object-method, .object-name {
        margin-left: 10px;
    }
    .object-structure li, .object-method li, .object-name li {
        list-style: none;
        padding: 6px 0;
        margin: 0;
        border-bottom: 1px #ddd solid;
        line-height: 14px;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
        -ms-user-select: none;
        user-select: none;
        cursor: pointer;
    }    
    .object-structure ul, .object-method ul, .object-name ul {
        list-style: none;
        padding: 0;
        margin: 0;        
    }    
    .object-structure li:hover, .object-method li:hover, .object-name li:hover {
        background-color: #eee;
    }    
    .joint-halo.joint-theme-modern .box {
        display: none;
    }
    .joint-inspector.joint-theme-modern .group .field {
        padding: 0 10px 10px 0;
    }
</style>

<script>var require = { paths: { 'vs': 'assets/custom/addon/plugins/monaco-editor/package/min/vs' } };</script>
<script src="assets/custom/addon/plugins/monaco-editor/package/min/vs/loader.js"></script>
<script src="assets/custom/addon/plugins/monaco-editor/package/min/vs/editor/editor.main.nls.js"></script>
<script src="assets/custom/addon/plugins/monaco-editor/package/min/vs/editor/editor.main.js"></script>

<script type="text/javascript">
    var mainObjectProps = <?php echo json_encode($this->columns); ?>;
    var indicatorRow = <?php echo json_encode($this->row); ?>;
    var conditionObjectProps = [
        {
            TEXT: 'isempty - хоосон эсэхийг шалгаж (0, 1) гэсэн утга буцаана',
            VALUE: 'isempty'
        }
    ];
    mainObjectProps = mainObjectProps.concat(conditionObjectProps);

    var dynamicHeight = $(window).height() - $("#app").offset().top - 20;
    $("#app").css('height', dynamicHeight - 20);
    // setTimeout(function(){
    //     $('.nav-tabs a[href="#tab_1661422630331128_1648712300257099"]').tab('show');
    // }, 1000);

    if (typeof window.joint === 'undefined') {
         $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/flowchart/css/style.css"/>');
         $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/flowchart/css/theme-picker.css"/>');
         $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/flowchart/css/style.dark.css"/>');
         $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/flowchart/css/style.material.css"/>');
         $("head").append('<link rel="stylesheet" type="text/css" href="assets/rappidjs/flowchart/css/style.modern.css"/>');
    }

    // $.cachedScript("http://localhost:8080/bundle.js").done(function() {
    $.cachedScript('<?php echo autoVersion('assets/rappidjs/flowchart/bundle.js'); ?>').done(function() {

        loadVexpression(<?php echo html_entity_decode($this->expressionJson, ENT_QUOTES, 'UTF-8') ?>);

    });

    function saveBlockExpression(elem) {
        Core.blockUI({message: 'Loading...', boxed: true});
        
        setTimeout(function () {
            saveVexpression(function(data){
            let postData = {
                id: $(elem).closest('.ws-area').find('div.ws-hidden-params input[name="workSpaceParam[id]"]').val(),
                varFncExpressionStringJson: data.expressionJson,
                varFncExpressionString: data.expressionString,
            };
            // console.log('postData exp :>> ', postData.varFncExpressionStringJson);
            // console.log('-------------------------------------------------------------------');
            // console.log('postData data.expressionString :>> ', data.expressionString);
            // return;

            var response = $.ajax({
                type: "post",
                url: "mdform/saveBlockExpression",
                data: postData,
                dataType: "json",
                async: false
            });
            var resultSave = response.responseJSON;


            new PNotify({
                title: resultSave.status,
                text: resultSave.message,
                type: resultSave.status,
                addclass: pnotifyPosition,
                sticker: false
            });                    
            Core.unblockUI();
        });                
        }, 300);
    }

    function microflowAddCriteria(elem) {
        var $dialogName = 'dialog-microflow-addcriteria';
        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
        var $dialog = $('#' + $dialogName);
        
        kpiIndicatorAttrs = [];
        var srcObjectCode = $(elem).closest('.group').find('div[data-field="attrs/label/objectCode"]').find('input[name="parentId_displayField"]').val();
        
        $.ajax({
            type: 'post',
            url: 'mdform/microflowAddCriteria',
            data: {
                id: '<?php echo $this->indicatorId ?>',
                srcObjectCode: srcObjectCode,
                criteria: $(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val()
            }, 
            dataType: 'json', 
            beforeSend: function(){
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                
                if (data.status == 'success') {
                    
                    $dialog.dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Add criteria',
                        width: "650px",
                        position: { my: "top", at: "top+100" },
                        modal: true,
                        open: function() {
                            disableScrolling();
                            $dialog.empty().append(data.html).promise().done(function() {                                
                                Core.unblockUI();
                            });
                        }, 
                        close: function() {
                            $dialog.empty().dialog("destroy").remove();
                            enableScrolling();
                        }, 
                        buttons: [
                            {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
                                var criteriaObjects = [];
                                $dialog.find('table > tbody > tr').each(function(){
                                    criteriaObjects.push({
                                        objectAttr: $(this).find('select[name="objectAttr[]"]').val(),
                                        operatorValue: $(this).find('select[name="operatorValue[]"]').val(),
                                        criteriaValue: $(this).find('select[name="criteriaValue[]"]').val(),
                                    });
                                });
                                $(elem).closest('.meta-autocomplete-wrap').find('input[name="criteriaText"]').val(criteriaObjects.length + ' нөхцөл');
                                $(elem).closest('.meta-autocomplete-wrap').find('input[name="criteria"]').val(htmlentities(JSON.stringify(criteriaObjects))).trigger('change');                                
                                $dialog.dialog('close');
                            }},
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                $dialog.dialog('close');
                            }}
                        ]
                    });
                    $dialog.dialog('open');
                
                } else {
                    
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    Core.unblockUI();
                }
            }
        });
    }

    function microflowAddParams(elem) {
        var $dialogName = 'dialog-microflow-addparams';
        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
        var $dialog = $('#' + $dialogName);
        
        kpiIndicatorAttrs = [];
        var srcObjectCode = $(elem).closest('.group').find('div[data-field="attrs/label/objectCode"]').find('input[name="parentId_displayField"]').val();
        
        $.ajax({
            type: 'post',
            url: 'mdform/microflowAddParams',
            data: {
                id: '<?php echo $this->indicatorId ?>',
                srcObjectCode: srcObjectCode,
                criteria: $(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val()
            }, 
            dataType: 'json', 
            beforeSend: function(){
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                
                if (data.status == 'success') {
                    
                    $dialog.dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Add parameters',
                        width: "650px",
                        position: { my: "top", at: "top+100" },
                        modal: true,
                        open: function() {
                            disableScrolling();
                            $dialog.empty().append(data.html).promise().done(function() {                                
                                Core.unblockUI();
                            });
                        }, 
                        close: function() {
                            $dialog.empty().dialog("destroy").remove();
                            enableScrolling();
                        }, 
                        buttons: [
                            {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
                                var criteriaObjects = [];
                                $dialog.find('table > tbody > tr').each(function(){
                                    criteriaObjects.push({
                                        objectAttr: $(this).find('select[name="objectAttr[]"]').val(),
                                        criteriaValue: $(this).find('select[name="criteriaValue[]"]').val(),
                                    });
                                });
                                $(elem).closest('.meta-autocomplete-wrap').find('input[name="criteriaText"]').val(criteriaObjects.length + ' parameter');
                                $(elem).closest('.meta-autocomplete-wrap').find('input[name="criteria"]').val(htmlentities(JSON.stringify(criteriaObjects))).trigger('change');                                
                                $dialog.dialog('close');
                            }},
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                $dialog.dialog('close');
                            }}
                        ]
                    });
                    $dialog.dialog('open');
                
                } else {
                    
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                    Core.unblockUI();
                }
            }
        });
    }

    function microflowAddCondition(elem) {
        var $dialogName = 'dialog-microflow-addcondition';
        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
        var $dialog = $('#' + $dialogName);
        
        $.getStylesheet('assets/custom/addon/plugins/monaco-editor/package/min/vs/editor/editor.main.css');
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Add condition',
            width: "650px",
            position: { my: "top", at: "top+100" },
            modal: true,
            open: function() {
                disableScrolling();
                $dialog.empty().append('<div id="codeeditor-container" style="height: 250px"></div>').promise().done(function() {                                
                    var suggesObj = [];
                    // suggesObj = mainObjectProps.map(function(val, index){
                    //     return {
                    //         label: val.TEXT, 
                    //         insertText: val.VALUE
                    //     };
                    // });
                    monaco.languages.registerCompletionItemProvider('markdown', {
                        provideCompletionItems: function() {
                            return {
                                suggestions: suggesObj
                            };
                        }
                    });

                    var getValue = $(elem).closest('.meta-autocomplete-wrap').find('input[type="text"]').val();
                    window.microfloweditor = monaco.editor.create(document.getElementById('codeeditor-container'), {
                        value: getValue,
                        lineNumbers: 'off',
                        language: 'markdown'
                    });

                    Core.unblockUI();
                });
                // $dialog.parent().find('.ui-dialog-buttonset').prepend('<span style="float:left;color:#979797;margin-top:10px;"><i>Ctrl + Space дарж object-н props харна уу.</i></span>');
            }, 
            close: function() {
                monaco.editor.getModels().forEach(model => model.dispose());
                $dialog.empty().dialog("destroy").remove();
                enableScrolling();
            }, 
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
                    $(elem).closest('.meta-autocomplete-wrap').find('input[type="text"]').val(window.microfloweditor.getValue()).trigger('change');
                    $dialog.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    }

    function microflowAddExpression(elem) {
        var $dialogName = 'dialog-microflow-addexpression';
        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
        var $dialog = $('#' + $dialogName);
        
        $.getStylesheet('assets/custom/addon/plugins/monaco-editor/package/min/vs/editor/editor.main.css');
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Add expression',
            width: "1000px",
            position: { my: "top", at: "top+100" },
            modal: true,
            open: function() {
                disableScrolling();
                $dialog.empty().append('<div class="row">'+
                    '<div class="col-md-4" style="max-height:400px;overflow-y:auto;padding-right:30px;">'+
                    '<div>Select class:</div>'+
                    '<div class="meta-autocomplete-wrap">' +
                    '<div class="input-group double-between-input">' +
                    '<input type="hidden" name="microflow_objectid" id="parentId_valueField" data-path="parentId" value="" class="popupInit" data-row-data="" placeholder="" data-isclear="0" data-in-param="" data-in-lookup-param="">' +
                    '<input type="text" name="microflow_objectid_displayField" tabindex="" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete ui-autocomplete-input" data-field-name="parentId" id="parentId_displayField" data-processid="16413659216321" data-lookupid="1664179169556932" placeholder="кодоор хайх" value="" title="" autocomplete="off">' +
                    '<span class="input-group-btn">' +
                    "<button type=\"button\" class=\"btn default btn-bordered btn-xs mr-0\" onclick=\"dataViewSelectableGrid('parentId', '16413659216321', '1664179169556932', 'single', 'parentId', this);\" tabindex=\"-1\"><i class=\"far fa-search\"></i></button>" +
                    "</span>" +
                    '<span class="input-group-btn">' +
                    '<input type="text" name="microflow_objectid_nameField" tabindex="" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete ui-autocomplete-input" data-field-name="parentId" id="parentId_nameField" data-processid="16413659216321" data-lookupid="1664179169556932" placeholder="нэрээр хайх" value="" title="" autocomplete="off">' +
                    "</span>" +
                    "</div>" +
                    "</div>"+
                    "<div class='object-wrapper d-none' style='margin-top:10px'>Class name:</div>"+
                    "<div class='object-name'></div>"+
                    "<div class='object-wrapper d-none' style='margin-top:10px'>Properties:</div>"+
                    "<div class='object-structure'></div>"+
                    "<div class='object-wrapper d-none' style='margin-top:10px'>Methods:</div>"+
                    "<div class='object-method'></div><hr/>"+
                    '<div class="">Select meta:</div>'+
                    '<div class="meta-autocomplete-wrap">' +
                    '<div class="input-group double-between-input">' +
                    '<input type="hidden" name="microflow_metaid" id="parentId_valueField" data-path="parentId" value="" class="popupInit" data-row-data="" placeholder="" data-isclear="0" data-in-param="" data-in-lookup-param="">' +
                    '<input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="кодоор хайх" type="text" required="required" value="">'+
                    '<span class="input-group-btn">' +
                    "<button type=\"button\" class=\"btn default btn-bordered btn-xs mr-0\" onclick=\"commonMetaDataSelectableGrid('single', '', this);\" tabindex=\"-1\"><i class=\"far fa-search\"></i></button>" +
                    "</span>" +
                    '<span class="input-group-btn">' +
                    '<input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="нэрээр хайх" type="text" required="required" value="">'+      
                    "</span>" +
                    "</div>" +
                    "</div>"+                    
                    "</div>"+
                    '<div class="col-md-8" style="border-left: 2px solid #ccc;padding-left:0px;"><div id="codeeditor-container" style="height: 400px"></div></div>'+
                    '</div>').promise().done(function() {                                
                    var suggesObj = [];
                    // suggesObj = mainObjectProps.map(function(val, index){
                    //     return {
                    //         label: val.TEXT, 
                    //         insertText: val.VALUE
                    //     };
                    // });
                    monaco.languages.registerCompletionItemProvider('markdown', {
                        provideCompletionItems: function() {
                            return {
                                suggestions: suggesObj
                            };
                        }
                    });

                    var getValue = $(elem).closest('.meta-autocomplete-wrap').find('input[type="text"]').val();
                    window.microfloweditor = monaco.editor.create(document.getElementById('codeeditor-container'), {
                        value: getValue,
                        minimap: { enabled: false },
                        language: 'markdown'
                    });

                    $dialog.on('change', 'input[name="microflow_objectid"]', function(){
                        var currentObjectId = $(this).val(),
                            currentObjectData = JSON.parse($(this).attr('data-row-data')),
                            textGlo = $dialog.find('input[name="microflow_objectid_displayField"]').val();

                        $dialog.find('.object-name').empty();
                        $dialog.find('.object-structure').empty();
                        $dialog.find('.object-method').empty();
                        $dialog.find('.object-wrapper').addClass('d-none');

                        if (currentObjectData.kpitypeid === '1191') {
                            $.ajax({
                                type: 'post',
                                url: 'mdform/getKpiDataMartRelationColumnsWithInput',
                                data: {
                                    id: currentObjectId
                                },                                
                                dataType: "json",
                                success: function(data) {
                                    if (data) {
                                        var ulString = '<ul style="list-style: none;padding-left: 0;">';
                                        for(var ii = 0; ii < data.length; ii++) {
                                            ulString += '<li data-value=""><div class="d-flex">'+
                                            '<div style="flex: 0 0 40%;padding: 5px 5px 4px 5px;background-color: #f5f5f5;text-align: right;">'+data[ii]['LABEL_NAME']+' - '+data[ii]['SRC_COLUMN_NAME']+'</div>'+
                                            '<div style="width:100%"><input class="form-control input-sm" data-micro-path="'+data[ii]['SRC_COLUMN_NAME']+'"></div>'+
                                            '</div></li>';
                                        }
                                        ulString += '</ul>';

                                        var $dialogName2 = 'dialog-microflow-bindparameter';
                                        if (!$("#" + $dialogName2).length) { $('<div id="' + $dialogName2 + '"></div>').appendTo('body'); }
                                        var $dialogBindParam = $('#' + $dialogName2);
                                        
                                        $dialogBindParam.dialog({
                                            cache: false,
                                            resizable: false,
                                            bgiframe: true,
                                            autoOpen: false,
                                            title: 'Bind parameter',
                                            width: "450px",
                                            position: { my: "top", at: "top+100" },
                                            modal: true,
                                            open: function() {
                                                disableScrolling();
                                                $dialogBindParam.empty().append(ulString);
                                            }, 
                                            close: function() {
                                                $dialogBindParam.empty().dialog("destroy").remove();
                                                enableScrolling();
                                            }, 
                                            buttons: [
                                                {text: "Run", class: 'btn btn-sm green-meadow bp-btn-run', click: function() {
                                                    let inputDatas = '';
                                                    $dialogBindParam.find('input').each(function(){
                                                        inputDatas += $(this).data('micro-path')+'@'+$(this).val()+'|';
                                                    });
                                                    inputDatas = rtrim(inputDatas, '|');
                                                    $.ajax({
                                                        type: 'post',
                                                        url: 'mdexpression/microRunProcedure/'+currentObjectId+'/'+inputDatas+'/1',
                                                        success: function(data) {
                                                            var $dialogName3 = 'dialog-microflow-bindparameter-view';
                                                            if (!$("#" + $dialogName3).length) { $('<div id="' + $dialogName3 + '"></div>').appendTo('body'); }
                                                            var $dialogBindParamView = $('#' + $dialogName3);
                                                            
                                                            $dialogBindParamView.dialog({
                                                                cache: false,
                                                                resizable: false,
                                                                bgiframe: true,
                                                                autoOpen: false,
                                                                title: 'Output',
                                                                width: "1000px",
                                                                position: { my: "top", at: "top+100" },
                                                                modal: false,
                                                                open: function() {
                                                                    disableScrolling();
                                                                    $dialogBindParamView.empty().append('<code><pre style="background-color: #000;color: greenyellow">'+data.replace(/Array/g, '')+'</pre></code>');
                                                                }, 
                                                                close: function() {
                                                                    $dialogBindParamView.empty().dialog("destroy").remove();
                                                                    enableScrolling();
                                                                }, 
                                                                buttons: [
                                                                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                                                        $dialogBindParamView.dialog('close');
                                                                    }}
                                                                ]
                                                            });
                                                            $dialogBindParamView.dialog('open');                                                              
                                                        }
                                                    });                              
                                                }},
                                                {text: "Insert to expression", class: 'btn btn-sm green bp-btn-save', click: function() {
                                                    let inputDatas = '';
                                                    $dialogBindParam.find('input').each(function(){
                                                        inputDatas += $(this).data('micro-path')+'@'+$(this).val()+'|';
                                                    });                                                
                                                    var selection = window.microfloweditor.getSelection();
                                                    var id = { major: 1, minor: 1 };             
                                                    var text = $(this).data('value');
                                                    var op = {identifier: id, range: selection, text: 'runProcedure:'+currentObjectId+'('+rtrim(inputDatas, '|')+')', forceMoveMarkers: true};
                                                    window.microfloweditor.executeEdits("my-source", [op]);                                                      
                                                    $dialogBindParam.dialog('close');
                                                }},
                                                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                                    $dialogBindParam.dialog('close');
                                                }}
                                            ]
                                        });
                                        $dialogBindParam.dialog('open');                                    
                                    }
                                }
                            });                            
                            return;
                        }
                        
                        var ulString = '<ul>';
                        ulString += '<li data-value="'+text+'">'+text+'</li>';
                        ulString += '</ul>';
                        $dialog.find('.object-name').html(ulString);                        

                        $.ajax({
                            type: 'post',
                            url: 'mdform/kpiDataMartRelationConfigJson',
                            dataType: "json",
                            data: {
                                id: currentObjectId
                            },
                            success: function(data) {
                                var ulString = '<ul>';
                                for(var ii = 0; ii < data.length; ii++) {
                                    ulString += '<li data-value="'+data[ii]['SRC_COLUMN_NAME']+'">'+data[ii]['LABEL_NAME']+' - '+data[ii]['SRC_COLUMN_NAME']+'</li>';
                                }
                                ulString += '</ul>';
                                $dialog.find('.object-structure').html(ulString);
                            }
                        });

                        $.ajax({
                            type: 'post',
                            url: 'mdform/objectMethod',
                            dataType: "json",
                            data: {
                                id: currentObjectId
                            },
                            success: function(data) {
                                var ulString = '<ul>';
                                for(var ii = 0; ii < data.length; ii++) {
                                    ulString += '<li data-value="'+data[ii]['NAME']+'">'+data[ii]['NAME']+'</li>';
                                }
                                ulString += '</ul>';
                                $dialog.find('.object-method').html(ulString);
                            }
                        });
                        $dialog.find('.object-wrapper').removeClass('d-none');
                    });

                    $dialog.on('change', 'input[name="microflow_metaid"]', function(){
                        //var selection = window.microfloweditor.getSelection();
                        //var id = { major: 1, minor: 1 };             
                        var textGlo = $dialog.find('input[id="_displayField"]').val();
                        var textVal = $dialog.find('input[name="microflow_metaid"]').val();                                                              

                        $.ajax({
                            type: 'post',
                            url: 'mdform/getShowInputParams/'+textVal,
                            dataType: "json",
                            success: function(data) {
                                if (data.renderData[0].data) {
                                    var ulString = '<ul style="list-style: none;padding-left: 0;">';
                                    for(var ii = 0; ii < data.renderData[0].data.length; ii++) {
                                        ulString += '<li data-value=""><div class="d-flex">'+
                                        '<div style="flex: 0 0 40%;padding: 5px 5px 4px 5px;background-color: #f5f5f5;text-align: right;">'+data.renderData[0].data[ii]['META_DATA_NAME']+' - '+data.renderData[0].data[ii]['PARAM_REAL_PATH']+'</div>'+
                                        '<div style="width:100%"><input class="form-control input-sm" data-micro-path="'+data.renderData[0].data[ii]['PARAM_REAL_PATH']+'"></div>'+
                                        '</div></li>';
                                    }
                                    ulString += '</ul>';

                                    var $dialogName2 = 'dialog-microflow-bindparameter';
                                    if (!$("#" + $dialogName2).length) { $('<div id="' + $dialogName2 + '"></div>').appendTo('body'); }
                                    var $dialogBindParam = $('#' + $dialogName2);
                                    
                                    $dialogBindParam.dialog({
                                        cache: false,
                                        resizable: false,
                                        bgiframe: true,
                                        autoOpen: false,
                                        title: 'Bind parameter',
                                        width: "450px",
                                        position: { my: "top", at: "top+100" },
                                        modal: true,
                                        open: function() {
                                            disableScrolling();
                                            $dialogBindParam.empty().append(ulString);
                                        }, 
                                        close: function() {
                                            $dialogBindParam.empty().dialog("destroy").remove();
                                            enableScrolling();
                                        }, 
                                        buttons: [
                                            {text: "Run", class: 'btn btn-sm green-meadow bp-btn-run', click: function() {
                                                let inputDatas = '';
                                                $dialogBindParam.find('input').each(function(){
                                                    inputDatas += $(this).data('micro-path')+'@'+$(this).val()+'|';
                                                });
                                                inputDatas = rtrim(inputDatas, '|');
                                                $.ajax({
                                                    type: 'post',
                                                    url: 'mdexpression/microRunMeta/'+text+'/'+inputDatas+'/1',
                                                    success: function(data) {
                                                        var $dialogName3 = 'dialog-microflow-bindparameter-view';
                                                        if (!$("#" + $dialogName3).length) { $('<div id="' + $dialogName3 + '"></div>').appendTo('body'); }
                                                        var $dialogBindParamView = $('#' + $dialogName3);
                                                        
                                                        $dialogBindParamView.dialog({
                                                            cache: false,
                                                            resizable: false,
                                                            bgiframe: true,
                                                            autoOpen: false,
                                                            title: 'Output',
                                                            width: "1000px",
                                                            position: { my: "top", at: "top+100" },
                                                            modal: false,
                                                            open: function() {
                                                                disableScrolling();
                                                                $dialogBindParamView.empty().append('<code><pre style="background-color: #000;color: greenyellow">'+data.replace(/Array/g, '')+'</pre></code>');
                                                            }, 
                                                            close: function() {
                                                                $dialogBindParamView.empty().dialog("destroy").remove();
                                                                enableScrolling();
                                                            }, 
                                                            buttons: [
                                                                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                                                    $dialogBindParamView.dialog('close');
                                                                }}
                                                            ]
                                                        });
                                                        $dialogBindParamView.dialog('open');                                                              
                                                    }
                                                });                              
                                            }},
                                            {text: "Insert to expression", class: 'btn btn-sm green bp-btn-save', click: function() {
                                                let inputDatas = '';
                                                $dialogBindParam.find('input').each(function(){
                                                    inputDatas += $(this).data('micro-path')+'@'+$(this).val()+'|';
                                                });                                                
                                                var selection = window.microfloweditor.getSelection();
                                                var id = { major: 1, minor: 1 };             
                                                var text = $(this).data('value');
                                                var op = {identifier: id, range: selection, text: 'runMeta:'+textGlo+'('+rtrim(inputDatas, '|')+')', forceMoveMarkers: true};
                                                window.microfloweditor.executeEdits("my-source", [op]);                                                      
                                                $dialogBindParam.dialog('close');
                                            }},
                                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                                $dialogBindParam.dialog('close');
                                            }}
                                        ]
                                    });
                                    $dialogBindParam.dialog('open');                                    
                                }
                            }
                        });
                    });

                    $dialog.on('dblclick', 'li', function(){
                        var selection = window.microfloweditor.getSelection();
                        var id = { major: 1, minor: 1 };             
                        var text = $(this).data('value');
                        var op = {identifier: id, range: selection, text: text, forceMoveMarkers: true};
                        window.microfloweditor.executeEdits("my-source", [op]);         
                    });

                    Core.unblockUI();           
                });
                // $dialog.parent().find('.ui-dialog-buttonset').prepend('<span style="float:left;color:#979797;margin-top:10px;"><i>Ctrl + Space дарж object-н props харна уу.</i></span>');
            }, 
            close: function() {
                monaco.editor.getModels().forEach(model => model.dispose());
                $dialog.empty().dialog("destroy").remove();
                enableScrolling();
            }, 
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
                    $(elem).closest('.meta-autocomplete-wrap').find('input[type="text"]').val(window.microfloweditor.getValue()).trigger('change');
                    $dialog.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    }

    function microflowAddIndicator(elem) {
        dataViewSelectableGrid(
            "nullmeta",
            "0",
            "1685071077874187",
            "single",
            "nullmeta",
            elem,
            "microflowSelectedIndicator"
        );  
    }

    function microflowSelectedIndicator(
        metaDataCode,
        processMetaDataId,
        chooseType,
        elem,
        rows,
        paramRealPath,
        lookupMetaDataId,
        isMetaGroup
    ) {
        var row = rows[0];
        $(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val(JSON.stringify(row));
        $(elem).closest('.meta-autocomplete-wrap').find('input[type="text"]').val(row.name).trigger('change');        
        $(elem).closest('.group').find('input[name="expressionDescriptionText"]').val(row.name).trigger('change');        
    }          

    function microflowAddMappingParameter(elem) {
        if ($(elem).closest('.inspector-container').find('input[name="indicatorId"]').val() === '') {
            PNotify.removeAll();
            new PNotify({
              title: "Warning",
              text: 'Indicator эсвэл Meta сонгоно уу!',
              type: "warning",
              sticker: false,
              addclass: "pnotify-center"
            });  
            return;
        }
        Core.blockUI({
            message: "Loading...",
            boxed: true
        });                
        setTimeout(function () {
            saveVexpression(function(data){
                var startIndicatorId = '';
                var rowData = JSON.parse($(elem).closest('.inspector-container').find('input[name="indicatorId"]').val());
                var flowData = JSON.parse(data.expressionJson);
                var getResult, ulString = '';
//                flowData.cells.forEach(el => {
//                    if (el.type != 'app.Link' && el.attrs.label.code == 'find-object') {
//                        startIndicatorId = JSON.parse(el.attrs.label.expressionindicator).id
//                    }
//                });                   
                if (rowData.type === 'META DATA') {
                    getResult = $.ajax({
                        type: "post",
                        url: 'mdform/getShowInputParams/'+rowData.id,
                        dataType: "json",
                        async: false
                    });                    

                    getResult = getResult.responseJSON;
                
                    if (getResult.renderData[0] && getResult.renderData[0].data) {
                        ulString = '<ul style="list-style: none;padding-left: 0;max-height: 350px;">';
                        for(var ii = 0; ii < getResult.renderData[0].data.length; ii++) {
                            ulString += '<li data-value="" class="mt-1"><div class="d-flex" style="align-items: center;">'+
                            '<div style="flex: 0 0 40%;padding: 5px 5px 4px 5px;text-align: right;overflow-wrap: anywhere;">'+getResult.renderData[0].data[ii]['META_DATA_NAME']+' - '+getResult.renderData[0].data[ii]['PARAM_REAL_PATH']+'</div>'+
                            '<div style="width:100%"><input class="form-control input-sm" placeholder="'+getResult.renderData[0].data[ii]['PARAM_REAL_PATH']+'" data-micro-path="'+getResult.renderData[0].data[ii]['PARAM_REAL_PATH']+'"></div>'+
                            '</div></li>';
                        }
                        ulString += '</ul>';
                    }                    
                } else {
                    getResult = $.ajax({
                        type: "post",
                        url: "mdform/getKpiIOIndicatorColumns",
                        data: {
                            mainIndicatorId: rowData.id
                        },
                        dataType: "json",
                        async: false
                    });

                    getResult = getResult.responseJSON;
                
                    if (getResult[0]) {
                        ulString = '<ul style="list-style: none;padding-left: 0;max-height: 350px;">';
                        for(var ii = 0; ii < getResult.length; ii++) {
                            ulString += '<li data-value="" class="mt-1"><div class="d-flex" style="align-items: center;">'+
                            '<div style="flex: 0 0 40%;padding: 5px 5px 4px 5px;text-align: right;overflow-wrap: anywhere;">'+getResult[ii]['COLUMN_NAME']+'</div>'+
                            '<div style="width:100%"><input class="form-control input-sm" placeholder="'+getResult[ii]['LABEL_NAME']+'" data-micro-path="'+getResult[ii]['COLUMN_NAME']+'"></div>'+
                            '</div></li>';
                        }
                        ulString += '</ul>';
                    }                       
                }
                
                if (ulString) {

                    var $dialogName2 = 'dialog-microflow-bindparameter';
                    if (!$("#" + $dialogName2).length) { $('<div id="' + $dialogName2 + '"></div>').appendTo('body'); }
                    var $dialogBindParam = $('#' + $dialogName2);

                    $dialogBindParam.dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Parameter map',
                        width: "500px",
                        position: { my: "top", at: "top+100" },
                        modal: true,
                        open: function() {
                            disableScrolling();
                            $dialogBindParam.empty().append(ulString).promise().done(function() {         
                                try {
                                    var mapsConfig = JSON.parse($(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val());
                                    $.each(mapsConfig, function(k, v){
                                        $dialogBindParam.find('input[data-micro-path="'+v.srcPath+'"]').val(v.value);
                                    })                     
                                } catch (e) { }
                            });                            
                        }, 
                        close: function() {
                            $dialogBindParam.empty().dialog("destroy").remove();
                            enableScrolling();
                        }, 
                        buttons: [
                            {text: "Run", class: 'btn btn-sm green-meadow bp-btn-run d-none', click: function() {
                                let inputDatas = '';
                                $dialogBindParam.find('input').each(function(){
                                    inputDatas += $(this).data('micro-path')+'@'+$(this).val()+'|';
                                });
                                inputDatas = rtrim(inputDatas, '|');
                                $.ajax({
                                    type: 'post',
                                    url: 'mdexpression/microRunMeta/'+text+'/'+inputDatas+'/1',
                                    success: function(data) {
                                        var $dialogName3 = 'dialog-microflow-bindparameter-view';
                                        if (!$("#" + $dialogName3).length) { $('<div id="' + $dialogName3 + '"></div>').appendTo('body'); }
                                        var $dialogBindParamView = $('#' + $dialogName3);

                                        $dialogBindParamView.dialog({
                                            cache: false,
                                            resizable: false,
                                            bgiframe: true,
                                            autoOpen: false,
                                            title: 'Output',
                                            width: "1000px",
                                            position: { my: "top", at: "top+100" },
                                            modal: false,
                                            open: function() {
                                                disableScrolling();
                                                $dialogBindParamView.empty().append('<code><pre style="background-color: #000;color: greenyellow">'+data.replace(/Array/g, '')+'</pre></code>');
                                            }, 
                                            close: function() {
                                                $dialogBindParamView.empty().dialog("destroy").remove();
                                                enableScrolling();
                                            }, 
                                            buttons: [
                                                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                                    $dialogBindParamView.dialog('close');
                                                }}
                                            ]
                                        });
                                        $dialogBindParamView.dialog('open');                                                              
                                    }
                                });                              
                            }},
                            {text: "Хадгалах", class: 'btn btn-sm green bp-btn-save', click: function() {
//                                let inputDatas = '';
//                                $dialogBindParam.find('input').each(function(){
//                                    inputDatas += $(this).data('micro-path')+'@'+$(this).val()+'|';
//                                });                                                
//                                var selection = window.microfloweditor.getSelection();
//                                var id = { major: 1, minor: 1 };             
//                                var text = $(this).data('value');
//                                var op = {identifier: id, range: selection, text: 'runMeta:'+textGlo+'('+rtrim(inputDatas, '|')+')', forceMoveMarkers: true};
//                                window.microfloweditor.executeEdits("my-source", [op]);                       
                                var getParamList = $dialogBindParam.find('ul > li');
                                var paramArr = [], paramStr = '';
                                getParamList.each(function(){
                                    var $this = $(this), pval = $this.find('input').attr('data-micro-path'), val = $this.find('input').val();
                                    if (val) {
                                        paramArr.push({
                                            srcPath: pval,
                                            value: val
                                        });
                                        paramStr += pval+':<strong>'+val+'</strong><br/>';
                                    }
                                })
                                $(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val(JSON.stringify(paramArr));
                                $(elem).closest('.meta-autocomplete-wrap').find('input[type="text"]').val(paramArr.length+' map тохиргоо').trigger('change');                                
                                $(elem).closest('.meta-autocomplete-wrap').find('.flowchart-mapping-data').html(paramStr);                                
                                $dialogBindParam.dialog('close');
                            }},
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                $dialogBindParam.dialog('close');
                            }}
                        ]
                    });
                    $dialogBindParam.dialog('open');                                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                      title: "Warning",
                      text: 'Parameter хоосон байна!',
                      type: "warning",
                      sticker: false,
                      addclass: "pnotify-center"
                    });                  
                }                

//                $dialog.dialog({
//                    cache: false,
//                    resizable: false,
//                    bgiframe: true,
//                    autoOpen: false,
//                    title: 'Parameter map',
//                    width: "1000px",
//                    position: { my: "top", at: "top+100" },
//                    modal: true,
//                    open: function() {
//                        disableScrolling();
//                        $dialog.empty().append(getResult.responseText).promise().done(function() {         
//                        });
//                    }, 
//                    close: function() {
//                        $dialog.empty().dialog("destroy").remove();
//                        enableScrolling();
//                    }, 
//                    buttons: [
//                        {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-save', click: function() {
//                            var getParamList = $dialog.find('#indicatorParameter-form').find('tbody > tr');
//                            var paramArr = [];
//                            getParamList.each(function(){
//                                var $this = $(this);
//                                if ($this.find('select[name="trgId[]"]').val()) {
//                                    paramArr.push({
//                                        srcId: $this.find('input[name="srcId[]"]').val(),
//                                        srcPath: $this.find('input[name="srcPath[]"]').val(),
//                                        trgId: $this.find('select[name="trgId[]"]').val(),
//                                        trgPath: $this.find('select[name="trgId[]"]').find(':selected').data('trgpath')
//                                    });
//                                }
//                            })
//                            $(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val(JSON.stringify(paramArr));
//                            $(elem).closest('.meta-autocomplete-wrap').find('input[type="text"]').val(paramArr.length+' map тохиргоо').trigger('change');
//                            $dialog.dialog('close');
//                        }},
//                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
//                            $dialog.dialog('close');
//                        }}
//                    ]
//                });
//                $dialog.dialog('open');
                Core.unblockUI();
            });
        }, 300);
    }

    function microflowAddUserPermission(elem) {
        var $dialogName = "dialog-pos-add-userpermission";
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo("body");
        }
        var $dialog = $("#" + $dialogName),
            jsonParam = [];

        // saveVexpression(function(data){
            var startIndicatorId = '';
            var crudIndicatorId = JSON.parse($(elem).closest('.inspector-container').find('input[name="indicatorId"]').val()).id;
            // var flowData = JSON.parse(data.expressionJson);
            // flowData.cells.forEach(el => {
            //     if (el.type != 'app.Link' && el.attrs.label.code == 'find-object') {
            //         startIndicatorId = JSON.parse(el.attrs.label.expressionindicator).id
            //     }
            // });                  
            jsonParam = JSON.stringify({
                indicatorId: crudIndicatorId,
                processIndicatorId: crudIndicatorId,
            });

            $.ajax({
                type: "post",
                url: "mdwebservice/callMethodByMeta",
                data: {
                    metaDataId: "166919892814510",
                    isDialog: true,
                    isSystemMeta: false,
                    fillJsonParam: jsonParam
                },
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        message: "Loading...",
                        boxed: true,
                    });
                },
                success: function (data) {
                    $dialog.empty().append(data.Html);

                    var processForm = $("#wsForm", "#" + $dialogName);
                    var processUniqId = processForm.parent().attr("data-bp-uniq-id");

                    var buttons = [
                        {
                        text: data.run_btn,
                        class: "btn green-meadow btn-sm bp-btn-save",
                        click: function (e) {
                            if (window["processBeforeSave_" + processUniqId]($(e.target))) {
                                processForm.validate({
                                    ignore: "",
                                    highlight: function (element) {
                                        $(element).addClass("error");
                                        $(element).parent().addClass("error");
                                        if (
                                            processForm.find("div.tab-pane:hidden:has(.error)").length
                                        ) {
                                            processForm
                                            .find("div.tab-pane:hidden:has(.error)")
                                            .each(function (index, tab) {
                                                var tabId = $(tab).attr("id");
                                                processForm
                                                .find('a[href="#' + tabId + '"]')
                                                .tab("show");
                                            });
                                        }
                                    },
                                    unhighlight: function (element) {
                                        $(element).removeClass("error");
                                        $(element).parent().removeClass("error");
                                    },
                                    errorPlacement: function () { },
                                });

                                var isValidPattern = initBusinessProcessMaskEvent(processForm);

                                if (processForm.valid() && isValidPattern.length === 0) {
                                    processForm.ajaxSubmit({
                                    type: "post",
                                    url: "mdwebservice/runProcess",
                                    dataType: "json",
                                    beforeSend: function () {
                                        Core.blockUI({
                                        boxed: true,
                                        message: plang.get("POS_0040"),
                                        });
                                    },
                                    success: function (responseData) {
                                        if (responseData.status === "success") {
                                            var responseParam = responseData.paramData;
                                            $(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val('эрх тохируулсан');
                                            $(elem).closest('.meta-autocomplete-wrap').find('input[type="text"]').val('эрх тохируулсан').trigger('change');
                                            $dialog.dialog("close");
                                        }
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    },
                                    });
                                }
                            }
                        },
                        },
                        {
                        text: data.close_btn,
                        class: "btn blue-madison btn-sm",
                        click: function () {
                            $dialog.dialog("close");
                        },
                        },
                    ];

                    var dialogWidth = data.dialogWidth,
                        dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === "auto") {
                        dialogWidth = 1200;
                        dialogHeight = "auto";
                    }

                    $dialog
                        .dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: dialogWidth,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape:
                            typeof isCloseOnEscape == "undefined" ? true : isCloseOnEscape,
                        close: function () {
                            $dialog.empty().dialog("destroy").remove();
                        },
                        buttons: buttons,
                        })
                        .dialogExtend({
                        closable: true,
                        maximizable: true,
                        minimizable: true,
                        collapsable: true,
                        dblclick: "maximize",
                        minimizeLocation: "left",
                        icons: {
                            close: "ui-icon-circle-close",
                            maximize: "ui-icon-extlink",
                            minimize: "ui-icon-minus",
                            collapse: "ui-icon-triangle-1-s",
                            restore: "ui-icon-newwin",
                        },
                        });
                    if (data.dialogSize === "fullscreen") {
                        $dialog.dialogExtend("maximize");
                    }
                    $dialog.dialog("open");
                },
                error: function () {
                    alert("Error");
                },
            }).done(function () {
                Core.initBPAjax($dialog);
                Core.unblockUI();
            });
        // })
    }    

    function microflowAddButtonIcon(elem) {
        var $dialogName = 'dialog-microflow-add-button-icon';
        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
        var $dialog = $('#' + $dialogName);
        
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Button icon',
            width: "700px",
            position: { my: "top", at: "top+100" },
            modal: true,
            open: function() {
                var selectedIcon = $(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val();
                disableScrolling();
                // if (!$().iconpicker) {
                    $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                    });
                // }                
                $dialog.empty().append('<div data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="15" data-rows="12" data-icon="'+selectedIcon+'" role="iconpicker"></div>').promise().done(function() {         
                });
            }, 
            close: function() {
                $dialog.empty().dialog("destroy").remove();
                enableScrolling();
            }, 
            buttons: [
                {text: 'Сонгох', class: 'btn btn-sm green bp-btn-save', click: function() {
                    $(elem).closest('.meta-autocomplete-wrap').find('input[type="hidden"]').val($dialog.find('input[type="hidden"]').val()).trigger('change');
                    $(elem).closest('.meta-autocomplete-wrap').find('.show-icon-div').html('<i class="far '+$dialog.find('input[type="hidden"]').val()+'"></i>');
                    $dialog.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    }    

    $(function() {    
    });    
</script>