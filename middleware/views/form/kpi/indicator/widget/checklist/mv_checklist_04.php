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
        <div class="row mv-checklist-render-parent mv-checklist2-render-parent" id="mv-checklist-render-parent-<?php echo $this->uniqId; ?>">

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
                                <div class="card-body mv-checklist-menu kpidv-data-tree-col">
                                    <div id="indicatorTreeView_17187610152661" data-indicatorid="17187610152661" class="tree-demo mt-1" style="overflow-x: hidden;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 checklist2-content-section content-wrapper-<?php echo $renderType ?>" style="background-color: #f4f4f4; max-width: 1205px">
                        <div>
                            <div class="content-wrapper pt-2 pl-3 pr-3 pb-0 mv-checklist-render">        
                            </div>                
                        </div>                
                        <div class="mv-checklist-render-comment pl-3 pr-3">
                        </div>                
                        <div class="mv-checklist4-render-relation pl-2 pr-2">
                        </div>                
                    </div>                
                </div>                
            <?php 
                $tabId ++;      
            ?>                  
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
.mv-checklist2-render-parent .mv-checklist-render-comment .media-body .p-2.pb3 {
    background-color: #eee !important;
}   
.mv-checklist2-render-parent .mv-checklist-render-comment .media-list .border-gray {
    border-color: transparent !important;
}   
.mv-checklist2-render-parent .mv-checklist-render-comment .dialog-chat .avatar {
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
.mv-checklist2-render-parent .jeasyuiTheme3 {
    padding-bottom: 15px;
}
.mv-checklist2-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-icon,
.mv-checklist2-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-group-btn:first-child {
    display: none;
}
.mv-checklist2-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-group-btn:last-child button {
    color: #252F4A;
    background-color: #eee;
    padding: 0px 5px 0px 5px !important;
}
.mv-checklist2-render-parent .mv-checklist-render .quick-item-process.bp-add-ac-row .input-group-btn:last-child button i:before {
    content: "Сонгох";
    font-family: Arial, Helvetica, sans-serif;
}
.mv-checklist2-render-parent .jeasyuiTheme3 .datagrid-htable .datagrid-header-row:not(.datagrid-filter-row) {
    height: 35px !important;
}
.mv-checklist2-render-parent .jeasyuiTheme3 .datagrid-header .datagrid-cell span, 
.mv-checklist2-render-parent .jeasyuiTheme3 .datagrid-view .datagrid-cell-group {
    font-size: 12px;
    font-weight: 700;
    color: #99A1B7;
}
.mv-checklist2-render-parent .datagrid-header td, 
.mv-checklist2-render-parent .datagrid-body td, 
.mv-checklist2-render-parent .datagrid-footer td {
    border-color: transparent;
}
.mv-checklist2-render-parent .panel-header-eui, 
.mv-checklist2-render-parent .panel-body-eui {
    border-color: transparent;
}
.mv-checklist2-render-parent .datagrid-pager {
    border-color: transparent;
}
.mv-checklist2-render-parent .datagrid-row-alt:not(.datagrid-row-over) {
    background: transparent;
}
.mv-checklist2-render-parent .jeasyuiTheme3 .datagrid-header td {
    background: #eee !important;
    border-style: solid;
    border-color: transparent;
}
.mv-checklist2-render-parent .mv-checklist-render .meta-toolbar {
    border-bottom: none;
    margin-top: 0;
}
.mv-checklist2-render-parent .mv-checklist-render .meta-toolbar .main-process-text {
    font-size: 12px;
} 
.mv-checklist2-render-parent .mv-checklist-main-render .meta-toolbar {
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
.mv-checklist2-render-parent .mv-checklist-main-render .meta-toolbar .main-process-text {
    /*display: none;*/
    font-size: 12px;
}    
.mv-checklist2-render-parent .bp-add-one-row-num {
    display: none;
}    
.mv-checklist2-render-parent .mv-checklist-render .mv-add-row-actions {
    margin-top: 0 !important;
}    
.mv-checklist2-render-parent .nav-group-sub-mv-opened .nav-group-sub {
    display: block;
}
.mv-checklist2-render-parent .nav-sidebar .nav-item:not(.nav-item-header):last-child {
    padding-bottom: 0 !important;
}
.mv-checklist2-render-parent .nav-item-submenu.nav-group-sub-mv-opened>.nav-link:after {
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);
}
.mv-checklist2-render-parent .nav-group-sub .nav-link {
    padding-left: 20px;
}
.mv-checklist2-render-parent .mv-checklist-render button.bp-add-one-row,
.mv-checklist2-render-parent .mv-checklist-render button.btn-xs.btn-outline-success,
.mv-checklist2-render-parent .mv-checklist-render button.btn-xs.green-meadow {
    background-color: #eee !important;
    color: #252F4A;
    font-size: 12px;
    padding: 0px 5px 0px 5px;
    height: 21px !important;
    min-height: 21px !important;    
    border-color: #eee !important;
}    
.mv-checklist2-render-parent .main-process-text-description {
    color: #99A1B7;
    text-transform: none;
    font-weight: normal;
    font-size: 11px;    
}    
.mv-checklist2-render-parent .mv-checklist-render button.bp-add-one-row:hover,
.mv-checklist2-render-parent .mv-checklist-render .bp-add-ac-row button:hover {
    background-color: #1B84FF !important;
    color: #fff !important;
}    
.nav-item-submenu>.nav-link.mv_checklist_02_groupname:after {
    margin-top: -6px;
}
.mv-checklist2-render-parent {
    margin: 20px -15px 0px -20px!important;
}
.mv-checklist2-render-parent button.bp-btn-save i {
    display: none;
}
.mv-checklist2-render-parent button.bp-btn-save, 
.mv-checklist2-render-parent button.bp-btn-check, 
.mv-checklist2-render-parent button.bp-btn-saveadd, 
.mv-checklist2-render-parent button.bp-btn-help,
.mv-checklist2-render-parent .meta-toolbar button.bp-btn-help {
    color: #1B84FF!important;
    border-color: #1B84FF!important;
    padding-left: 18px!important;
    padding-right: 18px!important;
    /*background-color: #1B84FF!important;*/
    padding-bottom: 2px !important;
    font-size: 12px!important;
}
.mv-checklist2-render-parent button.bp-btn-save:hover, 
.mv-checklist2-render-parent button.bp-btn-saveadd:hover, 
.mv-checklist2-render-parent button.bp-btn-help:hover,
.mv-checklist2-render-parent .meta-toolbar button.bp-btn-help:hover {
    background-color: #1B84FF!important;
}
.mv-checklist2-render-parent .mv-rows-title:not(.mv-rows-title-label) {
    display: none;
}
.mv-checklist2-render-parent .mv-rows-title-label {
    color: rgba(51,51,51,.8);
}
.mv-checklist2-render-parent > .sidebar {
    width: 16.875rem;
    padding: 0;
    background-color: rgb(243, 244, 246);
}
.mv-checklist2-render-parent > .sidebar .sidebar-content {
    padding: 15px 10px;
}
.mv-checklist2-render-parent .sidebar-light .nav-sidebar .nav-item>.nav-link {
    text-transform: none;
}
.mv-checklist2-render-parent .sidebar-light .nav-sidebar .nav-item>.nav-link:not(.mv_card_status_widget).active {
    background-color: #1b84ff54;
}
.mv-checklist2-render-parent .mv-checklist-title {
    color: #3C3C3C;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 700;
}
.mv-checklist2-render-parent .mv-checklist-description {
    color: #67748E;
    margin-top: 10px;
}
.mv-checklist2-render-parent > .sidebar > .sidebar-content > .card > .card-body .step {
    background: #A0A0A0;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
.mv-checklist2-render-parent > .sidebar > .sidebar-content > .card > .card-body .step.active {
    background: #468CE2;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
.mv-checklist2-render-parent .mv-checklist-menu {
    height: 100%;
    min-height: 100vh;
    padding: 0;
    /*margin-left: -5px;*/
    /*margin-right: -10px;*/
    overflow: auto;
}
.mv-checklist2-render-parent .mv-checklist-menu:not(.mv-checklist-card-menu) li {
    width: 100%;
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link {
    display: flex;
    align-items: center;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    padding: 10px 22px 10px 10px;
    overflow: hidden;
    font-size: 12px;
    text-transform: none;
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link:hover {
    background-color: #E8EBF0;
    color: #468CE2;
}
.mv-checklist2-render-parent .sidebar-light {
    border-right: none;
}
.mv-checklist2-render-parent .kpi-ind-tmplt-section {
    background-color: #fff;
    padding-top: 10px;
    padding-bottom: 0px;
    margin-bottom: 0px;
}
.mv-checklist2-render-parent .sectiongidseperatorcontent legend {
    padding: 12px !important;
    padding-left: 34px !important;
}
.mv-checklist2-render-parent .sectiongidseperator {
    height: 15px;
    background-color: rgb(244, 244, 244);
    width: 100%;
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link i {
    font-size: 18px;
    margin-right: 10px;
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link span {
    font-size: 12px;
    font-weight: 600;
}
.mv-checklist2-render-parent > .sidebar .card-body .nav-sidebar a.nav-link.active {
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
.mv-checklist2-render-parent .kpi-form-paper-portrait .tabbable-line>.nav-tabs>li.open, 
.mv-checklist2-render-parent .kpi-form-paper-portrait .tabbable-line>.nav-tabs>li a:hover {
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
.mv-checklist2-render-parent .mv-hdr-label-control-label:not(.type-check), 
.mv-checklist2-render-parent .mv-hdr-label-control-input:not(.type-check) {
    max-width: 100%;
}
/*.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control):not(.type-check),*/ 
.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-row,
.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-label:not(.type-check), 
.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-input:not(.type-check) {
    display: block;
}
.mv-checklist2-render-parent .mv-hdr-label-control, 
.mv-checklist2-render-parent .mv-hdr-label-control-row,
.mv-checklist2-render-parent .mv-hdr-label-control-label, 
.mv-checklist2-render-parent .mv-hdr-label-control-input {
    border: none;
    background-color: #fff;
}
.mv-checklist2-render-parent .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-label:not(.type-check) {
    width: 100%!important;
    padding-bottom: 8px;
}
.mv-checklist2-render-parent .mv-hdr-label-control-label {
    text-align: left;
    font-weight: bold;
}
.mv-checklist2-render-parent .mv-rows-title-label {
    font-size: 14px;
    color: #555;
    padding-left: 32px;
}
.mv-checklist2-render-parent .mv-hdr-label-control-label label {
    color: #666;
}
.mv-checklist2-render-parent .kpidv-data-tree-col .list-group {
    background-color:transparent;
}
.mv-checklist2-render-parent .mv-hdr-label-control-label label .label-colon {
    display: none;
}
.ui-dialog .mv-checklist2-render-parent .ws-area .ws-page-content-wrapper .ws-page-content {
    padding: 0px!important;
}
.mv-checklist2-render-parent .mv-hdr-label-control-input .form-control {
    height: 32px!important;
    min-height: 32px!important;
    border: 1px #f3f3f3 solid;
    /*padding: 7px 10px!important;*/
}
.mv-checklist2-render-parent .mv-hdr-label-control-input textarea.form-control {
    border-radius: 6px!important;
    border: 1px #eee solid;
    /*padding: 7px 10px!important;*/
}
.mv-checklist2-render-parent .mv-hdr-label-control {
    margin-bottom: 10px;
    padding-left: 25px;
    padding-right: 25px;    
}
.mv-checklist2-render-parent .mv-hdr-label-control-input .form-control .select2-choice,
.mv-checklist2-render-parent .mv-hdr-label-control-input .form-control.select2-container-active .select2-choice,
.mv-checklist2-render-parent .mv-hdr-label-control-input .form-control.select2-container-active .select2-choices {
    border: 1px #eee solid;
    height: 32px;
    padding-top: 2px;
}
.mv-checklist2-render-parent .mv-checklist-render div[data-meta-type="process"] .table-scrollable>.table, 
.mv-checklist2-render-parent .mv-checklist-render div[data-meta-type="process"] .tabbable-line>.tab-content {
    background-color: transparent;
}
.mv-checklist2-render-parent .mv-checklist-render div[data-meta-type="process"] .tabbable-line>.nav-tabs {
    border: none;
    margin: 0px;
    background: transparent;
}
.mv-checklist2-render-parent .viewer-container > .center-sidebar > .row > .content-wrapper > .row, 
.mv-checklist2-render-parent .viewer-container > .center-sidebar > .row > .top-sidebar-content > .xs-form.row {
    margin: 0;
}
.mv-checklist2-render-parent .render-object-viewer > .row > .col-md-12 > .viewer-container > .mv-datalist-show-filter > .row {
    margin-left: 0;
}
.mv-checklist2-render-parent .mv-checklist-render div[data-meta-type="process"] .bp-header-param ul.bp-icon-selection {
    max-height: 360px;
}
.mv-checklist2-render-parent .row > .col-md-2 > .mv-hdr-label-control > .mv-hdr-label-control-input > .dateElement {
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
.mv-checklist2-render-parent .sidebar.sidebar-dark {
    background-color: #a8bba4;
}
.mv-checklist2-render-parent .sidebar.sidebar-dark .kpidv-data-tree-col .jstree-default .jstree-custom-folder-icon.jstree-closed>.jstree-ocl, 
.mv-checklist2-render-parent .sidebar.sidebar-dark .kpidv-data-tree-col .jstree-default .jstree-custom-folder-icon.jstree-open>.jstree-ocl {
    color: #fad45f;
}
.mv-checklist2-render-parent .sidebar.sidebar-dark .kpidv-data-tree-col .mv-tree-filter-icon {
    color: #dadada;
    margin-right: 3px;
}
.mv-checklist2-render-parent .sidebar.sidebar-dark .kpidv-data-tree-col .nameField {
    padding-right: 40px;
    padding-left: 3px;
}
.mv-checklist2-render-parent .sidebar.sidebar-dark .kpidv-data-tree-col .p-row-title {
    color: #000;
    font-weight: bold;
    font-size: 13px;
    padding-left: 0;
}
.mv-checklist4-render-relation .indicatorView {
    margin-left: .625rem;
    margin-right: .625rem;    
    padding-right: 0;
    padding-left: 0;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation .viewer-container {
    padding-right: 0;
    padding-left: 0;    
}
.mv-checklist4-render-relation .center-sidebar.content {
    padding-left: 10px !important;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation .main-dataview-container {
    padding-top: 0;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation .mv_tiny_card_with_list_widget_main {
    background-color: #fff;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation .main-dataview-container .dv-right-tools-btn {
    display: none;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation .package-tab-name {
    font-size: 13px;
    border-bottom: none;
    margin-top: 0;
    margin-bottom: 0;
    margin-left: 8px;    
}
.mv-checklist2-render-parent .mv-checklist4-render-relation .explorer-table-cell {
    background-color: #fff !important;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation ul.dv-explorer8 {
    background-color: #fff !important;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation ul.dv-explorer8 > li {
    border: none;
    box-shadow: none;
    width: 145px;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation ul.dv-explorer8 > li .dv-img-container .dv-img-container-sub .dv-directory-img {
    width: 65px;
    height: 65px;
    margin-top: 18px;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation ul.dv-explorer8 > li .first-title {
    display: none;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation ul.dv-explorer8 > li:hover {
    background-color: transparent;
}
.mv-checklist2-render-parent .mv-checklist4-render-relation .kpi-ind-tmplt-section {
    margin-right: .625rem;
    margin-left: .625rem;
}
</style>

<script type="text/javascript">
var viewProcessWindow_<?php echo $this->uniqId; ?> = false;
var viewMode_<?php echo $this->uniqId; ?> = '';
var $checkList_<?php echo $this->uniqId; ?> = $('#mv-checklist-render-parent-<?php echo $this->uniqId; ?>');
var viewProcess_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.mv-checklist-render:visible');
var indicatorId = $("#indicatorTreeView_17187610152661").data('indicatorid');
var filterIdCheck4 = '';
var rowDataTreeSidebar = {};

var wcontw = $('.mv-checklist2-render-parent').width() - 300;
$('.mv-checklist2-render-parent').find('.checklist2-content-section').css('max-width', wcontw+'px'); 

$("#indicatorTreeView_17187610152661").jstree({
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
                                operand: '<?php echo $this->indicatorId; ?>'
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
    $('.kpidv-data-tree-col').find('li').removeClass('active');
    $('.kpidv-data-tree-col').find('li#'+nid).addClass('active');
    var mvTitle = $('.kpidv-data-tree-col').find('li#'+nid+'>.jstree-anchor').find('.p-row-title').text();
//    if ($treeFilterLi.hasClass('active')) {
//        $treeFilterLi.removeClass('active');
//        $treeFilterLi.find('.mv-tree-filter-icon').removeClass('fas fa-check-square').addClass('far fa-square');
//    } else {
//        $treeFilterLi.addClass('active');
//        $treeFilterLi.find('.mv-tree-filter-icon').removeClass('far fa-square').addClass('fas fa-check-square');
//    }        
//    filterKpiIndicatorValueGrid($treeFilterLi);

    var strIndicatorId = 17187615200661;
    /**
     * 
     * Render bp
     */
    
//    var metaDataId = 16413780044111;
//    var jsonObj = {};
//    $.ajax({
//        type: 'post',
//        url: 'mdwebservice/callMethodByMeta',
//        data: {
//            metaDataId: metaDataId,
//            isDialog: false, 
//            isHeaderName: true, 
//            isBackBtnIgnore: 1, 
//            isIgnoreSetRowId: 1, 
//            kpiIndicatorMapConfig: jsonObj, 
//            fillDataParams: "id=" + strIndicatorId + "&defaultGetPf=1",
//            callerType: 'dv', 
//            openParams: '{"callerType":"dv","afterSaveNoAction":true}'
//        },
//        dataType: 'json',
//        beforeSend: function() {
//            Core.blockUI({message: 'Loading...', boxed: true});
//        },
//        success: function(data) {
//            if (viewProcessWindow_<?php echo $this->uniqId; ?>) {
//                if (!viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).length) {
//                    viewProcess_<?php echo $this->uniqId; ?>.append('<div class="mv_checklist_render_all" id="mv_checklist_id_'+metaDataId+'"></div>');
//                }
//                viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).append(data.Html).promise().done(function () {
//                    viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).find('.bp-btn-back, .bpTestCaseSaveButton').remove();
//                    viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).find('.meta-toolbar').addClass('not-sticky');
//                    viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).addClass('bp-render-checklist');
//
//                    var $saveAddBtn = viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId).find('.bp-btn-saveadd:visible');
//                    if ($saveAddBtn.length) {
//                        $saveAddBtn.text(plang.get('save_btn'));
//                        viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-save').remove();
//                    }
//
//                    Core.initBPAjax(viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+metaDataId));
//                    Core.unblockUI();
//                });                            
//
//            } else {                        
//
//                viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
//                    viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-back, .bpTestCaseSaveButton').remove();
//                    viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar').addClass('not-sticky');
//                    viewProcess_<?php echo $this->uniqId; ?>.addClass('bp-render-checklist');
//
//                    var $saveAddBtn = viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-saveadd:visible');
//                    if ($saveAddBtn.length) {
//                        $saveAddBtn.text(plang.get('save_btn'));
//                        viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-save').remove();
//                    }
//
//                    Core.initBPAjax(viewProcess_<?php echo $this->uniqId; ?>);
//                    Core.unblockUI();
//                });
//            }
//        },
//        error: function() { alert('Error'); Core.unblockUI(); }
//    });    
//    return;

    /**
     * 
     * Render metaverse
     */               
    
    var isComment = false;
    var postData = {
        mainIndicatorId: '', 
        structureIndicatorId: strIndicatorId, 
        trgIndicatorId: 197235546, 
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

            var sveActionBtn = '';

            if (viewMode_<?php echo $this->uniqId; ?> != 'view') {

                sveActionBtn = '<div style="">';

//                if (typeof is_pfd != 'undefined' && is_pfd) {
//                    sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-help mr-1" onclick="setHelpContent(this, \''+dataHtml.helpContentId+'\', \''+indicatorId+'\', \'mv_method\');">'+plang.get('set_help_content_btn')+'</button>';
//                }

                if (dataHtml.hasOwnProperty('helpContentId') && dataHtml.helpContentId !== null && dataHtml.helpContentId !== '') {
                    sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-help mr-1" onclick="redirectHelpContent(this, \''+dataHtml.helpContentId+'\', \''+indicatorId+'\', \'mv_method\');">'+plang.get('menu_system_guide')+'</button>';
                }
                    sveActionBtn += '<button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save" onclick="checkListSaveKpiIndicatorForm(this, \'\', \''+strIndicatorId+'\');"><i class="icon-checkmark-circle2"></i> '+plang.get('save_btn')+'</button>';
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
                //html.push(sveActionBtn);
                html.push(dataHtml.html);
            html.push('</form>');

            if (viewProcessWindow_<?php echo $this->uniqId; ?>) {

                if (!viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).length) {
                    viewProcess_<?php echo $this->uniqId; ?>.append('<div class="mv_checklist_render_all" id="mv_checklist_id_'+indicatorId+'"></div>');
                }
                viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId).append(html.join('')).promise().done(function() {

                    if (viewMode_<?php echo $this->uniqId; ?> == 'view') {

                        var $render = viewProcess_<?php echo $this->uniqId; ?>.find("#mv_checklist_id_"+indicatorId);

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

                viewProcess_<?php echo $this->uniqId; ?>.empty().append(html.join('')).promise().done(function() {

                    if (viewMode_<?php echo $this->uniqId; ?> == 'view') {

                        var $render = viewProcess_<?php echo $this->uniqId; ?>;

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

                    if (viewProcess_<?php echo $this->uniqId; ?>.find(".sectiongidseperatorcontent-container").length) {
                        viewProcess_<?php echo $this->uniqId; ?>.find(".meta-toolbar").hide();
                    }

                    if (isComment == '1' && postData.hasOwnProperty('recordId')) {

                        viewProcessComment_<?php echo $this->uniqId; ?>.empty().append('<div style="font-weight: bold;padding: 10px 0 7px 0;">Сэтгэгдэл</div>');

                        $.ajax({
                            type: 'post',
                            url: 'mdwebservice/renderEditModeBpCommentTab',
                            data: {
                                uniqId: uniqId, 
                                refStructureId: jsonObj.mainIndicatorId, 
                                sourceId: postData.recordId, 
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
            
            $(".mv-checklist4-render-relation").empty().append('Loading relation...');
            
            $.ajax({
                type: 'post',
                url: 'mdform/checkList4RelationTab/'+nid,
                data: {
                    rowData: rowDataTreeSidebar
                },
                dataType: 'json',
                success: function(data) {
                    $(".mv-checklist4-render-relation").empty().append(data.html).promise().done(function() {
                        var $selTb = $(".mv-checklist4-render-relation").find(".mv-checklist-tab-link");
                        $selTb.first().trigger('click');                      
                    });                 
                }
            });            
        }
    });    

}).bind('loaded.jstree', function (e, data) {
    $('.kpidv-data-tree-col').find('li#1591184045140').click();
    $('.kpidv-data-tree-col').find('li').first().find('a').click();
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

$('#mv-checklist-render-parent-<?php echo $this->uniqId; ?>').on('shown.bs.tab', '.mv-checklist4-tab > ul.nav-tabs > li > a', function() {
    var $tabPane = $($(this).attr('href'));
    var indicatorId333  = $tabPane.data('id'), 
        indicatorMapId = $tabPane.data('mapid'),
        indicatorTypeId = $tabPane.data('type-id');
    var postData = {
        //isHideCheckBox: 0, 
        isIgnoreTitle: 1,
        isIgnoreFilter: 1,
        mIndicatorId: filterIdCheck4,
        drillDownCriteria: 'FILTERID='+filterIdCheck4
    };
    
    var relationMappingConfig = $.ajax({
        type: "post",
        url: "mdform/relationParamMapping",
        data: {
            mapId: indicatorMapId,
            rowData: rowDataTreeSidebar,
            dvId: indicatorId333,
        },
        dataType: "json",
        async: false,
        success: function (data) {
          return data;
        }
    });    
    
    relationMappingConfig = relationMappingConfig.responseJSON;
    _processAddonParam['addonJsonParam'] = relationMappingConfig.bp

    if (indicatorTypeId == '16641793815766') {
        $.ajax({
            type: 'post',
            url: 'mdform/indicatorList/' + indicatorId333,
            data: postData, 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(dataHtml) {
                $tabPane.empty().append(dataHtml).promise().done(function() {
                    Core.unblockUI();
                });   
            }
        });    

    } else if (indicatorTypeId == '200101010000016') {
        $.ajax({
            type: 'post',
            //url: "mdobject/dataValueViewer",
            url: 'mdobject/dataview/' + indicatorId333 + '/' + 'false'+ '/json',
            dataType: "json",
            data: {
//              metaDataId: indicatorId333,
//              viewType: "detail",
              //dataGridDefaultHeight: $(window).height() - 190,
              uriParams: relationMappingConfig.dv,
              ignorePermission: 1
            },
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $tabPane.empty().append('<div class="pl-2 pr-2" id="object-value-list-'+indicatorId333+'">' + data.Html + "</div>").promise().done(function() {
                    $tabPane.find('.meta-toolbar').parent().remove();
                    Core.unblockUI();
                });   
            }
        });    
        
    } else if (indicatorTypeId == '200101010000033') {
        $.ajax({
            type: 'post',
            url: 'mdobject/package/' + indicatorId333,
            data: {
                uriParams: relationMappingConfig.dv
            },
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $tabPane.empty().append('<div class="pl-2 pr-2">' + data + "</div>").promise().done(function() {
                    Core.unblockUI();
                });   
            }
        });    
        
    } else if (indicatorTypeId == '2008') {
        
        $.ajax({
            type: 'post',
            url: 'mdform/kpiIndicatorTemplateRender',
            data: {param: {
                indicatorId: '17189657291331', 
                crudIndicatorId: indicatorId333, 
                idField: 'SRC_RECORD_ID', 
                id: rowDataTreeSidebar.ID
            }},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(dataHtml) {
                var html = [];

                var sveActionBtn = '';

                html.push('<form method="post" enctype="multipart/form-data">');
                    //html.push(sveActionBtn);
                    html.push(dataHtml.html);
                html.push('</form>');
                
                $tabPane.empty().append(html.join('')).promise().done(function() {
                    Core.unblockUI();
                });                   
            }
        });    
        
    } else {
        
        $.ajax({
            type: 'post',
            url: 'mdform/indicatorRender/' + indicatorId333,
            data: postData, 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(dataHtml) {
                $tabPane.empty().append(dataHtml).promise().done(function() {
                    Core.unblockUI();
                });   
            }
        });          
    }
});

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
</script>