<?php
if ($this->contentMetas) {
    if (count($this->contentMetas) > 1) {
        $tabeHead = '';
        $tabeContent = '';
        foreach ($this->contentMetas as $k=>$row) {
            $active = '';
            $content = '';
            if ($k == 0) {
                $active = 'active';
                $content = (new Mdlayout())->contentRenderById($row['META_DATA_ID']);        
            }

            $tabeHead .= '
                <li class="nav-item">
                    <a href="#tab_content_'.$row['META_DATA_ID'].'" class="nav-link '.$active.'" data-toggle="tab">'.$row['META_DATA_NAME'].'</a>
                </li>';
            $tabeContent .= '
                <div class="tab-pane '.$active.'" id="tab_content_'.$row['META_DATA_ID'].'">
                    '.$content.'
                </div>';
        }
?>
<div class="col-md-12" id="meta-home-content">
    <div class="tabbable-line tabbable-tabdrop tab-not-back-color tab-not-padding-top">
        <ul class="nav nav-tabs">
            <?php echo $tabeHead; ?>
        </ul>
        <div class="tab-content">
            <?php echo $tabeContent; ?>
        </div>
    </div>
</div>    
<?php
    } else {
        if (Config::getFromCache('CONFIG_MULTI_TAB')) {
            if ($this->isAjax) {
?>
<div id="meta-home-content">
    <div class="row">
        <div class="col-md-12">
        <?php
        echo (new Mdlayout())->contentRenderById($this->contentMetas[0]['META_DATA_ID']);
        ?>
        </div>
    </div>    
</div>   
<?php
            } else {
?>
<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-title tabbable-line tabbable-tabdrop">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li data-type="layout">
                    <a href="#app_tab_<?php echo $this->metaDataId; ?>" class="nav-link active" data-toggle="tab">
                        <i class="fa fa-caret-right"></i> <?php echo $this->title; ?>
                        <span><i class="fa fa-times-circle"></i></span>
                    </a>
                </li>
            </ul>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="app_tab_<?php echo $this->metaDataId; ?>">
                    <div id="meta-home-content">
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo (new Mdlayout())->contentRenderById($this->contentMetas[0]['META_DATA_ID']); ?>
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
            }
        } else {
?>
<div id="meta-home-content">
    <div class="col-md-12">
    <?php
    echo (new Mdlayout())->contentRenderById($this->contentMetas[0]['META_DATA_ID']);
    ?>
    </div>
</div>
<?php    
        }
    }
}
?>

<script type="text/javascript">
var contentWindow = "#meta-home-content";    
$(function(){
    $('a[data-toggle="tab"]', contentWindow).on('shown.bs.tab', function (e) {
        var _this = $(e.target);
        var _href = _this.attr("href").split("_");
        var contentId = _href[2];
        $.ajax({
            type: 'post',
            url: 'mdlayout/contentRenderByPost',
            data: {metaDataId: contentId},
            beforeSend: function(){
                Core.blockUI({
                    animate: true
                });
            },
            success: function (dataHtml) {
                $('div#tab_content_'+contentId, contentWindow).html(dataHtml);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });
});    
$('.oneSearchBtn').on('click', function () {
    var $metaDataId = $('#joinMetaDataId').val();
    var $chartType = $('#joinChartType').val();
    var $metaDataIds = $metaDataId.split(',');
    var $chartTypes = $chartType.split(',');
    
    $(".dashboard-right-stoggler").trigger('click');
    
    $.each($metaDataIds, function(key, metaDataId) {
        if ($chartTypes[key] == 'am_dual' || $chartTypes[key] == 'am_column' || $chartTypes[key] == 'am_bar' || $chartTypes[key] == 'am_donut' || $chartTypes[key] == 'am_pie' || $chartTypes[key] == 'am_serial') {
            ChartsAmcharts.drawChartAmchart($('#one-default-criteria-form').serialize(), $chartTypes[key], metaDataId);
        }
        else {
            drawChart($('#one-default-criteria-form').serialize(), $chartTypes[key], metaDataId);
        }
    });
});
$('.reset-oneSearchForm').on('click', function () {
    var $metaDataId = $('#joinMetaDataId').val();
    var $chartType = $('#joinChartType').val();
    var $metaDataIds = $metaDataId.split(',');
    var $chartTypes = $chartType.split(',');
    
    $('#one-default-criteria-form')[0].reset();
    $(".dashboard-right-stoggler").trigger('click');
    $.each($metaDataIds, function(key, metaDataId) {
        if ($chartTypes[key] == 'am_dual' || $chartTypes[key] == 'am_column' || $chartTypes[key] == 'am_bar' || $chartTypes[key] == 'am_donut' || $chartTypes[key] == 'am_pie' || $chartTypes[key] == 'am_serial') {
            ChartsAmcharts.drawChartAmchart($('#one-default-criteria-form').serialize(), $chartTypes[key], metaDataId);
        }
        else {
            drawChart($('#one-default-criteria-form').serialize(), $chartTypes[key], metaDataId);
        }
    });
});
</script>