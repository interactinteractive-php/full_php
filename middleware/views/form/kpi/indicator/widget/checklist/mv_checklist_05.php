<?php
$renderType = '';
?>
<div class="kpi-form-paper-portrait <?php echo $renderType ?>">
    <div class="kpi-form-paper-portrait-child">    
        <?php $headerTitleCss = ''; 
            if ($renderType == 'paper_main_window') { 
                $headerTitleCss = 'position: fixed;
                top: 12px;
                z-index: 99;
                text-align: center;
                width: 100%;
                text-transform: uppercase;
                font-size: 16px;
                font-weight: 600;'; 
            } 
        ?>             
        <div class="mb-1 d-flex justify-content-between <?php echo $renderType == 'paper_main_window' ? 'hidden' : '' ?>">
            <?php                 
                $logoImage = 'assets/custom/img/new_veritech_black_logo.png';

                if (isset($this->logoImage) && file_exists($this->logoImage)) {
                    $logoImage = $this->logoImage;
                }
            ?>            
            <img style="height: 24px" src="<?php echo $logoImage; ?>"/>
            <p class="mb-0 mt-0" style="font-size: 20px;font-weight: bold;<?php echo $headerTitleCss ?>"><?php echo $this->title ?></p>
            <a title="Хаах" href="javascript:;" onclick="checklistCloseDialog(this)">
                <i style="font-size: 16px;color:#737373" class="far fa-times"></i>
            </a>
        </div>   
        <?php if ($renderType == 'paper_main_window') { ?>
            <p class="mb-0 mt-0 paper_main_window_sys_title_<?php echo Config::getFromCacheDefault('IS_APPMENU_NEWDESIGN', null, 0); ?>" style="font-size: 20px;font-weight: bold;<?php echo $headerTitleCss ?>"><?php echo $this->title ?></p>
        <?php } ?>
        <div class="row mv-checklist-render-parent mv-checklist5-render-parent mv-checklist2-render-parent" id="mv-checklist-render-parent-<?php echo $this->uniqId; ?>">
            <div class="hawtast-hereg-container" style="padding: 1.5rem 2.25rem;border:1px solid rgb(237 237 237);border-radius: 0.625rem;margin: 12px;width: 100%;background-color: rgb(244, 244, 244);min-height: 190px;">
                <div style="color:#99A1B7" class="empty-data-text col-12">Хавтаст хэрэг сонгогдоогүй байна</div>
                <div class="d-flex" style="gap: 45px">
                    <div class="hawtast-hereg-data hawtast-hereg-data-img" style="width: 132px;height: 115px;border-radius: 50%;">
                        <img src="assets/core/global/img/user.png" data-path="icon" style="width: 60px;height: 60px;border-radius: 50%;margin-left: 30px;margin-top: 30px;">
                    </div>      
                    <div class="row w-100">
                        <div class="col-4 hawtast-hereg-data">
                            <div style="font-weight: bold" data-path="c5"></div>
                            <div style="color:#99A1B7">Хэрэг хүлээн авсан</div>
                            <div style="font-weight: bold" data-path="c18" class="mt15"></div>
                            <div style="color:#99A1B7">Шүүх</div>
                            <div style="font-weight: bold" data-path="c2" class="mt15"></div>
                            <div style="color:#99A1B7">Эрүүгийн хэргийн дугаар</div>
                        </div>
                        <div class="col-4 hawtast-hereg-data">
                            <div style="font-weight: bold" data-path="c3"></div>
                            <div style="color:#99A1B7">Хянан шийдвэрлэх жагсаалт</div>
                            <div style="font-weight: bold" data-path="c9" class="mt15"></div>
                            <div style="color:#99A1B7">Үйлчилгээ</div>
                            <div style="font-weight: bold" data-path="c4" class="mt15"></div>
                            <div style="color:#99A1B7">Нууцлал</div>
                        </div>
                        <div class="col-4 hawtast-hereg-data">
                            <div class="d-flex justify-content-end">
                                <span class="badge badge-pill" style="padding: 8px 15px 8px 15px;font-size: 12px;" data-path="wfmstatusname">Status</span>
                            </div>
                            <div class="d-flex justify-content-end mt35" style="gap: 15px;">
<!--                                <div style="border:1px dashed #c3c6ce;border-radius: .475rem;padding-top: .75rem !important;padding-bottom: .75rem !important;padding-left: 6px;padding-right: 6px;">
                                    <div style="text-align: center;">
                                        <div style="font-weight: bold; font-size: 18px;" data-path="c7" class=""></div>
                                        <div style="color:#99A1B7">Хавтаст хэргийн тоо</div>
                                    </div>
                                </div>-->
                                <div style="border-radius: .475rem;padding-top: .75rem !important;padding-bottom: .75rem !important;padding-left: 6px;padding-right: 6px;background-color: #fff;">
                                    <div style="text-align: center;">
                                        <div style="font-weight: bold; font-size: 18px;" data-path="c8" class=""></div>
                                        <div style="color:#99A1B7">Хэргийн хуудасны тоо</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bp-tabs tabbable-line mv-main-tabs mv-checklist-tab w-100 mv-checklist5-tab">
                <ul class="nav nav-tabs" style="padding-top: 3px !important;padding-bottom: 0px !important;">
                    <li class="nav-item">
                        <a style="padding-top: 3px;" href="#maintabcustom_1719031535425301_4" class="nav-link active" data-toggle="tab" aria-expanded="false">Хавтаст хэрэг</a>
                    </li>
                    <li class="nav-item">
                        <a style="padding-top: 3px;" href="#maintabcustom_1719031535425301_1" class="nav-link" data-toggle="tab" aria-expanded="false">Процесс</a>
                    </li>
                    <li class="nav-item">
                        <a style="padding-top: 3px;" href="#maintabcustom_1719031535425301_2" class="nav-link" data-toggle="tab" aria-expanded="false">Тайлан</a>
                    </li>
                    <li class="nav-item">
                        <a style="padding-top: 3px;" href="#maintabcustom_1719031535425301_3" class="nav-link" data-toggle="tab" aria-expanded="false">Ажил үүрэг</a>
                    </li>
                    <li class="nav-item">
                        <a style="padding-top: 3px;" href="#maintabcustom_1719031535425301_5" class="nav-link" data-toggle="tab" aria-expanded="false">Календарь</a>
                    </li>
                </ul>
                <div class="tab-content" style="padding-top: 0px;padding-bottom: 0px;">                                         
                    <div class="tab-pane active" id="maintabcustom_1719031535425301_4" style="padding-bottom: 0 !important;padding-top: 0 !important;padding-right: 0 !important;">                
                        <div class="pl10 pt10">Loading...</div>
                    </div>
                    <div class="tab-pane" id="maintabcustom_1719031535425301_1" style="padding-bottom: 0 !important;padding-top: 0 !important;padding-right: 0 !important;">                
                        <?php if ($renderType == 'paper_main_window') { ?>
                            <div style="position: absolute;right: 15px;top: 15px;display: none"><a title="Хаах" href="javascript:history.go(-1)"><i style="font-size: 15px;color:#737373" class="far fa-times"></i></a></div>        
                        <?php } 
                            $tabId = 1;
                            ?>                    
                        <div class="d-flex w-100" style="background-color: rgb(244, 244, 244)">
        <!--                                sidebar-light-->
                            <div class="sidebar sidebar-dark sidebar-secondary sidebar-expand-md mt-2 ml-2" style="width:300px;border-radius: .75rem;">
                                <div class="d-flex justify-content-end">
                                    <a href="javascript:;" title="Sidebar хураах" class="checklist-sidebar-close-btn" onclick="mvCheckListSidebarClose(this)" style="">
                                        <i class="icon-arrow-left5 hidden"></i>
                                    </a>
                                </div>
                                <div class="sidebar-content">

                                    <div class="card">
                                        <div class="card-body mv-checklist-menu kpidv-data-tree-col kpidv-data-bptree-col">
                                            <div id="indicatorTreeView_17189630742541" data-indicatorid="17189630742541" class="tree-demo mt-1" style="overflow-x: hidden;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 checklist2-content-section content-wrapper-<?php echo $renderType ?>" style="background-color: #f4f4f4; max-width: 1205px">
                                <div>
                                    <div class="content-wrapper pt-2 pl-3 pr-3 pb-0 mv-checklist-render mv-checklist-render-bp">        
                                    </div>                
                                </div>                
                                <div class="mv-checklist-render-comment pl-3 pr-3">
                                </div>                
                                <div class="mv-checklist5-render-relation pl-2 pr-2">
                                </div>                
                            </div>           
                        </div>                
                    <?php 
                        $tabId ++;      
                    ?>                  
                    </div>
                    <div class="tab-pane" id="maintabcustom_1719031535425301_2" style="padding-bottom: 0 !important;padding-top: 0 !important;padding-right: 0 !important;">
                        <?php
                            $tabId = 1;
                            ?>                    
                        <div class="d-flex w-100" style="background-color: rgb(244, 244, 244)">
        <!--                                sidebar-light-->
                            <div class="sidebar sidebar-dark sidebar-secondary sidebar-expand-md mt-2 ml-2" style="width:300px;border-radius: .75rem;">
                                <div class="d-flex justify-content-end">
                                    <a href="javascript:;" title="Sidebar хураах" class="checklist-sidebar-close-btn" onclick="mvCheckListSidebarClose(this)" style="">
                                        <i class="icon-arrow-left5 hidden"></i>
                                    </a>
                                </div>
                                <div class="sidebar-content">

                                    <div class="card">
                                        <div class="card-body mv-checklist-menu kpidv-data-tree-col kpidv-data-rtree-col">
                                            <div id="indicatorTreeView_17189630747991" data-indicatorid="17189630747991" class="tree-demo mt-1" style="overflow-x: hidden;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 checklist2-content-section content-wrapper-<?php echo $renderType ?>" style="background-color: #f4f4f4; max-width: 1205px;overflow-x: auto;">
                                <div>
                                    <div class="content-wrapper pt-2 pl-3 pr-3 pb-0 mv-checklist-render mv-checklist-render-report">        
                                    </div>                
                                </div>                
                            </div>               
                        </div>                
                    <?php 
                        $tabId ++;      
                    ?>                                  
                    </div>
                    <div class="tab-pane" id="maintabcustom_1719031535425301_3" style="padding-bottom: 0 !important;padding-top: 0 !important;padding-right: 0 !important;">
                        <?php
                            $tabId = 1;
                            ?>                    
                        <div class="d-flex w-100" style="background-color: rgb(244, 244, 244)">
        <!--                                sidebar-light-->
                            <div class="sidebar sidebar-dark sidebar-secondary sidebar-expand-md mt-2 ml-2" style="width:300px;border-radius: .75rem;">
                                <div class="sidebar-content">
                                    <div class="card">
                                        <div class="card-body mv-checklist-menu kpidv-data-tree-col mv-checklist-taskmenu">
                                            <div style="text-align: right;" class="mt12">
                                                <a href="javascript:;" onclick="checklist5AddTaskBtn(this);" style="background-color: #2281cd !important;" class="btn btn-light bg-primary border-0 mr-1 p-0 pt3 pl-1 pr-1 pb1 text-white">
                                                    <i class="icon-plus2"></i>
                                                </a>                                                
                                            </div>
                                            <div id="indicatorTreeView_17189630753551" data-indicatorid="17189630753551" class="tree-demo mt-1" style="overflow-x: hidden;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 checklist2-content-section content-wrapper-<?php echo $renderType ?>" style="background-color: #f4f4f4; max-width: 1205px;overflow-x: auto;">
                                <div>
                                    <div class="content-wrapper pt-2 pl-3 pr-3 pb-0 mv-checklist-render mv-checklist-render-task">        
                                    </div>                
                                </div>                
                            </div>             
                        </div>                
                    <?php 
                        $tabId ++;      
                    ?>                                  
                    </div>
                    <div class="tab-pane" id="maintabcustom_1719031535425301_5" style="padding-bottom: 0 !important;padding-top: 0 !important;padding-right: 0 !important;">                
                        <div class="pl10 pt10">Loading...</div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
