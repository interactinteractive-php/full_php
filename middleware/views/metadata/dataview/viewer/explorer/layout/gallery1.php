<div class="gallery1-dv" id="gallery1-dv-<?php echo $this->dataViewId; ?>">
    <?php
    if ($this->recordList) {
        if ($this->groupName) {
            foreach ($this->recordList as $recordRow) {
                $groupName = $recordRow['row'][$this->groupName];
                $rows = $recordRow['rows'];        
            ?>
    <div class="ml8 mt6"><strong><?php echo $groupName; ?></strong></div>
                <ul class="gallery mb0">
                        <?php
                  foreach ($rows as $row) {
                      $imgSrc  = isset($row[$this->photoField]) ? $row[$this->photoField] : $this->defaultImage;
                      $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                      ?>
                        <li class="item gallery-item mb5" data-full="<?php echo $imgSrc; ?>">
                            <a href="<?php echo URL.$imgSrc; ?>" data-fancybox="images" class="selected-row-link" rel="gallery" data-row-data="<?php echo $rowJson; ?>" data-caption="<?php echo isset($row[$this->name2]) ? $row[$this->name2] : ''; ?>">
                                <img src="<?php echo $imgSrc; ?>" data-default-image="<?php echo $this->defaultImage; ?>"
                                        onerror="onDataViewImgError(this);">
                                <span>
                                        <h3 class="item-pos-1"><?php echo isset($row[$this->name1]) ? $row[$this->name1] : ''; ?></h3>
                                        <p class="item-pos-2"><?php echo isset($row[$this->name2]) ? $row[$this->name2] : ''; ?></p>
                                </span>
                            </a>
                        </li>
                        <?php
                }
                ?>
                </ul>
            <?php
            }
        } else {
        ?>
	<ul class="gallery">
		<?php
          foreach ($this->recordList as $row) {
              $imgSrc  = isset($row[$this->photoField]) ? $row[$this->photoField] : $this->defaultImage;
              $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
              ?>
		<li class="item gallery-item" data-full="<?php echo $imgSrc; ?>">
                    <a href="<?php echo URL.$imgSrc; ?>" data-fancybox="images" class="selected-row-link" rel="gallery" data-row-data="<?php echo $rowJson; ?>" data-caption="<?php echo isset($row[$this->name2]) ? $row[$this->name2] : ''; ?>">
                        <img src="<?php echo $imgSrc; ?>" data-default-image="<?php echo $this->defaultImage; ?>"
                                onerror="onDataViewImgError(this);">
                        <span>
                                <h3 class="item-pos-1"><?php echo isset($row[$this->name1]) ? $row[$this->name1] : ''; ?></h3>
                                <p class="item-pos-2"><?php echo isset($row[$this->name2]) ? $row[$this->name2] : ''; ?></p>
                        </span>
                    </a>
		</li>
		<?php
        }
        ?>
	</ul>
        <?php
        }
  } else {
      echo html_tag('div', array('class' => 'alert alert-info'), 'No data!');
  }
  ?>
</div>

<script type="text/javascript">
	$(function () {
                $("a[rel=gallery]").fancybox({
                    caption : function( instance, item ) {
                        var caption = $(this).data('caption');
                        return caption;
                    }
                });

		$('#gallery1-dv-<?php echo $this->dataViewId; ?>').on('contextmenu', '.gallery-item', function (e) {
			var elem = this;
			var _this = $(elem);
			var _parent = _this.closest('.gallery1-dv');
			_parent.find('.selected-row').removeClass('selected-row');
			_this.addClass('selected-row');
		});
	});
</script>