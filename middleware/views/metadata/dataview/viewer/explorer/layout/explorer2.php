<ul class="dv-explorer2 dv-explorer" id="main-item-container">
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
                    <img class="dv-directory-img" src="assets/core/global/img/meta/folder_transparent.png"/>
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
            echo html_tag('div', array('class' => 'alert alert-danger'), 'DV error message: '.$this->recordList['message']); exit();
        }
        
        if ($this->isGrouped == false) {
            
            $onClickCustom = false;
            $onClick = 'echo "clickItem_'.$this->dataViewId.'(this);";';
            
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction']) 
                && $this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction'] != '') {
                
                $onClickCustom = true;
                $onClickField = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction']);
            } 
            
            $firstRow = $this->recordList[0];
            $name1 = $name2 = $name3 = $photoField = $rowcolor = ' echo "";';
            
            if (isset($firstRow[$this->name1])) {
                if ($this->name1 == 'wfmstatusname' && isset($firstRow['wfmstatusid'])) {
                    $name1 = 'echo \'<span class="label label-sm" style="background-color: \'.$recordRow[\'wfmstatuscolor\'].\'" onclick="dataViewWfmStatusFlowViewer(this, \\\'\'.$recordRow[\'id\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusid\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusname\'].\'\\\', \\\'\'.$this->dataViewId.\'\\\', \\\'\'.$this->refStructureId.\'\\\', \\\'\'.$recordRow[\'wfmstatuscolor\'].\'\\\');"><i class="far fa-cogs"></i> \'.$recordRow[\'wfmstatusname\'].\'</span>\';';
                } else {
                    $name1 = '$str = $recordRow[$this->name1]; $strTemp = strip_tags($str); echo str_replace($strTemp, Lang::line($strTemp), $str);';
                }
            }
            
            if (isset($firstRow[$this->name2])) {
                if ($this->name2 == 'wfmstatusname' && isset($firstRow['wfmstatusid'])) {
                    $name2 = 'echo \'<div class="first-title"><span class="label label-sm" style="background-color: \'.$recordRow[\'wfmstatuscolor\'].\'" onclick="dataViewWfmStatusFlowViewer(this, \\\'\'.$recordRow[\'id\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusid\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusname\'].\'\\\', \\\'\'.$this->dataViewId.\'\\\', \\\'\'.$this->refStructureId.\'\\\', \\\'\'.$recordRow[\'wfmstatuscolor\'].\'\\\');"><i class="far fa-cogs"></i> \'.$recordRow[\'wfmstatusname\'].\'</span></div>\';';
                    $name3 = 'echo \'<div class="row" style="height: 1px; width: 1px; float: right; margin-right: 0;"><div class="col-md-12" style="top: 5px; right: 15px;"><div class="btn-group pull-right" >
                                    <button type="button" class="btn btn-circle btn-default btn-sm dropdown-toggle" data-toggle="dropdown" data-close-others="true" onclick="changeWfmStatusByRow_\'.$this->dataViewId.\'(this);">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu"></ul>
                            </div></div></div>\';';
                } else {
                    $name2 = 'echo \'<div class="first-title">\'.$recordRow[$this->name2].\'</div>\';';
                }
            }
            
            if (array_key_exists('rowcolor', $firstRow)) {
                $rowcolor = 'echo " style=\"background-color: ".$recordRow[\'rowcolor\']."\"";';
            }
            
            if ($this->photoField && array_key_exists($this->photoField, $firstRow)) {
                $photoField = 'echo $recordRow[$this->photoField];';
            }
            
            if ($onClickCustom && isset($firstRow[$onClickField]) && strpos($firstRow[$this->name1], 'checkMetaDataTypeFunction(') === false) {
                
                $onClick = 'echo $recordRow[$onClickField];';
            }
            
            foreach ($this->recordList as $recordRow) {

                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
    ?>
    <li class="dv-explorer-row">	
        <div class="selected-row-link mt-element-ribbon" title="<?php echo strip_tags($recordRow[$this->name1]); ?>" data-row-data="<?php echo $rowJson; ?>" onclick="<?php eval($onClick); ?>">
            <?php eval($name3); ?>
            <div class="dv-img-container">
                <?php
                if (isset($recordRow['rowcolor'])) {
                ?>
                <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-info" style="background-color: <?php echo $recordRow['rowcolor']; ?>">
                    <div class="ribbon-sub ribbon-clip ribbon-right"><i class="fa fa-file-text"></i></div>
                </div>
                <?php
                }
                ?>
                <div class="dv-img-container-sub">
                        <?php if (issetParam($recordRow[$this->iconField])) { ?>
                            <div class="dv-img-container-img" style="border: none;">
                                <i style="font-size: 48px;color:var(--root-color1)" class="<?php echo $recordRow[$this->iconField]; ?>"></i>
                            </div>
                        <?php } else { ?>
                            <div class="dv-img-container-img">
                                <img class="dv-directory-img" src="<?php eval($photoField); ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                            </div>                        
                        <?php } ?>
                </div>
            </div>
            <div class="second-title">
                <h4>
                    <?php eval($name1); ?> 
                </h4>
            </div>
        </div>	
    </li>
    <?php
            }
        } else {
            
            $onClickCustom = false;
            $onClick = 'echo "clickItem_'.$this->dataViewId.'(this);";';
            
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction']) 
                && $this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction'] != '') {
                
                $onClickCustom = true;
                $onClickField = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['clickRowFunction']);
            } 
            
            $keys   =   array_keys($this->recordList);
            $firstRow = $this->recordList[$keys[0]]['row'];
            $name1 = $name2 = $name3 = $photoField = $rowcolor = ' echo "";';
            
            if (isset($firstRow[$this->name1])) {
                if ($this->name1 == 'wfmstatusname' && isset($firstRow['wfmstatusid'])) {
                    $name1 = 'echo \'<span class="label label-sm" style="background-color: \'.$recordRow[\'wfmstatuscolor\'].\'" onclick="dataViewWfmStatusFlowViewer(this, \\\'\'.$recordRow[\'id\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusid\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusname\'].\'\\\', \\\'\'.$this->dataViewId.\'\\\', \\\'\'.$this->refStructureId.\'\\\', \\\'\'.$recordRow[\'wfmstatuscolor\'].\'\\\');"><i class="far fa-cogs"></i> \'.$recordRow[\'wfmstatusname\'].\'</span>\';';
                } else {
                    $name1 = '$str = $recordRow[$this->name1]; $strTemp = strip_tags($str); echo str_replace($strTemp, Lang::line($strTemp), $str);';
                }
            }
            
            if (isset($firstRow[$this->name2])) {
                if ($this->name2 == 'wfmstatusname' && isset($firstRow['wfmstatusid'])) {
                    $name2 = 'echo \'<div class="first-title"><span class="label label-sm" style="background-color: \'.$recordRow[\'wfmstatuscolor\'].\'" onclick="dataViewWfmStatusFlowViewer(this, \\\'\'.$recordRow[\'id\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusid\'].\'\\\', \\\'\'.$recordRow[\'wfmstatusname\'].\'\\\', \\\'\'.$this->dataViewId.\'\\\', \\\'\'.$this->refStructureId.\'\\\', \\\'\'.$recordRow[\'wfmstatuscolor\'].\'\\\');"><i class="far fa-cogs"></i> \'.$recordRow[\'wfmstatusname\'].\'</span></div>\';';
                    $name3 = 'echo \'<div class="row" style="height: 1px; width: 1px; float: right; margin-right: 0;"><div class="col-md-12" style="top: 5px; right: 15px;"><div class="btn-group pull-right" >
                                    <button type="button" class="btn btn-circle btn-default btn-sm dropdown-toggle" data-toggle="dropdown" data-close-others="true" onclick="changeWfmStatusByRow_\'.$this->dataViewId.\'(this);">
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu"></ul>
                            </div></div></div>\';';
                } else {
                    $name2 = 'echo \'<div class="first-title">\'.$recordRow[$this->name2].\'</div>\';';
                }
            }
            
            if (isset($firstRow['rowcolor'])) {
                $rowcolor = 'echo " style=\"background-color: ".$recordRow[\'rowcolor\']."\"";';
            }
            
            if (isset($firstRow[$this->photoField])) {
                $photoField = 'echo $recordRow[$this->photoField];';
            }
            
            if ($onClickCustom && isset($firstRow[$onClickField]) && strpos($firstRow[$this->name1], 'checkMetaDataTypeFunction(') === false) {
                $onClick = 'echo $recordRow[$onClickField];';
            }
            
            foreach ($this->recordList as $recordRow) {
                $groupName = $recordRow['row'][$this->groupName];
                $rows = $recordRow['rows'];
    ?>
    <li class="dv-explorer-row-split">
        <?php echo Lang::line($groupName); ?>
    </li>
    <?php
    foreach ($rows as $recordRow) {

        $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
    ?>
    <li class="dv-explorer-row">	
        <div class="selected-row-link mt-element-ribbon" title="<?php echo strip_tags($recordRow[$this->name1]); ?>" data-row-data="<?php echo $rowJson; ?>" onclick="<?php eval($onClick); ?>">
            <?php eval($name3); ?>
            <div class="dv-img-container">
                <?php
                if (isset($recordRow['rowcolor'])) {
                ?>
                <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-info" style="background-color: <?php echo $recordRow['rowcolor']; ?>">
                    <div class="ribbon-sub ribbon-clip ribbon-right"><i class="fa fa-file-text"></i></div>
                </div>
                <?php
                }
                ?>
                <div class="dv-img-container-sub">
                    <div class="dv-img-container-img">
                        <?php if (issetParam($recordRow[$this->iconField])) { ?>
                            <i style="font-size: 18px;color:var(--root-color1)" class="<?php echo $recordRow[$this->iconField]; ?>"></i>
                        <?php } else { ?>
                            <img class="dv-directory-img" src="<?php eval($photoField); ?>" data-default-image="<?php echo $this->defaultImage; ?>" onerror="onDataViewImgError(this);"/>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="second-title">
                <h4>
                    <?php eval($name1); ?> 
                </h4>
            </div>
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
    if (!$("link[href='middleware/assets/css/gridlayout/explorer2.css']").length) {
        $("head").prepend('<link rel="stylesheet" type="text/css" href="middleware/assets/css/gridlayout/explorer2.css"/>');
    }
});
</script>