$bgImage = 'middleware/assets/img/process/background/khanbank_mv_bg_image.jpg';

if (isset($this->bgImage) && file_exists($this->bgImage)) {
    $bgImage = $this->bgImage;
}

if ($renderType == 'paper_main_window') {
    $bgImage = '';
}
?>

<style type="text/css">
.mv-checklist-render-report .kl-layout-filter {
    position: absolute;
    right: 32px;        
    margin-top: 7px;
}
.mv-checklist-render-report .kl-layout-filter.kl-layout-filter-closed .kl-layout-filter-footer,
.mv-checklist-render-report .kl-layout-filter.kl-layout-filter-closed .kpi-indicator-filter-title {
    display: none;
}
.mv-checklist5-render-parent .mv-checklist-render-comment .media-body .p-2.pb3 {
    background-color: #eee !important;
}   
.mv-checklist5-render-parent .mv-checklist-render-comment .media-list .border-gray {
    border-color: transparent !important;
}   
.mv-checklist5-render-parent .mv-checklist-render-comment .dialog-chat .avatar {
    display: none;
}   
.sidebar-dark .nav-link.mv_checklist_02_groupname {
    color: #c1bdab !important;
}
.sidebar-dark .checklist-sidebar-close-btn {
    color: #fff;
}
.sidebar-dark .checklist-sidebar-close-btn .icon-arrow-right5 {
    color: #333;
}
.sidebar-dark .nav-sidebar>.nav-item-open>.nav-link:not(.disabled), .sidebar-dark .nav-sidebar>.nav-item>.nav-link.active {
    background-color: rgba(255,255,255,.1);
}
.checklist-sidebar-close-btn {
    color: #333;
    font-size: 15px;
    line-height: 0;
    margin-top: 15px;
    margin-right: 15px;
}
.nav-link.mv_checklist_02_groupname {
    font-size: 13px;
    color: #333 !important;
    font-weight: bold !important;
    padding-top: 5px;
    padding-bottom: 5px;
    text-transform: none !important;
}    
.nav-link.mv_checklist_02_sub {
    padding-top: 5px;
    padding-bottom: 3px;
    font-size: 12px;
}
.nav-link.mv_checklist_02_sub i {
    color: #b0b7ee !important;
    margin-top: 2px;
    font-size: <?php echo $renderType == 'paper_main_window' ? '13px' : '18px'; ?>;    
    margin-right: 13px;
}    
.mv-checklist5-render-parent .jeasyuiTheme3 {
    padding-bottom: 15px;
}
.mv-checklist5-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-icon,
.mv-checklist5-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-group-btn:first-child {
    display: none;
}
.mv-checklist5-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-group-btn:last-child button {
    color: #252F4A;
    background-color: #eee;
    padding: 0px 5px 0px 5px !important;
}
.mv-checklist5-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-group-btn:last-child button i:before {
    content: "Сонгох";
    font-family: Arial, Helvetica, sans-serif;
}
.mv-checklist5-render-parent .jeasyuiTheme3 .datagrid-htable .datagrid-header-row:not(.datagrid-filter-row) {
    height: 35px !important;
}
.mv-checklist5-render-parent .jeasyuiTheme3 .datagrid-header .datagrid-cell span, 
.mv-checklist5-render-parent .jeasyuiTheme3 .datagrid-view .datagrid-cell-group {
    font-size: 12px;
    font-weight: 700;
    color: #99A1B7;
}
.mv-checklist5-render-parent .datagrid-header td, 
.mv-checklist5-render-parent .datagrid-body td, 
.mv-checklist5-render-parent .datagrid-footer td {
    border-color: transparent;
}
.mv-checklist5-render-parent .panel-header-eui, 
.mv-checklist5-render-parent .panel-body-eui {
    border-color: transparent;
}
.mv-checklist5-render-parent .datagrid-pager {
    border-color: transparent;
}
.mv-checklist5-render-parent .datagrid-row-alt:not(.datagrid-row-over) {
    background: transparent;
}
.mv-checklist5-render-parent .jeasyuiTheme3 .datagrid-header td {
    background: #eee !important;
    border-style: solid;
    border-color: transparent;
}
.mv-checklist5-render-parent .mv-checklist-render .meta-toolbar {
    border-bottom: none;
    margin-top: 0;
}
.mv-checklist5-render-parent .mv-checklist-render .meta-toolbar .main-process-text {
    font-size: 12px;
} 
.mv-checklist5-render-parent .mv-checklist-main-render .meta-toolbar {
    border-bottom: 0;
    padding-bottom: 0;
    margin-bottom: 0;
    background-color: #fff;
    padding-left: 40px;
    padding-right: 25px;
    padding-top: 15px;    
    border-top-left-radius: .75rem;
    border-top-right-radius: .75rem;    
}
.mv-checklist5-render-parent .mv-checklist-main-render .meta-toolbar .main-process-text {
    /*display: none;*/
    font-size: 12px;
}    
.mv-checklist5-render-parent .bp-add-one-row-num {
    display: none;
}    
.mv-checklist5-render-parent .mv-checklist-render .mv-add-row-actions {
    margin-top: 0 !important;
}    
.mv-checklist5-render-parent .nav-group-sub-mv-opened .nav-group-sub {
    display: block;
}
.mv-checklist5-render-parent .nav-sidebar .nav-item:not(.nav-item-header):last-child {
    padding-bottom: 0 !important;
}
.mv-checklist5-render-parent .nav-item-submenu.nav-group-sub-mv-opened>.nav-link:after {
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);
}
.mv-checklist5-render-parent .nav-group-sub .nav-link {
    padding-left: 20px;
}
.mv-checklist5-render-parent .mv-checklist-render button.bp-add-one-row,
.mv-checklist5-render-parent .mv-checklist-render button.btn-xs.btn-outline-success,
.mv-checklist5-render-parent .mv-checklist-render button.btn-xs.green-meadow {
    background-color: #eee !important;
    color: #252F4A;
    font-size: 12px;
    padding: 0px 5px 0px 5px;
    height: 21px !important;
    min-height: 21px !important;    
    border-color: #eee !important;
}    
.mv-checklist5-render-parent .main-process-text-description {
    color: #99A1B7;
    text-transform: none;
    font-weight: normal;
    font-size: 11px;    
}    
.mv-checklist5-render-parent .mv-checklist-render button.bp-add-one-row:hover,
.mv-checklist5-render-parent .mv-checklist-render .bp-add-ac-row button:hover {
    background-color: #1B84FF !important;
    color: #fff !important;
}    
.nav-item-submenu>.nav-link.mv_checklist_02_groupname:after {
    margin-top: -6px;
}
.mv-checklist5-render-parent {
    margin: 20px -15px 0px -20px!important;
}
.mv-checklist5-render-parent button.bp-btn-save i {
    display: none;
}
.mv-checklist5-render-parent button.bp-btn-save, 
.mv-checklist5-render-parent button.bp-btn-check, 
.mv-checklist5-render-parent button.bp-btn-saveadd, 
.mv-checklist5-render-parent button.bp-btn-help,
.mv-checklist5-render-parent .meta-toolbar button.bp-btn-help {
    color: #1B84FF!important;
    border-color: #1B84FF!important;
    padding-left: 18px!important;
    padding-right: 18px!important;
    /*background-color: #1B84FF!important;*/
    padding-bottom: 2px !important;
    font-size: 12px!important;
}
.mv-checklist5-render-parent button.bp-btn-save:hover, 
.mv-checklist5-render-parent button.bp-btn-saveadd:hover, 
.mv-checklist5-render-parent button.bp-btn-help:hover,
.mv-checklist5-render-parent .meta-toolbar button.bp-btn-help:hover {
    background-color: #1B84FF!important;
}
.mv-checklist5-render-parent .mv-rows-title:not(.mv-rows-title-label) {
    display: none;
}
.mv-checklist5-render-parent .mv-rows-title-label {
    color: rgba(51,51,51,.8);
}
.mv-checklist5-render-parent > .sidebar {
    width: 16.875rem;
    padding: 0;
    background-color: rgb(243, 244, 246);
}
.mv-checklist5-render-parent > .sidebar .sidebar-content {
    padding: 15px 10px;
}
.mv-checklist5-render-parent .sidebar-light .nav-sidebar .nav-item>.nav-link {
    text-transform: none;
}
.mv-checklist5-render-parent .sidebar-light .nav-sidebar .nav-item>.nav-link:not(.mv_card_status_widget).active {
    background-color: #1b84ff54;
}
.mv-checklist5-render-parent .mv-checklist-title {
    color: #3C3C3C;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 700;
}
.mv-checklist5-render-parent .mv-checklist-description {
    color: #67748E;
    margin-top: 10px;
}
.mv-checklist5-render-parent > .sidebar > .sidebar-content > .card > .card-body .step {
    background: #A0A0A0;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
.mv-checklist5-render-parent > .sidebar > .sidebar-content > .card > .card-body .step.active {
    background: #468CE2;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
/*.mv-checklist5-render-parent .mv-checklist-taskmenu {
    min-height: 300px !important;
}*/
.mv-checklist5-render-parent .mv-checklist-menu {
    padding: 0;
    /*margin-left: -5px;*/
    /*margin-right: -10px;*/
    overflow: auto;
}
.mv-checklist5-render-parent .mv-checklist-menu:not(.mv-checklist-card-menu) li {
    width: 100%;
}
.mv-checklist5-render-parent > .sidebar .card-body .nav-sidebar a.nav-link {
    display: flex;
    align-items: center;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    padding: 10px 22px 10px 10px;
    overflow: hidden;
    font-size: 12px;
    text-transform: none;
}
.mv-checklist5-render-parent > .sidebar .card-body .nav-sidebar a.nav-link:hover {
    background-color: #E8EBF0;
    color: #468CE2;
}
.mv-checklist5-render-parent .sidebar-light {
    border-right: none;
}
.mv-checklist5-render-parent .kpi-ind-tmplt-section {
    background-color: #fff;
    padding-top: 10px;
    padding-bottom: 0px;
    margin-bottom: 0px;
}
.mv-checklist5-render-parent .sectiongidseperatorcontent legend {
    padding: 12px !important;
    padding-left: 34px !important;
}
.mv-checklist5-render-parent .sectiongidseperator {
    height: 15px;
    background-color: rgb(244, 244, 244);
    width: 100%;
}
.mv-checklist5-render-parent > .sidebar .card-body .nav-sidebar a.nav-link i {
    font-size: 18px;
    margin-right: 10px;
}
.mv-checklist5-render-parent > .sidebar .card-body .nav-sidebar a.nav-link span {
    font-size: 12px;
    font-weight: 600;
}
.mv-checklist5-render-parent > .sidebar .card-body .nav-sidebar a.nav-link.active {
    background-color: #E8EBF0;
    color: #468CE2;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child {
    padding-right: 15px !important;
    padding-bottom: 0 !important;    
}
.kpi-form-paper-portrait {
    background-image: url("<?php echo $bgImage; ?>"); 
    background-repeat: no-repeat; 
    background-position: top center;
    background-attachment: fixed;
    background-color: #ededed;
    margin: -10px -15px;
    padding-top: 11px;
    padding-bottom: 20px;
    background-size: cover;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child {
    position: relative;
    /* width: <?php //echo $this->windowWidth ? $this->windowWidth: '1200px'; ?>; */
    width: 90%;
    min-height: calc(100vh - 44px);
    margin-top: 10px;
    margin-left: auto;
    margin-right: auto;
    background: #FFF;
    padding: 20px;
    box-shadow: 0px 2px 6px 0 rgba(0,0,0,.5);
}
.kpi-form-paper-portrait.paper_main_window {
    padding-top: 0;
    padding-bottom: 0;
}
.kpi-form-paper-portrait.paper_main_window .kpi-form-paper-portrait-child {
    width: 100%;
    box-shadow: none;
    margin-top: 0;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-title {
    margin-bottom: -14px;
    /*margin-top: 10px;*/
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-logo {
    position: absolute;
    max-width: 150px;
    max-height: 70px;
    top: 20px;
    left: 20px;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-header-text {
    position: absolute;
    max-width: 180px;
    max-height: 70px;
    top: 20px;
    right: 20px;
    text-align: right;
    line-height: 14px;
}
.kpi-form-paper-portrait h1 {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    line-height: 28px;
    margin-bottom: 20px;
    margin-left: auto;
    margin-right: auto;
    max-width: 500px;
}
.kpi-form-paper-portrait .mv-main-tabs {
    margin-top: 0;
}
.kpi-form-paper-portrait.paper_main_window .mv-main-tabs {
    margin-top: <?php echo Config::getFromCacheDefault('IS_APPMENU_NEWDESIGN', null, 0) ? -3 : 10; ?>px !important;
}
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::-webkit-input-placeholder {
    color: transparent !important;
}
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input:-moz-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::-moz-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input:-ms-input-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::placeholder {
    color: transparent !important;
}
.kpi-form-paper-portrait .bp-overflow-xy-auto {
    border: 0;
}
.kpi-form-paper-portrait table.kpi-dtl-table td, 
.kpi-form-paper-portrait table.kpi-dtl-table th {
    border: 1px solid transparent;
}
.kpi-form-paper-portrait table.kpi-dtl-table td {
    border-bottom: 1px #eee solid;
    border-right: 1px #eee solid;
    background-color: #fff;
}
.kpi-form-paper-portrait table.kpi-dtl-table thead tr {
    height: 50px;
}
.kpi-form-paper-portrait table.kpi-dtl-table thead tr th {
    border-top: 1px #eee solid !important;
    border-bottom: 1px #eee solid !important;
    background: #fff !important;
    font-weight: bold;
    font-size: 12px!important;
    background-color: #eee !important;
    color: #99A1B7;
}
.kpi-form-paper-portrait .tabbable-line>.nav-tabs>li a.active:not(.nav-tabs-btn-filter, .nav-tabs-btn-search) {
    border-bottom: 4px solid #1B84FF !important;
    color: #1B84FF;
}
.mv-checklist5-render-parent .kpi-form-paper-portrait .tabbable-line>.nav-tabs>li.open, 
.mv-checklist5-render-parent .kpi-form-paper-portrait .tabbable-line>.nav-tabs>li a:hover {
    border-bottom: 4px solid #1B84FF;
    color: #1B84FF;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link {
    background-color: transparent;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link:before {
    height: 2px;
    top: -1px;
    left: -1px;
    right: -1px;
    content: '';
    position: absolute;
}
.kpi-notfocus-readonly-input {
    cursor: text!important;
    background-color: inherit!important;
}
.kpi-notfocus-readonly-input:focus, 
table.table td.stretchInput input.kpi-notfocus-readonly-input:not(.select2-input):not(.error):focus {
    border: 1px solid transparent !important;
}
input.kpi-notfocus-readonly-input::-webkit-input-placeholder {
    color: transparent !important;
}
input.kpi-notfocus-readonly-input:-moz-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input::-moz-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input:-ms-input-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input::placeholder {
    color: transparent !important;
}
.select2-container.select2-container-disabled.kpi-notfocus-readonly-input .select2-choice {
    background-color: #fff;
    background-image: none;
    border: 1px solid #fff;
    cursor: text;
    padding-left: 3px;
}
.select2-container.select2-container-disabled.kpi-notfocus-readonly-input .select2-choice .select2-arrow {
    display: none;
}
.kpi-form-paper-portrait table.table td.stretchInput input[type="text"]:not(.select2-input):not(.error):focus, 
.kpi-form-paper-portrait table.table td.stretchInput textarea:not(.error):focus {
    border: 1px solid #e9a22f !important;
}
.mv-rows-title .label-colon {
    display: none;
}
.mv-inline-field {
    display: inline-block;
    padding-left: 10px;
}
.mv-checklist5-render-parent .mv-hdr-label-control-label:not(.type-check), 
.mv-checklist5-render-parent .mv-hdr-label-control-input:not(.type-check) {
    max-width: 100%;
}
/*.mv-checklist5-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control):not(.type-check),*/ 
.mv-checklist5-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-row,
.mv-checklist5-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-label:not(.type-check), 
.mv-checklist5-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-input:not(.type-check) {
    display: block;
}
.mv-checklist5-render-parent .mv-hdr-label-control, 
.mv-checklist5-render-parent .mv-hdr-label-control-row,
.mv-checklist5-render-parent .mv-hdr-label-control-label, 
.mv-checklist5-render-parent .mv-hdr-label-control-input {
    border: none;
    background-color: #fff;
}
.mv-checklist5-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-label:not(.type-check) {
    width: 100%!important;
    padding-bottom: 8px;
}
.mv-checklist5-render-parent .mv-hdr-label-control-label {
    text-align: left;
    font-weight: bold;
}
.mv-checklist5-render-parent .mv-rows-title-label {
    font-size: 14px;
    color: #555;
    padding-left: 32px;
}
.mv-checklist5-render-parent .mv-hdr-label-control-label label {
    color: #666;
}
.mv-checklist5-render-parent .kpidv-data-tree-col .list-group {
    background-color:transparent;
}
.mv-checklist5-render-parent .mv-hdr-label-control-label label .label-colon {
    display: none;
}
.ui-dialog .mv-checklist5-render-parent .ws-area .ws-page-content-wrapper .ws-page-content {
    padding: 0px!important;
}
.mv-checklist5-render-parent .mv-hdr-label-control-input .form-control {
    height: 32px!important;
    min-height: 32px!important;
    border: 1px #f3f3f3 solid;
    /*padding: 7px 10px!important;*/
}
.mv-checklist5-render-parent .mv-hdr-label-control-input textarea.form-control {
    border-radius: 6px!important;
    border: 1px #eee solid;
    /*padding: 7px 10px!important;*/
}
.mv-checklist5-render-parent .mv-hdr-label-control {
    margin-bottom: 10px;
    padding-left: 25px;
    padding-right: 25px;    
}
.mv-checklist5-render-parent .mv-hdr-label-control-input .form-control .select2-choice,
.mv-checklist5-render-parent .mv-hdr-label-control-input .form-control.select2-container-active .select2-choice,
.mv-checklist5-render-parent .mv-hdr-label-control-input .form-control.select2-container-active .select2-choices {
    border: 1px #eee solid;
    height: 32px;
    padding-top: 2px;
}
.mv-checklist5-render-parent .mv-checklist-render div[data-meta-type="process"] .table-scrollable>.table, 
.mv-checklist5-render-parent .mv-checklist-render div[data-meta-type="process"] .tabbable-line>.tab-content {
    background-color: transparent;
}
.mv-checklist5-render-parent .mv-checklist-render div[data-meta-type="process"] .tabbable-line>.nav-tabs {
    border: none;
    margin: 0px;
    background: transparent;
}
.mv-checklist5-render-parent .viewer-container > .center-sidebar > .row > .content-wrapper > .row, 
.mv-checklist5-render-parent .viewer-container > .center-sidebar > .row > .top-sidebar-content > .xs-form.row {
    margin: 0;
}
.mv-checklist5-render-parent .render-object-viewer > .row > .col-md-12 > .viewer-container > .mv-datalist-show-filter > .row {
    margin-left: 0;
}
.mv-checklist5-render-parent .mv-checklist-render div[data-meta-type="process"] .bp-header-param ul.bp-icon-selection {
    max-height: 360px;
}
.mv-checklist5-render-parent .row > .col-md-2 > .mv-hdr-label-control > .mv-hdr-label-control-input > .dateElement {
    max-width: none!important;
}
.fileinput-button .big {
    font-size: 40px;
    line-height: 90px;
    text-align: center;
    color: #ddd;    
}
.new-vlogo-link-selector {
    padding-top: 12px !important;
    padding-bottom: 0 !important;    
}
.kpidv-data-tree-col .p-row-title {
    color: #fff;
    padding-left: 3px;
}
.kpidv-data-tree-col .nameField {
    padding-right: 30px;
}
.kpidv-data-tree-col {
    /*width: 260px;*/
    /*border-right: 1px solid #ddd;*/
    overflow-x: hidden;
    overflow-y: auto;
    padding-left: 3px !important;
    padding-right: 7px !important;    
}
.kpidv-data-tree-col .list-group {
    border: none;
    padding: 0;
}
.kpidv-data-tree-col .list-group-item {
    padding: 0.35rem 0;
}
.kpidv-data-tree-col .list-group-item.opened i {
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);        
}
.kpidv-data-tree-col .list-group-item i {
    min-width: 8px;
}
.kpidv-data-tree-col .list-group-item.active {
    color: rgba(51,51,51,.85);
    background-color: transparent;
    border-color: rgba(93, 173, 226, 0.3);
}
.kpidv-data-tree-col .jstree-default .jstree-custom-folder-icon.jstree-closed>.jstree-ocl, 
.kpidv-data-tree-col .jstree-default .jstree-custom-folder-icon.jstree-open>.jstree-ocl {
    -webkit-font-smoothing: antialiased;
    background-color: transparent;
    background-image: none;
    background-position: 0 0;
    background-repeat: no-repeat;
    font: normal normal normal 15px / 1 icomoon;
    color: #dfba49;
    text-rendering: auto;
}
.kpidv-data-tree-col .jstree-default .jstree-closed>.jstree-ocl:before {
    content: "\ea48";
}
.kpidv-data-tree-col .jstree-default .jstree-open>.jstree-ocl:before {
    content: "\ea49";
}
.mv-checklist-taskmenu .jstree-default .jstree-ocl,
.kpidv-data-tree-col .jstree-default li:not(.jstree-closed,.jstree-open) .jstree-ocl {
    display: none;
}
.kpidv-data-tree-col.mv-checklist-taskmenu .mv-tree-filter-icon {
    color:#b0b7ee;
    font-size: 15px;    
}
.kpidv-data-tree-col .mv-tree-filter-icon {
    color:#b0b7ee;
    font-size: 15px;
    margin-left: 4px;
}
.kpidv-data-tree-col .jstree-default .jstree-custom-folder-icon.green.jstree-closed>.jstree-ocl, 
.kpidv-data-tree-col .jstree-default .jstree-custom-folder-icon.green.jstree-open>.jstree-ocl {
    color: #41c7ae;
}
.kpidv-data-tree-col .jstree-default .jstree-node, 
.kpidv-data-tree-col .jstree-default .jstree-icon {
    background-image: none !important;
}
.kpidv-data-tree-col .jstree-default .jstree-clicked {
    background-color: transparent;
    box-shadow: none;
}
.kpidv-data-tree-col .jstree-default .jstree-hovered {
    background-color: transparent;
    box-shadow: none;
}
/*.kpidv-data-tree-col .jstree-default li:hover {
    background-color: rgba(255,255,255,.1);
}*/
.kpidv-data-tree-col .jstree-default li.active {
    background-color: rgba(255,255,255,.1);
}
.kpidv-data-tree-col .jstree-default li {
    padding-top: 6px;
    margin-bottom: 3px;
}
.kpidv-data-tree-col .jstree-default li:not(.jstree-loading) a.jstree-anchor {
    width: 100%;
}
.mv-checklist5-render-relation .indicatorView {
    margin-left: .625rem;
    margin-right: .625rem;    
    padding-right: 0;
    padding-left: 0;
}
#maintabcustom_1719031535425301_5 .kpidv-data-filter-col .list-group-item,
#maintabcustom_1719031535425301_5 .kpi-indicator-filter-title {
    padding: 0.28rem 5px;
}
#maintabcustom_1719031535425301_5 .indicatorView {
    margin-left: .625rem;
    margin-right: .625rem;    
    padding-right: 0;
    padding-left: 0;
    margin-top: 12px;
}
.mv-checklist5-render-parent .mv-checklist5-render-relation .viewer-container {
    padding-right: 0;
    padding-left: 0;    
}
.mv-checklist5-render-relation .center-sidebar.content {
    padding-left: 10px !important;
}
.mv-checklist5-render-parent .mv-checklist5-render-relation .main-dataview-container {
    padding-top: 0;
}
.mv-checklist5-render-parent .mv-checklist5-render-relation .mv_tiny_card_with_list_widget_main {
    background-color: #fff;
}
.mv-checklist5-render-parent .mv-checklist5-render-relation .main-dataview-container .dv-right-tools-btn {
    display: none;
}
.mv-checklist5-render-parent .mv-checklist5-render-relation .package-tab-name {
    font-size: 13px;
    border-bottom: none;
    margin-top: 0;
    margin-bottom: 0;
    margin-left: 8px;    
}
.hawtast-hereg-data {
    display: none;
}
.mv-checklist5-render-parent .mv_tiny_card3_with_list_widget.active > .card {
    box-shadow: 5px 5px 5px 0px rgba(170, 170, 170, 0.5) !important;
}
.mv-checklist5-render-parent .mv_tiny_card3_with_list_widget > .card:hover {
    box-shadow: 5px 5px 5px 0px rgba(170, 170, 170, 0.5) !important;
}
.mv-checklist5-render-parent .mv_tiny_card3_with_list_widget_main {
    background-color: #fff;
}
</style>

<script type="text/javascript">
var viewProcessWindow_<?php echo $this->uniqId; ?> = false;
var viewMode_<?php echo $this->uniqId; ?> = '';
var $checkList_<?php echo $this->uniqId; ?> = $('#mv-checklist-render-parent-<?php echo $this->uniqId; ?>');
var $checkListMenu_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.mv-checklist-menu');
var viewProcess_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.mv-checklist-render-bp');
var viewReportProcess_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.mv-checklist-render-report');
var viewTaskProcess_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.mv-checklist-render-task');
var indicatorId = $("#indicatorTreeView_17189630742541").data('indicatorid');
var rIndicatorId = $("#indicatorTreeView_17189630747991").data('indicatorid');
var taskIndicatorId = $("#indicatorTreeView_17189630753551").data('indicatorid');
var filterIdCheck4 = '';
var rowDataTreeSidebar = {};
var _rowDataMetaDataview = {};
var _tempRowDataMetaDataview = {};

$('#mv-checklist-render-parent-<?php echo $this->uniqId; ?>').on('shown.bs.tab', '.mv-checklist5-tab > ul.nav-tabs > li > a', function() {
    if ($(this).attr('href') == '#maintabcustom_1719031535425301_5') {
        var $tabPane2 = $('#maintabcustom_1719031535425301_5');
        var postData = {
            mapSrcMapId: '', 
            mapSelectedRow: '', 
            srcMapId: '', 
            /*isIgnoreFilter: 1,*/
            isHideCheckBox: 0, 
            isIgnoreTitle: 1
        };
        
        if ($tabPane2.html().length > 200) {
            return;
        }

        $.ajax({
            type: 'post',
            url: 'mdform/indicatorList/197281522',
            data: postData, 
            beforeSend: function() {
            },
            success: function(dataHtml) {
                console.log($tabPane2)
                $tabPane2.empty().append(dataHtml).promise().done(function() {
                });
            }
        });            
    }
});

var wcontw = $('.mv-checklist5-render-parent').width() - 300;
$('.mv-checklist5-render-parent').find('.checklist2-content-section').css('max-width', wcontw+'px'); 

if ($checkListMenu_<?php echo $this->uniqId; ?>.length) {
    $checkListMenu_<?php echo $this->uniqId; ?>.css('min-height', $(window).height() - 195);
}   

if ($('#maintabcustom_1719031535425301_4').length) {
    var $tabPane = $('#maintabcustom_1719031535425301_4');
    $.ajax({
        type: 'post',
        //url: "mdobject/dataValueViewer",
        url: 'mdobject/dataview/1719369128320017/' + 'false'+ '/json',
        dataType: "json",
        data: {
    //              metaDataId: indicatorId333,
    //              viewType: "detail",
          //dataGridDefaultHeight: $(window).height() - 190,
          //uriParams: relationMappingConfig.dv,
          ignorePermission: 1
        },
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $tabPane.empty().append('<div class="pl-2 pr-2" id="object-value-list-1719369128320017">' + data.Html + "</div>").promise().done(function() {
                $tabPane.find('.meta-toolbar').parent().remove();
                Core.unblockUI();
            });   
        }
    });    
}

$("#indicatorTreeView_17189630742541").jstree({
    "core": {
        "themes": {
            "responsive": true,
            "icons": false
        },
        "check_callback": true,
        "data": {
            "url": function (node) {
                return 'mdform/getAjaxTree';
            },
            "data": function (node) {
                return {
                    'parent': node.id, 
                    'indicatorId' : indicatorId, 
                    'colName' : 'PARENT_ID', 
                    icon: 'far fa-file',
                    criteria: {
                        FILTERID:[
                            {
                                operator: '=',
                                operand: '<?php echo Ue::sessionUserKeyId() ?>'
                            }
                        ]
                    }
                };
            }
        }
    },       
    "types": {
        "default": {
            "icon": "icon-folder2 text-orange-300"
        }
    },
    'search': {
        'case_insensitive': true,
        'show_only_matches' : true
    },        
    "plugins": ["types", "cookies", "search"]
}).bind("select_node.jstree", function (e, data) {
    var nid = data.node.id === 'null' || data.node.id === 'all' ? '' : data.node.id;
    rowDataTreeSidebar = data.node.original ? data.node.original.rowdata : {};
    $('.kpidv-data-bptree-col').find('li').removeClass('active');
    $('.kpidv-data-bptree-col').find('li#'+nid).addClass('active');
    var mvTitle = $('.kpidv-data-bptree-col').find('li#'+nid).find('.p-row-title').text();

    var strIndicatorId = rowDataTreeSidebar.MAIN_INDICATOR_ID;
    
    if (strIndicatorId == '' || strIndicatorId == null) {
        viewProcess_<?php echo $this->uniqId; ?>.empty().append('Indicator тохируулаагүй байна!');
        return;
    }

    /**
     * 
     * Render metaverse
     */               
    
    var isComment = false;
    var postData = {
        mainIndicatorId: '', 
        structureIndicatorId: strIndicatorId, 
        trgIndicatorId: rowDataTreeSidebar.CRUD_ID, 
        trgIndicatorKpiTypeId: '', 
        typeCode: '', 
        recordId: nid, 
        srcMapId: '', 
        selectedRow: ''
    };
    filterIdCheck4 = nid;
    
//    var relationMappingConfig = $.ajax({
//        type: "post",
//        url: "mdform/relationParamMapping",
//        data: {
//            mapId: indicatorMapId,
//            rowData: _rowDataMetaDataview,
//            dvId: indicatorId333,
//        },
//        dataType: "json",
//        async: false,
//        success: function (data) {
//          return data;
//        }
//    });    
//    
//    relationMappingConfig = relationMappingConfig.responseJSON;    
    
    $.ajax({
        type: 'post',
        url: 'mdform/kpiIndicatorTemplateRender',
        data: {
            param: {
                indicatorId: strIndicatorId, 
                mainIndicatorId: _rowDataMetaDataview.indicatorid, 
                crudIndicatorId: rowDataTreeSidebar.CRUD_ID,                  
                isListRelation: 1
                /*idField: 'SRC_RECORD_ID', 
                id: rowDataTreeSidebar.ID*/
            },
            selectedRow: _rowDataMetaDataview
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(dataHtml) {
            var html = [];

            var sveActionBtn = '';            
                sveActionBtn = '<div style="">';

            if (dataHtml.hasOwnProperty('helpContentId') && dataHtml.helpContentId !== null && dataHtml.helpContentId !== '') {
                sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-help mr-1" onclick="redirectHelpContent(this, \''+dataHtml.helpContentId+'\', \''+indicatorId+'\', \'mv_method\');">'+plang.get('menu_system_guide')+'</button>';
            }
                sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save" onclick="checkList5SaveKpiIndicatorForm(this);"><i class="icon-checkmark-circle2"></i> '+plang.get('save_btn')+'</button>';
            sveActionBtn += '</div>';
            var renderHeader = '<div class="meta-toolbar is-bp-open- d-flex justify-content-between">'+
                '<div class="main-process-text">\n\
                    <div>'+dataHtml.name+'</div>\n\
                    <div class="main-process-text-description">'+(dataHtml.indicatorInfo && dataHtml.indicatorInfo.DESCRIPTION ? dataHtml.indicatorInfo.DESCRIPTION : '')+'</div>\n\
                </div>'+sveActionBtn;

            renderHeader += '</div>';

            html.push('<form method="post" enctype="multipart/form-data">');
                html.push(renderHeader);
                html.push(dataHtml.html);
            html.push('</form>');            

            viewProcess_<?php echo $this->uniqId; ?>.empty().append(html.join('')).promise().done(function() {
                Core.unblockUI();
            });                   
        }
    });       

}).bind('loaded.jstree', function (e, data) {
//    /$('.kpidv-data-tree-col').find('li').first().find('a').click();
});

$("#indicatorTreeView_17189630747991").jstree({
    "core": {
        "themes": {
            "responsive": true,
            "icons": false
        },
        "check_callback": true,
        "data": {
            "url": function (node) {
                return 'mdform/getAjaxTree';
            },
            "data": function (node) {
                return {
                    'parent': node.id, 
                    'indicatorId' : rIndicatorId, 
                    'colName' : 'PARENT_ID', 
                    icon: 'far fa-file',
                    criteria: {
                        FILTERID:[
                            {
                                operator: '=',
                                operand: '<?php echo Ue::sessionUserKeyId() ?>'
                            }
                        ]
                    }
                };
            }
        }
    },       
    "types": {
        "default": {
            "icon": "icon-folder2 text-orange-300"
        }
    },
    'search': {
        'case_insensitive': true,
        'show_only_matches' : true
    },        
    "plugins": ["types", "cookies", "search"]
}).bind("select_node.jstree", function (e, data) {
    var nid = data.node.id === 'null' || data.node.id === 'all' ? '' : data.node.id;
    rowDataTreeSidebar = data.node.original ? data.node.original.rowdata : {};
    $('.kpidv-data-rtree-col').find('li').removeClass('active');
    $('.kpidv-data-rtree-col').find('li#'+nid).addClass('active');
    var mvTitle = $('.kpidv-data-rtree-col').find('li#'+nid).find('.p-row-title').text();
    var metaDataId = rowDataTreeSidebar.META_DATA_ID,
        metaTypeId = rowDataTreeSidebar.META_TYPE_ID,
        kpiTypeId = rowDataTreeSidebar.KPI_TYPE_ID,
        kpiIndicatorId = rowDataTreeSidebar.INDICATOR_ID,
        jsonObj = {};
    
    if (metaDataId != '' && metaDataId != null) {

        if (metaTypeId == '200101010000016') { //Dataview

            $.ajax({
                type: 'post',
                url: 'mdobject/dataview/' + metaDataId + '/0/json',
                data: {kpiIndicatorMapConfig: jsonObj},
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    if (data.hasOwnProperty('Html')) {
                        viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                            viewReportProcess_<?php echo $this->uniqId; ?>.find('> .row > .col-md-12:eq(0)').remove();
                            Core.unblockUI();
                        });
                    } else {
                        viewReportProcess_<?php echo $this->uniqId; ?>.removeClass('pl-3 pr-3').addClass('pl5 pr5');
                        viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(data.html).promise().done(function () {
                            Core.unblockUI();
                        });
                    }
                },
                error: function(){ alert('Error'); Core.unblockUI(); }
            });

        } else if (metaTypeId == '200101010000035') { //Statement

            $.ajax({
                type: 'post',
                url: 'mdstatement/index/' + metaDataId,
                data: {kpiIndicatorMapConfig: jsonObj},
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (dataHtml) {
                    if (viewProcessWindow_<?php echo $this->uniqId; ?>) {                            
                        if (!viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).length) {
                            viewReportProcess_<?php echo $this->uniqId; ?>.append('<div class="mv_checklist_render_all" id="mv_checklist_id_'+metaDataId+'"></div>');
                        }  
                        viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).append(dataHtml).promise().done(function () {
                            Core.unblockUI();
                        });                            
                    } else {
                        viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(dataHtml).promise().done(function () {
                            Core.unblockUI();
                        });
                    }                        
                },
                error: function(){ alert('Error'); Core.unblockUI(); }
            });
        } else if (metaDataId == '1522652361821242') { //Pos menu meta id

            $.ajax({
                type: 'post',
                url: 'mdpos',
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    $.ajax({
                        url: "assets/custom/addon/plugins/jquery-fixedheadertable/jquery.fixedheadertable.min.js",
                        dataType: "script",
                        cache: true,
                        async: false,
                        beforeSend: function() {
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/css/pos/style.css?v=1"/>');
                        }
                    }).done(function() {
                        $.ajax({
                            url: "assets/custom/addon/plugins/scannerdetection/jquery.scannerdetection.js",
                            dataType: "script",
                            cache: true,
                            async: false
                        });
                        viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(data.html).promise().done(function () {
                            Core.unblockUI();                        
                            if (typeof data.chooseCashier === 'undefined') {

                                setTimeout(function () {
                                    viewReportProcess_<?php echo $this->uniqId; ?>.find('.pos-wrap').css({"margin-left":"-15px", "margin-right":"-16px", "margin-top":"-9px"});
                                    viewReportProcess_<?php echo $this->uniqId; ?>.find('.pos-left').css({"position":"inherit","overflow-y":"auto","overflow-x":"hidden","height":viewReportProcess_<?php echo $this->uniqId; ?>.find('.pos-center-inside-height').height()+180+'px'});
                                    viewReportProcess_<?php echo $this->uniqId; ?>.find('.pos-left-inside-help').css("position","inherit");
                                }, 600);

                                isPOSLayoutAjaxLoad = false;

                                if (typeof checkInitPosJS === 'undefined') {
                                    $.ajax({
                                        url: "middleware/assets/js/pos/pos.js",
                                        dataType: "script",
                                        cache: false,
                                        async: false
                                    });
                                } else {
                                    setTimeout(function() {
                                        Core.initDecimalPlacesInput();
                                        posConfigVisibler($('body'));
                                        posPageLoadEndVisibler();
                                        posItemCombogridList('');
                                        $('.pos-item-combogrid-cell').find('input.textbox-text').val('').focus();

                                        var $tbody = $('#posTable').find('> tbody');                

                                        if ($tbody.find('> tr').length) {

                                            Core.initLongInput($tbody);
                                            Core.initUniform($tbody);

                                            posGiftRowsSetDelivery($tbody);

                                            var $firstRow = $tbody.find('tr[data-item-id]:eq(0)');
                                            $firstRow.click();

                                            posCalcTotal();
                                        }                  

                                        /*if (posUseIpTerminal === '1') {
                                            posConnectBankTerminal();
                                        }*/

                                        if (isConfirmSaleDate === '1' && !isBasketOnly) {
                                            askDateTransaction();
                                        }                    

                                    }, 300);
                                }
                                setTimeout(function() {
                                    posTableSetHeight(80);
                                    posFixedHeaderTable();
                                }, 300);
                            }                    
                        });
                    });                        
                },
                error: function(){ alert('Error'); Core.unblockUI(); }
            });
        } else if (metaDataId == '1482131909084156') { //Salary menu meta id

            viewReportProcess_<?php echo $this->uniqId; ?>.find(".mv_checklist_render_all").addClass("hidden");
            if (viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).length && viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).html().length) { 
                Core.unblockUI();
                viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).removeClass("hidden");
                return;
            }            

            $.ajax({
                type: 'post',
                url: 'mdsalary/salary_v3',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    if (viewProcessWindow_<?php echo $this->uniqId; ?>) {
                        if (!viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).length) {
                            viewReportProcess_<?php echo $this->uniqId; ?>.append('<div class="mv_checklist_render_all" id="mv_checklist_id_'+metaDataId+'"></div>');
                        }
                        viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).append(data).promise().done(function () {
                            Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId));
                            Core.unblockUI();                                   
                        });    
                    } else {
                        viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(data).promise().done(function () {
                            Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>);
                            Core.unblockUI();                                   
                        });     
                    }
                },
                error: function(){ alert('Error'); Core.unblockUI(); }
            });

        } else if (metaDataId == '16842269788489') { //Time Plan menu meta id

            viewReportProcess_<?php echo $this->uniqId; ?>.find(".mv_checklist_render_all").addClass("hidden");
            if (viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).length && viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).html().length) { 
                Core.unblockUI();
                viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).removeClass("hidden");
                return;
            }                 

            $.ajax({
                type: 'post',
                url: 'mdtimestable/timeEmployeePlanV2',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    if (typeof tnaTimeEmployeePlanData === 'undefined') {
                        $.ajax({
                            url: "middleware/assets/js/time/timePlanV2.js?v="+Date.now(),
                            dataType: "script",
                            cache: true,
                            async: false,
                            beforeSend: function() {
                                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/css/time/time.css"/>');
                            }
                        }).done(function() {
                        });
                    }         

                    if (viewProcessWindow_<?php echo $this->uniqId; ?>) {

                        if (!viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).length) {
                            viewReportProcess_<?php echo $this->uniqId; ?>.append('<div class="mv_checklist_render_all" id="mv_checklist_id_'+metaDataId+'"></div>');
                        }
                        viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).append(data).promise().done(function () {
                            Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId));
                            Core.unblockUI();                                   
                        });    

                    } else {                        

                        viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(data).promise().done(function () {
                            Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>);
                            Core.unblockUI();                                   
                        });                
                    }
                },
                error: function(){ alert('Error'); Core.unblockUI(); }
            });

        } else if (metaDataId == '16293670316521') { //Time Balance menu meta id

            viewReportProcess_<?php echo $this->uniqId; ?>.find(".mv_checklist_render_all").addClass("hidden");
            if (viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).length && viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).html().length) { 
                Core.unblockUI();
                viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).removeClass("hidden");
                return;
            }                  

            $.ajax({
                type: 'post',
                url: 'mdtimestable/timebalanceV5',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    if (viewProcessWindow_<?php echo $this->uniqId; ?>) {

                        if (!viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).length) {
                            viewReportProcess_<?php echo $this->uniqId; ?>.append('<div class="mv_checklist_render_all" id="mv_checklist_id_'+metaDataId+'"></div>');
                        }
                        viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).append(data).promise().done(function () {
                            Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId));
                            Core.unblockUI();                                   
                        });    

                    } else {                                 

                        viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(data).promise().done(function () {
                            Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>);
                            Core.unblockUI();                                   
                        });                    
                    }
                },
                error: function(){ alert('Error'); Core.unblockUI(); }
            });

        } else if (metaDataId == '1710231625314794') { //FA_DEPRECTION_WEBLINK

            $.ajax({
                type: 'post',
                url: 'mdasset/deprecation',
                dataType: 'html',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (dataHtml) {
                    var getRenderWidth = viewReportProcess_<?php echo $this->uniqId; ?>.width();
                    viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(dataHtml).promise().done(function () {
                        viewReportProcess_<?php echo $this->uniqId; ?>.find('.pf-custom-pager > .freeze-overflow-xy-auto').css('width', getRenderWidth);
                        Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>);
                        Core.unblockUI();
                    });
                }
            });

        } else if (metaDataId == '1710746826924995') { //Create GL

            $.ajax({
                type: 'post',
                url: 'mdgl/entry',
                dataType: 'html',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (dataHtml) {
                    var getRenderWidth = viewReportProcess_<?php echo $this->uniqId; ?>.width();
                    viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(dataHtml).promise().done(function () {
                        viewReportProcess_<?php echo $this->uniqId; ?>.find('.freeze-overflow-xy-auto').removeClass('w-100').css('width', '1160px');
                        Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>);
                        Core.unblockUI();
                    });
                }
            });

        } else if (metaDataId == '1710748364382042') { //Create Cashrate

            $.ajax({
                url: "assets/custom/addon/plugins/datatables/media/js/jquery.dataTables.min.js",
                dataType: "script",
                cache: true,
                async: false,
                beforeSend: function() {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>');
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css"/>');
                }
            }).done(function() {
                $.ajax({
                    url: "assets/custom/addon/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "middleware/assets/js/mdgl.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
            });

            $.ajax({
                type: 'post',
                url: 'mdgl/cashrate',
                dataType: 'html',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (dataHtml) {
                    var getRenderWidth = viewReportProcess_<?php echo $this->uniqId; ?>.width();
                    viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(dataHtml).promise().done(function () {
                        viewReportProcess_<?php echo $this->uniqId; ?>.find('.freeze-overflow-xy-auto').removeClass('w-100').css('width', getRenderWidth);
                        Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>);
                        Core.unblockUI();
                    });
                }
            });

        } else if (metaDataId == '1710748420762728') { //Create Clearingtrans

            $.ajax({
                url: "assets/custom/addon/plugins/datatables/media/js/jquery.dataTables.min.js",
                dataType: "script",
                cache: true,
                async: false,
                beforeSend: function() {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>');
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/datatables/extensions/FixedColumns/css/dataTables.fixedColumns.min.css"/>');
                }
            }).done(function() {
                $.ajax({
                    url: "assets/custom/addon/plugins/datatables/extensions/FixedColumns/js/dataTables.fixedColumns.min.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "assets/custom/addon/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
                $.ajax({
                    url: "middleware/assets/js/mdgl.js",
                    dataType: "script",
                    cache: true,
                    async: false
                });
            });

            $.ajax({
                type: 'post',
                url: 'mdgl/clearingtrans',
                dataType: 'html',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (dataHtml) {
                    var getRenderWidth = viewReportProcess_<?php echo $this->uniqId; ?>.width();
                    viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(dataHtml).promise().done(function () {
                        viewReportProcess_<?php echo $this->uniqId; ?>.find('.freeze-overflow-xy-auto').removeClass('w-100').css('width', getRenderWidth);
                        Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>);
                        Core.unblockUI();
                    });
                }
            });

        } else if (metaDataId == '1712204023134451') { //Data import

            var $parent = $this.closest('.mv-checklist-render-parent');
            var listIndicatorId = $parent.find('input[data-path="listIndicatorId"]').val();

            $.ajax({
                type: 'post',
                url: 'mdform/importManageAI',
                data: {mainIndicatorId: listIndicatorId}, 
                dataType: 'html',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (dataHtml) {
                    viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(dataHtml).promise().done(function () {
                        Core.initAjax(viewReportProcess_<?php echo $this->uniqId; ?>);
                        Core.unblockUI();
                    });
                }
            });
        }

    } else {

        var strIndicatorId = jsonObj.strIndicatorId;
        var mapId = jsonObj.mapId;
        var isMartRender = Number(jsonObj.isMartRender);

        var headerRecordId = '';

        if (kpiTypeId == '2008' || isMartRender > 0) { 

            viewReportProcess_<?php echo $this->uniqId; ?>.find(".mv_checklist_render_all").addClass("hidden");
            if (viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).length && viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).html().length) { 
                Core.unblockUI();
                viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).removeClass("hidden");
                viewProcessComment_<?php echo $this->uniqId; ?>.empty();
                return;
            }                   

            var typeCode = (jsonObj.typeCode).toLowerCase();
            var postData = {
                mainIndicatorId: jsonObj.mainIndicatorId, 
                structureIndicatorId: strIndicatorId, 
                trgIndicatorId: indicatorId, 
                trgIndicatorKpiTypeId: kpiTypeId, 
                typeCode: typeCode, 
                recordId: '', 
                srcMapId: mapId, 
                selectedRow: ''
            };

