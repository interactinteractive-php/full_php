<?php
if ($this->isAjax == false) {
    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li>
                    <a href="#app_tab_<?php echo $this->metaDataId; ?>" class="active" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span></a>
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
                    <?php if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU && isset($this->needTitle) && $this->needTitle && isset($this->chooseTypeBasket) && $this->chooseTypeBasket == '0' && !isset($this->row['getDataViewLayoutTypesModel']['ecommerce'])) { ?>
                        <div class="meta-toolbar">
                            <span class="text-uppercase"><?php echo $this->title; ?></span>
                            <div class="float-right dv-process-buttons-<?php echo $this->metaDataId ?>"></div>
                        </div>
                    <?php } ?>
                    <div id="object-value-list-<?php echo $this->metaDataId; ?>" class="<?php echo issetParam($this->row['FORM_CONTROL']) === '1' ?  'min-formcontrol' : '' ?> main-dataview-container main-action-meta" data-folder-id="<?php echo $this->folderId; ?>" data-process-id="<?php echo $this->metaDataId; ?>" data-meta-type="dv" data-meta-code="<?php echo $this->metaDataCode; ?>">
                        <div class="render-object-viewer">
                            <div class="row">
                                <div class="col-md-12 dataview-search-filter display-none"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row viewer-container sidebar-right-visible"><?php echo $this->dataValueViewer; ?></div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row viewer-dashboard-container"></div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="valueViewerType"/>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    } else {
?>
<div id="object-value-list-<?php echo $this->metaDataId; ?>" class="col-md-12 <?php echo issetParam($this->row['FORM_CONTROL']) === '1' ?  'min-formcontrol' : '' ?> main-dataview-container main-action-meta" data-folder-id="<?php echo $this->folderId; ?>" data-process-id="<?php echo $this->metaDataId; ?>" data-meta-type="dv" data-meta-code="<?php echo $this->metaDataCode; ?>">
    <div class="card light shadow mb0">
        <div class="card-title tabbable-line">
            <div class="caption buttons">
                <?php
                echo html_tag('a', 
                    array(
                        'href' => $this->metaBackLink, 
                        'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10'
                    ), 
                    '<i class="icon-arrow-left7"></i>', 
                    $this->isBackLink 
                );
                ?>                          
            </div>
            <div class="card-title">
                <span class="caption-subject font-weight-bold uppercase card-subject-blue">
                    <?php echo $this->title; ?>
                </span>
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body <?php echo ($this->isDashboard) ? 'pt0' : '' ?>">
            <div class="render-object-viewer">
                <div class="row">
                    <div class="col-md-12 dataview-search-filter display-none"></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row viewer-container sidebar-right-visible"><?php echo $this->dataValueViewer; ?></div>
                    </div>
                    <div class="col-md-12">
                        <div class="row viewer-dashboard-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="valueViewerType"/>
</div>      
<?php
    }
} else {
    $isEmptyTitle = isset($this->isEmptyTitle) ? $this->isEmptyTitle : false;
?>
<div class="<?php echo (isset($this->isDrilldown) && $this->isDrilldown) ? '' : 'row'; ?>">
    <?php if (isset($this->isNeedTitle) && $this->isNeedTitle == '1') { ?>
        <div class="col-md-12">
            <div class="card light shadow bordered<?php echo $isEmptyTitle ? ' dv-empty-title' : ''; ?>">
                <?php
                if (!$isEmptyTitle) {
                ?>
                <div class="card-header card-header-no-padding header-elements-inline">
                    <div class="card-title">
                        <i class="fa fa-list font-green-sharp"></i>
                        <span class="caption-subject font-green-sharp font-weight-bold uppercase" id="calendar-title-1484798277318563"><?php echo $this->title; ?></span>
                        <span class="caption-helper"></span>
                    </div>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="fullscreen"></a>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="card-body">
                    <div class="tab-content card-multi-tab-content">
                        <div class="tab-pane active" id="app_tab_<?php echo $this->metaDataId; ?>">
                            <div id="object-value-list-<?php echo $this->metaDataId; ?>" class="<?php echo issetParam($this->row['FORM_CONTROL']) === '1' ?  'min-formcontrol' : '' ?> main-dataview-container main-action-meta" data-folder-id="<?php echo $this->folderId; ?>" data-process-id="<?php echo $this->metaDataId; ?>" data-meta-type="dv" data-meta-code="<?php echo $this->metaDataCode; ?>">
                                <div class="render-object-viewer">
                                    <div class="row">
                                        <div class="col-md-12 dataview-search-filter display-none"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="row viewer-container sidebar-right-visible"><?php echo $this->dataValueViewer; ?></div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row viewer-dashboard-container"></div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="valueViewerType"/>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php 
    } else { 
        if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU && isset($this->needTitle) && $this->needTitle && isset($this->chooseTypeBasket) && $this->chooseTypeBasket == '0' && !isset($this->row['getDataViewLayoutTypesModel']['ecommerce'])) { 
    ?>
        <div class="col-md-12">
            <div class="meta-toolbar">
                <span class="text-uppercase"><?php echo $this->title; ?></span>
                <div class="float-right dv-process-buttons-<?php echo $this->metaDataId ?>"></div>
            </div>
        </div>
    <?php } ?>
        <div id="object-value-list-<?php echo $this->metaDataId; ?>" class="col-md-12 <?php echo issetParam($this->row['FORM_CONTROL']) === '1' ?  'min-formcontrol' : '' ?> main-dataview-container main-action-meta <?php echo isset($this->useBasket) && $this->useBasket ? 'dialog-ecommerce-basket' : ''; ?>" data-folder-id="<?php echo $this->folderId; ?>" data-process-id="<?php echo $this->metaDataId; ?>" data-meta-code="<?php echo $this->metaDataCode; ?>" data-meta-type="dv">
            <div class="render-object-viewer">
                <div class="row">
                    <div class="col-md-12 dataview-search-filter display-none"></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row viewer-container sidebar-right-visible"><?php echo $this->dataValueViewer; ?></div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="valueViewerType"/>
        </div>     
    <?php } ?>
</div>    
<?php
}
?>

