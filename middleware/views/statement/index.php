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
                    <div id="statement-area-<?php echo $this->metaDataId; ?>">
                        <div class="render-object-viewer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row viewer-container"></div>
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
<div class="col-md-12" id="statement-area-<?php echo $this->metaDataId; ?>">
    <div class="card light shadow">
        <div class="card-header card-header-no-padding header-elements-inline">
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
        <div class="card-body">
            <div class="render-object-viewer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row viewer-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo Form::hidden(array('id'=>'reportViewerType')); ?>
</div>    
<?php
    }
} else {
?>
<div class="row">
    <div class="col-md-12" id="statement-area-<?php echo $this->metaDataId; ?>">
        <div class="row viewer-container"></div>
        <?php echo Form::hidden(array('id'=>'reportViewerType')); ?>
    </div> 
</div>    
<?php
}
?>
<div class="clearfix w-100"></div>

<script type="text/javascript">
var statementWindow_<?php echo $this->metaDataId; ?> = 'div#statement-area-<?php echo $this->metaDataId; ?>';
$(function(){
    $("a[data-value='<?php echo $this->reportType; ?>']", statementWindow_<?php echo $this->metaDataId; ?>).addClass("active");
    statementViewer_<?php echo $this->metaDataId; ?>($("a[data-value='<?php echo $this->reportType; ?>']", statementWindow_<?php echo $this->metaDataId; ?>), '<?php echo $this->reportType; ?>', '<?php echo $this->metaDataId; ?>');
});    
function statementViewer_<?php echo $this->metaDataId; ?>(elem, viewType, metaDataId){
    $.ajax({
        type: 'post',
        url: 'mdstatement/reportViewer',
        data: {
            viewType: viewType, 
            metaDataId: metaDataId, 
            fillData: <?php echo json_encode($this->fillData, JSON_UNESCAPED_UNICODE) ?>, 
            autoSearch: '<?php echo issetVar($this->autoSearch); ?>'
        },
        beforeSend: function(){
            Core.blockUI({
                message: 'Loading...', 
                boxed: true
            });
        },
        success: function(data){
            $('.viewer-container', statementWindow_<?php echo $this->metaDataId; ?>).empty().append(data);
            $('input#reportViewerType', statementWindow_<?php echo $this->metaDataId; ?>).val(viewType);
            Core.unblockUI();
        }
    }).done(function(){
        Core.initAjax($('.viewer-container', statementWindow_<?php echo $this->metaDataId; ?>));
    });
}
</script>