//            if ($headerParams.length) {
//                postData.selectedRow = rowParse;
//                postData.recordId = headerRecordId;
//            }

            $.ajax({
                type: 'post',
                url: 'mdform/renderValueMapStructure',
                data: postData,
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(dataHtml) {
                    var html = [];

                    var renderHeader = '<div class="meta-toolbar is-bp-open-">'+
                        '<div class="main-process-text">\n\
                            <div>'+$this.text()+'</div>\n\
                            <div class="main-process-text-description">'+(dataHtml.indicatorInfo && dataHtml.indicatorInfo.DESCRIPTION ? dataHtml.indicatorInfo.DESCRIPTION : '')+'</div>\n\
                        </div>';
                    var sveActionBtn = '';

                    if (viewMode_<?php echo $this->uniqId; ?> != 'view') {

                        sveActionBtn = '<div class="ml-auto pull-right">';

//                        if (typeof is_pfd != 'undefined' && is_pfd) {
//                            sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-help mr-1" onclick="setHelpContent(this, \''+dataHtml.helpContentId+'\', \''+indicatorId+'\', \'mv_method\');">'+plang.get('set_help_content_btn')+'</button>';
//                        }

                        if (dataHtml.hasOwnProperty('helpContentId') && dataHtml.helpContentId !== null && dataHtml.helpContentId !== '') {
                            sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-help mr-1" onclick="redirectHelpContent(this, \''+dataHtml.helpContentId+'\', \''+indicatorId+'\', \'mv_method\');">'+plang.get('menu_system_guide')+'</button>';
                        }
                            sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save" onclick="checkList5SaveKpiIndicatorForm(this);"><i class="icon-checkmark-circle2"></i> '+plang.get('save_btn')+'</button>';
                        sveActionBtn += '</div>';
                    }

                    renderHeader += '</div>';

                    html.push('<form method="post" enctype="multipart/form-data">');
                        html.push(renderHeader);
                        html.push(dataHtml.html);
                        html.push(sveActionBtn);
                    html.push('</form>');

                    if (viewProcessWindow_<?php echo $this->uniqId; ?>) {

                        if (!viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).length) {
                            viewReportProcess_<?php echo $this->uniqId; ?>.append('<div class="mv_checklist_render_all" id="mv_checklist_id_'+indicatorId+'"></div>');
                        }
                        viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).append(html.join('')).promise().done(function() {

                            if (viewMode_<?php echo $this->uniqId; ?> == 'view') {

                                var $render = viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId);

                                $render.find('.bp-add-one-row').parent().remove();
                                $render.find('.bp-remove-row, button.red, button.bp-btn-save, button.green-meadow, button.bp-file-choose-btn, a[onclick*="bpFileChoosedRemove"], span.filename, a[onclick*="kpiIndicatorRelationRemoveRows"], div.input-group.quick-item-process').remove();
                                $render.find('input[type="text"], textarea').addClass('kpi-notfocus-readonly-input').attr('readonly', 'readonly');
                                $render.find("div[data-s-path]").addClass('select2-container-disabled kpi-notfocus-readonly-input');
                                $render.find('button[onclick*="dataViewSelectableGrid"], button[onclick*="chooseKpiIndicatorRowsFromBasket"]').prop('disabled', true);
                                $render.find('[data-action-name="exportexcel"]').removeClass('d-none');

                                var $radioElements = $render.find("input[type='radio']");
                                if ($radioElements.length) {
                                    $radioElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                                    $radioElements.closest('.radio').addClass('disabled');
                                }

                                var $checkElements = $render.find("input[type='checkbox']");
                                $checkElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                                $checkElements.closest('.checker').addClass('disabled');
                            }
                            
                            Core.unblockUI();

                        });                            

                    } else {                           

                        viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(html.join('')).promise().done(function() {

                            if (viewMode_<?php echo $this->uniqId; ?> == 'view') {

                                var $render = viewReportProcess_<?php echo $this->uniqId; ?>;

                                $render.find('.bp-add-one-row').parent().remove();
                                $render.find('.bp-remove-row, button.red, button.bp-btn-save, button.green-meadow, button.bp-file-choose-btn, a[onclick*="bpFileChoosedRemove"], span.filename, a[onclick*="kpiIndicatorRelationRemoveRows"], div.input-group.quick-item-process').remove();
                                $render.find('input[type="text"], textarea').addClass('kpi-notfocus-readonly-input').attr('readonly', 'readonly');
                                $render.find("div[data-s-path]").addClass('select2-container-disabled kpi-notfocus-readonly-input');
                                $render.find('button[onclick*="dataViewSelectableGrid"], button[onclick*="chooseKpiIndicatorRowsFromBasket"]').prop('disabled', true);
                                $render.find('[data-action-name="exportexcel"]').removeClass('d-none');

                                var $radioElements = $render.find("input[type='radio']");
                                if ($radioElements.length) {
                                    $radioElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                                    $radioElements.closest('.radio').addClass('disabled');
                                }

                                var $checkElements = $render.find("input[type='checkbox']");
                                $checkElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                                $checkElements.closest('.checker').addClass('disabled');
                            }

                            if (viewReportProcess_<?php echo $this->uniqId; ?>.find(".sectiongidseperatorcontent-container").length) {
                                viewReportProcess_<?php echo $this->uniqId; ?>.find(".meta-toolbar").hide();
                            }

                            Core.unblockUI();

                        });
                    }
                }
            });

        } else if (kpiTypeId == '2022') {

            var postData = {
                mainIndicatorId: jsonObj.mainIndicatorId, 
                structureIndicatorId: strIndicatorId, 
                trgIndicatorId: indicatorId, 
                trgIndicatorKpiTypeId: kpiTypeId, 
                uniqId: '<?php echo $this->uniqId; ?>', 
                typeCode: '', 
                recordId: '', 
                srcMapId: mapId, 
                selectedRow: ''
            };

//            if ($headerParams.length) {
//                postData.selectedRow = rowParse;
//                postData.recordId = headerRecordId;
//            }

            $.ajax({
                type: 'post',
                url: 'mdform/renderKpiPackage',
                data: postData,
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(dataHtml) {
                    var html = [];

                    html.push(dataHtml.html);

                    viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(html.join('')).promise().done(function() {
                        Core.unblockUI();
                    });
                }
            });            

        } else if (kpiTypeId == '2010') {

            var postData = {
                mainIndicatorId: jsonObj.mainIndicatorId, 
                structureIndicatorId: strIndicatorId, 
                trgIndicatorId: indicatorId, 
                trgIndicatorKpiTypeId: kpiTypeId, 
                uniqId: '<?php echo $this->uniqId; ?>', 
                typeCode: '', 
                recordId: '', 
                srcMapId: mapId, 
                selectedRow: ''
            };

//            if ($headerParams.length) {
//                postData.selectedRow = rowParse;
//                postData.recordId = headerRecordId;
//            }

            $.ajax({
                type: 'post',
                url: 'mdform/indicatorStatement/'+kpiIndicatorId,
                data: postData,
                dataType: 'html',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(dataHtml) {

                    viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(dataHtml).promise().done(function() {
                        Core.unblockUI();
                    });
                }
            });            

        } else if (kpiTypeId == '1130') {

            var postData = {
                mainIndicatorId: jsonObj.mainIndicatorId, 
                structureIndicatorId: strIndicatorId, 
                trgIndicatorId: indicatorId, 
                trgIndicatorKpiTypeId: kpiTypeId, 
                uniqId: '<?php echo $this->uniqId; ?>', 
                typeCode: '', 
                recordId: '', 
                srcMapId: mapId, 
                selectedRow: ''
            };

//            if ($headerParams.length) {
//                postData.selectedRow = rowParse;
//                postData.recordId = headerRecordId;
//            }

            $.ajax({
                type: 'post',
                url: 'mdform/indicatorDashboard/'+indicatorId,
                data: postData,
                dataType: 'html',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(dataHtml) {

                    viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(dataHtml).promise().done(function() {
                        Core.unblockUI();
                    });
                }
            });            

        } else if (kpiTypeId == '2020') {

            var postData = {
                mainIndicatorId: jsonObj.mainIndicatorId, 
                structureIndicatorId: strIndicatorId, 
                trgIndicatorId: indicatorId, 
                trgIndicatorKpiTypeId: kpiTypeId, 
                uniqId: '<?php echo $this->uniqId; ?>', 
                typeCode: '', 
                recordId: '', 
                srcMapId: mapId, 
                selectedRow: ''
            };

//            if ($headerParams.length) {
//                postData.selectedRow = rowParse;
//                postData.recordId = headerRecordId;
//            }

            $.ajax({
                url: "assets/custom/addon/plugins/echarts/echarts.js",
                dataType: "script",
                cache: true,
                async: false
            });                

            $.ajax({
                url: "middleware/assets/js/addon/echartsBuilder.js",
                dataType: "script",
                cache: true,
                async: false
            });                

            $.ajax({
                type: 'post',
                url: 'mdwidget/renderLayoutSection/' + indicatorId,
                data: postData,
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(dataHtml) {
                    var html = [];

                    html.push(dataHtml.html);

                    viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(html.join('')).promise().done(function() {
                        Core.unblockUI();
                    });

                    $.ajax({
                        type: 'post',
                        url: 'mdform/filterKpiIndicatorValueForm',
                        data: {indicatorId: indicatorId, drillDownCriteria: '', filterPosition: 'top', filterColumnCount: '3'},
                        dataType: 'json',
                        success: function(data) {
                            var $filterCol = viewReportProcess_<?php echo $this->uniqId; ?>.find('.kpipage-data-top-filter-col').last();

                            if (data.status == 'success' && data.html != '') {

                                if ($filterCol.length) {

                                    $filterCol.closest('.mv-datalist-container').addClass('mv-datalist-show-filter');
                                    $filterCol.closest('.ws-page-content').removeClass('mt-2');

                                    $filterCol.append(data.html).promise().done(function() {
                                        Core.initNumberInput($filterCol);
                                        Core.initLongInput($filterCol);
                                        Core.initDateInput($filterCol);
                                        Core.initSelect2($filterCol);         
                                    });
                                }

                            }
                        }
                    });                        
                }
            });            

        } else {

            viewReportProcess_<?php echo $this->uniqId; ?>.find(".mv_checklist_render_all").addClass("hidden");
            if (viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).length && viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).html().length) { 
                Core.unblockUI();
                viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).removeClass("hidden");
                viewProcessComment_<?php echo $this->uniqId; ?>.empty();
                return;
            }            

            var recordId = headerRecordId;
            var postData = {
                mapSrcMapId: mapId, 
                mapSelectedRow: '', 
                srcMapId: mapId, 
                /*isIgnoreFilter: 1,*/
                isHideCheckBox: 0, 
                isIgnoreTitle: 1
            };

            $.ajax({
                type: 'post',
                url: 'mdform/indicatorList/' + kpiIndicatorId,
                data: postData, 
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(dataHtml) {
                    $.ajax({
                        type: 'post',
                        url: 'mdform/getIndicatorDescription',
                        data: {
                            indicatorId: indicatorId
                        }, 
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(dataJson) {
                            var html = [];

                            var renderHeader = '<div class="meta-toolbar is-bp-open-">'+
                                '<div class="main-process-text">\n\
                                    <div></div>\n\
                                    <div style="" class="main-process-text-description">'+(dataJson && dataJson.DESCRIPTION ? dataJson.DESCRIPTION : '')+'</div>\n\
                                </div>'+
                            '</div>';

                            html.push(renderHeader);
                            html.push(dataHtml);

                            if (viewProcessWindow_<?php echo $this->uniqId; ?>) {

                                if (!viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).length) {
                                    viewReportProcess_<?php echo $this->uniqId; ?>.append('<div class="mv_checklist_render_all" id="mv_checklist_id_'+indicatorId+'"></div>');
                                }

                                viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).append(html.join('')).promise().done(function() {
                                    if (postData.hasOwnProperty('isComment') && postData.isComment == '1') {

                                        viewProcessComment_<?php echo $this->uniqId; ?>.empty().append('<div style="font-weight: bold;padding: 10px 0 7px 0;">Сэтгэгдэл</div>');

                                        $.ajax({
                                            type: 'post',
                                            url: 'mdwebservice/renderEditModeBpCommentTab',
                                            data: {
                                                uniqId: uniqId, 
                                                refStructureId: jsonObj.mainIndicatorId, 
                                                sourceId: recordId, 
                                                listMetaDataId: indicatorId
                                            },
                                            success: function(data) {
                                                viewProcessComment_<?php echo $this->uniqId; ?>.append(data);
                                                Core.unblockUI();
                                            }
                                        });
                                    } else {
                                        Core.unblockUI();
                                    }
                                });                                    

                            } else {                                         

                                viewReportProcess_<?php echo $this->uniqId; ?>.empty().append(html.join('')).promise().done(function() {

                                    if (viewMode_<?php echo $this->uniqId; ?> == 'view') {
                                        viewReportProcess_<?php echo $this->uniqId; ?>.find('[data-actiontype="update"], [data-actiontype="update"], [data-actiontype="delete"]').remove();
                                    }

                                    if (postData.hasOwnProperty('isComment') && postData.isComment == '1') {

                                        viewProcessComment_<?php echo $this->uniqId; ?>.empty().append('<div style="font-weight: bold;padding: 10px 0 7px 0;">Сэтгэгдэл</div>');

                                        $.ajax({
                                            type: 'post',
                                            url: 'mdwebservice/renderEditModeBpCommentTab',
                                            data: {
                                                uniqId: uniqId, 
                                                refStructureId: jsonObj.mainIndicatorId, 
                                                sourceId: recordId, 
                                                listMetaDataId: indicatorId
                                            },
                                            success: function(data) {
                                                viewProcessComment_<?php echo $this->uniqId; ?>.append(data);
                                                Core.unblockUI();
                                            }
                                        });
                                    } else {
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }
                    });      
                }
            });
        }
    }    

}).bind('loaded.jstree', function (e, data) {
    //$('.kpidv-data-tree-col').find('li').first().find('a').click();
});

