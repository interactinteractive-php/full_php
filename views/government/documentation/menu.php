<div class="sidebar v2 sidebar-light sidebar-main sidebar-expand-md" style="min-height: inherit;">
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
                <ul class="nav nav-tabs v2 nav-tabs-bottom border-bottom-0 nav-justified mb-0 bg-white">
                    <li class="nav-item"><a href="#intranet-file" class="nav-link active text-uppercase font-weight-bold text-left" data-toggle="tab">Гарын авлага</a></li>
                </ul>
                <div class="tab-content">
                    <div class="height-scroll tab-pane fade show active" id="intranet-file">
                        <div class="card">
                            <div class="card-body p-0">
                                <ul class="nav nav-sidebar soyombo-fil" data-nav-type="accordion">
                                    <?php if(isset($this->menu) && $this->menu) { 
                                        foreach($this->menu as $key => $menu) {
                                        ?>
                                        <li class="nav-item">
                                            <a href="javascript:void();" onclick="getCategory_<?php echo $this->uniqId ?>(<?php echo $menu['materialtypeid'] ?>);" class="nav-link"><i class="<?php echo $menu['icon']; ?>"></i><?php echo $menu['materialtypename'] ?></a>
                                        </li>
                                        <?php 
                                        }
                                    } else { ?>
                                        <p class="text-center">Тохирох үр дүн олдсонгүй</p>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>