<div id="ws_cover_div_<?php echo $this->uniqId; ?>" class="ws_cover_div">
  <div id="jcropDiv"></div>

  <form action="#" class="form-horizontal" id="wsCoverForm" enctype="multipart/form-data">
    <canvas id="canvas" width="32" height="32" style="display: none;"></canvas>
    <input id="pngInput" type="hidden" value="">
  </form>
</div>

<script type="text/javascript">
    $(function(){
      if(!$().Jcrop){
        $.getScript(URL_APP + 'assets/custom/addon/plugins/jcrop/js/jquery.Jcrop.min.js', function(){
          $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/jcrop/css/jquery.Jcrop.min.css');
          $.getScript(URL_APP + 'assets/custom/pages/scripts/form-image-crop.js', function(){

          });
        });
      }
    });
</script>