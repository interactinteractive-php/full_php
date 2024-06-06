<ul class="dv-note1 dv-explorer list-inline" id="main-item-container" data-ref-structure-id="<?php echo $this->refStructureId; ?>">
    <?php
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
                <a href="javascript:;" class="selected-row-link" title="<?php echo isset($recordRow[$this->name1]) ? strip_tags($recordRow[$this->name1]) : ''; ?>" data-row-data="<?php echo $rowJson; ?>" onclick="<?php echo $onClick; ?>">
                  <div class="text-center">
                    <img class="rounded-circle mt5 img-fluid img-explorer" src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                  </div>

                  <span class="badge label-sm label-type">
                      <?php echo isset($recordRow[$this->name1]) ? $recordRow[$this->name1] : ''; ?>
                  </span>

                  <h3 class="name-title" title="<?php echo isset($recordRow[$this->name2]) ? $recordRow[$this->name2] : ''; ?>">
                      <?php echo isset($recordRow[$this->name2]) ? $recordRow[$this->name2] : ''; ?>
                  </h3>
                  <p class="bottom-info-t"><?php echo isset($recordRow[$this->name3]) ? $recordRow[$this->name3] : ''; ?></p>
                </a>
              </li>

              <?php
          }
      }
  }
  ?>
</ul>
<script type="text/javascript">
    $(function(){
      if(!$("link[href='middleware/assets/css/gridlayout/note1.css']").length){
        $("head").prepend(
                '<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/note1.css"/>');
      }
    });
</script>