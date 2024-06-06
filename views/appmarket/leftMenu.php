<div class="appmarket_menu_wrapper">
    <p class="appmarket-menu-title mb-2">Хайлт</p>
    <div class="appmarket-search">
        <input type="text" name="appMarketSearch" placeholder="Хайлт...">
        <i class="fas fa-search font-size-12 mt10"></i>
    </div>
    <p class="appmarket-menu-title mt-3">Төрөл</p>
    <ul class="nav nav-sidebar" data-nav-type="accordion" data-part="dv-twocol-first-list" style="display: block; max-height: 380px;">
    <?php
        if (issetParam($this->leftMenuData)) {
            foreach ($this->leftMenuData as $row) { ?>
                <li class="nav-item">
                    <a href="javascript:;" class="pt-1 pb-1 nav-link appmarket-filter-typemenu <?php echo $row['id'] == issetParam($this->moduleId) ? 'nav-item-selected' : ''; ?>" data-id="<?php echo $row['id']; ?>">
                        <img class="mr-2 mt1" style="height: 16px;width: 16px;" src="https://process.veritech.mn/<?php echo $row['icon'] ? $row['icon'] : 'assets/core/global/img/veritech-erp.png' ?>"/> <span><?php echo $row['name']; ?></span>
                    </a>
                </li>                
            <?php }
        }
    ?>
    <!--<li class="nav-item nav-item-submenu" style="">
            <a href="javascript:void(0);" class="nav-link">
                <i class="icon-folder2" style="color: #FEC345;"></i><span>Ерөнхий</span>
            </a>

            <ul class="nav nav-group-sub" style="display: none;">
                <li class="nav-item with-icon">
                    <a href="javascript:void(0);" class="nav-link v2" title=""><i class="icon-primitive-dot" style="font-size: 12px;"></i> Sub menu 1</a>
                </li>
                <li class="nav-item with-icon">
                    <a href="javascript:void(0);" class="nav-link v2" title=""><i class="icon-primitive-dot" style="font-size: 12px;"></i> Sub menu 2</a>
                </li>
            </ul>
        </li>
        <li class="nav-item" style="">
            <a href="<?php echo URL; ?>appmenu/appmarketign2" class="nav-link">
                <i class="icon-folder2" style="color: #FEC345;"></i><span>Хувьцаа эзэмшигчид</span>
            </a>
        </li>
        <li class="nav-item" style="">
            <a href="<?php echo URL; ?>appmenu/appmarketign3" class="nav-link">
                <i class="icon-folder2" style="color: #FEC345;"></i><span>Хамтрагч, түншлэл</span>
            </a>
        </li>
        <li class="nav-item" style="">
            <a href="<?php echo URL; ?>appmenu/appmarketign4" class="nav-link">
                <i class="icon-folder2" style="color: #FEC345;"></i><span>Менежментийн баг</span>
            </a>
        </li>-->
    </ul>
    <hr class="mt-4"/>
    <p class="appmarket-menu-title">Салбар</p>
    <ul class="nav nav-sidebar" data-nav-type="accordion" data-part="dv-twocol-first-list" style="display: block; max-height: 380px;">
    <?php
        if (issetParam($this->leftIndustryMenuData)) {
            foreach ($this->leftIndustryMenuData as $row) { ?>
                <li class="nav-item">
                    <a href="javascript:;" class="pt-0 nav-link">
                        <i class="icon-folder2 mr-2" style="color: #636363;"></i><span><?php echo $row['name']; ?></span>
                    </a>
                </li>                
            <?php }
        }
    ?>
    </ul>
</div>