<ul class="dv-explorer3 dv-explorer" id="main-item-container" data-ref-structure-id="<?php echo $this->refStructureId; ?>">
    <?php
    if ($this->isBack) {
    ?>
    <li class="dv-explorer-row list-inline" onclick="explorerBackList_<?php echo $this->dataViewId; ?>('<?php echo $this->folderId; ?>');">	
        <div class="col-md-6 col-sm-6 col-xs-12">
            <img class="img-fluid img-preview img-back" src="assets/core/global/img/meta/folder_back_transparent.png"/>
            <span class="title-f"><?php echo $this->lang->line('back_btn'); ?></span> 
        </div>    
        <div class="col-md-3 col-sm-6 col-xs-12"></div>    
        <div class="col-md-3 d-sm-none col-xs-12"></div>    
        <div class="clearfix w-100"></div>
    </li>
    <?php
    }
    if ($this->folderList) {
        foreach ($this->folderList as $folderRow) {
    ?>
        <li class="dv-explorer-row list-inline dv-explorer-folder-row" 
            id="<?php echo $folderRow['META_VALUE_CODE']; ?>" 
            title="<?php echo $folderRow['META_VALUE_NAME']; ?>" 
            data-row-id="<?php echo $folderRow['META_VALUE_ID']; ?>" 
            onclick="explorerSideBarFolder_<?php echo $this->dataViewId; ?>(this);"	
            ondblclick="dblClickItem_<?php echo $this->dataViewId; ?>(this, '<?php echo $folderRow['META_VALUE_ID']; ?>');">	
            <div class="col-md-6 col-sm-6 col-xs-12">
                <img class="img-fluid img-preview" src="assets/core/global/img/meta/folder_transparent.png"/>
                <span class="title-f"><?php echo $folderRow['META_VALUE_NAME']; ?></span> 
            </div>    
            <div class="col-md-3 col-sm-6 col-xs-12"></div>    
            <div class="col-md-3 d-sm-none col-xs-12"></div>    
            <div class="clearfix w-100"></div>
        </li>
    <?php
        }
    }
    if ($this->recordList) {
        if (isset($this->recordList['status'])) {
            echo html_tag('div', array('class' => 'alert alert-danger'), 'DV error message: ' . $this->recordList['message']); exit();
        }

        if ($this->isGrouped == false) {

            foreach ($this->recordList as $recordRow) {

                $name1 = isset($recordRow[$this->name1]) ? $recordRow[$this->name1] : '';

                if (isset($recordRow['fileextension'])) {

                    $fileExtension = strtolower($recordRow['fileextension']);

                    if ($fileExtension == 'png' or
                            $fileExtension == 'gif' or
                            $fileExtension == 'jpeg' or
                            $fileExtension == 'pjpeg' or
                            $fileExtension == 'jpg' or
                            $fileExtension == 'x-png' or
                            $fileExtension == 'bmp') {
                        $photoField = isset($recordRow['physicalpath']) ? $recordRow['physicalpath'] : '';
                        $name1 = '<a href="' . $recordRow['physicalpath'] . '" class="fancybox-button" data-rel="fancybox-button" title="' . $recordRow[$this->name1] . '">' . $recordRow[$this->name1] . '</a>';
                    } else {
                        if ($fileExtension == 'pdf' or $fileExtension == 'xls' or
                                  $fileExtension == 'xlsx' or
                                  $fileExtension == 'doc' or
                                  $fileExtension == 'ppt' or
                                  $fileExtension == 'pptx' or
                                  $fileExtension == 'docx') {
                            $name1 = '<a href="javascript:;" class="filePreviewBtn" data-fn-param="this, \\\'' . $recordRow['id'] . '\\\', \\\'' . $fileExtension . '\\\', \\\'' . $recordRow[$this->name1] . '\\\', \\\'' . $recordRow['physicalpath'] . '\\\'">' . $recordRow[$this->name1] . '</a>';
                        }
                        $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                    }
                } else {
                    $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                }

                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
    ?>
        <li class="dv-explorer-row list-inline" title="<?php echo $recordRow[$this->name1]; ?>" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);" ondblclick="showFileViewer(this);">	
            <div class="selected-row-link">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <img class="img-fluid img-preview" src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                    <span class="title-f"><?php echo $name1; ?></span> <br>
                    <span class="title-path"><?php echo isset($recordRow[$this->name4]) ? $recordRow[$this->name4] : ''; ?></span> 
                </div>    
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <span><?php echo isset($recordRow[$this->name2]) ? $recordRow[$this->name2] : ''; ?> </span>
                </div>    
                <div class="col-md-3 d-sm-none col-xs-12">
                    <span><?php echo isset($recordRow[$this->name3]) ? $recordRow[$this->name3] : ''; ?> </span>
                </div>    
                <div class="clearfix w-100"></div>
            </div>
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
            
            $name1 = isset($recordRow[$this->name1]) ? $recordRow[$this->name1] : '';
            
            if (isset($recordRow['fileextension'])) {
                
                $fileExtension = strtolower($recordRow['fileextension']);
                
                if ($fileExtension == 'png' or
                        $fileExtension == 'gif' or
                        $fileExtension == 'jpeg' or
                        $fileExtension == 'pjpeg' or
                        $fileExtension == 'jpg' or
                        $fileExtension == 'x-png' or
                        $fileExtension == 'bmp') {
                    $photoField = isset($recordRow['physicalpath']) ? $recordRow['physicalpath'] : '';
                    $name1 = '<a href="' . $recordRow['physicalpath'] . '" class="fancybox-button" data-rel="fancybox-button" title="' . $recordRow[$this->name1] . '">' . $recordRow[$this->name1] . '</a>';
                } else {
                    if ($fileExtension == 'pdf' or
                            $fileExtension == 'xls' or
                            $fileExtension == 'xlsx' or
                            $fileExtension == 'doc' or
                            $fileExtension == 'pptx' or
                            $fileExtension == 'docx') {
                        $name1 = '<a href="javascript:;" class="filePreviewBtn" data-fn-param="this, \\\'' . $recordRow['id'] . '\\\', \\\'' . $fileExtension . '\\\', \\\'' . $recordRow[$this->name1] . '\\\', \\\'' . $recordRow['physicalpath'] . '\\\'">' . $recordRow[$this->name1] . '</a>';
                    }
                    $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                }
            } else {
                $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
            }

            $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
    ?>
        <li class="dv-explorer-row list-inline" title="<?php echo $recordRow[$this->name1]; ?>" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);" ondblclick="showFileViewer(this);">	
            <div class="selected-row-link">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <img class="img-fluid img-preview" src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                    <span class="title-f"><?php echo $name1; ?></span> <br>
                    <span class="title-path"><?php echo isset($recordRow[$this->name4]) ? $recordRow[$this->name4] : ''; ?></span> 
                </div>    
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <span><?php echo isset($recordRow[$this->name2]) ? $recordRow[$this->name2] : ''; ?></span>
                </div>    
                <div class="col-md-3 d-sm-none col-xs-12">
                    <span><?php echo isset($recordRow[$this->name3]) ? $recordRow[$this->name3] : ''; ?></span>
                </div>    
                <div class="clearfix w-100"></div>
            </div>
        </li>
    <?php
                }
            }
        }
    }
    ?>
