<?php
    if (isset($this->hiddenFields) && $this->hiddenFields == '0') {
        
        $dataViewHeaderData = !empty($this->dataViewHeaderData['data']) ? $this->dataViewHeaderData['data'] : $this->dataViewHeaderData;
        $isHidden = (isset($this->callerType) && $this->callerType == 'package' && isset($this->packageRenderType) && $this->packageRenderType == 'leftside') ? true : false;
        
        if ($isHidden) {
            echo '<div class="d-none">';
        }
        
        if ($this->dataViewMandatoryHeaderData) {
            echo $this->defaultCriteriaMandatory;
        }
        
        $lowerDataViewCriteriaType = empty($this->dataViewCriteriaType) ? 'top' : $this->dataViewCriteriaType;
        
        if ($this->isTree || $dataViewHeaderData) {
            if (($lowerDataViewCriteriaType == 'left' || $lowerDataViewCriteriaType == 'hidden') && $this->isCheckDataViewHeaderData) {
?>
                <div class="left-sidebar datagrid-filter-panel pl0 pr0 remove-type-<?php echo $this->metaDataId; ?> <?php echo $lowerDataViewCriteriaType == 'hidden' ? 'd-none' : ''; ?>" data-status="closed">
                    <div class="left-stoggler sidebar-right">
                        <span style="display: block;" class="fa fa-chevron-right">&nbsp;</span> 
                        <span style="display: none;" class="fa fa-chevron-left">&nbsp;</span>
                    </div>
                    <div class="left-sidebar-content">
                        <div class="col-md-12 mt10">
                            <div class="tabbable-line">
                                <ul class="nav nav-tabs nav-tabs-btn px-2">
                                    <?php if ($this->isTree) { ?>
                                        <li class="in">
                                            <a href="#meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>" class="pt0 nav-tabs-btn-filter <?php echo (!$dataViewHeaderData) ? 'active' : '' ?>" data-toggle="tab"><?php echo $this->lang->line('filter'); ?></a>
                                        </li>      
                                    <?php } ?>
                                    <?php if ($dataViewHeaderData) { ?>
                                        <li class="in">
                                            <a href="#meta-search-view-tab-2-<?php echo $this->metaDataId; ?>" class="pt0 nav-tabs-btn-search active" data-toggle="tab"><?php echo $this->lang->line('search'); ?></a>
                                        </li>      
                                    <?php } ?>
                                </ul>
                                <div class="tab-content">
                                    <?php if ($this->isTree) { ?>
                                        <div class="tab-pane in <?php echo (!$dataViewHeaderData) ? 'active' : '' ?> height-dynamic" id="meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>">
                                            <?php
                                            if (count($this->treeCategoryList) === 1) {
                                                echo "<span class='hide'>";
                                                echo Form::select(
                                                    array(
                                                        'name' => 'treeCategory',
                                                        'id' => 'treeCategory',
                                                        'op_value' => 'ID',
                                                        'op_text' => 'NAME',
                                                        'glue' => '-',
                                                        'data' => $this->treeCategoryList,
                                                        'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                        'text' => 'notext'
                                                    )
                                                );
                                                echo "</span>";
                                            } else {
                                                echo Form::select(
                                                    array(
                                                        'name' => 'treeCategory',
                                                        'id' => 'treeCategory',
                                                        'class' => 'form-control form-control-sm select2 mb10',
                                                        'op_value' => 'ID',
                                                        'op_text' => 'NAME',
                                                        'glue' => '-',
                                                        'data' => $this->treeCategoryList,
                                                        'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                        'text' => 'notext'
                                                    )
                                                );
                                            }
                                            ?>
                                            <div id="treeContainer">
                                                <div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo"></div>
                                            </div>
                                            <form role="form" id="tree-click-form" method="post">
                                                <input type="hidden" id="tree-click-hidden-input" />
                                            </form>
                                        </div>     
                                    <?php } if ($dataViewHeaderData) { ?>
                                        <div class="tab-pane in active " id="meta-search-view-tab-2-<?php echo $this->metaDataId; ?>">
                                            <?php echo $this->defaultCriteria; ?> 
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            <?php
            }
            if ($lowerDataViewCriteriaType == 'top' && $this->isCheckDataViewHeaderData) {
                $cardHide = '';
                if (!$this->isTree && strpos($this->defaultCriteria, 'data-path="') === false) {
                    $cardHide = ' hide';
                }
                ?>
                <div class="col-md-12 object-height-row2-minus-<?php echo $this->metaDataId ?>">
                    <div class="card light shadow<?php echo $cardHide; ?> p-0 mb0">
                        <div class="card-header card-header-no-padding header-elements-inline" style="min-height: 0px;">
                            <div class="caption p-0 card-collapse dataview expand"><i class="fa fa-search"></i> <?php echo $this->lang->line('META_00193'); ?></div>
                            <div class="tools p-0"> 
                                <a href="javascript:;" class="tool-collapse expand"></a>
                            </div>
                        </div>
                        <div class="card-body form xs-form display-none top-sidebar-content mb10 pl0 pr0" style="display: none;">
                            <div class="tabbable-line">
                                <?php
                                $tabContentClass = '';
                                if ($this->isTree && $dataViewHeaderData) {
                                ?>
                                <ul class="nav nav-tabs nav-tabs-btn px-2">     
                                    <?php if ($this->isTree) { ?>
                                        <li class="in">
                                            <a href="#meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>" class="pt0 nav-tabs-btn-filter <?php echo (!$dataViewHeaderData) ? 'active' : '' ?>" data-toggle="tab" style="font-size: 14px !important; font-weight: 400;"><?php echo $this->lang->line('filter'); ?></a>
                                        </li>      
                                    <?php } if ($dataViewHeaderData) { ?>
                                        <li class="in">
                                            <a href="#meta-search-view-tab-2-<?php echo $this->metaDataId; ?>" class="pt0 nav-tabs-btn-search active" data-toggle="tab" style="font-size: 14px !important; font-weight: 400;"><?php echo $this->lang->line('search'); ?></a>
                                        </li>      
                                    <?php } ?>
                                </ul>
                                <?php
                                    $tabContentClass = 'tab-content';
                                }
                                ?>
                                <div class="<?php echo $tabContentClass; ?>">
                                    <?php if ($this->isTree) { ?>
                                        <div class="tab-pane in <?php echo (!$dataViewHeaderData) ? 'active' : ''; ?>" id="meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>">
                                            <?php
                                            if (count($this->treeCategoryList) === 1) {
                                                echo "<span class='hide'>";
                                                echo Form::select(
                                                    array(
                                                        'name' => 'treeCategory',
                                                        'id' => 'treeCategory',
                                                        'op_value' => 'ID',
                                                        'op_text' => 'NAME',
                                                        'glue' => '-',
                                                        'data' => $this->treeCategoryList,
                                                        'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                        'text' => 'notext'
                                                    )
                                                );
                                                echo "</span>";
                                            } else {
                                                echo Form::select(
                                                    array(
                                                        'name' => 'treeCategory',
                                                        'id' => 'treeCategory',
                                                        'class' => 'form-control form-control-sm select2 mb10',
                                                        'op_value' => 'ID',
                                                        'op_text' => 'NAME',
                                                        'glue' => '-',
                                                        'data' => $this->treeCategoryList,
                                                        'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                        'text' => 'notext'
                                                    )
                                                );
                                            }
                                            ?>
                                            <div id="treeContainer" style="max-height: 200px; overflow-y: auto">
                                                <div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo"></div>
                                            </div>
                                            <form role="form" id="tree-click-form" method="post">
                                                <input type="hidden" id="tree-click-hidden-input" />
                                            </form>
                                        </div>     
                                    <?php 
                                    } 
                                    if ($dataViewHeaderData) { 
                                    ?>
                                    <div class="tab-pane in active" id="meta-search-view-tab-2-<?php echo $this->metaDataId; ?>">
                                        <?php echo $this->defaultCriteria; ?> 
                                    </div>
                                    <?php 
                                    } 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            if ($lowerDataViewCriteriaType == 'left static' && ($this->isTree || ($dataViewHeaderData && issetParam($this->isEmptyCriteria)))) { ?>
                <div class="col-md-3 left-sidebar-content <?php echo ($lowerDataViewCriteriaType == 'left web' || $lowerDataViewCriteriaType == 'left web civil') ? 'leftweb-criteria' : '' ?> pl0">
                    <div class="tabbable-line">
                        <ul class="nav nav-tabs nav-tabs-btn px-2">     
                            <?php if ($this->isTree) { ?>
                                <li class="in">
                                    <a href="#meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>" class="pt0 nav-tabs-btn-filter <?php echo (!$dataViewHeaderData || $lowerDataViewCriteriaType == 'left static' || $lowerDataViewCriteriaType == 'left web') ? 'active' : '' ?>" data-toggle="tab"><?php echo $this->lang->line('filter'); ?></a>
                                </li>      
                            <?php } if ($dataViewHeaderData && issetParam($this->isEmptyCriteria)) { ?>
                                <li class="in">
                                    <a href="#meta-search-view-tab-2-<?php echo $this->metaDataId; ?>" class="pt0 <?php echo (($lowerDataViewCriteriaType != 'left static' && $lowerDataViewCriteriaType != 'left web') || !$this->isTree) ? 'active' : '' ?> nav-tabs-btn-search" data-toggle="tab"><?php echo $this->lang->line('search'); ?></a>
                                </li>      
                            <?php } ?>
                        </ul>
                        <div class="tab-content">
                            <?php if ($this->isTree) { ?>
                                <div class="tab-pane in <?php echo ($lowerDataViewCriteriaType == 'left web' || $lowerDataViewCriteriaType == 'left web civil') ? 'active leftweb-tree-criteria' : 'height-dynamic' ?> <?php echo (!$dataViewHeaderData || $lowerDataViewCriteriaType == 'left static') ? 'active' : '' ?> " id="meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>">
                                    <?php
                                    if (count($this->treeCategoryList) === 1) {
                                        echo "<span class='hide'>";
                                        echo Form::select(
                                            array(
                                                'name' => 'treeCategory',
                                                'id' => 'treeCategory',
                                                'op_value' => 'ID',
                                                'op_text' => 'NAME',
                                                'glue' => '-',
                                                'data' => $this->treeCategoryList,
                                                'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                'text' => 'notext'
                                            )
                                        );
                                        echo "</span>";
                                    } else {
                                        echo Form::select(
                                            array(
                                                'name' => 'treeCategory',
                                                'id' => 'treeCategory',
                                                'class' => 'form-control form-control-sm select2 mb10',
                                                'op_value' => 'ID',
                                                'op_text' => 'NAME',
                                                'glue' => '-',
                                                'data' => $this->treeCategoryList,
                                                'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                'text' => 'notext'
                                            )
                                        );
                                    }
                                    ?>
                                    <div id="treeContainer">
                                        <div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo"></div>
                                    </div>
                                    <form role="form" id="tree-click-form" method="post">
                                        <input type="hidden" id="tree-click-hidden-input" />
                                    </form>
                                </div>     
                            <?php } if ($dataViewHeaderData && issetParam($this->isEmptyCriteria)) { ?>
                            <div class="tab-pane in <?php echo (($lowerDataViewCriteriaType != 'left static' && $lowerDataViewCriteriaType != 'left web'  && $lowerDataViewCriteriaType != 'left web civil') || !$this->isTree) ? 'active' : '' ?>"  id="meta-search-view-tab-2-<?php echo $this->metaDataId; ?>">
                                    <?php echo $this->defaultCriteria; ?> 
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php 
            }
            
            if (($lowerDataViewCriteriaType == 'left web' || $lowerDataViewCriteriaType == 'left web civil') && $this->isCheckDataViewHeaderData && issetParam($this->isEmptyCriteria)) { ?>
                <div class="sidebar-sticky sidebar sidebar-light sidebar-component sidebar-component-left sidebar-expand-md sidebar-sticky-custom">
                    <div class="sidebar-content left-sidebar-content leftweb-criteria <?php echo $this->isTree ? 'mt-0' : '' ?>">
                        <div class="tabbable-line">
                            <?php if ($this->isTree) { ?>
                                <ul class="nav nav-tabs nav-tabs-btn px-2">     
                                    <li class="in">
                                        <a href="#meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>" class="pt0 nav-tabs-btn-filter <?php echo (!$dataViewHeaderData || $lowerDataViewCriteriaType == 'left static' || $lowerDataViewCriteriaType == 'left web' && $lowerDataViewCriteriaType != 'left web civil') ? 'active' : '' ?>" data-toggle="tab"><?php echo $this->lang->line('filter'); ?></a>
                                    </li>
                                    <?php if ($dataViewHeaderData) { ?>
                                    <li class="in">
                                        <a href="#meta-search-view-tab-2-<?php echo $this->metaDataId; ?>" class="pt0 <?php echo (($lowerDataViewCriteriaType != 'left static' && $lowerDataViewCriteriaType != 'left web' && $lowerDataViewCriteriaType != 'left web civil') || !$this->isTree) ? 'active' : '' ?> nav-tabs-btn-search" data-toggle="tab"><?php echo $this->lang->line('search'); ?></a>
                                    </li>
                                    <?php } ?>
                                </ul>                        
                            <?php } ?>
                            <div class="tab-content">
                                <?php if ($this->isTree) { ?>
                                        <div class="tab-pane in active leftweb-tree-criteria height-dynamic" id="meta-tree-view-tab-1-<?php echo $this->metaDataId; ?>">
                                            <?php
                                            if (count($this->treeCategoryList) === 1) {
                                                echo "<span class='hide'>";
                                                echo Form::select(
                                                    array(
                                                        'name' => 'treeCategory',
                                                        'id' => 'treeCategory',
                                                        'op_value' => 'ID',
                                                        'op_text' => 'NAME',
                                                        'glue' => '-',
                                                        'data' => $this->treeCategoryList,
                                                        'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                        'text' => 'notext'
                                                    )
                                                );
                                                echo "</span>";
                                            } else {
                                                echo Form::select(
                                                    array(
                                                        'name' => 'treeCategory',
                                                        'id' => 'treeCategory',
                                                        'class' => 'form-control form-control-sm select2 mb10',
                                                        'op_value' => 'ID',
                                                        'op_text' => 'NAME',
                                                        'glue' => '-',
                                                        'data' => $this->treeCategoryList,
                                                        'onchange' => 'drawTree_' . $this->metaDataId . '();',
                                                        'text' => 'notext'
                                                    )
                                                );
                                            }
                                            ?>
                                            <div id="treeContainer">
                                                <div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo"></div>
                                            </div>
                                            <form role="form" id="tree-click-form" method="post">
                                                <input type="hidden" id="tree-click-hidden-input" />
                                            </form>
                                        </div>     
                                    <?php if ($dataViewHeaderData) { ?>
                                        <div class="tab-pane in <?php echo (($lowerDataViewCriteriaType != 'left static' && $lowerDataViewCriteriaType != 'left web' && $lowerDataViewCriteriaType != 'left web civil') || !$this->isTree) ? 'active' : '' ?>"  id="meta-search-view-tab-2-<?php echo $this->metaDataId; ?>">
                                            <?php echo $this->defaultCriteria; ?> 
                                        </div>
                                    <?php } 
                                    } elseif ($dataViewHeaderData) { ?>
                                    <div class="tab-pane in active"  id="meta-search-view-tab-2-<?php echo $this->metaDataId; ?>">
                                        <?php echo $this->defaultCriteria; ?> 
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
<?php
            } 
        }
        
        if (isset($this->dataViewHeaderData['dataGroup']['notShowCriteria']) && !empty($this->dataViewHeaderData['dataGroup']['notShowCriteria'])) {
            
            echo Form::create(array('class' => 'd-none dv-notshowcriteria-form mandatory-criteria-form-'.$this->metaDataId));
            
            foreach ($this->dataViewHeaderData['dataGroup']['notShowCriteria'] as $notShowCriteria) {
                $notShowCriteria['IS_SHOW'] = 0;
                echo Mdwebservice::renderParamControl($this->metaDataId, $notShowCriteria, 'param['.$notShowCriteria['META_DATA_CODE'].']', $notShowCriteria['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)); 
            }
            
            echo Form::close();
        }
        
        if ($isHidden) {
            echo '</div>';
        }
    }
?>