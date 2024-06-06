<div class="dvecommerce dvecommerce-<?php echo $this->uniqid ?>">
    <div class="center-sidebar col-md-12">
        <div style="margin: 0 -20px;" class="row">
            <div class="hidden" id="sidebarLeft-<?php echo $this->uniqid ?>">
                <?php //echo isset($this->sideBarLeft) ? $this->sideBarLeft : ''; ?>
            </div>
            <div class="content-wrapper">
                <div class="col-md-12 colplr">
                    <div class="barimts">
                        <?php echo isset($this->templateRes['Html']) ? $this->templateRes['Html'] : ''; ?>
                    </div>
                    <div class="clearfix w-100"></div>
                </div>
            </div>
            <div id="sidebarRight-<?php echo $this->uniqid ?>">
                <?php echo isset($this->sideBarRight) ? $this->sideBarRight : ''; ?>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    #sidebarRight-<?php echo $this->uniqid ?> .bp-btn-saveprint,
    #sidebarRight-<?php echo $this->uniqid ?> .bp-btn-saveprint .fa
    {
        color: #FFF;
    }

</style>
<script type="text/javascript">
    var processRender_<?php echo $this->uniqid; ?> = $('.process-<?php echo $this->uniqid; ?>');
    function previewErsFileSave_<?php echo $this->uniqId ?>() {
        $.ajax({
            type: "POST",
            url: 'mddoc/previewErsFileSave',
            data: {
                'dataRow': <?php echo json_encode($this->dataRow); ?>,
                'paperNumber': $('.dvecommerce-<?php echo $this->uniqid ?>').find('input[data-path="paperNumber"]').val(),
                'id': $('.dvecommerce-<?php echo $this->uniqid ?>').find('input[data-path="id"]').val()
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                if (typeof data.status !== 'undefined') {
                    new PNotify({
                        title: data.status,
                        text: data.text,
                        type: data.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            },
            error: function () {
                Core.unblockUI();
            }
        });
    }

    $(document).ready(function () {
        var fillDataParams = '', 
            rowData = <?php echo json_encode($this->dataRow) ?>;
        
        $.each(rowData, function (index, row) {
           fillDataParams += '&'+ index +'=' + row;
        });
        
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: '1563518461667',
                isDialog: false,
                isHeaderName: false,
                isBackBtnIgnore: 1,
                callerType: 'dv',
                openParams: {},
                fillDataParams: fillDataParams
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {

                processRender_<?php echo $this->uniqid; ?>.empty().append(data.Html).promise().done(function () {
                    
                    processRender_<?php echo $this->uniqid; ?>.find('.meta-toolbar').attr('style', 'background: none !important; margin: 0; position: absolute; top: -55px; right: -10px;');
                    processRender_<?php echo $this->uniqid; ?>.find('.bp-btn-quickmenu').addClass('d-none');
                    processRender_<?php echo $this->uniqid; ?>.find('button.bp-btn-save').removeAttr('style');
                    processRender_<?php echo $this->uniqid; ?>.find('label[data-label-path]').addClass('d-none');
                    
                    Core.initBPAjax(processRender_<?php echo $this->uniqid; ?>);
                    Core.unblockUI();
                });

            },
            error: function () {
                alert('Error');
                Core.unblockUI();
            }
        });
        
    });

</script>

<style type="text/css">
    .main-charvideo {
        margin-bottom: 20px;
        background: #f2f2f2;
        margin-left: -15px;
        margin-right: -15px;
        padding-left: 15px;
        margin-top: -20px;
        padding-top: 10px;
        padding-bottom: 5px;
    }
    .dvecommerce-body .ui-dialog .ui-widget-header {
        height: 40px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-title {
        line-height: 24px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane button {
        padding: 5px 20px;
        text-transform: uppercase;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane {
        margin-top: 0;
        background: #DDD;
        border: 0;
        padding: 5px 10px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-content {
        padding: 10px 15px 0;
    }
    .dvecommerce .detrightsidebar,
    .dvecommerce .detpr4 {
        width: 265px;
    }
    .dvecommerce .detrscfr {
        margin-right: 320px;
    }
    .dvecommerce button#sidebarCollapse,
    .dvecommerce button#sidebarCollapse2 {
        position: absolute;
        top: -3px;
        z-index: 999999;
        padding: 4px 8px;
        border-radius: 0;
        background: none;
        border: 0;
        color: #003d74;
    }
    .dvecommerce button#sidebarCollapse {
        left: -15px;
    }
    .dvecommerce button.btnsidebarCollapse2 {
        right: -15px;
    }
    .dvecommerce button#sidebarCollapse i,
    .dvecommerce button#sidebarCollapse2 i {
        font-size: 34px;
    }
    #sidebar.active {
        margin-left: -250px;
        transition: all 0.3s;
    }
    .dvecommerce .rightsidebar.active {
        margin-right: -320px;
        transition: all 0.3s;
    }
    #content {
        transition: all 0.3s;
    }
    #content.active {
        margin-left: 0;
    }
    #content.active i.actived {
        display: block !important;
    }
    #content.active i.actived2 {
        display: none !important;
    }
    .dvecommerce .right-sidebar-content-for-resize {
        margin-top: 20px;
    }
    ul.media-list span.badge.badge-mark,
    #navbar-footer span.badge.badge-mark {
        padding: 0;
    }
    @media (min-width: 768px) {
        .dvecommerce-<?php echo $this->uniqid ?> .sidebar-expand-md.sidebar-right.sidebar-right-2,
        .dvecommerce-<?php echo $this->uniqid ?> .sidebar-expand-md.sidebar-secondary.sidebar-secondary-2 {
            display: block !important;
        }
    }
</style>