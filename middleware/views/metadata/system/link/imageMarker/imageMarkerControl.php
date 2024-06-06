<div class="row">
    <?php 
    if (!empty($this->getPhoto['region'])) {
        foreach ($this->getPhoto['region'] as $row) {
            $row = json_decode(html_entity_decode($row['REGION']), true);
            echo "<div style='position: absolute;z-index: 1000;background-color: rgba(152, 85, 195, 0.71);left:".($row['x']+9)."px;top:".($row['y']+10)."px;height:".$row['h']."px;width:".$row['w']."px;'></div>";
        }
    }
    ?>    
    <div id="jcropDiv" class="pl10" data-imagejsonstring=""></div>
</div>

<script type="text/javascript">
    var j = '';
    <?php
    if (isset($this->data) && Json::isJson($this->data)) {
    ?>
    var jsStr = JSON.parse(<?php echo json_encode($this->data); ?>);
    j = [jsStr.x, jsStr.y, jsStr.x + jsStr.w, jsStr.y + jsStr.h];
    <?php
    }
    ?>
    
    $(function(){
        if(!$().Jcrop){
          $.getScript(URL_APP + 'assets/custom/addon/plugins/jcrop/js/jquery.Jcrop.min.js', function(){
            $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/jcrop/css/jquery.Jcrop.min.css');
            $.getScript(URL_APP + 'assets/custom/pages/scripts/form-image-crop.js', function(){
            });
          });
        }
        
        setTimeout(function(){
            $("#jcropDiv").html("").append('<img src="<?php echo $this->getPhoto['url']; ?>" data-id="3"  style="max-width: 1150px;" alt="" />');
            $("#jcropDiv img").Jcrop({
              onChange: canvas,
              bgColor: '#fff',
              bgOpacity: 1,
              onSelect: canvas,
              /*boxWidth: '950',
              boxHeight: '600',*/
              setSelect: j
            });        
            
        }, 100);
        setTimeout(function(){
            $("#jcropDiv").find('.jcrop-holder').children().children().find('.jcrop-tracker').css('background-color', 'rgba(32, 193, 87, 0.71)');
        }, 150);
      
        var canvas = function(coords){
            var js = {};
            js.h = coords.h;
            js.w = coords.w;
            js.x = coords.x;
            js.y = coords.y;
            $("#jcropDiv").attr('data-imagejsonstring', JSON.stringify(js));
        };
    });
</script>
