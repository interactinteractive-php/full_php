<?php
if ($this->isAjax == false) {
?>
<div class="col-md-12 indicatorView">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li>
                    <a href="#app_tab_<?php echo $this->subGridUniqId; ?>" class="active" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span></a>
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
                <div class="tab-pane active" id="app_tab_<?php echo $this->subGridUniqId; ?>">
                    <div id="object-value-list-<?php echo $this->subGridUniqId; ?>">
                        <div class="render-object-viewer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row viewer-container">
                                        <?php echo $this->renderGrid; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
?>
<div class="row indicatorView">
    <div id="object-value-list-<?php echo $this->subGridUniqId; ?>" class="col-md-12">
        <div class="render-object-viewer">
            <div class="row">
                <div class="col-md-12">
                    <div class="row viewer-container">
                        <?php echo $this->renderGrid; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>     
</div>    
<?php
}
?>
<script type="text/javascript">
    function kpiIndicatorViewList_<?php echo $this->subGridUniqId ?> (e, indicatorId) {
        var _this = $(e),
            _viewContent = _this.closest('.indicatorView');

        $.ajax({
            type: 'post',
            url: 'mdform/indicatorList/' + indicatorId + '/1',
            data: {indicatorId: indicatorId, 'isJson': '1'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                if (data.html != '') {
                    _viewContent.parent().empty().append(data.html).promise().done(function() {
                        Core.initNumberInput(_viewContent);
                        Core.initLongInput(_viewContent);
                        Core.initDateInput(_viewContent);
                        Core.initSelect2(_viewContent);
                        Core.unblockUI();
                    });
                }
            }
        });
    }
    function kpiIndicatorViewCalendar_<?php echo $this->subGridUniqId ?> (e, indicatorId) {
        var _this = $(e),
            _viewContent = _this.closest('.indicatorView');

        $.ajax({
            type: 'post',
            url: 'mdform/indicatorRelationView/' + indicatorId + '/1',
            data: {indicatorId: indicatorId, 'isJson': '1'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                if (data.html != '') {
                    _viewContent.parent().empty().append(data.html).promise().done(function() {
                        Core.initNumberInput(_viewContent);
                        Core.initLongInput(_viewContent);
                        Core.initDateInput(_viewContent);
                        Core.initSelect2(_viewContent);
                        Core.unblockUI();
                    });
                }
            }
        });
    }
</script>