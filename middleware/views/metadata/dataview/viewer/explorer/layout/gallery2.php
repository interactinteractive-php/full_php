<div class="gallery2-dv" id="gallery2-dv-<?php echo $this->dataViewId; ?>">
  <ul class="gallery" data-ref-structure-id="<?php echo $this->refStructureId; ?>">
      <?php
      if ($this->isBack) {
          ?>
        <li class="gallery-item folder" onclick="explorerBackList_<?php echo $this->dataViewId; ?>('<?php echo $this->folderId; ?>');">
          <a href="javascript:;">
            <img class="img-fluid img-preview img-back" src="assets/core/global/img/meta/folder_back_transparent.png"/>
          </a>
        </li>            
        <?php
    }

    if ($this->folderList) {
        foreach ($this->folderList as $folderRow) {
            ?>
            <li class="gallery-item folder"
                id="<?php echo $folderRow['META_VALUE_CODE']; ?>" 
                title="<?php echo $folderRow['META_VALUE_NAME']; ?>" 
                data-row-data="<?php
                echo isset($folderRow['folderRecord']) ? htmlentities(json_encode($folderRow['folderRecord']), ENT_QUOTES, 'UTF-8') : '';
                ?>" 
                onclick="explorerSideBarFolder_<?php echo $this->dataViewId; ?>(this);"	
                ondblclick="dblClickItem_<?php echo $this->dataViewId; ?>(this, '<?php echo $folderRow['META_VALUE_ID']; ?>');">
              <a href="javascript:;">
                <img class="img-fluid img-preview" src="assets/core/global/img/meta/folder_transparent.png"/>
                <span>
                  <h3 class="item-pos-1"><?php echo $folderRow['META_VALUE_NAME']; ?></h3>                    
                </span>
              </a>
            </li>
            <?php
        }
    }

    if ($this->recordList) {
        ?>
        <?php
        foreach ($this->recordList as $row) {
            if (isset($row['fileextension'])) {
                $fileExtension = $row['fileextension'];
                if ($fileExtension == 'png' or
                        $fileExtension == 'gif' or
                        $fileExtension == 'jpeg' or
                        $fileExtension == 'pjpeg' or
                        $fileExtension == 'jpg' or
                        $fileExtension == 'x-png' or
                        $fileExtension == 'bmp') {
                    $photoField = isset($row['physicalpath']) ? $row['physicalpath'] : '';
                } else {
                    $photoField = isset($row[$this->photoField]) ? $row[$this->photoField] : '';
                }
            } else {
                $photoField = isset($row[$this->photoField]) ? $row[$this->photoField] : '';
            }

            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
            ?>
            <li class="item gallery-item" data-full="<?php echo $photoField; ?>">
              <a href="javascript:;" class="selected-row-link" data-row-data="<?php echo $rowJson; ?>">
                <img src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);">
                <span data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);">
                  <h3 class="item-pos-1"><?php echo isset($row[$this->name1]) ? $row[$this->name1] : ''; ?></h3>
                  <p class="item-pos-2"><?php echo isset($row[$this->name2]) ? $row[$this->name2] : ''; ?></p>
                </span>
              </a>
            </li>
            <?php
        }
        ?>
        <?php
    } else {
        echo html_tag('div', array('class' => 'alert alert-info'), 'No data!');
    }
    ?>
  </ul>

  <div class="lightbox" >
    <figure></figure>
  </div>
</div>

<script type="text/javascript">
    $(function(){
      if(!$("link[href='middleware/assets/css/gridlayout/gallery2.css']").length){
        $("head").prepend('<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/gallery2.css"/>');
      }
      var $galleryDv=$('.gallery2-dv'),
              $lightbox=$galleryDv.find('.lightbox'),
              $figure=$lightbox.find('figure');

      $galleryDv.find('ul.gallery .item img').on('click', function(){
        var full=$(this).closest('li').attr('data-full');
        toggleLightbox(full);
      });

      function toggleLightbox(url){
        if($lightbox.is('.open')){
          $lightbox.removeClass('open').fadeOut(200);
        } else {
          $figure.css('background-image', 'url(' + url + ')');
          $lightbox.addClass('open').fadeIn(200);
        }
      }

      $lightbox.on('click', toggleLightbox);

      $('#gallery2-dv-<?php echo $this->dataViewId; ?>').on('contextmenu', '.gallery-item', function(e){
        var elem=this;
        var _this=$(elem);
        var _parent=_this.closest('.gallery2-dv');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');
      });

      var $parentAppTab=$('#app_tab_<?php echo $this->dataViewId; ?>');
      if(!$parentAppTab.find('.button-text-left > .btn-group-devided').hasClass('isAddedBtn')){
        $parentAppTab.find('.button-text-left > .btn-group-devided').prepend(
                '<a class="btn btn-primary btn-circle btn-sm" title="<?php echo $this->lang->line('MET_99990111'); ?>" onclick="getEcmContentModal();" href="javascript:;"><i class="fa fa-upload"></i> <?php echo $this->lang->line('MET_99990111'); ?></a>');
      }
      $parentAppTab.find('.gallery2-dv').parents('.main-dataview-container').addClass('dv-explorer-3-parent');
      $parentAppTab.find('.dv-explorer-3-parent .explorer-table > .explorer-table-row > .explorer-table-cell').css({height: ($(
                '.page-sidebar-menu').attr('data-height') - 120)});

    });

</script>