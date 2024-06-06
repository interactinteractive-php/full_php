<div class="sidebar v2 sidebar-light sidebar-main sidebar-expand-md">
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
                <ul class="nav nav-tabs nav-tabs-bottom border-bottom-0 nav-justified mb-0">
                    <li class="nav-item"><a href="#bottom-justified-divided-tab1" class="nav-link active text-uppercase font-weight-bold" data-toggle="tab"><?php echo Lang::line('EA_001') ?></a></li>
                    <li class="nav-item"><a href="#bottom-justified-divided-tab2" class="nav-link text-uppercase font-weight-bold" data-toggle="tab"><?php echo Lang::line('EA_002') ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="bottom-justified-divided-tab1">
                        <ul class="nav nav-sidebar" data-nav-type="accordion">
                            <?php 
                            
                            if (isset($this->leftSideBarMenu) && $this->leftSideBarMenu) {
                                foreach ($this->leftSideBarMenu as $keys => $rightMenu) {
                                    $rowJson = htmlentities(json_encode($rightMenu), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <li class="nav-item nav-item-submenu">
                                        <a href="javascript:void(0);" 
                                        data-row="<?php echo $rowJson ?>"
                                        li-status="closed"
                                        onclick="getSubMenuEa_<?php echo $this->uniqId ?>(this, '<?php echo $rightMenu['id'] ?>', '1', '')" 
                                        id="menu<?php echo $rightMenu['id'] ?>" class="nav-link font-weight-bold">
                                            <i class="<?php echo (isset($rightMenu['icon']) && $rightMenu['icon']) ? $rightMenu['icon'] : 'icon-portfolio' ?> font-weight-bold" style="font-size:22px;top:-3px;color: <?php echo (isset($rightMenu['color']) && $rightMenu['color']) ? $rightMenu['color'] : '#f44336' ?>;"></i> <span><?php echo $rightMenu['name'] ?></span>
                                        </a>
                                        <ul class="nav nav-group-sub add-submenu-<?php echo $rightMenu['id'] ?>" data-submenu-title="Layouts"></ul>
                                        <?php
                                        if (isset($this->rightSideBarMenuChild) && $this->rightSideBarMenuChild) {
                                            foreach ($this->rightSideBarMenuChild as $key => $childMenu) {
                                                if ($childMenu['parentid'] == $rightMenu['id']) { ?>
                                                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                                                        <li class="nav-item"><a href="intranet1" class="nav-link"><?php echo $childMenu['name'] ?></a></li>
                                                    </ul>
                                                <?php } ?>
                                            <?php }
                                        } ?>
                                    </li>
                                <?php } 
                        } ?>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="bottom-justified-divided-tab2">
                        <div class="card">
                            <div class="card-header bg-transparent header-elements-inline">
                                
                            </div>
                            <div class="card-body pt-0">
                                <form id="filter-form-<?php echo $this->uniqId ?>" action="javascript:void(0);">
                                    <?php 
                                    $ticket = false;
                                    if (isset($this->dataViewHeaderData)) {
                                        
                                        $this->dataViewHeaderData = !empty($this->dataViewHeaderData['data']) ? $this->dataViewHeaderData['data'] : $this->dataViewHeaderData;
                                        foreach ($this->dataViewHeaderData as $gKey => $param) { 

                                            if (isset($param['META_DATA_NAME'])) {
                                                $ticket = true;
                                                ?>
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line($param['META_DATA_NAME']); ?></label>
                                                <div class="form-group form-group-feedback ">
                                                    <?php
                                                    echo  Mdwebservice::renderParamControl($this->metaDataId1,
                                                                            $param, 
                                                                            "param[".$param['META_DATA_CODE']."]", 
                                                                            $param['META_DATA_CODE'], 
                                                                            false , '', true);

                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            }
                                        }
                                    } 
                                    if ($ticket) {
                                        echo '<button type="button" class="btn bg-blue btn-block mt-3 font-weight-bold text-uppercase filter-btn-'. $this->uniqId .'">Шүүх</button>';
                                    }
                                    ?>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>