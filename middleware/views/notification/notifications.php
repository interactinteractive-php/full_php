<?php if (!defined('_VALID_PHP')) { exit('Direct access to this location is not allowed.'); } ?>
<li class="dropdown dropdown-extended dropdown-notification dropdown-dark nav-item" id="header_notification_bar">
    <a href="#" class="dropdown-toggle dropdown-none-arrow hdr-open-notification-list navbar-nav-link" data-toggle="dropdown" data-close-others="true">
        <i class="icon-bell2"></i>
        <?php echo ($this->totalCount ? '<span class="badge badge-warning">'.$this->totalCount.'</span>' : ''); ?>
    </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-content" style="width: 450px;">
            <div style="border-bottom: 1px solid #e0e0e0;" class="d-none">
                <input tpe="text" class="form-control hdr-notification-search" placeholder="<?php echo Lang::line('search') ?>" style="border:none;height: 38px !important;" />
            </div>
            <div class="dropdown-content-body dropdown-scrollable p-0 hdr-notification-body">
                <ul class="media-list hdr-user-notification-list">
                    <div class="text-center mt20" style="height:40px"></div>
                </ul>
            </div>
    <?php if (Config::getFromCache('disabledNotifAllBtn') == '1') { ?>
        
    <?php } else { ?>
            <div class="dropdown-content-footer bg-light">
                <a href="mdobject/package/1557890263122861" class="text-grey no-padding">Бүгдийг харах</a>
            </div>
    <?php } ?>
        </div>
</li>