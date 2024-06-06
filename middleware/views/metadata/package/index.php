<?php
if ($this->isAjax == false) {
    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li>
                    <a href="#app_tab_<?php echo $this->metaDataId; ?>" class="active" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->pageTitle; ?><span><i class="fa fa-times-circle"></i></span></a>
                </li>
            </ul>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="app_tab_<?php echo $this->metaDataId; ?>">
                    <div id="package-meta-<?php echo $this->metaDataId; ?>" class="package-meta <?php echo issetParam($this->packageClass); ?>" data-packageid="<?php echo $this->metaDataId; ?>" data-package-rendertype="<?php echo $this->row['RENDER_TYPE']; ?>">
                        <?php echo $this->packageTabs; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
<?php
    } else {
?>
<div class="col-md-12" id="package-meta-<?php echo $this->metaDataId; ?>">
    <div class="card light">
        <?php echo $this->packageTabs; ?>
    </div>
</div>
<div class="clearfix w-100"></div>
<?php
    }
} else {
?>
<div id="package-meta-<?php echo $this->metaDataId; ?>" class="package-meta <?php echo issetParam($this->packageClass); ?>" data-packageid="<?php echo $this->metaDataId; ?>" data-package-rendertype="<?php echo $this->row['RENDER_TYPE']; ?>">
    <?php echo $this->packageTabs; ?>
</div>
<?php 
} 
?>

<style type="text/css">
.khan-package { 
    background: #FFF !important;
}
.khan-package .sidebar-right-visible{ 
    margin: 0 !important;
}
.khan-package .content {
    background: #FFF !important;
    padding: 0 15px 0 15px;
}
.khan-package .xs-form .input-group-btn > .btn, 
.khan-package .xs-form .input-group-append > .btn, 
.khan-package .xs-form .input-group-prepend > .btn{
    padding: 4px 6px 3px 6px;
    line-height: 23px;
    border-radius: 0;
}
.khan-package .nav-tabs {
    display:none;
}
.khan-package .table-toolbar .row .dv-process-buttons {
    display: inherit !important;
}
.khan-package .datagrid-pager {
    border-top: 1px solid rgb(2, 166, 105);
    height: 36px;
}
.khan-package .datagrid-empty {
    background: none !important;
}
.khan-package .jeasyuiTheme7 .datagrid-header .datagrid-cell span {
    color: rgb(2, 166, 105);
    font-weight: normal;
    text-transform: uppercase;
}
.khan-package .jeasyuiTheme7 .datagrid-header td.datagrid-header-over {
    background: #e3fdff;
}
.khan-package .jeasyuiTheme7 .datagrid-header td, 
.khan-package .jeasyuiTheme7 .datagrid-body td, 
.khan-package .jeasyuiTheme7 .datagrid-footer td {
    border: none !important;
}
.khan-package .jeasyuiTheme7 .datagrid-header td {
    border-bottom: 1px solid #e3fdff !important; 
}
.khan-package .jeasyuiTheme7 .pagination-info { 
    color: rgb(2, 166, 105);
    font-size: 12px;
}
.khan-package .jeasyuiTheme7 .datagrid-filter-row {
    display: none;
}
.khan-package .jeasyuiTheme7 .datagrid-htable {
    border-bottom: 1px solid #e3fdff !important;
}
.khan-package .jeasyuiTheme7 .panel-header-eui,
.khan-package .jeasyuiTheme7 .panel-body-eui {
    border: none !important;
}
.khan-package .jeasyuiTheme7 .datagrid-header-row {
    height: 30px !important;
}
.khan-package .jeasyuiTheme7 .datagrid-header, 
.khan-package .jeasyuiTheme7 .datagrid-toolbar, 
.khan-package .jeasyuiTheme7 .datagrid-pager, 
.khan-package .jeasyuiTheme7 .datagrid-footer-inner {
    border: none !important;
}
.khan-package .jeasyuiTheme7 {
    border-top: 1px solid #e3fdff !important;
}
.khan-package .meta-toolbar {
    height: 26px !important;
    margin-top: 0px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    position: relative !important;
}
.conference-log .right-sidebar-content-for-resize {
    width: 100%;
    padding: 10px;
    background: #FFF !important;
}
.conference-log .not-datagrid{
    background-color: #FFF !important;
}
.grey-package .sidebar-light {
    margin-top: -11px;
    margin-left: -15px;
    margin-right: 15px;
    background-color: #f6f6f6;
    border-right: none;
    height: calc(100vh - 95px) !important;
}
.grey-package .sidebar-light .sidebar-content {
    padding-left: 10px!important;
}
.grey-package .sidebar-light .sidebar-content .bp-icon-selection.bg-white {
    background-color: transparent!important;
}
.grey-package .jeasyuiTheme10 .panel-body-eui {
    border: none;
}
.grey-package .jeasyuiTheme10 .panel-body-eui .datagrid-header .datagrid-header-inner table.datagrid-htable tbody tr:last-child > td {
    border-bottom: 2px solid #eee !important;
}
</style>