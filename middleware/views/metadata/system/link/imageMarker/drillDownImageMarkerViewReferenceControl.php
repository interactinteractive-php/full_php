<?php 
if(!empty($this->getPhoto['region'])) {
    foreach ($this->getPhoto['region'] as $row) {
        
        $rowVal = $row;
        $existItem = ' imageMarkerViewDivImageExist';
        $row = json_decode(html_entity_decode($row['REGION']), true);
        
        if (empty($rowVal['ITEM_KEY_ID'])) {
            $existItem = '';
        }
        
        $title = $rowVal['LOCATION_CODE'].' '.$rowVal['LOCATION_NAME'];
        
        if (isset($rowVal['COLOR']) && $rowVal['COLOR'] == '1') {
            echo "<div class='imageMarkerViewDivImage2".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' data-prev-locationId='".$this->locationId."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                  <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                  <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";        
        } else {
            if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {            
                echo "<div title='$title' class='imageMarkerViewDivImage".$existItem."' data-locationKeyId='".$rowVal['LOCATION_KEY_ID']."' data-locationId='".$rowVal['LOCATION_ID']."' ".(isset($rowVal['CHILD_PHOTO']) ? 'data-assetmarker=""' : '')." data-picturepath='".$rowVal['PHOTO']."' data-prev-locationId='".$this->locationId."' style='left:".($row['x']+(isset($this->isWorkspace) && $this->isWorkspace == 1 ? 0 : 12))."px;top:".($row['y']+11)."px;height:".$row['h']."px;width:".$row['w']."px; -webkit-background-size: contain; -moz-background-size: contain; -o-background-size: contain; background-size: contain; background-repeat: no-repeat;background-position: center;background-image: ".(issetVar($rowVal['CHILD_PHOTO']) ? 'url('.$rowVal['CHILD_PHOTO'].')' : 'none')."'>
                      <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i class='fa fa-navicon'></i></a></span>
                      </div>";        
            } else {
                echo "<div class='imageMarkerViewDivImage 1111".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' data-picturepath='".$rowVal['PHOTO']."' data-prev-locationId='".$this->locationId."' style='left:".($row['x'])."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
                      <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
                      <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' data-processId='".issetVar($this->postParams['processid'])."' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";        
            }
        }
        
        //echo "<div onclick='drillDownImageMarkerView(this)' data-locationId='".$rowVal['LOCATION_ID']."' data-prev-locationId='".$this->locationId."' data-itemkeyid='".$rowVal['ITEM_KEY_ID']."' data-photo-url='".$rowVal['PHOTO']."' class='imageMarkerViewDivImage".$existItem."' style='left:".($row['x']+10)."px;top:".($row['y']+10)."px;height:".$row['h']."px;width:".$row['w']."px;'><span>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span></div>";
    }
}
?>    
<div id="jcropDiv" class="pl10"></div>

<script type="text/javascript">
$(function(){
    $("#jcropDiv", ".imageMarkerReferenceViewContainer").empty().append('<img src="<?php echo $this->getPhoto['url']; ?>" data-id="2"  style="max-width: 1150px;" alt="" />');
    $("#jcropDiv img", ".imageMarkerReferenceViewContainer").Jcrop({
        bgColor: '#fff',
        bgOpacity: 1,
        allowSelect: false,
        allowMove: true,
        allowResize: false
    });
});
</script>

