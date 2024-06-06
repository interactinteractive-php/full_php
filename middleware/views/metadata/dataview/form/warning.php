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
                    
                    <div id="object-value-list-<?php echo $this->metaDataId; ?>" class="main-dataview-container main-action-meta" data-folder-id="<?php echo $this->folderId; ?>" data-process-id="<?php echo $this->metaDataId; ?>" data-meta-type="dv">
                        <div class="render-object-viewer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-warning"><?php echo $this->message; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    
                    <div class="clearfix w-100"></div> 
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    } else {
?>
<div class="col-md-12 main-dataview-container main-action-meta" id="object-value-list-<?php echo $this->metaDataId; ?>" data-folder-id="<?php echo $this->folderId; ?>" data-process-id="<?php echo $this->metaDataId; ?>" data-meta-type="dv">
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
                    <div class="col-md-12">
                        <div class="alert alert-warning"><?php echo $this->message; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>      
<?php
    }
} else {
?>
<div class="row">
    <div class="col-md-12 main-dataview-container main-action-meta" id="object-value-list-<?php echo $this->metaDataId; ?>" data-folder-id="<?php echo $this->folderId; ?>" data-process-id="<?php echo $this->metaDataId; ?>" data-meta-type="dv">
        <div class="render-object-viewer">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning"><?php echo $this->message; ?></div>
                </div>
            </div>
        </div>
    </div> 
</div>    
<?php
}
?>
<div class="clearfix w-100"></div>