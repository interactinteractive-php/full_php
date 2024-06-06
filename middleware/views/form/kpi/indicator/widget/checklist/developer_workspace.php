<link href="<?php echo autoVersion('middleware/assets/css/intranet/style.css'); ?>" rel="stylesheet"/>
<div class="mv-developer-workspace">

    <div id="mv-developer-workspace-parent-<?php echo $this->uniqId; ?>" style="padding-left: 10px;padding-right: 10px;">
        
        <ul class="mv-developer-workspace-nav-tabs nav nav-tabs nav-tabs-bottom mb-0">
            <li class="nav-item"><a href="#navigation-tab" class="nav-link active" data-toggle="tab">Navigation</a></li>
            <li class="nav-item"><a href="#techdocument-tab" class="nav-link" data-toggle="tab">Tech document</a></li>
            <li class="nav-item"><a href="#community-tab" class="nav-link" data-toggle="tab">Community</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="navigation-tab">
                
                <div class="navbar navbar-dark bg-white navbar-component navbar-expand-xl mt-2" style="background-color: #3b4248;margin-bottom: 10px;">
                    <div class="navbar-collapse collapse pl-2 pr-2">

                        <div class="my-3 my-xl-0">
                            <button type="button" class="btn btn-primary mr-1 add-indicator-btn"><i class="far fa-plus-circle"></i></span> Нэмэх</button>
                            <button type="button" class="btn btn-success mr-1 add-excel-btn"><i class="far fa-file-import"></i></span> Импорт</button>
                            <button type="button" class="btn bg-danger-300 app-edit-btn"><i class="far fa-cog"></i></span> Тохиргоо</button>
                        </div>

                        <div class="mb-3 mb-xl-0 ml-xl-auto">
                            <button type="button" class="btn btn-light preview-btn"><i class="far fa-eye mr-2"></i> Preview</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-auto" style="width:300px;">
                        <div class="card mv-developer-workspace-card">
                            <ul class="nav nav-sidebar mv-developer-workspace-sidebar" data-nav-type="accordion">
                                <?php echo $this->developerSidebar; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col overflow-auto intranet">
                        <div class="ea-content mv-developer-workspace-render">
                            
                        </div>
                    </div>
                    <div class="col-md-auto mv-developer-workspace-right-sidebar" style="width:350px;">

                    </div>
                </div>
                
            </div>

            <div class="tab-pane fade" id="techdocument-tab">
            </div>

            <div class="tab-pane fade" id="community-tab">
                Community
            </div>
        </div>
    </div>
</div>

