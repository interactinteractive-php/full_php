<ul class="dv-explorer8 dv-explorer" id="main-item-container">
    <?php
    if ($this->isBack) {
    ?>
    <li class="dv-explorer-row-f">	
        <a href="javascript:;" onclick="explorerBackList_<?php echo $this->dataViewId; ?>('<?php echo $this->folderId; ?>');" class="folder-link">
            <div class="first-title"></div>
            <div class="dv-img-container">
                <div class="dv-img-container-sub">
                    <img class="dv-directory-img" src="assets/core/global/img/meta/folder_back_transparent.png"/>
                </div>
            </div>
            <div class="second-title">
                <h4><?php echo $this->lang->line('back_btn'); ?></h4>
            </div>
        </a>	
    </li>
    <?php
    }
    if ($this->folderList) {
        foreach ($this->folderList as $folderRow) {
    ?>
    <li class="dv-explorer-row-f" id="<?php echo $folderRow['META_VALUE_CODE']; ?>">	
        <a href="javascript:;" onclick="selectDataViewByCategory_<?php echo $this->dataViewId; ?>('<?php echo $folderRow['META_VALUE_ID']; ?>')" class="folder-link" title="<?php echo $folderRow['META_VALUE_NAME']; ?>">
            <div class="first-title">
                <?php echo $folderRow['META_VALUE_NAME']; ?>
            </div>
            <div class="dv-img-container">
                <div class="dv-img-container-sub">
                    <img class="dv-directory-img" src="assets/core/global/img/meta/folder_transparent_2.png"/>
                </div>
            </div>
            <div class="second-title">
                <h4><?php echo $folderRow['META_VALUE_NAME']; ?></h4>
            </div>
        </a>	
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
            $onClick = 'echo "clickItem_' . $this->dataViewId . '(this);";';

            $firstRow = $this->recordList[0];
            $name1 = $name2 = $name3 = $name4 = $name5 = $name6 = $photoField = $rowcolor = ' echo "";';

            if (isset($firstRow[$this->name1])) {
                if ($this->name1 == 'wfmstatusname' && isset($firstRow['wfmstatusid'])) {
                    $name1 = 'echo \'<span class="badge label-sm" style="background-color: \'.$recordRow[\'wfmstatuscolor\'].\'" onclick="dataViewWfmStatusFlowViewer(this, \\\'\'.$recordRow[\'id\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusid\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusname\'].\'\\\', \\\'\'.$this->dataViewId.\'\\\', \\\'\'.$this->refStructureId.\'\\\', \\\'\'.$recordRow[\'wfmstatuscolor\'].\'\\\');"> \'.$recordRow[\'wfmstatusname\'].\'</span>\';';
                } else {
                    $name1 = 'echo $recordRow[$this->name1];';
                }
            }

            if (isset($firstRow[$this->name2])) {
                if ($this->name2 == 'wfmstatusname' && isset($firstRow['wfmstatusid'])) {
                    $name2 = 'echo \'<span class="badge label-sm" style="background-color: \'.$recordRow[\'wfmstatuscolor\'].\'" onclick="dataViewWfmStatusFlowViewer(this, \\\'\'.$recordRow[\'id\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusid\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusname\'].\'\\\', \\\'\'.$this->dataViewId.\'\\\', \\\'\'.$this->refStructureId.\'\\\', \\\'\'.$recordRow[\'wfmstatuscolor\'].\'\\\');"> \'.$recordRow[\'wfmstatusname\'].\'</span>\';';
                } else {
                    $name2 = 'echo $recordRow[$this->name2];';
                }
            }

            if (isset($firstRow[$this->name3])) {
                $name3 = 'echo $recordRow[$this->name3];';
            }

            if (isset($firstRow[$this->name4])) {
                $name4 = 'echo $recordRow[$this->name4];';
            }

            if (isset($firstRow[$this->name5])) {
                $name5 = 'echo $recordRow[$this->name5];';
            }

            if (isset($firstRow[$this->name6])) {
                $name6 = 'echo $recordRow[$this->name6];';
            }

            if (isset($firstRow['rowcolor'])) {
                $rowcolor = 'echo " style=\"background-color: ".$recordRow[\'rowcolor\']."\"";';
            }

            if (isset($firstRow[$this->photoField])) {
                $photoField = 'echo $recordRow[$this->photoField];';
            }
            
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['photoview']) && array_key_exists($this->photoField, $firstRow)) {
                
                $onClick = 'echo "clickItemFancyBox(this, \'".$recordRow[$this->photoField]."\');";';
                
            } else {
                if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction'])
                    && $this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction'] != '') {

                    $onClickCustom = true;
                    $onClickField = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction']);
                }
            }

            if ($onClickCustom && isset($firstRow[$onClickField])) {
                $onClick = 'echo $recordRow[$onClickField];';
            }
          
          foreach ($this->recordList as $recordRow) {
            $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
              $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
            ?>
              <li class="dv-explorer-row"<?php eval($rowcolor); ?>>	
                <div class="selected-row-link" title="<?php echo strip_tags(isset($recordRow[$this->name1]) ? $recordRow[$this->name1] : ''); ?>" data-row-data="<?php echo $rowJson; ?>" onclick="<?php eval($onClick); ?>">
                  <div class="first-title font-weight-bold">
                      <?php eval($name2); ?>
                  </div>
                  <div class="dv-img-container mt10">
                    <div class="dv-img-container-sub">
                        <div style="width: 100px;height: 100px;border-radius: 50%;margin-left: 16px;background-color:<?php eval($name6); ?>">
                            <img class="dv-directory-img" src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                        </div>
                    </div>
                  </div>
                  <div class="second-title mt20">
                    <h4>
                        <?php eval($name1); ?> 
                    </h4>
                  </div>
                  <div class="third-title mt10 mb15" style="color: #2196F3;font-weight: bold; font-size: 15px">
                      <?php eval($name3); ?> 
                  </div>
                  <div class="third-title mb5" style="font-size: 12px; color: #000">
                      <?php eval($name4); ?> 
                  </div>
                  <div class="third-title mb5">
                      <?php eval($name5); ?> 
                  </div>
                </div>	
              </li>
              <?php
          }
      } else {
          foreach ($this->recordList as $recordRow) {
              $groupName = $recordRow['row'][$this->groupName];
              $rows = $recordRow['rows'];
              ?>
              <li class="dv-explorer-row-split">
                  <?php echo $groupName; ?>
              </li>
              <?php
              foreach ($rows as $recordRow) {
                  $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                  $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
                  ?>
                  <li class="dv-explorer-row"<?php echo (isset($recordRow['rowcolor']) ? ' style="background-color: ' . $recordRow['rowcolor'] . '"' : ''); ?>>	
                    <a href="javascript:;" class="selected-row-link" title="<?php echo strip_tags($recordRow[$this->name1]); ?>" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);">
                      <div class="first-title">
                          <?php echo isset($recordRow[$this->name2]) ? $recordRow[$this->name2] : ''; ?>
                      </div>
                      <div class="dv-img-container">
                        <div class="dv-img-container-sub">
                          <img class="dv-directory-img" src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                        </div>
                      </div>
                      <div class="second-title">
                        <h4>
                            <?php echo $recordRow[$this->name1]; ?> 
                        </h4>
                      </div>
                      <div class="third-title">
                        <?php echo isset($recordRow[$this->name3]) ? $recordRow[$this->name3] : ''; ?>
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
    if (!$("link[href='middleware/assets/css/gridlayout/explorer8.css?v=2']").length) {
        $("head").prepend('<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/explorer8.css?v=2"/>');
    }
});
</script>