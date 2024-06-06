<script type="text/javascript">
$(function(){
});
function dataViewAll(elem) {
    // var did = $(elem).data('dataviewid');
    // var getCustomerItems = $.ajax({
    //     type: "post",
    //     url: "mdmetadata/getMetaDataDrill/"+did,
    //     dataType: "json",
    //     async: false,
    //     success: function (data) {
    //         Core.unblockUI();
    //         return data.result;
    //     },
    // });    
    // appMultiTab({type: 'dataview', metaDataId: getCustomerItems.responseJSON.META_DATA_ID, title: getCustomerItems.responseJSON.META_DATA_NAME});
    // Core.blockUI({
    //     message: "Loading...",
    //     boxed: true,
    // });        
    var metaDataId = $(elem).data('dataviewid');
    if (metaDataId === '') {
        alert('Metadata ID хоосон байна!');
        return;
    }
    var getCustomerItems = $.ajax({
        type: "post",
        url: "mdmetadata/getMetaDataDrill/"+metaDataId,
        dataType: "json",
        async: false,
        success: function (data) {
            Core.unblockUI();
            return data.result;
        },
    });
    if (getCustomerItems.responseJSON.META_TYPE_CODE == 'BOOKMARK') {
        appMultiTab({weburl: getCustomerItems.responseJSON.BOOKMARK_URL, metaDataId: getCustomerItems.responseJSON.BOOKMARK_URL+'223kdlfoeor666', title: getCustomerItems.responseJSON.META_DATA_NAME, type: 'selfurl'});
    } else if (getCustomerItems.responseJSON.META_TYPE_CODE == 'PACKAGE') {
        appMultiTab({type: 'package', metaDataId: getCustomerItems.responseJSON.META_DATA_ID, title: getCustomerItems.responseJSON.META_DATA_NAME});
    } else {
        gridDrillDownLink(elem, getCustomerItems.responseJSON.META_DATA_CODE, getCustomerItems.responseJSON.META_TYPE_CODE.toLowerCase(), '1', '',  '', '',metaDataId, '', true, true)
    }    
}
function widgetLayoutCallDiagramByMeta(metaDataId, executeType) {
    var $layout = $("div#widget-layout-" + metaDataId);
    var workSpaceId = '', workSpaceParams = '';

    if ($layout.closest('div.ws-area').length > 0) {
        var $wsArea = $layout.closest('div.ws-area');
        var workSpaceIdAttr = $wsArea.attr('id').split('-');
        workSpaceId = workSpaceIdAttr[2];
        workSpaceParams = $("div.ws-hidden-params", $wsArea).find("input[type=hidden]").serialize();
    }

    if (typeof executeType === 'undefined') {
        executeType = '<?php echo isset($this->executeType) ? $this->executeType : ''; ?>';
    }

    $.ajax({
        type: 'post',
        url: 'mddashboard/diagramRenderByPost',
        data: {
            metaDataId: metaDataId,
            executeType: executeType == 'refreshTimer' ? '' : executeType,
            workSpaceParams: workSpaceParams,
            workSpaceId: workSpaceId,
            setHeight: 350
        },
        dataType: "json",
        beforeSend: function () {
        },
        success: function (data) {
            $("div#widget-layout-" + metaDataId).empty().append(data.Html);
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        $("div#widget-layout-" + metaDataId).find('.mddashboard-card-title').addClass('hidden');
        $("div#widget-layout-" + metaDataId).find('.mddashboard-card').removeClass('bordered').attr('style', 'padding:0 !important');
        $layout.attr('data-fetched', true);
        Core.initAjax($("div#widget-layout-" + metaDataId));
    });
}
</script>

<link rel="stylesheet" href="assets/custom/css/tailwind.min.css">
<style type="text/css">
    /* .content-wrapper .content {
        padding-left: 0px;
    } */
    .widget-container {
        margin-left: -15px;
        margin-right: -15px;
    }
    .widget-container .page-main-layout-<?php echo $this->metaDataId ?> {
        margin-left: -15px;
        margin-right: -15px;
        font-family: "Rubik", sans-serif !important;
    }
    .shadow-citizen {
        /* box-shadow: 0 0 #0000, 0 0 #0000, 0px 20px 27px 0px rgba(0, 0, 0, 0.05); */
        box-shadow: 0px 2px 14px rgba(0, 0, 0, 0.1);
    }
    .p-4 {
        padding: 1rem !important;
    }    
    .p-3 {
        padding: .75rem !important;
    }   
    .mb-5 {
      margin-bottom: 1.25rem !important;
    }
    .page-content > .content-wrapper > .content {
        padding-bottom: 0 !important;
    }     
    .bg-ssoSecond {
        background-color: rgba(67, 56, 202, 1);
    }   
    .text-ssoSecond {
        color: rgba(67, 56, 202, 1);
    }     
    .hover\:bg-ssoSecond:hover {
        background-color: rgba(67, 56, 202, 1) !important;
    }    
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, #4338CA, #4338ca75, rgba(67, 56, 202, 0));
    }   
    .hover\:text-white:hover {
        color: rgba(255, 255, 255, 1);
    }
    .hover\:text-white:hover i {
        color: #fff;
    }
    .hover\:from-sso:hover {
        background-color: #B2E392 !important;
    }    
    .cloud-font-color-black {
        color: #333;
    }
    div.datepicker .table-sm {
        width:100%;
        height: 500px;
    }    
    div.datepicker .table-sm td, div.datepicker .table-sm th {
        font-size: 16px;
    }    
    div.datepicker {
        background-color: #F7F8FF;
        padding-top: 30px;
        margin-top: 20px;
        padding-bottom: 30px;
        border-radius: 1rem !important;
        margin-right: 20px;
    }   
    .cloud-grid-icon i {
        color:#B2E392
    } 
    .cloud-grid-icon {
        text-align: center;
        margin-top:10px;
    }
    .cloud-modulelist-tab {
        margin-top: 20px;
    } 
    .cloud-modulelist-tab li {
        display: inline-block;
        font-size: 14px;
        color: #585858;        
        margin-right: 25px;
        cursor: pointer;
        font-weight: bold;
    }
    .cloud-modulelist-tab li.active {
        color: #fff;        
        border-bottom: 2px solid #fff;
    }
    .cloud-badge {
        border-radius: 7px;
        font-size:14px;
        padding: 4px 13px 4px 13px;
        border-color:#D1D5DB !important;        
        color: #67748E !important;
        font-weight: normal;
        cursor: pointer;
    }
    .cloud-badge.active {
        border-color:#699BF7 !important;        
        color: #699BF7 !important
    }
