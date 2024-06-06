<link href="<?php echo autoVersion('middleware/assets/css/gridlayout/explorer11.css'); ?>" rel="stylesheet"/>

<ul class="dv-explorer11 dv-explorer" id="main-item-container" style="display: none;">
    <?php
    if ($this->isBack) {
        
        $parentId = null;
        
        if ($this->isTreeGridData) {
            $parentId = $this->parentId;
            $backOnClick = 'explorerBackList_'.$this->dataViewId.'(\''.$this->parentId.'\', true);';
        } else {
            $backOnClick = 'explorerBackList_'.$this->dataViewId.'(\''.$this->folderId.'\');';
        }
    ?>
    <li class="dv-explorer-row dv-explorer-row-back" data-parentid="<?php echo $parentId; ?>">	
        <div class="selected-row-link mt-element-ribbon" title="<?php echo $this->lang->line('back_btn'); ?>" onclick="<?php echo $backOnClick; ?>">
            <div class="dv-img-container">
                <div class="dv-img-container-sub">
                    <div class="dv-img-container-img">
                        <img class="dv-directory-img" src="assets/core/global/img/meta/folder_back_transparent.png"/>
                    </div>
                </div>
            </div>
            <div class="second-title">
                <h4>
                    <?php echo $this->lang->line('back_btn'); ?>
                </h4>
            </div>
        </div>	
    </li>
    <?php
    }
    if ($this->folderList) {
        foreach ($this->folderList as $folderRow) {
    ?>
    <li class="dv-explorer-row">	
        <div class="selected-row-link mt-element-ribbon" title="<?php echo $folderRow['META_VALUE_NAME']; ?>" onclick="selectDataViewByCategory_<?php echo $this->dataViewId; ?>('<?php echo $folderRow['META_VALUE_ID']; ?>')">
            <div class="dv-img-container">
                <div class="dv-img-container-sub">
                    <div class="dv-img-container-img">
                        <img class="dv-directory-img" src="assets/core/global/img/meta/folder_transparent.png"/>
                    </div>
                </div>
            </div>
            <div class="second-title">
                <h4>
                    <?php echo $folderRow['META_VALUE_NAME']; ?>
                </h4>
            </div>
        </div>	
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
                $isPhotoField = true;
            }
            
            if ($onClickCustom && isset($firstRow[$onClickField])) {
                $onClick = 'echo $recordRow[$onClickField];';
            }
            
            foreach ($this->recordList as $recordRow) {
                
                $loopPhotoField = $photoField;
                $loopOnClick = $onClick;
                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
                $defaultImage = 'assets/custom/img/appmenu.png';
                
                if (issetParam($recordRow['childrecordcount']) && $recordRow['childrecordcount']) {
                    
                    $loopOnClick = 'echo "selectDataViewByCategory_'.$this->dataViewId.'(\''.$recordRow['id'].'\', \''.$this->folderId.'\');";';
                    $defaultImage = 'assets/core/global/img/meta/folder_transparent.png';
                    
                    if (isset($isPhotoField) && $recordRow[$this->photoField]) {
                        $loopPhotoField = 'echo $recordRow[$this->photoField];';
                    } else {
                        $loopPhotoField = 'echo "assets/core/global/img/meta/folder_transparent.png";';
                    }
                }
    ?>
    <li class="dv-explorer-row">	
        <div class="selected-row-link mt-element-ribbon" title="<?php echo strip_tags($recordRow[$this->name1]); ?>" data-row-data="<?php echo $rowJson; ?>" onclick="<?php eval($loopOnClick); ?>">
            <?php eval($name3); ?>
            <div class="dv-img-container">
                <div class="dv-img-container-sub">
                    <div class="dv-img-container-img">
                        <img class="dv-directory-img" src="<?php eval($loopPhotoField); ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>
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
            
            if (array_key_exists('rowcolor', $firstRow)) {
                $rowcolor = 'echo " style=\"background-color: ".$recordRow[\'rowcolor\']."\"";';
            }
            
            if ($this->photoField && array_key_exists($this->photoField, $firstRow)) {
                $photoField = 'echo $recordRow[$this->photoField];';
                $isPhotoField = true;
            }
            
            if ($onClickCustom && isset($firstRow[$onClickField])) {
                $onClick = 'echo $recordRow[$onClickField];';
            }

            foreach ($this->recordList as $groupName => $recordRow) {
                $rows = $recordRow['rows'];
    ?>
    <li class="dv-explorer-row-split">
        <i class="icon-circle-down2"></i> <?php echo $groupName; ?>
    </li>
    <?php
    foreach ($rows as $recordRow) {
        
        $loopPhotoField = $photoField;
        $loopOnClick = $onClick;
        $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
        $defaultImage = 'assets/custom/img/appmenu.png';

        if (issetParam($recordRow['childrecordcount']) && $recordRow['childrecordcount']) {

            $loopOnClick = 'echo "selectDataViewByCategory_'.$this->dataViewId.'(\''.$recordRow['id'].'\', \''.$this->folderId.'\');";';
            $defaultImage = 'assets/core/global/img/meta/folder_transparent.png';

            if (isset($isPhotoField) && $recordRow[$this->photoField]) {
                $loopPhotoField = 'echo $recordRow[$this->photoField];';
            } else {
                $loopPhotoField = 'echo "assets/core/global/img/meta/folder_transparent.png";';
            }
        }
    ?>
    <li class="dv-explorer-row">	
        <div class="selected-row-link mt-element-ribbon" title="<?php echo strip_tags($recordRow[$this->name1]); ?>" data-row-data="<?php echo $rowJson; ?>" onclick="<?php eval($loopOnClick); ?>">
            <?php eval($name3); ?>
            <div class="dv-img-container">
                <div class="dv-img-container-sub">
                    <div class="dv-img-container-img">
                        <img class="dv-directory-img" src="<?php eval($loopPhotoField); ?>" data-default-image="<?php echo $defaultImage; ?>" onerror="onDataViewImgError(this);"/>
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
$(function() {
    $('#objectdatagrid-<?php echo $this->dataViewId; ?> .dv-explorer11').show();
});
</script>