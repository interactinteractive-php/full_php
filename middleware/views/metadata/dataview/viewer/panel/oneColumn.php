<link href="<?php echo autoVersion('middleware/assets/css/intranet/style.css'); ?>" rel="stylesheet"/>
<div class="intranet">
    <div class="page-content">
        
        <div class="sidebar v2 sidebar-light sidebar-main sidebar-expand-md dv-onecol-first-sidebar" style="min-height: inherit;">
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
                                        <a href="#dv-panel-tab1-<?php echo $this->uniqId; ?>" class="nav-link active text-uppercase font-weight-bold" data-toggle="tab"><?php echo $this->title; ?></a>
                                    </li>
                                    
                                    <?php
                                    if (!$this->isTree && issetParamArray($this->filterParams)) {  
                                    ?>
                                    <li class="nav-item">
                                        <a href="#dv-panel-tab2-<?php echo $this->uniqId; ?>" class="nav-link text-uppercase font-weight-bold" data-toggle="tab"><?php echo $this->lang->line('filter'); ?></a>
                                    </li>
                                    <?php } ?>
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
                            if ($this->filter) {
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
        <div class="content-wrapper" id="split-content-<?php echo $this->uniqId; ?>">
            <div class="page-header page-header-light bg-white">
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline v2">
                    <div class="d-flex">
                        <span class="text-uppercase font-weight-bold pr100" id="dv-twocol-view-title"></span>
                    </div>
                </div>
            </div>
            <div class="ea-content" id="dv-twocol-view-process">
            </div>
        </div>
    </div>
</div>

<?php require_once 'middleware/views/metadata/dataview/viewer/panel/script/oneColumnScript.php'; ?>