</style>

<div class="widget-container">
<?php
if (Config::getFromCache('CONFIG_MULTI_TAB') && !$this->isAjax) { ?>
        <div class="col-md-12">
            <div class="card light shadow card-multi-tab">
                <div class="card-header header-elements-inline tabbable-line">
                    <ul class="nav nav-tabs card-multi-tab-navtabs">
                        <li data-type="layout">
                            <a href="#app_tab_<?php echo $this->metaDataId; ?>" class="nav-link active" data-title="<?php echo $this->title . ' - ' . issetParam($this->moduleName) ?>" data-toggle="tab"><i class="fa fa-caret-right"></i> <?php echo $this->title; ?><span><i class="fa fa-times-circle"></i></span></a>
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
                            <div class="layout-fullscreen-btn">
                                <!--<button type="button" class="btn btn-sm btn-icon mr-1" title="Fullscreen" onclick="layoutFullScreen(this);">
                                    <i class="fa fa-expand"></i>
                                </button>-->
                                <button type="button" class="btn btn-sm btn-icon layout-manual-refresh-btn mr-1" title="Refresh" style="height: 22px;width: 22px;padding: 0;top: 28px;right: -27px;">
                                    <i class="fa fa-refresh"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon mr-1 layout-print-btn" title="<?php echo $this->lang->line('print_btn'); ?>" style="height: 22px;width: 22px;padding: 0;">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>
                            <?php echo $this->sectionHtml; ?>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
<?php } else {
    echo $this->sectionHtml; 
}
?>
</div>