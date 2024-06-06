<div class="government" id="gallery4-dv-<?php echo $this->dataViewId; ?>">
  <div data-ref-structure-id="<?php echo $this->refStructureId; ?>">
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
        <?php echo "<div class='card'><div class='media-list media-list-linked'>";
        $n = 1;
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
            <div data-full="<?php echo $photoField; ?>">
              <li style="cursor: pointer;" data-toggle="collapse" data-target="#collapse<?php echo $n; ?>" aria-expanded="true" aria-controls="collapse<?php echo $n; ?>" data-row-data="<?php echo $rowJson; ?>">
                  <div class="media mb-0 d-flex align-items-center">
                      <div class="mr-2">
                        <img src="<?php echo $photoField; ?>" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);" width='36' height='36' class='rounded-circle'>
                      </div>
                      <div class="media-body" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);">
                          <div class="media-title d-flex">
                              <span class="font-weight-bold"><?php echo isset($row[$this->name1]) ? $row[$this->name1] : ''; ?></span>
                              <span class="text-muted ml-auto"><?php echo isset($row[$this->name2]) ? $row[$this->name2] : ''; ?></span>
                          </div>
                          <div class="media-title d-flex">
                              <span class="text-muted" style="line-height: normal;"><?php echo isset($row[$this->name3]) ? $row[$this->name3] : ''; ?></span>
                              <!-- <span style="font-size: 12px;" class="text-muted ml-auto font-weight-bold text-uppercase text-green"><?php echo isset($row[$this->name4]) ? $row[$this->name4] : ''; ?></span> -->
                              <?php 
                                $color = '';
                                if(isset($row[$this->name6]) && $row[$this->name6]) {
                                    $color = $row[$this->name6];
                                }
                              
                                if(isset($row[$this->name4]) && $row[$this->name4]) {
                                  echo "<span style='font-size: 12px;color: ".$color." !important;' class='text-muted ml-auto font-weight-bold text-uppercase'>".$row[$this->name4]."</span>";
//                                  if (($row[$this->name4]) == 'Баталгаажсан') {
//                                    echo "<span style='font-size: 12px;color: green !important;' class='text-muted ml-auto font-weight-bold text-uppercase'>Баталгаажсан</span>";
//                                  } else {
//                                    echo "<span style='font-size: 12px;color: #CC0000 !important;' class='text-muted ml-auto font-weight-bold text-uppercase'>Цуцалсан</span>";
//                                }
                                
                                  }
                              ?>
                          </div>
                      </div>
                  </div>
              </li>
              <div id="accordion">
                  <div class="card">
                      <div id="collapse<?php echo $n; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                          <div class="card-body">
                              <p class="text-justify p-3">
                                <?php echo isset($row[$this->name5]) ? $row[$this->name5] : ''; ?>
                              </p>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
            <?php $n++; } echo "</div></div>"; ?>
        <?php
    } else {
        echo html_tag('div', array('class' => 'alert alert-info'), 'No data!');
    }
    ?>
  </div>

  <div class="lightbox" >
    <figure></figure>
  </div>
</div>

<script type="text/javascript">
    $(function(){
      if(!$("link[href='middleware/assets/css/gridlayout/gallery4.css']").length){
        $("head").prepend('<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/gallery4.css"/>');
      }
      var $galleryDv=$('.gallery4-dv'),
              $lightbox=$galleryDv.find('.lightbox'),
              $figure=$lightbox.find('figure');

      $galleryDv.find('ul.gallery .item img').on('click', function(){
        var full=$(this).closest('div').attr('data-full');
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

      $('#gallery4-dv-<?php echo $this->dataViewId; ?>').on('contextmenu', '.gallery-item', function(e){
        var elem=this;
        var _this=$(elem);
        var _parent=_this.closest('.gallery4-dv');
        _parent.find('.selected-row').removeClass('selected-row');
        _this.addClass('selected-row');
      });

      var $parentAppTab=$('#app_tab_<?php echo $this->dataViewId; ?>');
      if(!$parentAppTab.find('.button-text-left > .btn-group-devided').hasClass('isAddedBtn')){
        $parentAppTab.find('.button-text-left > .btn-group-devided').prepend(
                '<a class="btn btn-primary btn-circle btn-sm" title="<?php echo $this->lang->line('MET_99990111'); ?>" onclick="getEcmContentModal();" href="javascript:;"><i class="fa fa-upload"></i> <?php echo $this->lang->line('MET_99990111'); ?></a>');
      }
      $parentAppTab.find('.gallery4-dv').parents('.main-dataview-container').addClass('dv-explorer-3-parent');
      $parentAppTab.find('.dv-explorer-3-parent .explorer-table > .explorer-table-row > .explorer-table-cell').css({height: ($(
                '.page-sidebar-menu').attr('data-height') - 120)});

    });

</script>
<style>
  .explorer-table-cell-sidebar.explorer-sidebar-1564736044014426 {
    display: none !important;
  }
</style>