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
                <div class="tab-content pt55">
                    <div class="height-scroll tab-pane fade show active" id="bottom-justified-divided-tab1">
                        <div class="side">
                            <ul class="nav nav-sidebar leftsidebar-<?php echo $this->uniqId ?>" data-nav-type="accordion">
                                <?php
                                if (isset($this->rightSideBarMenu) && $this->rightSideBarMenu) {
                                    foreach ($this->rightSideBarMenu as $keys => $rightMenu) {
                                        $rowJson = htmlentities(json_encode($rightMenu), ENT_QUOTES, 'UTF-8');
                                        ?>
                                        <li class="nav-item nav-item-submenu" rowdata="<?php echo $rowJson ?>">
                                            <a href="javascript:void(0);" data-row ="<?php echo $rowJson ?>" 
                                               onclick="getSubMenuIntranet_<?php echo $this->uniqId ?>(this, '<?php echo $rightMenu['id'] ?>', '1', '')" 
                                               id="menu<?php echo $rightMenu['id'] ?>" 
                                               class="nav-link font-weight-bold">
                                                <i class="icon-<?php echo $rightMenu['icon'] ?> font-size-18" style="color: <?php echo $rightMenu['color'] ?>;"></i> 
                                                <span><?php echo $rightMenu['name'] ?></span>
                                                <span class="badge badge-success badge-pill ml-auto mr-3" style="min-width: 20px;height:20px;"><?php echo $rightMenu['unseenpost'] ?></span>
                                            </a>
                                        </li>
                                    <?php }
                                }
                                ?>
<!--                            <a href="javascript:;" class="addfolter" onclick="addFolder(this, null)" data-typeid="null" title="Хавтас үүсгэх">
                                <i class="icon-folder-plus" style="font-size: 15px;margin-left: 10px; color: #1d315a;float: right; margin-right: 15px;"></i>
                            </a>-->
                            </ul>
                            <hr>
                            <a href="javascript:;" class="addfolter" onclick="addFolder(this, null)" data-typeid="null" title="Хавтас үүсгэх"><i class="icon-folder-plus" style="
                                font-size: 15px;
                                margin-left: 10px;
                                color: #1d315a;
                                float: right;
                                margin-right: 15px;
                            "></i></a>
                        </div>
                        <!-- <div class="card card-collapsed">
                            <div class="card-header bg-transparent header-elements-inline mb-2">
                                <span class="text-uppercase font-weight-bold">Шүүлтүүр</span>
                                <div class="header-elements">
                                    <div class="list-icons">
                                        <a class="list-icons-item" data-action="collapse"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body pt-0">
                                <form id="search_form" action="javascript:void(0);">
                                    <div class="form-group">
                                        <label>Гарчиг</label>
                                        <div class="form-group form-group-feedback form-group-feedback-left">
                                            <input type="text" id="title" name="title" onkeyup="search()" class="form-control" placeholder="Гарчгаар хайх">
                                            <div class="form-control-feedback">
                                                <i class="icon-search4 font-size-base text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Нэрээр</label>
                                        <div class="form-group form-group-feedback form-group-feedback-left">
                                            <input type="text" id="user" name="user" onkeyup="search()" class="form-control" placeholder="Нэрээр хайх">
                                            <div class="form-control-feedback">
                                                <i class="icon-user font-size-base text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Эхлэх огноо</label>
                                        <div class="dateElement input-group">
                                            <input type="text" id="start_date" name="start_date" class="form-control dateInit" value="" placeholder="Эхлэх огноо">
                                            <span class="input-group-btn">
                                                <button tabindex="-1" onclick="return false;" class="btn">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Дуусах огноо</label>
                                        <div class="dateElement input-group">
                                            <input type="text" id="end_date" name="end_date" class="form-control dateInit" value="" placeholder="Дуусах огноо">
                                            <span class="input-group-btn">
                                                <button tabindex="-1" onclick="return false;" class="btn">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <button type="submit" onclick="search()" class="btn bg-blue btn-block mt-3 font-weight-bold text-uppercase">Шүүх</button>
                                </form>
                            </div>
                        </div> -->
                    </div>
                    <div class="tab-pane fade" id="bottom-justified-divided-tab2">
                        <div class="card">
                            <div class="card-body p-0">
                                <ul class="nav nav-sidebar" data-nav-type="accordion">
                                    <li class="nav-item">
                                        <a href="javascript:void();" class="nav-link active">
                                            <i class="icon-drawer-in"></i>
                                            Ирсэн
                                            <span class="badge bg-success badge-pill ml-auto">32</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void();" class="nav-link"><i class="icon-drawer-out"></i> Явсан</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void();" class="nav-link"><i class="icon-drawer3"></i> Хадгалсан</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void();" class="nav-link">
                                            <i class="icon-spam"></i>
                                            Спам
                                            <span class="badge bg-danger badge-pill ml-auto">99+</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void();" class="nav-link"><i class="icon-bin"></i> Хогийн сав</a>
                                    </li>
                                    <div class="card">
                                        <div class="card-header bg-transparent header-elements-inline">
                                            <span class="text-uppercase font-weight-bold">Шүүлтүүр</span>
                                            <div class="header-elements">
                                                <div class="list-icons">
                                                    <a class="list-icons-item" data-action="collapse"></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body pt-0">
                                            <form action="javascript:void(0);">
                                                <div class="form-group">
                                                    <label>Салбар нэгж</label>
                                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                                        <input type="search" class="form-control" placeholder="Салбар нэгжээр хайх">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-search4 font-size-base text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Эхлэх огноо</label>
                                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                                        <input type="search" class="form-control" placeholder="Эхлэх огноо">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-calendar font-size-base text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Дуусах огноо</label>
                                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                                        <input type="search" class="form-control" placeholder="Дуусах огноо">
                                                        <div class="form-control-feedback">
                                                            <i class="icon-calendar font-size-base text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn bg-blue btn-block mt-3 font-weight-bold text-uppercase">Шүүх</button>
                                            </form>
                                        </div>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>