<style type="text/css">
.dev-dialog .ui-dialog-content {
    background-color: #fff;
    padding: 0;
    padding-top: 5px;
}
.dev-dialog-iframe .ui-dialog-content {
    background: radial-gradient(#262a2d, #212527);
    padding: 0;
}
.dev-dialog-iframe iframe {
    transform: scale(1);
    pointer-events: all;
    position: absolute;
    transform-origin: top left;
    transition: 0.10s;
    top: 0;
    left: 0;
    display: block;
    border: 0;
    background-color: #2f363a;
    height: 100%;
    box-shadow: 0 0 10px rgb(0 0 0 / 20%);
    width: 100%; 
    height: 800px;
}
.dev-dialog-iframe .sizer {
    position: absolute;
    top: 15px;
    padding: 42px;
    padding-bottom: 0;
    pointer-events: none;
    min-width: 100%;
    min-height: 80%;
    display: flex;
    justify-content: center;
}
.dev-dialog-iframe .view-wrapper {
    position: relative;
    width: 100%; 
    height: 800px;
    transition: 0.10s;
}
.mv-field-configuration-<?php echo $this->uniqId; ?> {
    position: absolute;
    width: 450px;
    padding: 18px;
    background: #fff;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    border-radius: 6px;
    right: 15px;
    margin-top: 55px;
    z-index: 999999;
}
.mv-developer-workspace {
    background-color: #fff;
}
.mv-developer-workspace .mv-developer-workspace-nav-tabs {
    margin-left: -10px;
    margin-right: -10px;
}
.mv-developer-workspace .btn {
    border-radius: 0.5875rem;
    padding: 0.3375rem 0.875rem;
}
.mv-developer-workspace .mv-developer-workspace-card {
    border: none;
    border-radius: 0;
    box-shadow: none;
    padding-top: 0;
    overflow: auto;
}
.mv-developer-workspace .vr-workspace-theme32 .package-div {
    background: none;
    background-color: transparent;
    padding: 0;
    margin-bottom: 0;
}
.mv-developer-workspace .vr-workspace-theme32 .package-tab {
    margin-top: -10px !important;
    padding: 10px;
    padding-top: 5px;
    background: none;
    background-color: transparent;
}
.intranet .mv-developer-workspace-render .content-wrapper, 
.intranet .mv-developer-workspace-render .content-wrapper .content {
    background-color: transparent;
}
.mv-developer-workspace .app-name {
    font-size: 16px;
}
.mv-developer-workspace .mv-developer-workspace-render {
    background-color: #f9f9f9;
}
.mv-developer-workspace .nav-sidebar .nav-link {
    color: rgba(51, 51, 51, .85);
}
.mv-developer-workspace .nav-sidebar .nav-link:not(.disabled):hover {
    color: #333;
    background-color: #f5f5f5;
}
.mv-developer-workspace .nav-sidebar .nav-link {
    padding-top: 0.50rem;
    padding-bottom: 0.50rem;
}
.mv-developer-workspace-sidebar .nav-link i {
    margin-top: 3px;
    margin-right: 10px;
    width: 13px;
}
.mv-developer-workspace-sidebar .nav-item-submenu>.nav-link {
    padding-left: 10px;
    padding-right: 10px;
}
.mv-developer-workspace-sidebar .nav-group-sub .nav-link {
    padding-left: 32px;
}
.mv-developer-workspace-sidebar .card-body {
    flex: none;
}
.mv-developer-workspace-sidebar .nav-item-submenu>.nav-link:after {
    top: 0.45rem;
    right: 10px;
}
.mv-developer-workspace .intranet {
    margin: unset;
}
.mv-developer-workspace .intranet .ea-content {
    padding: unset;
}
.mv-developer-workspace-sidebar .nav-item>.nav-link.active {
    background-color: #f5f5f5;
    color: #333;
}
.mv-developer-workspace-iframe-icons a.navbar-nav-link {
    padding: 0.575rem 1rem;
    color: inherit;
}
.mv-developer-workspace-iframe-icons a.navbar-nav-link i {
    font-size: 20px;
}
.mv-developer-workspace .package-tab > .row > .col-md-12 > .render-object-viewer > .row {
    margin: 0;
    padding: 0;
}
.mv-developer-workspace .package-tab > .row > .col-md-12 > .render-object-viewer > .row > .col-md-12 {
    padding: 0;
}
.mv-developer-workspace .package-tab > .row > .col-md-12 > .render-object-viewer > .row > .col-md-12 > .row > .center-sidebar > .row, 
.mv-developer-workspace .workspace-part .center-sidebar > div.row > div.content-wrapper > div.row {
    margin: 0;
}
.mv-developer-workspace .workspace-main > .ws-page-container > .ws-page-content-wrapper > .ws-page-content {
    margin-top: 0!important;
}
.mv-developer-workspace .intranet .card-header.v2, 
.mv-developer-workspace .intranet .card-header {
    height: inherit;
}
.mv-developer-workspace .vr-workspace-theme32 .package-div.odd, 
.mv-developer-workspace .vr-workspace-theme32 .package-div.odd .package-tab {
    background: none;
    background-color: transparent;
}
.mv-developer-workspace div[data-process-id="16424366404971"] .table-scrollable>.table {
    background-color: transparent;
}
.mv-developer-workspace div[data-process-id="16424366404971"] tr[data-cell-path="labelName"] > td[data-cell-path="labelName"]:first-child {
    display: none;
}
.mv-developer-workspace div[data-process-id="16424366404971"] input[name="param[labelName]"] {
    border: 1px #E1E1E1 solid !important;
    border-radius: 10px !important;
}
.mv-developer-workspace div[data-process-id="16424366404971"] div[data-path-message="labelName"] {
    background-color: transparent !important;
    color: #A0A0A0 !important;
    padding: 12px !important;
    padding-bottom: 0 !important;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar > .row {
    margin: 0;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .bl-section > .card {
    padding: 0 !important;
    padding-top: 1.25rem !important;
    border: none;
    box-shadow: none;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .bl-section > .card > .card-body {
    padding-left: 12px;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .bl-section > .card > .card-header:not(.invisible) {
    border-bottom: none;
    margin-bottom: 10px;
    padding-bottom: 0px;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .bl-section .card-header .card-title {
    font-size: 18px;
    font-weight: bold;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .bl-widget-form_with_serial_down_label_top .col-form-label {
    font-weight: normal !important;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .bp-layout {
    overflow: auto;
    margin-right: 0;
}
.mv-developer-workspace .datagrid-header td, 
.mv-developer-workspace .datagrid-body td, 
.mv-developer-workspace .datagrid-footer td {
    background: none;
    border: none;
}
.mv-developer-workspace .panel-eui.datagrid {
    margin-top: 10px;
}
.mv-developer-workspace .panel-body-eui, 
.mv-developer-workspace .datagrid-view1, 
.mv-developer-workspace .datagrid-view2, 
.mv-developer-workspace .datagrid-header, 
.mv-developer-workspace .datagrid-td-rownumber {
    background: none;
    background-color: transparent;
}
.mv-developer-workspace .datagrid-header-check, 
.mv-developer-workspace .datagrid-cell-check {
    width: 20px;
}
.mv-developer-workspace .panel-header-eui, 
.mv-developer-workspace .panel-body-eui {
    border: none;
}
.mv-developer-workspace .datagrid-header .datagrid-cell span {
    line-height: 14px;
    font-size: 12px;
    text-transform: unset;
    font-weight: 700;
    color: #A0A0A0;
}
.mv-developer-workspace .datagrid-toolbar, 
.mv-developer-workspace .datagrid-pager {
    background: none;
    background-color: transparent;
    border: none;
}
.mv-developer-workspace .mv-datalist-container .jeasyuiTheme3 .datagrid-header td {
    background: none !important;
    border: none !important;
}
.mv-developer-workspace .nav-tabs-bottom .nav-link.active {
    color: #468CE2;
    font-weight: bold;
}
.mv-developer-workspace .nav-tabs-bottom .nav-link.active:before {
    background-color: #468CE2;
}
.mv-developer-workspace .ws-menu .workspace-menu li.active > a {
    border-bottom: 2px solid #468CE2 !important;
    color: #468CE2;
    font-weight: bold;
}
.mv-developer-workspace .ws-menu .workspace-menu li.active > a > span {
    color: #468CE2;
    font-weight: bold;
}
.mv-developer-workspace .vr-workspace-theme32 .workspace-part > div.row:first-child {
    background: none;
    background-color: transparent;
}
.mv-developer-workspace .list-group {
    background-color: transparent;
}

.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control:not(.mv-hdr-right-label-control), 
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-row,
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-label, 
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-input {
    display: block;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control, 
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control-row,
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control-label, 
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control-input {
    border: none;
    padding-left: 0;
    padding-right: 0;
    background-color: transparent;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control:not(.mv-hdr-right-label-control) .mv-hdr-label-control-label {
    width: 100%!important;
    padding-bottom: 5px;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control-label {
    text-align: left;
    font-weight: bold;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-rows-title-label {
    font-size: 14px;
    color: #555;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control-label label {
    color: #111;
    font-weight: normal;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control-input input.form-control {
    height: 28px!important;
    min-height: 28px!important;
    border-radius: 6px!important;
    border: 1px #d2dae2 solid;
    padding: 7px 10px!important;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control-input textarea.form-control {
    /*background-color: #fafafa;*/
    border-radius: 6px!important;
    border: 1px #d2dae2 solid;
    padding: 7px 10px!important;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control {
    margin-bottom: 6px;
}
.mv-developer-workspace .mv-developer-workspace-right-sidebar.mv-hdr-label-control-input .form-control .select2-choice,
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control-input .form-control.select2-container-active .select2-choice,
.mv-developer-workspace .mv-developer-workspace-right-sidebar .mv-hdr-label-control-input .form-control.select2-container-active .select2-choices {
    border: 1px #d2dae2 solid;
    height: 28px;
    padding-top: 2px;
}
.mv-developer-workspace #techdocument-tab {
    margin-left: -10px;
    margin-right: -10px;
}
.mv-developer-workspace #techdocument-tab > .pf-paneltype-dataview {
    padding-left: 0;
    padding-right: 0;
}
.mv-developer-workspace #techdocument-tab > .pf-paneltype-dataview .ea-content-sidebar-tabs .ea-nav-tabs > li:nth-of-type(2) {
    display: none;
}
.bp-btn-translate, 
.object-height-row3-minus-1642419374729118 .table-toolbar {
    display: none;
}
.font-size-6 {
    font-size: 6px;
}
</style>

<?php require getBasePath() . 'middleware/views/form/kpi/indicator/checklist/devwsscript.php'; ?>