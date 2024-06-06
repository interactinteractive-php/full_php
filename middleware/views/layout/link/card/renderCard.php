<link rel="stylesheet" type="text/css" href="middleware/assets/css/card/card.css"/>
<style type="text/css">
  .card-more {
      border-top:1px #CCC; 
      font-size: 14px; color:#FFF;
      font-weight: 400;
      padding-top:0px;
      text-transform: uppercase;
      font-size: 14px;
  }
  .text-service {
       
      margin-top:10px;
      color:#FFF;
      font-weight: 400;
      text-transform: uppercase;
      font-size: 15px;
      padding-left: 10px;
  }
  .card-main {
      margin-bottom:0;
  }
</style>
<?php
    $align = "left";
    $smallIcon = '';
    if (isset($this->card['TEXT_ALIGN']) && $this->card['TEXT_ALIGN'] == "R") 
        $align = "right";
    
    if (isset($this->card['META_ICON_NAME']) && $this->card['META_ICON_NAME'] != "" && $this->card['META_ICON_NAME'] != null && $this->card['META_ICON_NAME'] != "null") 
        $smallIcon = "assets/core/global/img/metaicon/small/" . $this->card['META_ICON_NAME'];
    
    if (isset($this->card['META_ICON_NAME'])) {
        $cardResult = str_replace("[value]", $this->card['CARD_RESULT'], $this->card['TEXT']);
        $explodeData = explode(':', $cardResult);
    }
        
?>
  <div class="col-md-12 pl0 pr0">
      <div class="dashboard-stat blue-madison card-main card-main-<?php echo isset($this->card['META_DATA_ID']) ? $this->card['META_DATA_ID'] : '' ?>"  style="background-color: <?php echo (isset($this->card['BGCOLOR'])) ? $this->card['BGCOLOR'] : '' ?>;">
        <div class="desc text-left pl15 text-service"><?php echo isset($this->card['TEXT_FROM_SERVICE']) ? $this->card['TEXT_FROM_SERVICE'] : (isset($explodeData[0]) ? $explodeData[0] : (isset($this->card['TEXT']) ? $this->card['TEXT'] : '')); ?></div>
        <div class="visual">
            <i class="fa <?php echo isset($this->card['FONT_ICON']) ? $this->card['FONT_ICON'] : '' ?>" style="font-size: 120px;"></i>
        </div>
        <div class="details" style="padding-right: 15px;">
          <div class="number" style="padding-top:20px; font-weight: 700; font-size: 50px;  "><div class="text-<?php echo isset($this->card['META_DATA_ID']) ? $this->card['META_DATA_ID'] : '' ?>" style="width: 300px !important; white-space: nowrap; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php 
                echo isset($this->card['ROW_COUNT']) ? (isset($explodeData[1]) ? $explodeData[1] : (isset($this->card['ROW_COUNT']) ? $this->card['ROW_COUNT'] : '')) : ''; 
            ?></div></div>
        </div>
          <a href="<?php echo (isset($this->card['URL'])) ? $this->card['URL'] : '' ?>" class="more card-more link-card-more" style="background-color: <?php echo (isset($this->card['BGCOLOR'])) ? $this->card['BGCOLOR'] : '' ?>;" href="javascript:;">Цааш нь <i class="m-icon-swapright m-icon-white"></i></a>
      </div>
  </div>
<style type="text/css">
  .dashboard-stat .details {
      right: 9px !important;
  }
</style>
<script type="text/javascript">
    $(function () {
        var metaDataId = '<?php echo isset($this->card['META_DATA_ID']) ? $this->card['META_DATA_ID'] : '' ?>';
        if (0 < metaDataId.length) {
            var cardMain = $('.card-main-'+metaDataId).width();
            $('.text-'+metaDataId).attr('style', "width: "+ (cardMain-100) +"px !important; white-space: nowrap; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;").attr('title', $('.text-'+metaDataId).html());
        }
    });
</script>