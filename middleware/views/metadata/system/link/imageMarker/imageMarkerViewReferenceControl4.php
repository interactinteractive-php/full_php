<?php if (isset($this->postParams['sidebardvid'])) { ?>
<div class="row">
    <div class="col-md-9">
        <div class="row imageMarkerReferenceViewContainer_<?php echo $this->uniqId; ?>" data-main-locationId='<?php echo $this->locationId; ?>' id="windowid-<?php echo $this->uniqId; ?>">
            <?php 
            if (!empty($this->getPhoto['region'])) {
                foreach ($this->getPhoto['region'] as $row) {
                    
                    $rowVal = $row;
                    $existItem = ' imageMarkerViewDivImageExist';
                    $row = json_decode(html_entity_decode($row['REGION']), true);
                    
                    if (empty($rowVal['ITEM_KEY_ID'])) {
                        $existItem = '';
                    }
                    
                    if (isset($rowVal['COLOR']) && $rowVal['COLOR'] == '1') {
                        echo "<div class='imageMarkerViewDivImage2".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                              <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                              <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";
                    } else {
                        if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
                            echo "<div class='imageMarkerViewDivImage".$existItem."' ".(isset($rowVal['CHILD_PHOTO']) ? 'data-assetmarker=""' : '')." data-locationKeyId='".$rowVal['LOCATION_KEY_ID']."' data-locationId='".$rowVal['LOCATION_ID']."' data-picturepath='".$rowVal['PHOTO']."' style='left:".($row['x']+(isset($this->isWorkspace) && $this->isWorkspace == 1 ? 12 : 12))."px;top:".($row['y']+1)."px;height:".$row['h']."px;width:".$row['w']."px; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover; background-image: ".(issetVar($rowVal['CHILD_PHOTO']) ? 'url('.$rowVal['CHILD_PHOTO'].')' : 'none')."'>
                                  <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                                  <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";
                        } else {
                            echo "<div class='imageMarkerViewDivImage".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' data-picturepath='".$rowVal['PHOTO']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                                  <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                                  <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";
                        }
                    }
                }
            }
            ?>    
            <div id="jcropDiv" class="pl10"></div>
        </div>
    </div>
    <div class="col-md-3">
        <div id="sidebardv-<?php echo $this->uniqId; ?>"></div>
    </div>
</div>

<?php } else { ?>

<div class="row imageMarkerReferenceViewContainer_<?php echo $this->uniqId; ?>" data-main-locationId='<?php echo $this->locationId; ?>' id="windowid-<?php echo $this->uniqId; ?>">
    <?php 
    if (!empty($this->getPhoto['region'])) {

        foreach ($this->getPhoto['region'] as $row) {

            $rowVal = $row;
            $existItem = ' imageMarkerViewDivImageExist';
            $row = json_decode(html_entity_decode($row['REGION']), true);

            if (empty($rowVal['ITEM_KEY_ID'])) {
                $existItem = '';
            }
            
            $title = $rowVal['LOCATION_CODE'].' '.$rowVal['LOCATION_NAME'];
            
            if (isset($rowVal['COLOR']) && $rowVal['COLOR'] == '1') {
                echo "<div class='imageMarkerViewDivImage2".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                      <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                      <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";
            } else {
                if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
                    $addClass = (isset($this->region) && $this->region['x'] == $row['x'] && $this->region['y'] == $row['y']) ? 'imgdragDrop' . $this->locationId  : '';
                    echo "<div title='$title' data-x-". $this->region['x']  ." = '". $row['x'] ."' class='". $addClass ." imageMarkerViewDivImage".$existItem."' ".(isset($rowVal['CHILD_PHOTO']) ? 'data-assetmarker=""' : '')." data-locationKeyId='".$rowVal['LOCATION_KEY_ID']."' data-locationId='".$rowVal['LOCATION_ID']."' data-picturepath='".$rowVal['PHOTO']."' style='left:".($row['x']+(isset($this->isWorkspace) && $this->isWorkspace == 1 ? 35 : 12))."px;top:".($row['y']+11)."px;height:".$row['h']."px;width:".$row['w']."px; -webkit-background-size: contain; -moz-background-size: contain; -o-background-size: contain; background-size: contain; background-repeat: no-repeat;background-position: center;background-image: ".(issetVar($rowVal['CHILD_PHOTO']) ? 'url('.$rowVal['CHILD_PHOTO'].')' : 'none')."'>
                          <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i class='fa fa-navicon'></i></a></span>
                        </div>";
                } else {
                    
                    echo "<div class='imageMarkerViewDivImage".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' data-locationKeyId='".$rowVal['LOCATION_KEY_ID']."' data-picturepath='".$rowVal['PHOTO']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                          <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                          <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";
                }
            }
        }
    }
    ?>    
    <div id="jcropDiv_<?php echo $this->uniqId; ?>" class="pl10"></div>
