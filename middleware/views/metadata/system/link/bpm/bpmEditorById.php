<div id="graphContainer" style="width:100%;" class="geEditor"></div>
<textarea id="graphXmlById" style="display: none"><?php echo $this->graphXml; ?></textarea>

<script type="text/javascript">
    isBpmEditorUiInit = true;
    mainEditor(document.getElementById('graphContainer'));
</script>