<script type="text/javascript">
var objectWindow_<?php echo $this->metaDataId; ?> = 'div#object-value-list-<?php echo $this->metaDataId; ?>';

<?php if (isset($this->backTargetLink)) { ?>
    function backTargetLink_<?php echo $this->backTargetLink ?>(element) {
        var backId_<?php echo $this->metaDataId; ?> =  $(element).attr('data-back-id');
        $('a[attr-' + backId_<?php echo $this->metaDataId; ?> + '="'+ backId_<?php echo $this->metaDataId; ?> +'"]').trigger('click');
        $(element).hide();
    }
<?php } ?>

function dataViewer_<?php echo $this->metaDataId; ?>(elem, viewType, metaDataId, callback) {
    var $elem = $(elem);
    var postData = {
        viewType: viewType, 
        <?php
        if ($this->uriParams) {
            echo 'uriParams: \''.$this->uriParams.'\','."\n";
        }
        if (isset($this->calendarParams) && $this->calendarParams) {
            echo 'calendarParams: \''.$this->calendarParams.'\','."\n";
        }
        if ($this->workSpaceId) {
            echo 'workSpaceId: \''.$this->workSpaceId.'\','."\n";
            echo 'workSpaceParams: \''.htmlentities($this->workSpaceParams, ENT_QUOTES, 'utf-8').'\','."\n";
        }
        if ($this->permissionCriteria != '') {
            echo 'permissionCriteria: \''.$this->permissionCriteria.'\','."\n";
        }
        if ($this->dvDefaultCriteria != '') {
            echo 'dvDefaultCriteria: '.json_encode($this->dvDefaultCriteria).','."\n";
        }
        if ($this->dataGridDefaultHeight != '') {
            echo 'dataGridDefaultHeight: \''.$this->dataGridDefaultHeight.'\','."\n";
        }
        if ($this->drillDownDefaultCriteria != '') {
            echo 'drillDownDefaultCriteria: \''.$this->drillDownDefaultCriteria.'\','."\n";
        }
        if (isset($this->isDynamicHeight) && $this->isDynamicHeight == '0') {
            echo 'isDynamicHeight: \''.$this->isDynamicHeight.'\','."\n";
        }
        if (isset($this->ajaxSync)) {
            echo 'async: '.$this->ajaxSync.',';
        }
        if (isset($this->isSelectedBasket)) {
            echo 'isSelectedBasket: '.$this->isSelectedBasket.',';
        }
        if (isset($this->selectedRowData)) {
            echo 'selectedRowData: '. json_encode($this->selectedRowData) . ',';
        }
        if (isset($this->chooseTypeBasket)) {
            echo 'chooseTypeBasket: \''.$this->chooseTypeBasket.'\','."\n";
        }
        if (Input::post('ignorePermission') == 1) {
            echo 'ignorePermission: 1,';
        }
        if (Input::post('dvIgnoreToolbar') == 1) {
            echo 'dvIgnoreToolbar: 1,'; 
        } 
        if (Input::isEmpty('proxyId') == false) {
            echo "proxyId: '".Input::post('proxyId')."',"; 
        }
        if (Input::isEmpty('runSrcMetaId') == false) {
            echo "runSrcMetaId: '".Input::post('runSrcMetaId')."',"; 
        }
        ?>
        metaDataId: metaDataId, 
        callerType: 'changeViewer'
    };

    if ($elem.length && ($elem.hasClass('btn') || $elem.hasClass('fc-button') || $elem.hasClass('dropdown-item'))) {
        postData.isSaveViewer = 1;
        
        var $package = $elem.closest('.dvecommerce-package');
        
        if ($package.length) {
            postData.callerType = 'package';
            
            var $packageRenderType = $package.closest('[data-package-rendertype]');
            if ($packageRenderType.length) {
                postData.packageRenderType = $packageRenderType.attr('data-package-rendertype');
            }
        }
    }
        
    $.ajax({
        type: 'post',
        url: 'mdobject/dataValueViewer/<?php echo $this->hiddenFields; ?>',
        data: postData,
        <?php
        if (isset($this->ajaxSync)) {
            echo 'async:'.$this->ajaxSync.',';
        }
        ?>
        success: function(data) {
            
            var $objectWindow = $(objectWindow_<?php echo $this->metaDataId; ?>);
            var $viewer = $objectWindow.find('.viewer-container');
            
            $objectWindow.find('input#valueViewerType').val(viewType);
            $viewer.empty().append(data);
            
            if (typeof callback === 'function') {
                callback();
            }
        }
    });
}
</script>