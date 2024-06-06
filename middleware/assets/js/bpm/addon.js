var bpmEditorUiEditMode, isBpmEditorUiInit = false;

function bpmMetaDiagramToolInit(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    PNotify.removeAll();
    
    Core.blockUI({message: 'Loading...', boxed: true});
    
    setTimeout(function() {
        
        loadMxGraphScripts();
        
        var $dialogName = 'dialog-bpmeditor-dialog';
        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
        var $dialogContainer = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdbpmn/bpmEditorById',
            data: paramData, 
            dataType: 'json',
            success: function (data) {

                $dialogContainer.dialog({
                    dialogClass: 'no-titlebar-dialog', 
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: $(window).width(),
                    height: $(window).height(),
                    modal: false,
                    closeOnEscape: false, 
                    open: function() {
                        disableScrolling();
                        $dialogContainer.empty().append(data.html);
                        Core.unblockUI();
                    }, 
                    close: function() {
                        enableScrolling();
                    }, 
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green', click: function () {

                            var graphXmlString = mxUtils.getXml(bpmEditorUiEditMode.editor.getGraphXml());

                             $.ajax({
                                type: 'post',
                                url: 'mdbpmn/saveBpmGraphXml',
                                data: {id: data.id, graphXml: graphXmlString},
                                dataType: 'json',
                                beforeSend: function() {
                                    Core.blockUI({message: 'Saving...', boxed: true});
                                },
                                success: function(data) {
                                    
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });      
                                        
                                    if (data.status == 'success') {
                                        $dialogContainer.dialog('close'); 
                                    }
                                    
                                    Core.unblockUI();
                                }
                            });
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                            $dialogContainer.dialog('close');
                        }}
                    ]
                });
                $dialogContainer.dialog('open');
            }
        });
        
    }, 100);
}
function bpmDiagramTool(elem, processMetaDataId, dataViewId, selectedRow, paramData) {
    PNotify.removeAll();
    
    Core.blockUI({message: 'Loading...', boxed: true});
    
    setTimeout(function() {
        
        loadMxGraphScripts();
        
        var $dialogName = 'dialog-bpmeditor-dialog';
        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
        var $dialogContainer = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdbpmn/bpmEditorByConfig',
            data: paramData, 
            dataType: 'json',
            success: function (data) {

                $dialogContainer.dialog({
                    dialogClass: 'no-titlebar-dialog', 
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: $(window).width(),
                    height: $(window).height(),
                    modal: false,
                    closeOnEscape: false, 
                    open: function() {
                        disableScrolling();
                        $dialogContainer.empty().append(data.html);
                        Core.unblockUI();
                    }, 
                    close: function() {
                        enableScrolling();
                    }, 
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-save', click: function () {

                            var graphXmlString = mxUtils.getXml(bpmEditorUiEditMode.editor.getGraphXml());
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdbpmn/saveBpmGraphXmlByConfig',
                                data: paramData,
                                dataType: 'json',
                                beforeSend: function(xhr) {
                                    Core.blockUI({message: 'Saving...', boxed: true});
                                    this.data += '&' + $.param({
                                        graphXml: graphXmlString
                                    });
                                },
                                success: function(data) {
                                    
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });      
                                        
                                    if (data.status == 'success') {
                                        $dialogContainer.dialog('close'); 
                                    }
                                    
                                    Core.unblockUI();
                                }
                            });
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                            $dialogContainer.dialog('close');
                        }}
                    ]
                });
                $dialogContainer.dialog('open');
            }
        });
        
    }, 100);
}
function mainEditor(container) {
    var editorUiInit = EditorUi.prototype.init;
    
    EditorUi.prototype.footerHeight = 0;
    
    EditorUi.prototype.init = function() {
        editorUiInit.apply(this, arguments);
    };
    
    // Adds required resources (disables loading of fallback properties, this can only
    // be used if we know that all keys are defined in the language specific file)
    mxResources.loadDefaultBundle = false;
    var bundle = mxResources.getDefaultBundle(RESOURCE_BASE, mxLanguage) || mxResources.getSpecialBundle(RESOURCE_BASE, mxLanguage);

    // Fixes possible asynchronous requests
    mxUtils.getAll([bundle, STYLE_PATH + '/default.xml'], function(xhr) {
        
        // Adds bundle text to resources
        mxResources.parse(xhr[0].getText());

        // Configures the default graph theme
        var themes = new Object();
        themes[Graph.prototype.defaultThemeName] = xhr[1].getDocumentElement(); 

        // Main
        bpmEditorUiEditMode = new EditorUi(new Editor(urlParams['chrome'] == '0', themes), container);
        
        var graphXmlById = graphXmlSpecialCharReplace($('#graphXmlById').val());
        var xmlString = mxUtils.parseXml(graphXmlById).documentElement;
        
        bpmEditorUiEditMode.editor.setGraphXml(xmlString);
        
    }, function(){
        document.body.innerHTML = '<center style="margin-top:10%;">Error loading resource files. Please check browser console.</center>';
    });
}

