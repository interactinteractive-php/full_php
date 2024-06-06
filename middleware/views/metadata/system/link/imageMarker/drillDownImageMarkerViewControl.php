<?php 
if(!empty($this->getPhoto['region'])) {
    foreach ($this->getPhoto['region'] as $row) {
        $rowVal = $row;
        $existItem = ' imageMarkerViewDivImageExist';
        $row = json_decode(html_entity_decode($row['REGION']), true);
        if(empty($rowVal['ITEM_KEY_ID']))
            $existItem = '';
        
        //echo "<div onclick='drillDownImageMarkerView(this)' data-locationId='".$rowVal['LOCATION_ID']."' data-prev-locationId='".$this->locationId."' data-itemkeyid='".$rowVal['ITEM_KEY_ID']."' data-photo-url='".$rowVal['PHOTO']."' class='imageMarkerViewDivImage".$existItem."' style='left:".($row['x']+10)."px;top:".($row['y']+10)."px;height:".$row['h']."px;width:".$row['w']."px;'><span>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span></div>";
        echo "<div class='imageMarkerViewDivImage".$existItem."' data-locationId='".$rowVal['LOCATION_ID']."' data-prev-locationId='".$this->locationId."' data-itemkeyid='".$rowVal['ITEM_KEY_ID']."' data-photo-url='".$rowVal['PHOTO']."' data-prev-photo-url='".$this->getPhoto['url']."' style='left:".($row['x']+0)."px;top:".($row['y']+38)."px;height:".$row['h']."px;width:".$row['w']."px;'>
              <span style='position: absolute;margin-top: -22px;font-size: 10px;line-height: 8px;font-weight: bold;padding: 1px;'>".$rowVal['LOCATION_CODE']." ".$rowVal['LOCATION_NAME']."</span>
              <span class='hidden' style='position: absolute;bottom: -5px;left: -5px;'><a class='btn btn-sm green callDataviewImageMarker' href='javascript:;'><i class='fa fa-navicon'></i></a></span></div>";        
    }
}
?>    
<div id="jcropDiv" class="pl10"></div>

<script type="text/javascript">
    $(function(){
        $("#jcropDiv", ".imageMarkerViewContainer").html("").append('<img src="<?php echo $this->getPhoto['url']; ?>" data-id="1" style="max-width: 1150px;" alt="" />');
        $("#jcropDiv img", ".imageMarkerViewContainer").Jcrop({
          bgColor: '#fff',
          bgOpacity: 1,
          allowSelect: false,
          allowMove: true,
          allowResize: false              
        });
    });
</script>

