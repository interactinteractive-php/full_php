<link href="<?php echo autoVersion('middleware/assets/css/intranet/style.css'); ?>" rel="stylesheet"/>
<div class="intranet">
    <div class="page-content">
        <div class="sidebar v2 sidebar-light sidebar-main sidebar-expand-md dv-twocol-first-sidebar" style="min-height: inherit;">
            
            <?php
            if ($this->isTree) { 
            ?>
            <div class="dv-filter-withtreeview" id="dv-filter-withtreeview-<?php echo $this->uniqId; ?>" style="display:none;position: absolute;width: 264px;border: 1px #dfdfdf solid;border-left:0;background-color: white;z-index: 1;margin-top: 48px;box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;border-top-right-radius: 4px;border-bottom-right-radius: 4px;">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item">
                            <a href="#dv-filter-tab1-<?php echo $this->uniqId; ?>" data-toggle="tab" class="nav-link active"><?php echo $this->lang->line('filter'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a href="#dv-filter-tab2-<?php echo $this->uniqId; ?>" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('search'); ?></a>
                        </li>
                    </ul>
                    <div class="tab-content pt0" style="overflow-y: auto; overflow-x: hidden;">
                        <div class="tab-pane active p-2" id="dv-filter-tab1-<?php echo $this->uniqId; ?>">
                            
                            <?php 
                            if (count($this->treeCategoryList) === 1) {
                                echo Form::select(
                                    array(
                                        'class' => 'd-none',
                                        'name' => 'treeCategory',
                                        'id' => 'treeCategory',
                                        'op_value' => 'ID',
                                        'op_text' => 'NAME',
                                        'glue' => '-',
                                        'data' => $this->treeCategoryList,
                                        'onchange' => 'drawTree_' . $this->uniqId . '();',
                                        'text' => 'notext'
                                    )
                                );
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
                                        'onchange' => 'drawTree_' . $this->uniqId . '();',
                                        'text' => 'notext'
                                    )
                                );
                            }
                            ?>
                            <div id="treeContainer" class="mt0">
                                <div id="dataViewStructureTreeView_<?php echo $this->uniqId; ?>" class="tree-demo"></div>
                            </div>
                            
                        </div>
                        <div class="tab-pane" id="dv-filter-tab2-<?php echo $this->uniqId; ?>">
                            <div class="card">
                                <div class="card-body">
                                    <?php echo $this->filter; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
            <?php
            }
            ?>
            
            <div class="sidebar-mobile-toggler text-center">
                <a href="javascript:void(0);" class="sidebar-mobile-main-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                Navigation
                <a href="javascript:void(0);" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <div class="sidebar-content">
                <div class="card card-sidebar-mobile">
                    <div class="card-body p-0">
                        <div class="ea-content-sidebar-tabs">
                            <div class="ea-first-sidebar-tabs-<?php echo $this->uniqId; ?> w-100 mr-1">
                                <ul class="nav nav-tabs v2 nav-tabs-bottom border-bottom-0 nav-justified mb-0 d-flex align-items-center ea-nav-tabs">
                                    <li class="nav-item">
                                        <a href="#dv-panel-tab1-<?php echo $this->uniqId; ?>" class="nav-link active text-uppercase font-weight-bold" data-toggle="tab" title="<?php echo $this->title; ?>"><?php echo $this->title; ?></a>
                                    </li>
                                    
                                    <?php
                                    if (!$this->isTree && issetParamArray($this->filterParams)) { 
                                    ?>
                                    <li class="nav-item">
                                        <a href="#dv-panel-tab2-<?php echo $this->uniqId; ?>" class="nav-link text-uppercase font-weight-bold" data-toggle="tab"><?php echo $this->lang->line('filter'); ?></a>
                                    </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="first-sidebar-search-box mr-1" id="first-sidebar-search-box-<?php echo $this->uniqId; ?>" style="display: none;">
                                <input type="text" class="form-control first-sidebar-search-input" placeholder="Хайх..." style="width:calc(100% - 10px);">
                            </div>
                            <div class="d-flex flex-row ml-auto mr-2">
                                <a href="javascript:void(0);" class="btn btn-light bg-gray border-0 p-1 pl-2 pr-2 first-sidebar-search bg-grey-c0">
                                    <i class="icon-search4"></i>
                                </a>
                                
                                <?php
                                if (isset($this->dataViewProcessCommand['commandAddMeta']) 
                                        && is_countable($this->dataViewProcessCommand['commandAddMeta']) 
                                        && $commandAddMetaCount = count($this->dataViewProcessCommand['commandAddMeta'])) {
                                    
                                    $commandAddMeta = $this->dataViewProcessCommand['commandAddMeta'];
                                    
                                    if ($commandAddMetaCount == 1) {
                                ?>
                                <a href="javascript:;" onclick="dvPanelRunMeta_<?php echo $this->uniqId; ?>(this, '<?php echo $commandAddMeta[0]['PROCESS_META_DATA_ID']; ?>', '<?php echo $commandAddMeta[0]['META_TYPE_ID']; ?>');" title="<?php echo $this->lang->line($commandAddMeta[0]['PROCESS_NAME']); ?>" class="btn btn-light bg-primary border-0 ml-1 p-1 pl-2 pr-2 text-white">
                                    <i class="icon-plus2"></i>
                                </a>
                                <?php
                                    } else {
                                ?>
                                <div class="btn-group">
                                    <button type="button" class="btn bg-primary addbtn btn-icon dropdown-toggle pt-0 pb-0 pl-1 pr-1 ml-1" data-toggle="dropdown">
                                        <i class="icon-plus2"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                        <?php
                                        foreach ($commandAddMeta as $bpRow) {
                                        ?>
                                        <a href="javascript:;" onclick="dvPanelRunMeta_<?php echo $this->uniqId; ?>(this, '<?php echo $bpRow['PROCESS_META_DATA_ID']; ?>', '<?php echo $bpRow['META_TYPE_ID']; ?>');" class="dropdown-item">
                                            <i class="icon-menu7"></i> <?php echo $this->lang->line($bpRow['PROCESS_NAME']); ?>
                                        </a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="dv-panel-tab1-<?php echo $this->uniqId; ?>">
                                <div id="objectdatagrid-<?php echo $this->metaDataId; ?>" class="not-datagrid div-objectdatagrid-<?php echo $this->metaDataId; ?> p0 m0">
                                    <?php echo $this->mainColumn; ?>
                                </div>    
                            </div>
                            
                            <?php
                            if ($this->filter && !$this->isTree) {
                            ?>
                            <div class="tab-pane fade" id="dv-panel-tab2-<?php echo $this->uniqId; ?>">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <ul class="nav nav-sidebar" data-nav-type="accordion">
                                            <div class="card">
                                                <div class="card-body">
                                                    <?php echo $this->filter; ?>
                                                </div>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
            if (isset($this->row['subQuery']) && $this->row['subQuery']) {
            ?>
            <div class="sidebar-footer">
                <div class="btn-group dropup">
                    <button type="button" class="btn btn-light dropdown-toggle border-radius-0 border-right-0" data-toggle="dropdown" aria-expanded="false"><?php echo $this->title; ?></button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:;" class="dropdown-item" data-subqueryid=""><i class="icon-database4"></i> <?php echo $this->title; ?></a>
                        <?php
                        foreach ($this->row['subQuery'] as $subQuery) {
                        ?>
                        <a href="javascript:;" class="dropdown-item" data-subqueryid="<?php echo $subQuery['ID']; ?>"><i class="icon-database4"></i> <?php echo $subQuery['GLOBE_CODE']; ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php    
            }
            ?>
            
        </div>
        <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md" id="split-second-sidebar-<?php echo $this->uniqId; ?>" style="width:18.875rem;background-color: #f2f2f2;min-height: inherit;z-index:9">
            <div class="sidebar-mobile-toggler text-center">
                <a href="javascript:void(0);" class="sidebar-mobile-secondary-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                <span class="font-weight-semibold">Secondary sidebar</span>
                <a href="javascript:void(0);" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <div class="sidebar-content">
                <div class="card">
                    <div class="card-header bg-white header-elements-inline d-flex justify-content-end px-2">
                        <span class="text-uppercase font-weight-bold second-sidebar-title mr-auto text-two-line line-height-normal" data-secondlist-name="1"></span>
                        
                        <div class="d-flex flex-row ml-1">
                            <button type="button" class="btn btn-light bg-gray bg-grey-c0 border-0 p-1 pl-2 pr-2 text-white panel-dv-collapse-btn"><i class="far fa-arrow-alt-to-left"></i></button>
                        </div>
                    </div>
                    <div class="media-list media-list-linked" id="dv-twocol-second-list">
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper" id="split-content-<?php echo $this->uniqId; ?>">
            <div class="page-header page-header-light bg-white">
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline v2">
                    <div class="d-flex">
                        <span class="align-items-center" data-addon-left-title="1"></span>
                        <span class="text-uppercase font-weight-bold pr100" id="dv-twocol-view-title"></span>
                    </div>
                </div>
            </div>
            <div class="ea-content not-sticky" id="dv-twocol-view-process">
            </div>
        </div>
    </div>
</div>

<?php require_once 'middleware/views/metadata/dataview/viewer/panel/script/twoColumnScript.php'; ?>