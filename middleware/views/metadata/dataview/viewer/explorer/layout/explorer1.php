<ul class="grid cs-style-2 list-view0 dv-explorer" id="main-item-container">
    <?php
    if ($this->isBack) {
    ?>
    <li class="back">
        <figure class="back-directory">
            <a class="folder-link" href="javascript:;" onclick="explorerBackList_<?php echo $this->dataViewId; ?>('<?php echo $this->folderId; ?>');">
                <div class="img-precontainer">
                    <div class="img-container directory"><span></span>
                        <img class="directory-img" src="assets/core/global/img/meta/folder_back.png"/>
                    </div>
                </div>
                <div class="box no-effect">
                    <h4><?php echo $this->lang->line('back_btn'); ?></h4>
                </div>
            </a>
        </figure>
    </li>
    <?php
    }
    if ($this->folderList) {
        foreach ($this->folderList as $folderRow) {
    ?>
    <li class="dir" id="<?php echo $folderRow['META_VALUE_ID']; ?>">	
        <figure class="directory">
            <a href="javascript:;" onclick="selectDataViewByCategory_<?php echo $this->dataViewId; ?>('<?php echo $folderRow['META_VALUE_ID']; ?>')" class="folder-link" title="<?php echo $folderRow['META_VALUE_NAME']; ?>">
                <div class="img-precontainer">
                    <div class="img-container directory"><span></span>
                        <img class="directory-img" src="assets/core/global/img/meta/folder.png"/>
                    </div>
                </div>
                <div class="box">
                    <h4 class="ellipsis"><?php echo $folderRow['META_VALUE_NAME']; ?></h4>
                </div>
            </a>	
        </figure>
    </li>
    <?php
        }
    }
    if ($this->recordList) {
        
        if (isset($this->recordList['status'])) {
            echo html_tag('div', array('class' => 'alert alert-danger'), 'DV error message: '.$this->recordList['message']); exit();
        }
        
        foreach ($this->recordList as $recordRow) {
            $photoField = isset($recordRow[$this->photoField]) ? $recordRow[$this->photoField] : '';
            $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
    ?>
    <li class="meta dv-explorer-row">	
        <figure class="directory">
            <a href="javascript:;" class="selected-row-link folder-link" title="<?php echo strip_tags($recordRow[$this->name1]); ?>" data-row-data="<?php echo $rowJson; ?>" onclick="clickItem_<?php echo $this->dataViewId; ?>(this);" ondblclick="showFileViewer(this);">
                <div class="img-precontainer">
                    <div class="img-container directory"><span></span>
                        <img class="directory-img" src="<?php echo $photoField; ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                    </div>
                </div>
                <div class="box">
                    <h4 class="ellipsis"><?php echo $recordRow[$this->name1]; ?></h4>
                </div>
            </a>	
        </figure>
    </li>
    <?php
        }
    }
    ?>
</ul>