function graphXmlSpecialCharReplace(graphXml) {
    
    graphXml = graphXml.replace(/leftTagLeft/g, '&lt;');
    graphXml = graphXml.replace(/rightTagRight/g, '&gt;');
    graphXml = graphXml.replace(/doubleTagQuote/g, '&quot;');
    graphXml = graphXml.replace(/doubleTagQuotationMark/g, '&#34;');
    graphXml = graphXml.replace(/doubleTagQuotationMark/g, '&amp;#34;');
    graphXml = graphXml.replace(/&nbsp;/g, ' ');
    
    return graphXml;
}

function bpmDiagramToolById(elem, id) {
    
    PNotify.removeAll();
    Core.blockUI({message: 'Loading...', boxed: true});
    
    setTimeout(function() {
        
        loadMxGraphScripts();
        
        var $dialogName = 'dialog-bpmeditor-dialog';
        if (!$("#" + $dialogName).length) { $('<div id="' + $dialogName + '"></div>').appendTo('body'); }
        var $dialog = $('#' + $dialogName);

        $.ajax({
            type: 'post',
            url: 'mdbpmn/bpmEditorByPostInput',
            data: {graphData: $('#graphInput-' + id).val()},
            dataType: 'json',
            success: function (data) {

                $dialog.dialog({
                    dialogClass: 'no-titlebar-dialog', 
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'BPMN Tool',
                    width: $(window).width(),
                    height: $(window).height(),
                    modal: false, 
                    closeOnEscape: false, 
                    open: function() {
                        disableScrolling();
                        $dialog.empty().append(data.html);
                        Core.unblockUI();
                    }, 
                    close: function() {
                        enableScrolling();
                    }, 
                    buttons: [
                        {text: plang.get('save_btn_temp'), class: 'btn btn-sm green bp-btn-save', click: function () {

                            var graphXmlString = mxUtils.getXml(bpmEditorUiEditMode.editor.getGraphXml());
                            var graphView = bpmEditorUiEditMode.editor.graph;
                            var graphSvg = graphView.getSvg('#ffffff', 1, 0);
                            var $this = $(elem);
                            
                            if (graphXmlString) {
                                bpmDiagramConvertImagePath($this, id);
                            }
                            
                            $('#graphInput-' + id).val(graphXmlString);
                            $('#graphview-' + id).html(graphSvg);
                            
                            if ($this.next('button').length == 0) {
                                $this.after('<button type="button" class="btn btn-light btn-sm ml8" onclick="bpFieldGraphFullScreen(this);" title="Fullscreen"><i class="fa fa-expand"></i></button>');
                            }
                            
                            $dialog.dialog('close'); 
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            }
        });
        
    }, 100);
}
function bpmDiagramConvertImagePath(elem, graphId) {
    var $form = elem.closest('form');
    var $parent = elem.closest('[data-cell-path]');
    var path = $parent.attr('data-cell-path');
    var $imagePath = $form.find('[data-path="'+path+'_imagePath"]');
    
    if ($imagePath.length) {
        var id = $form.find('input[data-path="id"]').val();
        if (id == '') {
            id = getUniqueId('l');
        }
        var saveImage = 'mxgraph_capture_' + id;
        var graphSvg = $('#graphview-' + graphId).html();

        $.ajax({
            type: 'post',
            url: 'api/mxgraph/export',
            data: {saveImage: saveImage, xml: graphSvg, format: 'png'}, 
            dataType: 'json', 
            success: function(data) {
                if (data.status == 200) {
                    $imagePath.val('storage/uploads/process/'+saveImage+'.png');
                }
            }
        });
        
        /*$.cachedScript('assets/custom/addon/plugins/html2canvas/dom-to-image.js').done(function() {
            
            if ($('#graphview-' + graphId).find('svg').length) {
                
                var id = $form.find('input[data-path="id"]').val();
                if (id == '') {
                    id = getUniqueId('l');
                }
                var saveImage = 'mxgraph_capture_' + id;
                var node = $('#graphview-' + graphId)[0];

                domtoimage.toPng(node, {filter: htmlToImageTagFilter}).then(function(dataUrl) { 

                    $.ajax({
                        type: 'post',
                        url: 'mdcommon/base64ToImage',
                        data: {dataUrl: dataUrl, imagePath: 'storage/uploads/process/'+saveImage+'.png'}, 
                        dataType: 'json', 
                        success: function(subData) {
                            if (subData.status == 'success') {
                                $imagePath.val('storage/uploads/process/'+saveImage+'.png');
                            }
                        }
                    });

                }).catch(function (error) {
                    console.error('oops, something went wrong!', error);
                });
            }
        });*/
    }
}

var bpmEditorUiViewMode;

function mainBpmnEditorView(container, graphXml, id) {
    
    var editorUiInit = EditorUi.prototype.init;
    
    EditorUi.prototype.footerHeight = 0;
    
    EditorUi.prototype.init = function() {
        editorUiInit.apply(this, arguments);
    };
    
    mxResources.loadDefaultBundle = false;
    var bundle = mxResources.getDefaultBundle(RESOURCE_BASE, mxLanguage) || mxResources.getSpecialBundle(RESOURCE_BASE, mxLanguage);

    mxUtils.getAll([bundle, STYLE_PATH + '/default.xml'], function(xhr) {
        
        mxResources.parse(xhr[0].getText());

        var themes = new Object();
        themes[Graph.prototype.defaultThemeName] = xhr[1].getDocumentElement(); 

        bpmEditorUiViewMode = new EditorUi(new Editor(urlParams['chrome'] == '0', themes), container);
        
        graphXml = graphXmlSpecialCharReplace(graphXml); 
        
        var xmlString = mxUtils.parseXml(graphXml).documentElement;
        
        bpmEditorUiViewMode.editor.setGraphXml(xmlString);
        
        var graphView = bpmEditorUiViewMode.editor.graph;
                            
        $('#graphview-' + id).html(graphView.getSvg('#ffffff', 1, 0));
        
    }, function() {
        document.body.innerHTML = '<center style="margin-top:10%;">Error loading resource files. Please check browser console.</center>';
    });
}

function bpmDiagramViewByElement(elem) {
    
    loadMxGraphScripts();
    
    if ($('.mxgraph-global').length == 0) {
        $('body').append('<div class="mxgraph-global d-none"></div>');
    }
    
    elem.each(function() {
        var $this = $(this), graphVal = $this.val();
        mainBpmnEditorView($('.mxgraph-global')[0], graphVal, $this.attr('data-dtlid'));
    });
    
    $('.mxgraph-global').remove();
}