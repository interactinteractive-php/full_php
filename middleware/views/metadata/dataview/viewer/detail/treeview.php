<div class="page-content dvecommerce_tree dvecommerce dv2" id="dvecommerce_<?php echo $this->metaDataId; ?>">
    <div class="left-sidebar-content">
        <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md w-100">
            <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-secondary-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                <span class="font-weight-semibold">Secondary sidebar</span>
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <div class="sidebar-content">
                <div class="sortable">
                    <div class="card-header bg-light header-elements-inline mb-3">
                        <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                            <li class="nav-item"><a href="#components-tab" class="nav-link active" data-toggle="tab">Жагсаалт</a></li>
                            <li class="nav-item"><a href="#filter-tab" class="nav-link" data-toggle="tab">Шүүлт</a></li>
                        </ul>
                        <div class="first-sidebar-search-box mr-1" id="first-sidebar-search-box" style="display: none;">
                            <form action="#" class="w-100">
                                <?php //  echo $this->defaultCriteria; ?> 
                                <?php if ($this->metaDataId !== '1559543940630655') { ?>
                                    <input type="text" style="min-height: 26px; height: 26px;" class="form-control border-right-0 treefilter_<?php echo $this->metaDataId; ?>" placeholder="Хайх ...">
                                <?php } ?>
                            </form>
                        </div>
                        <div class="ml-auto">
                            <a href="javascript:void(0);" id="first-sidebar" class="btn btn-light first-sidebar  treefilter-<?php echo $this->metaDataId; ?> border-0 p-1 pl-2 pr-2 ">
                                <i class="icon-search4"></i>
                            </a>
                        </div>
                        <!-- <form action="#" class="w-100">
                            <?php echo $this->defaultCriteria; ?> 
                            <?php if ($this->metaDataId !== '1559543940630655') { ?>
                                <div class="input-group">
                                    <input type="text" style="min-height: 26px; height: 26px;" class="form-control border-right-0 treefilter" placeholder="Хайх">
                                    <span class="input-group-append">
                                        <button class="btn blue treefilter-<?php echo $this->metaDataId; ?>" style="padding: .1rem .4rem;" type="button">  <i class="icon-search4 text-light"></i></button>
                                    </span>
                                </div>
                            <?php } ?>
                        </form>
                        <span class="text-uppercase font-size-m font-weight-bold"> </span> -->
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="components-tab">
                            <div class="card">
                                <div class="card-body pt-0">
                                    <div class="treeviewcontainer">
                                    </div>     

                                    <div id="objectdatagrid-<?php echo $this->metaDataId; ?>" class="not-datagrid data-tree-view-grid div-objectdatagrid-<?php echo $this->metaDataId; ?>">
                                        <div class="selected-row" data-row-data="{}"> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="filter-tab">
                            <div class="card">
                                <div class="card-body pt-0">
                                    <!-- <?php echo $this->metaDataId; ?> -->
                                    <?php echo $this->filter; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---------------------- MAIN CONTENT START------------------------->
    <div class="right-sidebar-content-for-resize w-100">
        <div class="content-wrapper w-100 pl15  pr15 main-overflow right-sidebar-content-for-resize">
            <div class="topdpbutton">
                <?php if ($this->layoutLink) { ?>
                    <div class="btn-group">
                        <button class="btn active btn-secondary btn-lg tab-lookupcriteria-<?php echo $this->metaDataId; ?>" data-layoutid="<?php echo $this->row['LAYOUT_META_DATA_ID']; ?>" type="button" aria-expanded="false">
                            <?php echo $this->layoutLink['META_DATA_NAME']; ?>   
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-secondary active btn-lg tab-lookupcriteria-<?php echo $this->metaDataId; ?>" style="display:none" type="button" data-type="card" data-path="wfmstatusname" data-theme="wfmstatus" data-selection="">Дэлгэрэнгүй</button>                                    
                    </div>                
                <?php } ?>
            </div>        
            <?php 
                if (!empty($this->dataViewProcessCommand['commandBtnPosition'])) { ?>
                <div class="top-process-btn">
                <ul>
                    <?php
                    foreach ($this->dataViewProcessCommand['commandBtnPosition'] as $rowBtn) {
                        if ($rowBtn['position'] === 'top')
                            echo '<li>' .$rowBtn['html'].'</li>';
                    }
                    ?>
                        <li class="action-menu">
                            <button type="button" class="btn bg-purple-300  bg-purple-300 btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="icon-link"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                                <?php
                                echo Html::anchor(
                                        'javascript:;', '<i class="icon-cube"></i> Pivot view', array(
                                    'title' => 'Pivot view',
                                    'class' => 'dropdown-item',
                                    'onclick' => 'dataViewPivotView(\'' . $this->metaDataId . '\', this);'
                                        ), (defined('CONFIG_PIVOT_SERVICE_ADDRESS') && CONFIG_PIVOT_SERVICE_ADDRESS)
                                );
                                echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-qrcode"></i> Qrcode', array(
                                    'onclick' => 'dataViewStatementPreview_' . $this->metaDataId . '(\'' . $this->metaDataId . '\', true, \'toolbar\', this);',
                                    'class' => 'dropdown-item',
                                        ), $this->isStatementBtnSee
                                );
                                
                                if (isset($this->row['dataViewLayoutTypes']['explorer']) || isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                                
                                    if (isset($this->row['dataViewLayoutTypes']['calendar'])) {
                                        $iconName = 'calendar';
                                        $title = 'Calendar view';
                                    } else {
                                        $iconName = 'folder';
                                        $title = 'Explorer view';
                                    }
                                    
                                    echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-'.$iconName.'"></i> '.$title, array(
                                            'title' => $title,
                                            'class' => 'dropdown-item',
                                            'onclick' => 'dataViewer_' . $this->metaDataId . '(this, \''.key($this->row['dataViewLayoutTypes']).'\', \'' . $this->metaDataId . '\');'
                                        ), true
                                    );
                                }
                                        
                                echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-bar-chart-o"></i> Layout', array(
                                    'class' => 'dropdown-item callLayoutDataView_' . $this->metaDataId,
                                    'title' => 'Layout',
                                    'onclick' => 'callLayoutDataView_' . $this->metaDataId . '(' . $this->metaLayoutLinkId . ', this);'
                                        ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false
                                );
                                echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-table"></i> Table', array(
                                    'class' => 'dropdown-item callDataView_' . $this->metaDataId,
                                    'title' => 'Table',
                                    'onclick' => 'callDataView_' . $this->metaDataId . '(' . $this->metaDataId . ', this);'
                                        ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false
                                );
                                echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-map-marker"></i> Map', array(
                                    'class' => 'dropdown-item googleMapBtnByDataView_' . $this->metaDataId,
                                    'title' => 'Map',
                                    'onclick' => 'googleMapBtnByDataView_' . $this->metaDataId . '(this);'
                                        ), isset($this->isGoogleMap) ? $this->isGoogleMap : false
                                );
                                echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-calendar"></i> Calendar view', array(
                                    'title' => 'Calendar view',
                                    'class' => 'dropdown-item',
                                    'onclick' => 'callCalendarByMeta(' . $this->calendarMetaDataId . ');'
                                        ), isset($this->isCalendarSee) ? $this->isCalendarSee : false
                                );
                                
                                if (issetParam($this->row['IS_EXCEL_EXPORT_BTN']) != '') {

                                    if (strpos($commandBtn, '<!--excelexportbutton-->') !== false) {
                                        echo Html::anchor(
                                                'javascript:;', '<i class="icon-file-excel"></i> ' . $this->lang->line('excel_btn'), array(
                                            'title' => $this->lang->line('excel_btn'),
                                            'class' => 'dropdown-item',
                                            'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                                ), true
                                        );
                                    }
                                } else {
                                    echo Html::anchor(
                                            'javascript:;', '<i class="icon-file-excel"></i> ' . $this->lang->line('excel_btn'), array(
                                        'title' => $this->lang->line('excel_btn'),
                                        'class' => 'dropdown-item',
                                        'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                                            ), (!isset($this->row['IS_IGNORE_EXCEL_EXPORT']) || (isset($this->row['IS_IGNORE_EXCEL_EXPORT']) && $this->row['IS_IGNORE_EXCEL_EXPORT'] != '1'))
                                    );
                                }
                                echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-file-text-o"></i> Text file', array(
                                        'title' => 'Text file',
                                        'class' => 'dropdown-item',
                                        'onclick' => 'dataViewExportToText_' . $this->metaDataId . '();'
                                    ), isset($this->isExportText) ? $this->isExportText : false
                                );
                                echo Html::anchor(
                                        'javascript:;', '<i class="far fa-print"></i> Print', array(
                                        'title' => 'Print',
                                        'class' => 'dropdown-item',
                                        'onclick' => 'dataViewExportToPrint_' . $this->metaDataId . '();'
                                    ), (issetParam($this->row['IS_DIRECT_PRINT']) == '1')
                                );
                                echo Html::anchor(
                                        'javascript:;', '<i class="icon-table2"></i> Merge cell', array(
                                    'class' => 'dropdown-item value-grid-merge-cell',
                                    'title' => 'Merge cell'
                                        ), ($this->dataGridOptionData['MERGECELLS'] == 'true' ? true : false)
                                );
                                echo Html::anchor(
                                        'javascript:;', '<i class="icon-cog"></i> '.$this->lang->line('user_configuration'), array(
                                    'title' => $this->lang->line('user_configuration'),
                                    'class' => 'dropdown-item',
                                    'onclick' => 'dataViewAdvancedConfig_' . $this->metaDataId . '(this);'
                                        ), true
                                );
                                echo Html::anchor(
                                    'javascript:;', (new Mduser())->iconQuickMenu($this->metaDataId) . ' QuickMenu', array(
                                    'onclick' => 'toQuickMenu(\'' . $this->metaDataId . '\', \'dataview\', this);',
                                    'class' => 'dropdown-item',
                                    'title' => 'Quick menu',
                                    ), true
                                );
                                echo Html::anchor(
                                        'javascript:;', '<i class="fa fa-file"></i> '.$this->lang->line('META_VIEW_REPORT_TEMPLATE'), array(
                                    'onclick' => 'objectReportTemplateView_'.$this->metaDataId.'();',
                                    'class' => 'dropdown-item',
                                    'title' => '',
                                        ), $this->isReportTemplate
                                );
                                echo Mdcommon::listHelpContentButton([
                                    'contentId' => issetParam($this->row['HELP_CONTENT_ID']), 
                                    'sourceId' => $this->metaDataId, 
                                    'fromType' => 'meta_dv', 
                                    'parentControl' => 'dropdown'
                                ]);
                                ?>
                            </div>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="btn  btn-sm sidebar-control sidebar-left-toggle d-none d-md-block">
                                <i class="icon-indent-decrease"></i>
                            </a>
                        </li>
                       
                        <li>
                            <a href="javascript:void(0);" data-isusesidebar="<?php echo $this->isUseSidebar ?>" class="btn btn-success btn-sm sidebar-control sidebar-right-toggle d-none d-md-block">
                                <i class="icon-indent-increase"></i>
                            </a>
                        </li>
                    </ul>
                </div>

            <?php } ?>
            <div class="treeview-maincontent"></div>
            <?php if ($this->layoutLink) { ?>
                <div class="div-ecommercelayoutmeta-<?php echo $this->metaDataId; ?>">
                </div>              
                <?php }
            ?>                  
        </div>
    </div>
    <!---------------------------- MAIN CONTENT END ----------------------------->
    <!-- <div class="toggleBtn">
        <a href="javascript:;" class="sidebar-control sidebar-main-toggle d-none d-md-block" id="toggle">
            <i class="icon-paragraph-justify3"></i>
        </a>
    </div> -->

    <?php
    $commandBtn = '';
    $commandBtn .= $this->dataViewProcessCommand['commandBtn'];
    $addonBtn = $wfmBtn = '';

    if (isset($this->dataViewWorkFlowBtn) && $this->dataViewWorkFlowBtn == true) {
        $wfmBtn = '<ul class="turulbtn workflow-dropdown-' . $this->metaDataId . '" role="menu"></ul>
        <li class="nav-item btn btn-sm btn-group workflow-btn-group-' . $this->metaDataId . '">
            <button type="button" class="btn hidden btn-sm blue btn-circle dropdown-toggle workflow-btn-' . $this->metaDataId . '" data-toggle="dropdown"><i class="fa icon-shuffle"></i> ' . $this->lang->line('change_workflow') . '</button></li>';

        $commandBtn = str_replace('<!--changewfmstatus-->', $wfmBtn, $commandBtn, $wfmBtnReplace);

        if (!$wfmBtnReplace) {
            $addonBtn .= $wfmBtn;
        }
    }

    if ($commandBtn) {
        $commandBtn = str_replace('<!--endbutton-->', $addonBtn, $commandBtn);
        if (isset($filterButton)) {
            $commandBtn = str_replace('<!--startbutton-->', $filterButton, $commandBtn);
        }
    } else {
        if (isset($filterButton)) {
            $addonBtn = $filterButton . $addonBtn;
        }
        $commandBtn = $addonBtn;
    }

    if ($commandBtn !== '') { ?>
        <div class="sidebar sidebar-main sidebar-light sidebar-right sidebar-expand-md trsidebar" style="display: block" id="trsidebar">
            <div class="sidebar-mobile-toggler text-center">
                <a href="#" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
                <span class="font-weight-semibold">Right sidebar</span>
                <a href="#" class="sidebar-mobile-right-toggle">
                    <i class="icon-arrow-right8"></i>
                </a>
            </div>
            <div class="sidebar-content">
                <div class="card card-sidebar-mobile">

                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="text-uppercase font-size-m font-weight-bold"><i class="icon-cog mr-1"></i>Үйлдэл</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                     
                        <?php
                        echo $commandBtn;
                        ?>    
                    </div>
                </div>
            </div>
        </div>
        <?php
    } 
    
    
    ?>
    <div class="d-none">
        <?php 
        if ($this->dataViewProcessCommand['isBpOpen']) {
            foreach ($this->dataViewProcessCommand['isBpOpen'] as $key => $bpParam) {
                echo '<a fn-callbackfunction="drawTreeView_'. $this->metaDataId .'" class="is-open-bp-default-'. $this->metaDataId .'" onclick="'. $bpParam['fn'] .'"></a>';
            }
        } ?>
    </div>