</ul>

<div id="folderAction-<?php echo $this->dataViewId; ?>"></div>

<script type="text/javascript">
    var globalFolderId = $('#objectdatagrid-<?php echo $this->dataViewId; ?>').attr('folder-id'), $parentObjectValueList;

    /* global folderAction,PNotify,Core */
    $(function(){
      if(!$("link[href='middleware/assets/css/gridlayout/explorer3.css']").length){
        $("head").prepend(
                '<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/explorer3.css"/>');
      }

      $parentObjectValueList=$('#object-value-list-<?php echo $this->dataViewId; ?>');

      $parentObjectValueList.find(".filePreviewBtn").click(function(){
        window["dataViewFileViewer"]($(this).data('fn-param'));
        return false;
      });

      Core.initFancybox($parentObjectValueList);
      $parentObjectValueList.addClass('dv-explorer-3-parent');
      $parentObjectValueList.find('.explorer-table > .explorer-table-row > .explorer-table-cell').css({height: ($('.page-sidebar-menu').
                attr('data-height') - 120)});

      $.ajax({
        type: 'post',
        url: 'mdcontentui/renderFolderAction/',
        dataType: 'html',
        success: function(response){
          $("#folderAction-<?php echo $this->dataViewId; ?>").html(response);
        }
      }).complete(function(){
        Core.unblockUI();
      });

      $parentObjectValueList.find('#moveToFolderBtn').off().on('click', function(){
        folderActionValidate(function(){
          folderAction.init(1, true);
        });
      });

      $parentObjectValueList.find('#copyToFolderBtn').off().on('click', function(){
        folderActionValidate(function(){
          folderAction.init(2, true);
        });
      });
    });

    function folderActionValidate(callback){
      if($parentObjectValueList.find('.dv-explorer3').find('li.dv-explorer-row.selected-row').length > 0){
        if(typeof callback === "function"){
          callback();
        }
      } else {
        PNotify.removeAll();
        new PNotify({
          title: 'Warning',
          text: 'Та мөр сонгоно у',
          type: 'warning',
          sticker: false
        });
      }
    }
</script>