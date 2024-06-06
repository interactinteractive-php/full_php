<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px; height: 32px;" class="left-padding">Диаграм:</td>
                <td>
                    <button type="button" class="btn btn-sm purple-plum" onclick="openBpmEditor(this);">...</button>
                    <div id="dialog-bpmeditor-dialog" style="display: none"></div>
                    <?php echo Form::textArea(array('name' => 'graphXml', 'id' => 'graphXml', 'style' => 'display: none', 'value' => $this->graphXml)); ?>
                </td>
            </tr>                  
        </tbody>
    </table>
</div>

<script type="text/javascript">
var bpmEditorUiEditMode, isBpmEditorUiInit = false;

function openBpmEditor(elem) {
    
    Core.blockUI({message: 'Loading...', boxed: true});

    var $dialogName = 'dialog-bpmeditor-dialog';
    var $dialogContainer = $('#' + $dialogName);
    
    if ($dialogContainer.children().length > 0) {
        
        var $mxWindow = $('div.mxWindow');
        
        $dialogContainer.dialog('open');
                                
        if ($mxWindow.length) {
            $mxWindow.show();
        }

    } else {
        
        setTimeout(function() {
            
            loadMxGraphScripts();
            
            $.ajax({
                type: 'post',
                url: 'mdbpmn/bpmEditor',
                success: function (data) {

                    $dialogContainer.dialog({
                        appendTo: 'form#editMetaSystemForm',
                        dialogClass: 'no-titlebar-dialog', 
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'BPM',
                        width: $(window).width(),
                        height: $(window).height(),
                        modal: false,
                        open: function(){
                            disableScrolling();
                            if (isBpmEditorUiInit == false) {
                                setTimeout(function(){
                                    $dialogContainer.empty().append(data);
                                }, 100);
                            }
                            Core.unblockUI();
                        }, 
                        close: function() {
                            enableScrolling();
                        }, 
                        buttons: [
                            {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {

                                var graphXmlString = mxUtils.getXml(bpmEditorUiEditMode.editor.getGraphXml());
                                var $mxWindow = $('div.mxWindow');

                                $('#graphXml').val(graphXmlString);

                                if ($mxWindow.length) {
                                    $mxWindow.hide();
                                }

                                $dialogContainer.dialog('close');
                            }},
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {

                                var $mxWindow = $('div.mxWindow');

                                if ($mxWindow.length) {
                                    $mxWindow.hide();
                                }

                                $dialogContainer.dialog('close');
                            }}
                        ]
                    });
                    $dialogContainer.dialog('open');
                }
            });
            
        }, 300);
    }
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
        
        var graphXmlById = $('#graphXml').val();
        
        graphXmlById = graphXmlById.replace(/leftTagLeft/g, '&lt;');
        graphXmlById = graphXmlById.replace(/rightTagRight/g, '&gt;');
        graphXmlById = graphXmlById.replace(/doubleTagQuote/g, '&quot;');
        graphXmlById = graphXmlById.replace(/&nbsp;/g, ' ');
        
        var xmlString = mxUtils.parseXml(graphXmlById).documentElement;
        bpmEditorUiEditMode.editor.setGraphXml(xmlString);
        
    }, function(){
        document.body.innerHTML = '<center style="margin-top:10%;">Error loading resource files. Please check browser console.</center>';
    });
}
</script>