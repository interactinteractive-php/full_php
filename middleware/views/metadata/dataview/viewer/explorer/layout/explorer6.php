<ul class="dv-explorer6 dv-explorer" id="main-item-container" data-ref-structure-id="<?php echo $this->refStructureId; ?>">
    <?php
    if ($this->isBack) {
    ?>
        <li class="dv-explorer-row list-inline explorer-back" onclick="explorerBackList_<?php echo $this->dataViewId; ?>('<?php echo $this->folderId; ?>');">	
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
            
            $firstRow = $this->recordList[0];
            $name5 = $name6 = $name7 = ' echo "";';

            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['name5']) 
                    && array_key_exists($this->row['dataViewLayoutTypes']['explorer']['fields']['name5'], $firstRow)) {
                $name5 = 'echo $recordRow[\''.$this->row['dataViewLayoutTypes']['explorer']['fields']['name5'].'\'];';
            }
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['name6']) 
                    && array_key_exists($this->row['dataViewLayoutTypes']['explorer']['fields']['name6'], $firstRow)) {
                $name6 = 'echo $recordRow[\''.$this->row['dataViewLayoutTypes']['explorer']['fields']['name6'].'\'];';
            }
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['name7']) 
                    && array_key_exists($this->row['dataViewLayoutTypes']['explorer']['fields']['name7'], $firstRow)) {
                $name7 = 'echo $recordRow[\''.$this->row['dataViewLayoutTypes']['explorer']['fields']['name7'].'\'];';
            }
            
            foreach ($this->recordList as $recordRow) {
                
                $name1 = isset($recordRow[$this->name1]) ? $recordRow[$this->name1] : '';
                
                if (isset($recordRow['fileextension'])) {
                    $fileExtension = strtolower($recordRow['fileextension']);
                    
                    if ($fileExtension == 'png' || $fileExtension == 'gif' ||
                            $fileExtension == 'jpeg' ||
                            $fileExtension == 'pjpeg' ||
                            $fileExtension == 'jpg' ||
                            $fileExtension == 'x-png' ||
                            $fileExtension == 'bmp') {
                        
                        $photoField = isset($recordRow['physicalpath']) ? $recordRow['physicalpath'] : '';
                        $name1 = '<a href="' . $recordRow['physicalpath'] . '" class="fancybox-button" data-rel="fancybox-button" title="' . $recordRow[$this->name1] . '">' . $recordRow[$this->name1] . '</a>';
                        
                    } else {
                        if ($fileExtension == 'pdf' || $fileExtension == 'xls' || $fileExtension == 'xlsx' || $fileExtension == 'doc' || $fileExtension == 'docx') {
                            $name1 = '<a href="javascript:;" class="filePreviewBtn" data-fn-param="this, \\\'' . $recordRow['id'] . '\\\', \\\'' . $fileExtension . '\\\', \\\'' . $recordRow[$this->name1] . '\\\', \\\'' . $recordRow['physicalpath'] . '\\\'">' . $recordRow[$this->name1] . '</a>';
                        }
                        $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                    }
                } else {
                    $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
                }

                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
    ?>
        <li class="dv-explorer-row list-inline" title="<?php echo $recordRow[$this->name1]; ?>" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);">	
            <div class="selected-row-link" data-row-data="<?php echo $rowJson; ?>">
                <div class="col-md-5 col-sm-12 col-xs-12">
                    <div class="f-section">
                        <div class="checker"><span><input type="checkbox"></span></div>
                    </div>
                    <div class="s-section">
                        <?php
                        if ($photoField != '') {
                            echo '<img class="img-fluid img-preview" src="' . $photoField . '"/>';
                        }
                        ?>
                        <span class="title-f"><?php echo $name1; ?></span><br />
                        <span class="title-path"><?php echo isset($recordRow[$this->name2]) ? $recordRow[$this->name2] : ''; ?></span><br />
                        <span class="title-status"><?php echo isset($recordRow[$this->name3]) ? $recordRow[$this->name3] : ''; ?></span>
                    </div>                    
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <span class="title-more"><?php echo isset($recordRow[$this->name4]) ? $recordRow[$this->name4] : ''; ?></span> 
                </div>    
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <span class="title-more"><?php eval($name5); ?></span> 
                </div>
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <span class="title-more"><?php eval($name6); ?></span> 
                </div>
                <div class="col-md-1 col-sm-12 col-xs-12">
                    <span class="title-more"><?php eval($name7); ?></span> 
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
    
    $firstRow = $rows[0];
    $name5 = $name6 = $name7 = ' echo "";';

    if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['name5']) 
            && array_key_exists($this->row['dataViewLayoutTypes']['explorer']['fields']['name5'], $firstRow)) {
        $name5 = 'echo $recordRow[\''.$this->row['dataViewLayoutTypes']['explorer']['fields']['name5'].'\'];';
    }
    if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['name6']) 
            && array_key_exists($this->row['dataViewLayoutTypes']['explorer']['fields']['name6'], $firstRow)) {
        $name6 = 'echo $recordRow[\''.$this->row['dataViewLayoutTypes']['explorer']['fields']['name6'].'\'];';
    }
    if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['name7']) 
            && array_key_exists($this->row['dataViewLayoutTypes']['explorer']['fields']['name7'], $firstRow)) {
        $name7 = 'echo $recordRow[\''.$this->row['dataViewLayoutTypes']['explorer']['fields']['name7'].'\'];';
    }
            
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
        
        <li class="dv-explorer-row list-inline" title="<?php echo $recordRow[$this->name1]; ?>" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);">	
            <div class="selected-row-link" data-row-data="<?php echo $rowJson; ?>">
                <div class="col-md-12 title-f" style="padding-left: 28px;"><?php echo $name1; ?></div>
                <div class="col-md-12 col-sm-12 col-xs-12 pl0">
                    <div class="f-section pt5" style="width: 30px">
                        <div class="checker"><span><input type="checkbox"></span></div>
                    </div>
                    <div class="s-section">
                        <?php
                        if ($photoField != '') {
                            echo '<img class="img-fluid img-preview" src="' . $photoField . '"/>';
                        }
                        ?>
                        <span class="title-path"><?php echo isset($recordRow[$this->name2]) ? $recordRow[$this->name2] : ''; ?></span>
                        <span class="title-path"><?php eval($name5); ?></span>
                        <span class="title-path"><?php eval($name6); ?></span>
                        <span class="title-path"><?php eval($name7); ?></span>
                        <br />
                        <span class="title-status"><?php echo isset($recordRow[$this->name3]) ? $recordRow[$this->name3] : ''; ?></span>
                        <span class="title-more ml10"><?php echo isset($recordRow[$this->name4]) ? $recordRow[$this->name4] : ''; ?></span> 
                    </div>                    
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

<script type="text/javascript">
$(function(){
    if (!$("link[href='middleware/assets/css/gridlayout/explorer6.css']").length) {
        $("head").prepend('<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/explorer6.css"/>');
    }

    var $parentObjectValueList = $('#object-value-list-<?php echo $this->dataViewId; ?>');
    $parentObjectValueList.addClass('dv-explorer-6-parent');
    $parentObjectValueList.find('.explorer-table > .explorer-table-row > .explorer-table-cell').css({height: ($('.page-sidebar-menu').attr('data-height') - 120)});
});
</script>