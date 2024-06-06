<?php
if ($this->isAjax == false) {
    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li>
                    <a href="#app_tab_mdpivotindex" class="active" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span></a>
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
                <div class="tab-pane active" id="app_tab_mdpivotindex">
                    <?php echo $this->layout; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    } else {
?>
<div class="col-md-12">
    <div class="card light shadow mb0">
        <div class="card-title tabbable-line">
            <div class="card-title">
                <span class="caption-subject font-weight-bold uppercase card-subject-blue">
                    <?php echo $this->title; ?>
                </span>
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php echo $this->layout; ?>
        </div>
    </div>
</div>      
<?php
    }
} else {
?>
<div class="row">
    <?php echo $this->layout; ?>
</div>    
<?php
}
?>
<div class="clearfix w-100"></div>