</div>

<style type="text/css">
    .dvecommerce_tree.dv2 .top-process-btn ul li a.sidebar-secondary-toggle i{
        transform: rotate(180deg);
        margin-bottom: 0;
    }
    .bg-purple-300 i{
        color:#4b76a5 !important;
        padding: 5px 0;
        font-size: 12px;
        margin-top: -1px;
    }
    .bg-purple-300, .dvecommerce_tree.dv2 .top-process-btn ul li a{
        background-color: #bccff770;
        border: 1px solid #bccff750;
        color:#4b76a5 !important;
    }
    .dvecommerce_tree.dv2 .header-elements-inline {
        padding: 0 15px;
    }
    .dvecommerce_tree.dv2 .header-elements-inline .first-sidebar {
        background-color: #e2eafb !important;
        margin-right: 0;
    }
    #first-sidebar-search-box input{
        width: 204px;
        border: none;
    }
    #first-sidebar-search-box{
        position: absolute;
    }
    .dvecommerce_tree.dv2 .header-elements-inline .nav-tabs  {
        width: 100%;
        background: #fafafa;
        border-color: #fafafa;
        height: 45px;
        margin-right: 15px;
    }
    .dvecommerce_tree.dv2 .header-elements-inline .nav-tabs li a::before{
        bottom: 5px !important;
        height: 3px;
    }
    .dvecommerce_tree.dv2 .header-elements-inline .nav-tabs li a {
        padding: 17px 0;
        text-transform: uppercase;
    }
    .dvecommerce_tree.dv2 .top-process-btn ul li {
        float: left;
        list-style-type: none;
    }
    .dvecommerce_tree.dv2 .top-process-btn ul .action-menu a:hover{
        background: #ebebeb;
    }
    .dvecommerce_tree.dv2 .top-process-btn ul .action-menu a{
        background: #fff;
        border:none;
    }
        
    .dvecommerce_tree.dv2 .btn ,
    .dvecommerce_tree.dv2 .btn.bpMainSaveButton i {
        color:#fff;
    }
    .dvecommerce_tree.dv2 .top-process-btn ul {
        padding: 0;
    }
    
    #dvecommerce_<?php echo $this->metaDataId; ?> .jstree-default .jstree-icon:empty { 
        height: 24px !important;
        min-height: 24px !important;
    }
    
    #dvecommerce_<?php echo $this->metaDataId; ?> .render-process-page { 
        margin-top: 0 !important;
        margin-bottom: 25px !important;
    }
    
     #dvecommerce_<?php echo $this->metaDataId; ?> .meta-toolbar { 
        margin-top: -35px !important;
        padding-top: 0px;
    } 
    
    #dvecommerce_<?php echo $this->metaDataId; ?> > .left-sidebar-content > .ui-resizable-handle.ui-icon {
        border: inherit !important;
    }
    
    #dvecommerce_<?php echo $this->metaDataId; ?> > .left-sidebar-content.ui-resizable:after {
        content: url('../../../assets/custom/img/split-dot.png');
        position: absolute;
        top: 46%;
        right: -2px;
        cursor: col-resize;
        z-index:2;
    }
    
    .count-selective-task-treeview {
        border-radius: 50px;
        background-color: #2fbabc;
        color: #fff;
        position: absolute;
        right: 0;
        padding: 0 7px;
        font-size: 12px;
        margin-left: 2px;
        height: 20px;
        width: 26px;
        line-height: 20px;
        text-align: center;
    }    
    .dvecommerce_tree .workspace-main-container{
        height: 100vh;
    }
    body {
        height: 100%;
        min-height: 100%;
        background-color: #ebebeb;
    }
    
    .dvecommerce_tree .jstree-default .jstree-anchor {
        width: 100%;
    }    
    
    .dvecommerce_tree .jstree-children, .jstree-container-ul li {
        position: relative;
    }
    
    #dvecommerce_<?php echo $this->metaDataId; ?> .dataview-search-filter .tabbable-line > .tab-content,
    #dvecommerce_<?php echo $this->metaDataId; ?> .dataview-search-filter > .border-bottom-grey {
        border: none !important;
        padding: 0 !important;
        margin-bottom: 5px !important;
    }
    
    #dvecommerce_<?php echo $this->metaDataId; ?> .package-tab {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    .sidebar-right .sidebar-content .card-body a>i{
        margin-right: 12px !important;
    }
    .sidebar-right .sidebar-content .card-body a{
        margin-left: 15px !important;
    }
    #object-value-list-<?php echo $this->metaDataId; ?>{
        margin-top: -10px;
    }
    .dvecommerce_tree.dv2 .sidebar {
        border:none;
    }
    .dvecommerce_tree.dv2 .left-sidebar-content.ui-resizable::before{
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        background: #ebebeb;
        width: 1px;
        display: block;
        /* z-index: 9 */
    }
    .dvecommerce_tree.dv2 .card-multi-tab-content .tab-pane ul.workspace-menu-v2{
        margin-bottom:0;
    }
    .dvecommerce_tree.dv2 .sidebar .card-header {
        height: 44px;
        border-bottom: 1px solid #ebebeb;
    }
    .dvecommerce_tree.dv2 .vr-workspace-theme20 .ws-menu span {
        color:#000 !important; 
        font-weight: 400;
    }
    .dvecommerce_tree.dv2 .vr-workspace-theme20 .ws-menu a {
        color:#000;
    }
    .dvecommerce_tree.dv2 .vr-workspace-theme20 .ws-menu {
        background: #fff;
        border-bottom:1px solid #ebebeb;
    }
    .dvecommerce_tree.dv2 .top-process-btn{
        position: absolute;
        right: 15px;
        top: 8px;
    }
    .dvecommerce_tree.dv2 .right-sidebar-content-for-resize{
        position: relative;
    }
    .dvecommerce_tree.dv2 .left-sidebar-content{
        background: #FFF; 
        height: 100% !important;
    }
    .dvecommerce_tree.dv2 .vr-workspace-theme20 .workspace-main{
        margin-top: 5px;
        margin-left: 10px;
        margin-right: 10px; 
        padding: 0 15px; 
    }
