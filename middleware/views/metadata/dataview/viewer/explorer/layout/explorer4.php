<ul class="dv-explorer4 dv-explorer list-inline" id="main-item-container" data-ref-structure-id="<?php echo $this->refStructureId; ?>">
    <?php    
    if ($this->isBack) {
        ?>
      <li class="dv-explorer-row-f">	
        <div class="row contact-box" onclick="explorerBackList_<?php echo $this->dataViewId; ?>('<?php echo $this->folderId; ?>');">
          <a href="javascript:;">
            <div class="col-sm-4 no-padding">
              <div class="text-center">
                <img class="mt5 img-fluid img-explorer img-back" src="assets/core/global/img/meta/folder_back_transparent.png"/>
              </div>
            </div>
            <div class="col-sm-8">
              <h3 class="name-title"><?php echo $this->lang->line('back_btn'); ?></h3>
            </div>
            <div class="clearfix w-100"></div>
          </a>
        </div>
      </li>
      <?php
  }
  if ($this->folderList) {
      foreach ($this->folderList as $folderRow) {
          ?>
          <li class="dv-explorer-row-f" id="<?php echo $folderRow['META_VALUE_CODE']; ?>">	
            <div class="row contact-box" onclick="selectDataViewByCategory_<?php echo $this->dataViewId; ?>('<?php echo $folderRow['META_VALUE_ID']; ?>')">
              <a href="javascript:;">
                <div class="col-sm-4 no-padding">
                  <div class="text-center">
                    <img class="mt5 img-fluid img-explorer" src="assets/core/global/img/meta/folder_transparent.png"/>
                  </div>
                </div>
                <div class="col-sm-8">
                  <h3 class="name-title"><?php echo $folderRow['META_VALUE_NAME']; ?></h3>
                </div>
                <div class="clearfix w-100"></div>
              </a>
            </div>
          </li>
          <?php
      }
  }
  if ($this->recordList) {
      if (isset($this->recordList['status'])) {
          echo html_tag('div', array('class' => 'alert alert-danger'), 'DV error message: ' . $this->recordList['message']);
          exit();
      }

      if ($this->isGrouped == false) {
          $onClickCustom = false;
          $onClick = 'clickItem_' . $this->dataViewId . '(this)';

          if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction'])
                  && $this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction'] != '') {

              $onClickCustom = true;
              $onClickField = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction']);
          }

          foreach ($this->recordList as $recordRow) {
              if (isset($recordRow['fileextension'])) {
                  $fileExtension = $recordRow['fileextension'];
                  if ($fileExtension == 'png' or
                          $fileExtension == 'gif' or
                          $fileExtension == 'jpeg' or
                          $fileExtension == 'pjpeg' or
                          $fileExtension == 'jpg' or
                          $fileExtension == 'x-png' or
                          $fileExtension == 'bmp') {
                      $photoField = isset($recordRow['physicalpath']) ? $recordRow['physicalpath'] : '';
                  } else {
                      $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                  }
              } else {
                  $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
              }

              $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');

              if ($onClickCustom) {
                  $onClick = $recordRow[$onClickField];
              }
              ?>
              <li class="dv-explorer-row"<?php echo (isset($recordRow['rowcolor']) ? ' style="background-color: ' . $recordRow['rowcolor'] . '"' : ''); ?>>	
                <a href="javascript:;" class="selected-row-link" title="<?php echo strip_tags($recordRow[$this->name1]); ?>" data-row-data="<?php echo $rowJson; ?>" onclick="<?php echo $onClick; ?>">
                  <div class="row contact-box">
                    <div class="col-sm-4 no-padding">
                      <div class="text-center">
                        <img class="rounded-circle mt5 img-fluid img-explorer" src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                      </div>
                    </div>
                    <div class="col-sm-8">
                      <h3 class="name-title" title="<?php echo isset($recordRow[$this->name1]) ? $recordRow[$this->name1] : ''; ?>"><?php echo isset($recordRow[$this->name1])
                        ? $recordRow[$this->name1] : '';?></h3>
                      <p><?php echo isset($recordRow[$this->name2]) ? $recordRow[$this->name2] : ''; ?></p>
                      <address>
                        <?php echo isset($recordRow[$this->name3]) ? substr($recordRow[$this->name3], 0, 10) . '-' : ''; ?><?php 
                            echo isset($recordRow[$this->name4]) ? substr($recordRow[$this->name4], 0, 10) : '';
                        ?>
                      </address>
                    </div>
                    <div class="clearfix w-100"></div>
                  </div>
                </a>
              </li>

              <?php
          }
      } else {
          foreach ($this->recordList as $recordRow) {
              $groupName = $recordRow['row'][$this->groupName];
              $rows = $recordRow['rows'];
              ?>
              <li class="dv-explorer-row-split list-inline">
              <?php echo $groupName; ?>
              </li>
              <?php
              foreach ($rows as $recordRow) {
                  if (isset($recordRow['fileextension'])) {
                      $fileExtension = $recordRow['fileextension'];
                      if ($fileExtension == 'png' or
                              $fileExtension == 'gif' or
                              $fileExtension == 'jpeg' or
                              $fileExtension == 'pjpeg' or
                              $fileExtension == 'jpg' or
                              $fileExtension == 'x-png' or
                              $fileExtension == 'bmp') {
                          $photoField = isset($recordRow['physicalpath']) ? $recordRow['physicalpath'] : '';
                      } else {
                          $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                      }
                  } else {
                      $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                  }

                  $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
                  ?>
                  <li class="dv-explorer-row"<?php echo (isset($recordRow['rowcolor']) ? ' style="background-color: ' . $recordRow['rowcolor'] . '"' : ''); ?>>	
                    <a href="javascript:;" class="selected-row-link" title="<?php echo strip_tags($recordRow[$this->name1]); ?>" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);">
                      <div class="row contact-box">
                        <div class="col-sm-4 no-padding">
                          <div class="text-center">
                            <img class="rounded-circle mt5 img-fluid img-explorer" src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                          </div>
                        </div>
                        <div class="col-sm-8">
                          <h3 class="name-title" title="<?php echo isset($recordRow[$this->name1]) ? $recordRow[$this->name1] : ''; ?>"><?php echo isset($recordRow[$this->name1])
                            ? $recordRow[$this->name1] : '';?></h3>
                          <p><?php echo isset($recordRow[$this->name2]) ? $recordRow[$this->name2] : ''; ?></p>
                          <address>
                            <?php echo isset($recordRow[$this->name3]) ? $recordRow[$this->name3] : ''; ?> - <?php echo isset($recordRow[$this->name4]) ? $recordRow[$this->name4] : '';?>
                          </address>
                        </div>
                        <div class="clearfix w-100"></div>
                      </div>
                    </a>
                  </li>
                  <?php
              }
          }
      }
  }
  ?>
</ul>
<script type="text/javascript">
    $(function(){
      if(!$("link[href='middleware/assets/css/gridlayout/explorer4.css']").length){
        $("head").prepend(
                '<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/explorer4.css"/>');
      }
    });
</script>