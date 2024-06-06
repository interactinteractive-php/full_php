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
                    <div id="object-value-list-<?php echo $this->metaDataId; ?>" class="main-dataview-container main-action-meta pf-paneltype-dataview" data-folder-id="<?php echo $this->folderId; ?>" data-process-id="<?php echo $this->metaDataId; ?>" data-meta-type="dv" data-meta-code="<?php echo $this->metaDataCode; ?>" data-uniqid="<?php echo $this->uniqId; ?>">
                        <?php echo $this->panel; ?>
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
<div id="object-value-list-<?php echo $this->metaDataId; ?>" class="col-md-12 main-dataview-container main-action-meta pf-paneltype-dataview" data-folder-id="<?php echo $this->folderId; ?>" data-process-id="<?php echo $this->metaDataId; ?>" data-meta-code="<?php echo $this->metaDataCode; ?>" data-meta-type="dv" data-uniqid="<?php echo $this->uniqId; ?>">
    <?php echo $this->panel; ?>
</div>            
<?php
}
?>