</style>

<script type="text/javascript">

    $(document).ready(function() {
        var treecontainer = $('#object-value-list-<?php echo $this->metaDataId; ?>');
        treecontainer.parent().find('.meta-toolbar').hide(20);
    });

    var $sidebar = $("#trsidebar");
    var $sidebarLeft = $(".left-sidebar-content");

    $(document).on("click", ".sidebar-left-toggle", function () {
        $sidebarLeft.toggleClass("hidden");
    });

    $("#first-sidebar").click(function(){
        $("#first-sidebar-search-box").toggle();
    });

    $(document).on("click", ".sidebar-right-toggle", function () {
        $sidebar.toggleClass("hidden");
    });

    var windowId_<?php echo $this->metaDataId; ?> = 'div#object-value-list-<?php echo $this->metaDataId; ?>',
        objectdatagrid_<?php echo $this->metaDataId; ?> = $('#objectdatagrid-<?php echo $this->metaDataId; ?>');

    Core.initSelect2($(windowId_<?php echo $this->metaDataId; ?>));
    
    $('.leftweb-accordion-btn', windowId_<?php echo $this->metaDataId; ?>).addClass('hide');
    $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').on('click', function (e) {
        var $this = $(this),
        $div = $('.div-ecommercelayoutmeta-<?php echo $this->metaDataId; ?>');

        $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').removeClass('active');
        $this.addClass('active');

        if (typeof $this.attr('data-layoutid') !== 'undefined') {
            $('.treeview-maincontent', windowId_<?php echo $this->metaDataId; ?>).hide();
            $div.show();

            if (!$div.find('.layout-theme').length) {
                $.ajax({
                    type: 'post',
                    url: 'mdlayoutrender/index/' + $this.data('layoutid'),
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        var jsonObj = JSON.parse(data);
                        if ('Html' in Object(jsonObj)) {
                            $div.empty().append(jsonObj.Html + '<div class="clearfix w-100"/>');
                        } else {
                            $div.empty().append(data + '<div class="clearfix w-100"/>');
                        }
                    }
                }).done(function () {
                    Core.unblockUI();
                });
            }
            return;
        } else {
            $('.treeview-maincontent', windowId_<?php echo $this->metaDataId; ?>).show()
            $div.hide();
        }
    });

    $(".treefilter-<?php echo $this->metaDataId; ?>").on('click', function () {
        // var sval = $(this).closest('.input-group').find('input').val();
        // drawTreeView_<?php echo $this->metaDataId; ?>(sval);
        return false;
    });

    $(windowId_<?php echo $this->metaDataId; ?>).on("keydown", 'input.treefilter', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code === 13) {
            return false;
        }
    });

    $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').first().click();
    
    if ($("#dvecommerce_<?php echo $this->metaDataId; ?>").find('.is-open-bp-default-<?php echo $this->metaDataId; ?>').length) {
        var $bpOpenSelector = $("#dvecommerce_<?php echo $this->metaDataId; ?>").find('.is-open-bp-default-<?php echo $this->metaDataId; ?>');
        $bpOpenSelector.trigger('click');
        drawTreeView_<?php echo $this->metaDataId; ?>();
    } else {
        drawTreeView_<?php echo $this->metaDataId; ?>();
    }

    function drawTreeView_<?php echo $this->metaDataId; ?>(q) {
        var dataViewId = '<?php echo $this->metaDataId; ?>';
        var metaDataId = '', treeJsonData = [];

        $('.treeviewcontainer', windowId_<?php echo $this->metaDataId; ?>).html('<div id="dataViewStructureTreeView_<?php echo $this->metaDataId; ?>" class="tree-demo"></div>');
        var dataViewStructureTreeView_<?php echo $this->metaDataId; ?> = $('div#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>);

        $.ajax({
            type: 'post',
            url: 'mdobject/getAjaxTreeView',
            data: {
                'parent': '#',
                'dataViewId': '<?php echo $this->metaDataId; ?>',
                'structureMetaDataId': '<?php echo $this->metaDataId; ?>',
                'drillDownDefaultCriteria': '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>',
                'uriParams': '<?php echo isset($this->uriParams) ? $this->uriParams : ''; ?>'
            },
            dataType: 'json',
            async: false,
            success: function (data) {
                treeJsonData = data;
            },
            error: function () {
                alert('Error');
            }
        });

        if (treeJsonData.length && typeof treeJsonData[0].rowdata.PARENT_TREE_ONLY !== 'undefined') {
            var sval = typeof q === 'undefined' ? '' : q;

            $(windowId_<?php echo $this->metaDataId; ?>).on("keyup", 'input.treefilter', function (e) {
                var searchString = $(this).val();
                $('div#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('search', searchString);
            });

            dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.jstree({
                "core": {
                    "themes": {
                        "responsive": true
                    },
                    "check_callback": true,
                    "data": {
                        "url": function (node) {
                            return 'mdobject/getAjaxTreeView';
                        },
                        "data": function (node) {
                            return {
                                'parent': node.id,
                                'dataViewId': '<?php echo $this->metaDataId; ?>',
                                'structureMetaDataId': '<?php echo $this->metaDataId; ?>',
                                'type': 'treeview',
                                'q': sval
                            };
                        }
                    }
                },
                "types": {
                    "default": {
                        "icon": "icon-folder2 text-orange-300"
                    }
                },
                "search": {
                    "case_sensitive": false,
                    "show_only_matches": true
                },
                "plugins": ["types", "cookies", "search"]
            }).bind("select_node.jstree", function (e, data) {
                var nid = data.node.id === 'null' ? '' : data.node;
                selectedDataTreeView_<?php echo $this->metaDataId; ?>(nid);
            });

        } else {

            $(".treefilter_<?php echo $this->metaDataId; ?>").keyup(function() {
                var searchString = $(this).val();
                console.log(searchString);
                $('#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>').jstree('search', searchString);
            });
            
            // $(windowId_<?php echo $this->metaDataId; ?>).on("keyup", 'input.treefilter', function (e) {
            //     var $this = $(this);
            //     var searchString = $this.val() + '###' + ($this.closest('form').find('select[name="param[bookTypeId]"]').val()) + '$$$text';

            //     $('div#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('search', searchString);
            // });

            dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.jstree({
                "core": {
                    "themes": {
                        "responsive": true
                    },
                    "check_callback": true,
                    "data": treeJsonData
                },
                "types": {
                    "default": {
                        "icon": "icon-folder2 text-orange-300"
                    }
                },
                "search": {
                    "show_only_matches": true,
                    
                    "search_leaves_only": true,
                    // search_callback: function (searchString, node) {
                    //     var strSplit = searchString.split('$$$');
                    //     if (strSplit[1] == 'text') {
                    //         var vval = strSplit[0].split('###');
                    //         return ((node[strSplit[1]]).toLowerCase().indexOf(vval[0].toLowerCase()) != -1 && (node.original.rowdata['BOOK_TYPE_ID']).toLowerCase().indexOf(vval[1].toLowerCase()) != -1);
                    //     } else if (strSplit[1] == 'BOOK_TYPE_ID') {
                    //         return ((node.original.rowdata[strSplit[1]]).toLowerCase().indexOf(strSplit[0].toLowerCase()) != -1);
                    //     }
                    // }
                },
                "plugins": ["types", "cookies", "search"]
            }).bind("select_node.jstree", function (e, data) {
                var nid = data.node.id === 'null' ? '' : data.node;
                selectedDataTreeView_<?php echo $this->metaDataId; ?>(nid);
                if ($('.workflow-btn-<?php echo $this->metaDataId ?>', "#object-value-list-<?php echo $this->metaDataId; ?>").length) {
                    $('.workflow-dropdown-<?php echo $this->metaDataId ?>').empty();
                    if (!$('.workflow-btn-<?php echo $this->metaDataId ?>').is(':visible')) {
                        $('.workflow-btn-<?php echo $this->metaDataId ?>').trigger('click', [true]);
                    }
                }
            }).bind('loaded.jstree', function (e, data) {
                $(windowId_<?php echo $this->metaDataId; ?>).find('form').find('select').trigger('change');
            }).bind("open_node.jstree", function (e, data) {}).on('ready.jstree', function () {

                setTimeout(function () {
                    var getNodeId = dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.jstree("get_selected", true);
                    var getSelectedNodeArr = <?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : '[]'; ?>;

                    if (typeof getNodeId[0] !== 'undefined' && typeof getNodeId[0]['id'] !== 'undefined') {
                        dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.find('.jstree-children #' + getNodeId[0]['id']).find('.jstree-anchor:eq(0)').trigger('click');
                    } else {
                        if (typeof getSelectedNodeArr['tree_view_selected_id'] !== 'undefined' && typeof getSelectedNodeArr['id'] !== 'undefined' && getSelectedNodeArr['tree_view_selected_id'] && getSelectedNodeArr['id']) {

                            dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.jstree("open_node", $('#' + getSelectedNodeArr['id']));
                            dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.find('.jstree-children #' + getSelectedNodeArr['tree_view_selected_id']).find('.jstree-anchor:eq(0)').trigger('click');

                        } else {
                            dataViewStructureTreeView_<?php echo $this->metaDataId; ?>.find('li:eq(0) > a:eq(0)').trigger('click');
                        }
                    }
                }, 100);
            });
        }
    }

    function selectedDataTreeView_<?php echo $this->metaDataId; ?>(folderId) {
//        return;
        var typeId = folderId.original.rowdata.META_TYPE_ID,
            metaDataId = folderId.original.rowdata.META_DATA_ID;

        var review = {}, ii;
        for (var i in folderId.original.rowdata) {
            ii = i;
            review[i.replace(/_/g, '').toLowerCase()] = folderId.original.rowdata[ii]
        }
        $('.selected-row', windowId_<?php echo $this->metaDataId; ?>).attr('data-row-data', JSON.stringify(review));

        if (folderId == 'all') {

        } else {
            console.log(typeId);
            $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').removeClass('active');
            $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>:last').addClass('active').show();
            var $div = $('.treeview-maincontent', windowId_<?php echo $this->metaDataId; ?>);
            $('.div-ecommercelayoutmeta-<?php echo $this->metaDataId; ?>').hide();
            $div.show();

            if (typeId == '<?php echo Mdmetadata::$metaGroupMetaTypeId ?>') {
                $.ajax({
                    type: 'post',
                    url: 'mdobject/dataview/' + metaDataId,
                    // data: {proxyId: param.proxyId}, 
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function () {
                    if (typeof callback === 'function') {
                        callback($div, param);
                    }
                    Core.unblockUI();
                });
            } else if (typeId == 'statement') {
                $.ajax({
                    type: 'post',
                    url: 'mdstatement/index/' + metaDataId,
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function () {
                    Core.unblockUI();
                });
            } else if (typeId == '<?php echo Mdmetadata::$workSpaceMetaTypeId ?>') {
                var review = {}, ii, wsHiddenParams = '';
                var formData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form").serializeArray();

                if (formData) {
                    for (var fdata = 0; fdata < formData.length; fdata++) {
                        var mPath = /param\[([\w.]+)\]/g.exec(formData[fdata].name);
                        if (mPath === null)
                            continue;
                        mPath = mPath[1].toLowerCase();
                        review[mPath] = formData[fdata].value;
                    }
                }

                for (var i in folderId.original.rowdata) {
                    ii = i;
                    var kkey = i.replace(/_/g, '').toLowerCase();
                    review[kkey] = folderId.original.rowdata[ii];
                    wsHiddenParams += '<input type="hidden" name="workSpaceParam[' + kkey + ']" value="' + folderId.original.rowdata[ii] + '">';
                }
                
                if (typeof review['isworkspacerefresh'] !== 'undefined' && review['isworkspacerefresh'] == '0') {
                    if (!$div.find('#workspace-id-' + metaDataId).length) {
                        $.ajax({
                            type: 'post',
                            url: 'mdworkspace/index/' + metaDataId,
                            data: {selectedRow: review},
                            beforeSend: function () {
                                Core.blockUI({
                                    message: 'Loading...',
                                    boxed: true
                                });
                            },
                            success: function (data) {
                                $div.empty().append(data + '<div class="clearfix"/>').promise().done(function () {
                                    try {
                                        if (typeof window['renderCondition_' + metaDataId] === 'function') { 
                                            window['renderCondition_' + metaDataId]()
                                        } else {
                                        }
                                    } catch(err) {
                                        console.log('renderCondition_ : ' + err);
                                    }
                                });
//                                $div.find('.page-content .workspace-main').addClass('content-wrapper');
                            }
                        }).done(function () {
                            Core.unblockUI();
                        });
                    } else {

                        $getWsActiveMenu = $div.find('.ws-menu ul.workspace-menu li.active')
                        $div.find('div.ws-hidden-params').empty().append(wsHiddenParams);
                        try {
                            if (typeof window['renderCondition_' + metaDataId] === 'function') { 
                                window['renderCondition_' + metaDataId]('<?php echo $this->metaDataId; ?>', 'hide');
                            } else {
                            }
                        } catch(err) {
                            console.log('renderCondition_ : ' + err);
                        } 
                        /*
                        if ($("div#package-meta-1563931178326871").length) {
                            $("div#package-meta-1563931178326871").find('a[data-metadataid]').each(function () {
                                var $this = $(this);
                                var metadataid = $this.attr('data-metadataid');
                                var metatypeid = $this.attr('data-metatypeid'),
                                        pCondition = $this.attr('data-package-condition');

                                if ($this.closest("div.ws-area").length > 0 && pCondition != '') {
                                    var wsArea = $this.closest("div.ws-area");
                                    workSpaceParams = $("div.ws-hidden-params", wsArea).find("input[type=hidden]").serializeArray();

                                    for (var fdata = 0; fdata < workSpaceParams.length; fdata++) {
                                        var mPath = /workSpaceParam\[([\w.]+)\]/g.exec(workSpaceParams[fdata].name);
                                        if (mPath === null)
                                            continue;

                                        var regExp = new RegExp(mPath[1], "g"), criVal = null;
                                        if (workSpaceParams[fdata].value) {
                                            criVal = workSpaceParams[fdata].value;
                                        }
                                        pCondition = pCondition.trim().replace(regExp, criVal);
                                    }
                                }

                                if (pCondition != '' && eval(pCondition)) {
                                    packageRenderType(metadataid, metatypeid, this, '', '<?php echo $this->metaDataId; ?>');
                                    $this.closest('.package-meta').find('div' + $this.attr('href')).parent().show();
                                } else if (pCondition != '' && !eval(pCondition)) {
                                    $this.closest('.package-meta').find('div' + $this.attr('href')).parent().hide();
                                } else if (pCondition == '') {
                                    packageRenderType(metadataid, metatypeid, this, '', '<?php echo $this->metaDataId; ?>');
                                }
                            });
                        } else {
                            $("div#package-meta-1565410157968").find('a[data-metadataid]').each(function () {
                                var $this = $(this);
                                var metadataid = $this.attr('data-metadataid');
                                var metatypeid = $this.attr('data-metatypeid'),
                                        pCondition = $this.attr('data-package-condition');

                                if ($this.closest("div.ws-area").length > 0 && pCondition != '') {
                                    var wsArea = $this.closest("div.ws-area");
                                    workSpaceParams = $("div.ws-hidden-params", wsArea).find("input[type=hidden]").serializeArray();

                                    for (var fdata = 0; fdata < workSpaceParams.length; fdata++) {
                                        var mPath = /workSpaceParam\[([\w.]+)\]/g.exec(workSpaceParams[fdata].name);
                                        if (mPath === null)
                                            continue;

                                        var regExp = new RegExp(mPath[1], "g"), criVal = null;
                                        if (workSpaceParams[fdata].value) {
                                            criVal = workSpaceParams[fdata].value;
                                        }
                                        pCondition = pCondition.trim().replace(regExp, criVal);
                                    }
                                }

                                if (pCondition != '' && eval(pCondition)) {
                                    packageRenderType(metadataid, metatypeid, this, '', '<?php echo $this->metaDataId; ?>');
                                    $this.closest('.package-meta').find('div' + $this.attr('href')).parent().show();
                                } else if (pCondition != '' && !eval(pCondition)) {
                                    $this.closest('.package-meta').find('div' + $this.attr('href')).parent().hide();
                                } else if (pCondition == '') {
                                    packageRenderType(metadataid, metatypeid, this, '', '<?php echo $this->metaDataId; ?>');
                                }
                            });
                        }
                        *///$getWsActiveMenu.find('a').trigger('click');
                    }
                } else {
                    $.ajax({
                        type: 'post',
                        url: 'mdworkspace/index/' + metaDataId,
                        data: {selectedRow: review},
                        beforeSend: function () {
                            Core.blockUI({
                                message: 'Loading...',
                                boxed: true
                            });
                        },
                        success: function (data) {
                            $div.empty().append(data + '<div class="clearfix"/>');
//                            $div.find('.page-content .workspace-main').addClass('content-wrapper');
                        }
                    }).done(function () {
                        Core.unblockUI();
                    });
                }
            } else if (typeId == 'content') {
                $.ajax({
                    type: 'post',
                    url: 'mdlayout/index/' + metaDataId,
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        $div.empty().append(data + '<div class="clearfix"/>');
                    }
                }).done(function () {
                    Core.initAjax($div);
                    Core.unblockUI();
                });
            } else if (typeId == '<?php echo Mdmetadata::$packageMetaTypeId ?>') {
                $.ajax({
                    type: 'post',
                    url: 'mdobject/package/' + metaDataId,
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        $div.empty().append(data + '<div class="clearfix"/>').promise().done(function () {
                            try {
                                if (typeof window['renderCondition_' + metaDataId] === 'function') { 
                                    window['renderCondition_' + metaDataId]()
                                } else {
                                }
                            } catch(err) {
                                console.log('renderCondition_ : ' + err);
                            }
                        });
                    }
                }).done(function () {
                    Core.unblockUI();
                });
            } else if (typeId == '<?php echo Mdmetadata::$layoutMetaTypeId ?>') {
                $.ajax({
                    type: 'post',
                    url: 'mdlayoutrender/index/' + metaDataId,
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        var jsonObj = JSON.parse(data);
                        if ('Html' in Object(jsonObj)) {
                            $div.empty().append(jsonObj.Html + '<div class="clearfix"/>');
                        } else {
                            $div.empty().append(data + '<div class="clearfix"/>');
                        }
                    }
                }).done(function () {
                    Core.unblockUI();
                });
            } else if (typeId == 'calendar') {
                $.ajax({
                    type: 'post',
                    url: 'mdcalendar/calendarRenderByPost',
                    data: {
                        metaDataId: metaDataId
                    },
                    beforeSend: function () {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                    },
                    success: function (data) {
                        var jsonObj = JSON.parse(data);
                        if ('Html' in Object(jsonObj)) {
                            $div.empty().append(jsonObj.Html + '<div class="clearfix"/>');
                        } else {
                            $div.empty().append(data + '<div class="clearfix"/>');
                        }
                    }
                }).done(function () {
                    Core.unblockUI();
                });
            }
        }
    }

    $("div#object-value-list-<?php echo $this->metaDataId; ?> form").on('change', 'input, select', function () {
        var formData = $("div#object-value-list-<?php echo $this->metaDataId; ?> form").serializeArray();

        if (formData) {
            for (var fdata = 0; fdata < formData.length; fdata++) {
                var mPath = /param\[([\w.]+)\]/g.exec(formData[fdata].name);
                if (mPath === null)
                    continue;
                // if(mPath === null || formData[fdata].value === '') continue;

                mPath = mPath[1].toLowerCase();
                if (mPath === 'booktypeid') {
                    $('div#dataViewStructureTreeView_<?php echo $this->metaDataId; ?>', windowId_<?php echo $this->metaDataId; ?>).jstree('search', formData[fdata].value + '$$$BOOK_TYPE_ID');
                }
            }
        }
    });

    $('.workflow-btn-<?php echo $this->metaDataId ?>').on('click', function (e, type) {
        $('.workflow-dropdown-<?php echo $this->metaDataId ?>').empty();
        var rows = getDataViewSelectedRows('<?php echo $this->metaDataId ?>'),
                wfmIcon = '',
                wfmActions = [];

        if (rows.length === 0) {
            alert("Та мөр сонгоно уу!");
            return;
        }
        var row = rows[0], isManyRows = '';
        if (rows.length > 1) {
            row = rows;
            isManyRows = '1';
        }

        if (typeof type !== 'undefined') {
            wfmIcon = '<i class="fa icon-shuffle"></i> ';
        }

        $.ajax({
            type: 'post',
            url: 'mdobject/getWorkflowNextStatus',
            data: {metaDataId: '<?php echo $this->metaDataId ?>', dataRow: row, isManyRows: isManyRows},
            dataType: "json",
            async: false,
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (response) {
                if (response.status === 'success') {

                    if (response.datastatus && response.data) {
                        var rowId = '';

                        if (typeof row.id !== 'undefined') {
                            rowId = row.id;
                        }
                        var realWfmName = '';

                        $.each(response.data, function (i, v) {
                            var advancedCriteria = '';
                            if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                advancedCriteria = ' data-advanced-criteria="' + v.advancedCriteria.replace(/\"/g, '') + '"';
                            }

                            realWfmName = v.wfmstatusname;
                            if (typeof v.wfmstatusname != 'undefined' && typeof v.processname != 'undefined' && v.processname != '') {
                                v.wfmstatusname = v.processname;
                            }

                            if (isManyRows !== '') {
                                if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                    $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\', ' + undefined + ', ' + undefined + ', ' + undefined + ', ' + undefined + ', ' + undefined + ', \'' + isManyRows + '\', \'\');">' + v.wfmstatusname + '</a></li>');
                                    wfmActions.push({icon: wfmIcon, action: 'changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\', ' + undefined + ', ' + undefined + ', ' + undefined + ', ' + undefined + ', ' + undefined + ', \'' + isManyRows + '\', \'\')', name: v.wfmstatusname});
                                } else {
                                    var isIgnoreMultiRowRunBp = ('isignoremultirowrunbp' in Object(v) && v.isignoremultirowrunbp == '1') ? 1 : 0;
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && ((v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null) || isIgnoreMultiRowRunBp)) {
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '">' + v.wfmstatusname + ' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: wfmIcon, action: 'beforeSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '">' + v.wfmstatusname + ' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: wfmIcon, action: 'beforeHardSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\')', name: v.wfmstatusname});
                                        } else {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\', ' + undefined + ', ' + undefined + ', ' + undefined + ', ' + undefined + ', ' + undefined + ', \'' + isManyRows + '\', \'\');">' + v.wfmstatusname + '</a></li>');
                                            wfmActions.push({icon: wfmIcon, action: 'changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\', ' + undefined + ', ' + undefined + ', ' + undefined + ', ' + undefined + ', ' + undefined + ', \'' + isManyRows + '\', \'\')', name: v.wfmstatusname});
                                        }
                                    } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : '';
                                        var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\');">' + v.wfmstatusname + ' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action: 'transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\');">' + v.wfmstatusname + ' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action: 'transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\')', name: v.wfmstatusname});
                                        } else {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\');">' + v.wfmstatusname + '</a></li>');
                                            wfmActions.push({icon: wfmIcon, action: 'transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\')', name: v.wfmstatusname});
                                        }
                                    }
                                }
                            } else {
                                if (typeof v.wfmusedescriptionwindow != 'undefined' && v.wfmusedescriptionwindow == '0' && typeof v.wfmuseprocesswindow != 'undefined' && v.wfmuseprocesswindow == '0') {
                                    $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\');">' + v.wfmstatusname + '</a></li>');
                                    wfmActions.push({icon: wfmIcon, action: 'changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\')', name: v.wfmstatusname});
                                } else {
                                    if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="beforeSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '">' + v.wfmstatusname + ' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: wfmIcon, action: 'beforeSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="beforeHardSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + v.wfmstatusname + '\');" id="' + v.wfmstatusid + '">' + v.wfmstatusname + ' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: wfmIcon, action: 'beforeHardSignChangeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\')', name: v.wfmstatusname});
                                        } else {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\');">' + v.wfmstatusname + '</a></li>');
                                            wfmActions.push({icon: wfmIcon, action: 'changeWfmStatusId(this, \'' + v.wfmstatusid + '\', \'<?php echo $this->metaDataId ?>\', \'<?php echo $this->refStructureId ?>\', \'' + $.trim(v.wfmstatuscolor) + '\', \'' + realWfmName + '\')', name: v.wfmstatusname});
                                        }
                                    } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                                        var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : '';
                                        var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                                        if (v.wfmisneedsign == '1') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\');">' + v.wfmstatusname + ' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action: 'transferProcessAction(\'signProcess\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\')', name: v.wfmstatusname});
                                        } else if (v.wfmisneedsign == '2') {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li><a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\');">' + v.wfmstatusname + ' <i class="fa fa-key"></i></a></li>');
                                            wfmActions.push({icon: '<i class="fa fa-key"></i>', action: 'transferProcessAction(\'hardSignProcess\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\')', name: v.wfmstatusname});
                                        } else {
                                            $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" ' + advancedCriteria + ' onclick="transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\');">' + v.wfmstatusname + '</a></li>');
                                            wfmActions.push({icon: wfmIcon, action: 'transferProcessAction(\'\', \'<?php echo $this->metaDataId ?>\', \'' + v.wfmstatusprocessid + '\', \'' + metaTypeId + '\', \'toolbar\', this, {callerType: \'<?php echo $this->metaDataCode ?>\', isWorkFlow: true, wfmStatusId: \'' + v.wfmstatusid + '\', wfmStatusCode: \'' + wfmStatusCode + '\'}, \'dataViewId=<?php echo $this->metaDataId ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId=' + v.wfmstatusid + '&statusName=' + v.wfmstatusname + '&statusColor=' + $.trim(v.wfmstatuscolor) + '&rowId=' + rowId + '\')', name: v.wfmstatusname});
                                        }
                                    }
                                }
                            }
                        });
                    }

                    $('.workflow-dropdown-<?php echo $this->metaDataId ?>').append('<li>' + wfmIcon + '<a href="javascript:;" onclick="seeWfmStatusForm(this, \'<?php echo $this->metaDataId ?>\');">'+plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')+'</a></li>');
                    wfmActions.push({icon: wfmIcon, action: 'seeWfmStatusForm(this, \'<?php echo $this->metaDataId ?>\')', name: plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах')});

                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: response.message,
                        type: response.status,
                        addclass: pnotifyPosition,
                        sticker: false
                    });
                }
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });
    
    $(function () {
    
        var $leftSidebarContent = $("#dvecommerce_<?php echo $this->metaDataId; ?>").find('.left-sidebar-content:eq(0)'),
            $rightSidebarContent = $("#dvecommerce_<?php echo $this->metaDataId; ?>").find('.right-sidebar-content-for-resize:eq(0)'),
            totalContentWidth = $leftSidebarContent.width() + $rightSidebarContent.width();
            
            $leftSidebarContent.attr('style', 'height: ' + ($(window).height()-174) + 'px; width: 22%;');
        if (!$leftSidebarContent.hasClass("ui-resizable") && $().resizable) {
            $leftSidebarContent.resizable({
                autoHide: true,
                maxHeight: $(window).height()-84,
                minHeight: $(window).height()-84,
                start: function (event, ui) {
                    $(this).addClass("highliteShape");
                },
                stop: function (event, ui) {
                    $(this).removeClass("highliteShape");
                }
            });

            $leftSidebarContent.on("resizestop", function( event, ui ) {
                var rightSideWidth = totalContentWidth - $(event.target).width();
                $rightSidebarContent.css('width', rightSideWidth + 'px');
                
                if ($rightSidebarContent.find('.panel-eui.datagrid').length > 0) {
                    $rightSidebarContent.find('.panel-eui.datagrid').parent().each(function (indexDv, rowDv) {
                        window['objectdatagrid_' + $(rowDv).attr('dv-metadataid')].datagrid('resize', {width: rightSideWidth-50});
                    });
                }
            });
        }
    });
    
</script>