</div>
<?php } ?>

<style type="text/css">
    .imageMarkerViewDivImage {
        cursor:pointer;
        position: absolute;
        z-index: 97;
        background-color: rgba(209, 210, 40, 0.58);
        border: 2px solid transparent;
    }
    .imageMarkerViewDivImage2 {
        cursor:pointer;
        position: absolute;
        z-index: 97;
        background-color: rgba(152, 251, 152, 0.58);
        border: 2px solid transparent;
    }
    .imageMarkerViewDivImage:hover, .imageMarkerViewDivImage2:hover {
        outline: #35aa47 solid thick;
    }
    .imageMarkerViewDivImage, .imageMarkerViewDivImage2 {
        outline: #35aa47 solid 1px;
    }
    .imageMarkerViewDivImageExist {
        background: url("<?php echo URL; ?>assets/core/global/img/imageMarkerViewExist.gif");
        background-size: 28px 27px;
        background-repeat: no-repeat; 
        background-position: center center;
        background-color: rgba(209, 210, 40, 0.58);
    }
    .jcrop-tracker {
        z-index: 96 !important;
    }
    
    .imgdragDrop<?php echo $this->locationId ?> {
        outline: #F00 dashed 1px;
    }
</style>

<script type="text/javascript">
    var windowId_<?php echo $this->uniqId; ?> = "#windowid-<?php echo $this->uniqId; ?>";
    var j_<?php echo $this->uniqId; ?> = <?php echo isset($this->data) ? json_encode($this->data) : "''"; ?>;
    
    if (j_<?php echo $this->uniqId; ?> != '') {
        var jsStr = JSON.parse(<?php echo isset($this->data) ? json_encode($this->data) : json_encode(array()); ?>);
        j_<?php echo $this->uniqId; ?> = [jsStr.x, jsStr.y, jsStr.x + jsStr.w, jsStr.y + jsStr.h];
    }    
    
    $(function(){
        if (!$().Jcrop) {
            $.getScript(URL_APP + 'assets/custom/addon/plugins/jcrop/js/jquery.Jcrop.min.js', function(){
                $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/jcrop/css/jquery.Jcrop.min.css');
                $.getScript(URL_APP + 'assets/custom/addon/admin/pages/scripts/form-image-crop.js', function(){});
            });
        }
        
        setTimeout(function(){
            $("#jcropDiv_<?php echo $this->uniqId; ?>", windowId_<?php echo $this->uniqId; ?>).empty().append('<img src="<?php echo $this->getPhoto['url']; ?>" data-id="6"  style="max-width: 1150px;" />');
            $("#jcropDiv_<?php echo $this->uniqId; ?> img", windowId_<?php echo $this->uniqId; ?>).Jcrop({
                bgColor: '#fff',
                bgOpacity: 1,
                allowSelect: true,
                allowMove: true,
                allowResize: true,  
                onChange: canvas,
                onSelect: canvas,
                setSelect: j_<?php echo $this->uniqId; ?>
            });  
        }, 200);
        
        setTimeout(function(){
            $("#jcropDiv_<?php echo $this->uniqId; ?>").find('.jcrop-holder').children().children().find('.jcrop-tracker').css('background-color', 'rgba(32, 193, 87, 0.71)');
        }, 300);        
        
        var canvas = function(coords){
            var js = {};
            js.h = coords.h;
            js.w = coords.w;
            js.x = coords.x;
            js.y = coords.y;
            $("#jcropDiv_<?php echo $this->uniqId; ?>").attr('data-imagejsonstring', JSON.stringify(js));
        };   
        
        $('.imgdragDrop<?php echo $this->locationId ?>').draggable();
        
        $(".imageMarkerReferenceViewContainer_<?php echo $this->uniqId; ?>").droppable({
            drop: function( event, ui ) {
                var element = ui.draggable[0];
                console.log($(element).position());
                var position = $(element).position();
                var posi = {h: $(element).height()+4, w: $(element).width()+4, x: position.left-23, y: position.top-38};
                $("#jcropDiv_<?php echo $this->uniqId; ?>").attr('data-imagejsonstring', JSON.stringify(posi));
            }
        });
    });
    
    function refreshLocationPhoto(rowId, photoUrl) {
        $('.imageMarkerReferenceViewContainer_<?php echo $this->uniqId; ?>:visible:last').find('.imageMarkerViewDivImage[data-locationkeyid="'+rowId+'"]').css('background-image', 'url('+photoUrl+')');
    }
</script>