$("#indicatorTreeView_17189630753551").jstree({
    "core": {
        "themes": {
            "responsive": true,
            "icons": false
        },
        "check_callback": true,
        "data": {
            "url": function (node) {
                return 'mdform/getAjaxTree';
            },
            "data": function (node) {
                return {
                    'parent': node.id, 
                    'indicatorId' : taskIndicatorId, 
                    'colName' : 'PARENT_ID', 
                    icon: 'far fa-file',
                    criteria: {
                        FILTERID:[
                            {
                                operator: '=',
                                operand: '<?php echo Ue::sessionUserKeyId() ?>'
                            }
                        ]
                    }
                };
            }
        }
    },       
    "types": {
        "default": {
            "icon": "icon-folder2 text-orange-300"
        }
    },
    'search': {
        'case_insensitive': true,
        'show_only_matches' : true
    },        
    "plugins": ["types", "cookies", "search"]
}).bind("select_node.jstree", function (e, data) {
    var nid = data.node.id === 'null' || data.node.id === 'all' ? '' : data.node.id;
    rowDataTreeSidebar = data.node.original ? data.node.original.rowdata : {};
//    $('.kpidv-data-bptree-col').find('li').removeClass('active');
//    $('.kpidv-data-bptree-col').find('li#'+nid).addClass('active');

    var mvTitle = $('.mv-checklist-taskmenu').find('li#'+nid+'>.jstree-anchor').find('.p-row-title').text();

    var strIndicatorId = rowDataTreeSidebar.MAIN_INDICATOR_ID;
    
    if (strIndicatorId == '' || strIndicatorId == null) {
        viewTaskProcess_<?php echo $this->uniqId; ?>.empty().append('Indicator тохируулаагүй байна!');
        return;
    }

    /**
     * 
     * Render metaverse
     */               
    
    var isComment = false;
    var postData = {
        mainIndicatorId: '', 
        structureIndicatorId: strIndicatorId, 
        trgIndicatorId: rowDataTreeSidebar.CRUD_ID, 
        trgIndicatorKpiTypeId: '', 
        typeCode: '', 
        recordId: nid, 
        srcMapId: '', 
        selectedRow: ''
    };
    filterIdCheck4 = nid;

    $.ajax({
        type: 'post',
        url: 'mdform/renderValueMapStructure',
        data: postData,
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(dataHtml) {
            var html = [];

//            var renderHeader = '<div class="meta-toolbar is-bp-open-">'+
//                '<div class="main-process-text">\n\
//                    <div>'+mvTitle+'</div>\n\
//                    <div class="main-process-text-description">'+(dataHtml.indicatorInfo && dataHtml.indicatorInfo.DESCRIPTION ? dataHtml.indicatorInfo.DESCRIPTION : '')+'</div>\n\
//                </div>';
            var sveActionBtn = '';

            if (viewMode_<?php echo $this->uniqId; ?> != 'view') {

                sveActionBtn = '<div class="ml-auto pull-right">';

//                        if (typeof is_pfd != 'undefined' && is_pfd) {
//                            sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-help mr-1" onclick="setHelpContent(this, \''+dataHtml.helpContentId+'\', \''+indicatorId+'\', \'mv_method\');">'+plang.get('set_help_content_btn')+'</button>';
//                        }

//                if (dataHtml.hasOwnProperty('helpContentId') && dataHtml.helpContentId !== null && dataHtml.helpContentId !== '') {
//                    sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-help mr-1" onclick="redirectHelpContent(this, \''+dataHtml.helpContentId+'\', \''+indicatorId+'\', \'mv_method\');">'+plang.get('menu_system_guide')+'</button>';
//                }
                    sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save" onclick="checkList5SaveKpiIndicatorForm(this, \'\', \''+strIndicatorId+'\');"><i class="icon-checkmark-circle2"></i> '+plang.get('save_btn')+'</button>';
                sveActionBtn += '</div>';
            }
            var renderHeader = '<div class="meta-toolbar is-bp-open- d-flex justify-content-between">'+
                '<div class="main-process-text">\n\
                    <div>'+mvTitle+'</div>\n\
                    <div class="main-process-text-description">'+(dataHtml.indicatorInfo && dataHtml.indicatorInfo.DESCRIPTION ? dataHtml.indicatorInfo.DESCRIPTION : '')+'</div>\n\
                </div>'+sveActionBtn;            

            renderHeader += '</div>';

            html.push('<form method="post" enctype="multipart/form-data">');
                html.push(renderHeader);
                html.push(dataHtml.html);
                //html.push(sveActionBtn);
            html.push('</form>');

            if (viewProcessWindow_<?php echo $this->uniqId; ?>) {

                if (!viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).length) {
                    viewReportProcess_<?php echo $this->uniqId; ?>.append('<div class="mv_checklist_render_all" id="mv_checklist_id_'+indicatorId+'"></div>');
                }
                viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).append(html.join('')).promise().done(function() {

                    if (viewMode_<?php echo $this->uniqId; ?> == 'view') {

                        var $render = viewReportProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId);

                        $render.find('.bp-add-one-row').parent().remove();
                        $render.find('.bp-remove-row, button.red, button.bp-btn-save, button.green-meadow, button.bp-file-choose-btn, a[onclick*="bpFileChoosedRemove"], span.filename, a[onclick*="kpiIndicatorRelationRemoveRows"], div.input-group.quick-item-process').remove();
                        $render.find('input[type="text"], textarea').addClass('kpi-notfocus-readonly-input').attr('readonly', 'readonly');
                        $render.find("div[data-s-path]").addClass('select2-container-disabled kpi-notfocus-readonly-input');
                        $render.find('button[onclick*="dataViewSelectableGrid"], button[onclick*="chooseKpiIndicatorRowsFromBasket"]').prop('disabled', true);
                        $render.find('[data-action-name="exportexcel"]').removeClass('d-none');

                        var $radioElements = $render.find("input[type='radio']");
                        if ($radioElements.length) {
                            $radioElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                            $radioElements.closest('.radio').addClass('disabled');
                        }

                        var $checkElements = $render.find("input[type='checkbox']");
                        $checkElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                        $checkElements.closest('.checker').addClass('disabled');
                    }

                    Core.unblockUI();

                });                            

            } else {                           

                viewTaskProcess_<?php echo $this->uniqId; ?>.empty().append(html.join('')).promise().done(function() {

                    if (viewMode_<?php echo $this->uniqId; ?> == 'view') {

                        var $render = viewTaskProcess_<?php echo $this->uniqId; ?>;

                        $render.find('.bp-add-one-row').parent().remove();
                        $render.find('.bp-remove-row, button.red, button.bp-btn-save, button.green-meadow, button.bp-file-choose-btn, a[onclick*="bpFileChoosedRemove"], span.filename, a[onclick*="kpiIndicatorRelationRemoveRows"], div.input-group.quick-item-process').remove();
                        $render.find('input[type="text"], textarea').addClass('kpi-notfocus-readonly-input').attr('readonly', 'readonly');
                        $render.find("div[data-s-path]").addClass('select2-container-disabled kpi-notfocus-readonly-input');
                        $render.find('button[onclick*="dataViewSelectableGrid"], button[onclick*="chooseKpiIndicatorRowsFromBasket"]').prop('disabled', true);
                        $render.find('[data-action-name="exportexcel"]').removeClass('d-none');

                        var $radioElements = $render.find("input[type='radio']");
                        if ($radioElements.length) {
                            $radioElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                            $radioElements.closest('.radio').addClass('disabled');
                        }

                        var $checkElements = $render.find("input[type='checkbox']");
                        $checkElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
                        $checkElements.closest('.checker').addClass('disabled');
                    }

                    if (viewTaskProcess_<?php echo $this->uniqId; ?>.find(".sectiongidseperatorcontent-container").length) {
                        viewTaskProcess_<?php echo $this->uniqId; ?>.find(".meta-toolbar").hide();
                    }

                    Core.unblockUI();

                });
            }
        }
    }); 

}).bind('loaded.jstree', function (e, data) {
    //$('.kpidv-data-tree-col').find('li').first().find('a').click();
});

