<div class="carousel-mini-1" id="carousel-mini-1-<?php echo $this->dataViewId; ?>">
    <?php
    if ($this->recordList) {
        ?>
      <div class="mini-lists" role="list">
          <?php
          foreach ($this->recordList as $row) {
              $imgSrc  = isset($row[$this->photoField]) ? $row[$this->photoField] : '';
              $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
              ?>
            <div class="mini-list" role="listitem" title="<?php echo isset($row[$this->name1]) ? $row[$this->name1] : ''; ?>">
              <?php
              if (file_exists($imgSrc)) {
                  echo '<img class="mini-list-target" src="' . $imgSrc . '" role="presentation">';
              } else if (isset($row[$this->name1])) {
                  echo '<div class="mini-list-target" role="presentation"><span class="mini-list-target-r">' . mb_substr($row[$this->name1], 0,
                          1, 'utf-8') . '</span></div>';
              } else {
                  echo '<img class="mini-list-target" onerror="onUserImgError(this)" src="' . $imgSrc . '" role="presentation">';
              }
              ?>
            </div>
            <?php
        }
        ?>
      </div>
      <?php
  } 
  ?>
</div>

<script type="text/javascript">
    $(function(){
      if(!$("link[href='middleware/assets/css/gridlayout/carousel-mini1.css']").length){
        $("head").prepend(
                '<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/carousel-mini1.css"/>');
      }
    });
</script>