<div id="graphContainerView" style="overflow:auto; display: none"></div>
<textarea id="graphXmlByIdView" style="display: none"><?php echo $this->graphXml; ?></textarea>

<style type="text/css">
.geFormatContainer, .geSidebarContainer, .geMenubarContainer, .geHsplit {
    display: none;
}
.geDiagramContainer.geDiagramBackdrop {
    left: 0 !important;
    right: 0 !important;
}
.geSidebarContainer {
    top: 0;
}
</style>

<script type="text/javascript">

var bpmEditorUiViewMode, isBpmEditorUiInitView = true;

$(function(){
    
    setTimeout(function() {
        
        var $graphContainer = $('#graphContainerView');
        var $wrap = $graphContainer.closest('.workspace-main-container');
        $graphContainer.css({'width': $wrap.width(), 'height': 800, 'position': 'relative', 'display': ''});
        
        mainEditorView(document.getElementById('graphContainerView'));  
        
    }, 1000);
    
});

function mainEditorView(container) {
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
        bpmEditorUiViewMode = new EditorUi(new Editor(urlParams['chrome'] == '0', themes), container);
        
        var graphXmlById = $('#graphXmlByIdView').val();
        
        graphXmlById = graphXmlById.replace(/leftTagLeft/g, '&lt;');
        graphXmlById = graphXmlById.replace(/rightTagRight/g, '&gt;');
        graphXmlById = graphXmlById.replace(/doubleTagQuote/g, '&quot;');
        graphXmlById = graphXmlById.replace(/&nbsp;/g, ' ');
        
        var xmlString = mxUtils.parseXml(graphXmlById).documentElement;
        
        bpmEditorUiViewMode.editor.setGraphXml(xmlString);
        
    }, function() {
        document.body.innerHTML = '<center style="margin-top:10%;">Error loading resource files. Please check browser console.</center>';
    });
}

function mainGraphView(container) {
    var xml = $('#graphXmlByIdView').val();
    
    xml = xml.replace(/leftTagLeft/g, '&lt;');
    xml = xml.replace(/rightTagRight/g, '&gt;');
    xml = xml.replace(/doubleTagQuote/g, '&quot;');
    xml = xml.replace(/&nbsp;/g, ' ');
        
    var xmlDocument = mxUtils.parseXml(xml);

    if (xmlDocument.documentElement != null && xmlDocument.documentElement.nodeName == 'mxGraphModel') {
        
        var decoder = new mxCodec(xmlDocument);
        var node = xmlDocument.documentElement;

        //container.innerHTML = '';

        var graph = new mxGraph(container);
        graph.centerZoom = true;
        graph.setTooltips(true);
        graph.setEnabled(false);
        graph.resizeContainer = true;

        // Changes the default style for edges "in-place"
        //var style = graph.getStylesheet().getDefaultEdgeStyle();
        //style[mxConstants.STYLE_EDGE] = mxEdgeStyle.ElbowConnector;

        // Enables panning with left mouse button
        /*graph.panningHandler.useLeftButtonForPanning = true;
        graph.panningHandler.ignoreCell = true;
        graph.container.style.cursor = 'move';
        graph.setPanning(true);*/

        decoder.decode(node, graph.getModel());
    }
}
</script>