var $checkListTabLink = $('#mv-checklist-render-parent-<?php echo $this->uniqId; ?>').find('.mv-checklist-tab-link.active');
if ($checkListTabLink.length == 1) {
    var $selTb = $('#mv-checklist-render-parent-<?php echo $this->uniqId; ?>').find('.mv-checklist-tab > .tab-content');    
    var $selTbLength = $selTb.length;
    if ($selTbLength == 1) {
        var $tabPane = $selTb.find('> .tab-pane.active');
        var $tabInsideMenu = $tabPane.find('li.nav-item:not(.d-none) > .mv_checklist_02_sub.nav-link'), tabInsideMenuLength = $tabInsideMenu.length;
        
        if (tabInsideMenuLength == 1) {
            $tabPane.find('> .d-flex > .sidebar').hide();
            setTimeout(function () {
                $tabPane.find('> .d-flex > .w-100').css('max-width', '');
            }, 300);
        }
    } 
}
$(window).trigger("resize");

<?php
if ($renderType == 'paper_main_window') {
?>
    $(function() {
        $('#mv-checklist-render-parent-<?php echo $this->uniqId; ?>').find('.content-wrapper-paper_main_window').css("max-width", $(window).width() - 300);
    });
<?php
}
?>
function checklistCloseDialog (elem) {
    $(elem).closest(".ui-dialog-content").dialog('close');
}
function checkList5SelectRowDv1719369128320017 (elem) {
    var rowData = $(elem).data('row-data');
    _tempRowDataMetaDataview = rowData;
}
function checkList5SelectRowDvAcceptBtn1719369128320017 () {
    if (!Object.keys(_tempRowDataMetaDataview).length) {
        alert('Мөрөө сонгоно уу!');
        return;        
    }
    _rowDataMetaDataview = _tempRowDataMetaDataview;
    var row = _rowDataMetaDataview;
    $getCont = $('.hawtast-hereg-container');
    $getCont.find('div[data-path="c5"]').text(row.c5 ? row.c5 : '-');
    $getCont.find('div[data-path="c18"]').text(row.c18 ? row.c18 : '-');
    $getCont.find('div[data-path="c2"]').text(row.c2 ? row.c2 : '-');
    $getCont.find('div[data-path="c3"]').text(row.c3 ? row.c3 : '-');
    $getCont.find('div[data-path="c9"]').text(row.c9 ? row.c9 : '-');
    $getCont.find('div[data-path="c4"]').text(row.c4 ? row.c4 : '-');
    $getCont.find('div[data-path="c7"]').text(row.c7 ? row.c7 : '-');
    $getCont.find('div[data-path="c8"]').text(row.c8 ? row.c8 : '-');
    $getCont.find('span[data-path="wfmstatusname"]').text(row.wfmstatusname);
    $getCont.find('span[data-path="wfmstatusname"]').css('background-color', row.wfmstatuscolor);
    $getCont.find('.hawtast-hereg-data-img').css('background-color', row.wfmstatuscolor);
    if (row.icon) {
        $getCont.find('img[data-path="icon"]').attr('src', row.icon);
    } else {
        $getCont.find('img[data-path="icon"]').attr('src', 'assets/core/global/img/user.png');        
    }
    $('.hawtast-hereg-data').show();
    $('.empty-data-text').hide();
}
function checkList5SaveKpiIndicatorForm(elem) {
    var $this = $(elem);
    var $form = $this.closest('form');
    var uniqId = $form.find('[data-bp-uniq-id]').attr('data-bp-uniq-id');  

    if (bpFormValidate($form) && window['kpiIndicatorBeforeSave_' + uniqId]($this)) {
        
        var $parent = $this.closest('.mv-checklist-render-parent');
        var $active = $parent.find('ul.nav-sidebar a.nav-link.active[data-json]');
        
        $form.ajaxSubmit({
            type: 'post',
            url: 'mdform/saveKpiDynamicDataByList',
            dataType: 'json',
            beforeSubmit: function(formData, jqForm, options) {
                var $headerParams = $parent.find('input[data-path="headerParams"]');
                var $inputLogId = $parent.find('input[data-path="endToEndLogHdrId"]');
                var headerRecordId = $parent.find('input[data-path="headerRecordId"]').val();
                
                formData.push({name: 'mapHidden[recordId]', value: headerRecordId});
                formData.push({name: 'mapHidden[params]', value: $active.attr('data-hidden-params')});
                formData.push({name: 'mapHidden[selectedRow]', value: $headerParams.val()});
                
                if ($inputLogId.length) {
                    var rowJson = JSON.parse(html_entity_decode($active.attr('data-json'), 'ENT_QUOTES'));
                    formData.push({name: 'endToEndLog[hdrId]', value: $inputLogId.val()});
                    formData.push({name: 'endToEndLog[stepIndicatorId]', value: rowJson.indicatorId});
                    formData.push({name: 'endToEndLog[recordId]', value: headerRecordId});
                }
            },
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {

                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false, 
                    addclass: pnotifyPosition
                });

                if (data.status == 'success') {
                    window['kpiIndicatorAfterSave_' + uniqId]($this, data.status, data);
                    
                    if (data.hasOwnProperty('rowId')) {
                        $form.find('input[name="sf[ID]"]').val(data.rowId);
                    }
                    
//                    if (data.hasOwnProperty('result')) {
//                        var dataResult = data.result;
//
//                        if (dataResult.hasOwnProperty('checkListStatus') && dataResult.checkListStatus != '') {
//                            if (dataResult.checkListStatus == 'done') {
//                                $active.find('i:eq(0)').removeClass('far fa-square').addClass('fas fa-check-square');
//                            } else {
//                                $active.find('i:eq(0)').removeClass('fas fa-check-square').addClass('far fa-square');
//                            }
//                        }
//                    }
//                    
//                    $active.trigger('click');
                    Core.unblockUI();
                } else {
                    Core.unblockUI();
                }
            }
        });
    }
}
function checklist5AddTaskBtn() {
    $.ajax({
        type: 'post',
        url: 'mdform/kpiIndicatorTemplateRender',
        data: {
            param: {
                indicatorId: '17149687280013', 
                crudIndicatorId: '196030286'
            }
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(dataHtml) {
            var html = [];

            var sveActionBtn = '';            
                sveActionBtn = '<div style="">';

            if (dataHtml.hasOwnProperty('helpContentId') && dataHtml.helpContentId !== null && dataHtml.helpContentId !== '') {
                sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-help mr-1" onclick="redirectHelpContent(this, \''+dataHtml.helpContentId+'\', \''+indicatorId+'\', \'mv_method\');">'+plang.get('menu_system_guide')+'</button>';
            }
                sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save" onclick="checkList5SaveKpiIndicatorForm(this);"><i class="icon-checkmark-circle2"></i> '+plang.get('save_btn')+'</button>';
            sveActionBtn += '</div>';
            var renderHeader = '<div class="meta-toolbar is-bp-open- d-flex justify-content-between">'+
                '<div class="main-process-text">\n\
                    <div>'+dataHtml.name+'</div>\n\
                    <div class="main-process-text-description">'+(dataHtml.indicatorInfo && dataHtml.indicatorInfo.DESCRIPTION ? dataHtml.indicatorInfo.DESCRIPTION : '')+'</div>\n\
                </div>'+sveActionBtn;

            renderHeader += '</div>';

            html.push('<form method="post" enctype="multipart/form-data">');
                html.push(renderHeader);
                html.push(dataHtml.html);
            html.push('</form>');            

            viewTaskProcess_<?php echo $this->uniqId; ?>.empty().append(html.join('')).promise().done(function() {
                Core.unblockUI();
            });                   
        }
    });       
}
</script>