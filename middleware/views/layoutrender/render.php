<div class="intranet intranet-layout-<?php echo $this->uniqId ?>">
    <div class='dashboard-layout-<?php echo $this->uniqId ?>'></div>
</div>
<style>
    .dashboard-layout-<?php echo $this->uniqId ?> .hrmtimelogv2-cal-parent > .col-md-3, .dashboard-layout-<?php echo $this->uniqId ?> .hrmtimelog-calendar .fc-hrm-time-log-startdate {
        display: none !important;
    }
    
    .dashboard-layout-<?php echo $this->uniqId ?> .hrmtimelogv2-cal-parent > .col-md-9 {
        max-width: 100% !important;
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        width: 100% !important;
    }
    
    .dashboard-layout-<?php echo $this->uniqId ?> .fc-month-view .fc-scroller {
        overflow: auto !important;
        height: 305px !important;
    }
    
    .dashboard-layout-<?php echo $this->uniqId ?> .hrmtimelog-calendar .fc-hrm-time-duration, .dashboard-layout-<?php echo $this->uniqId ?> .hrmtimelog-calendar .fc-hrm-time-log-parent {
        height: inherit !important;
    }
    .dashboard-layout-<?php echo $this->uniqId ?> .hrmtimelog-calendar .fc-basic-view .fc-body .fc-row {
        min-height: 165px !important;
    }
    
    .intranet-layout-<?php echo $this->uniqId ?> .spinner {
        width: initial !important;
        height: initial !important;
    }
</style>
<script type="text/javascript">

    $(function () {
        
        if (!$("link[href='middleware/assets/css/intranet/style.css']").length) {
            $("head").prepend('<link rel="stylesheet" type="text/css" href="middleware/assets/css/intranet/style.css"/>');
        }
        
        initLayout<?php echo $this->metaDataId; ?>();
        
        <?php
        if (!empty($this->layoutLink['REFRESH_TIMER'])) {
            $refTimer = (int) $this->layoutLink['REFRESH_TIMER'];
            $refTimer = $refTimer >= 10 ? $refTimer : 10;
        ?>
        if ($('.intranet-layout-<?php echo $this->uniqId ?>').is(":visible")) {
            setInterval(function () {
                if (!document.hidden) {
                    initLayout<?php echo $this->metaDataId; ?>();
                }
            }, <?php echo $refTimer; ?> * 1000);
        }
        <?php } ?>
        
    });
    
    function initLayout<?php echo $this->metaDataId; ?>() {
        $.ajax({
            type: 'post',
            url: 'mdlayoutrender/reloadLayout', 
            data: {
                uniqId: '<?php echo $this->uniqId ?>',
                metaDataId: '<?php echo $this->metaDataId ?>',
            }, 
            dataType: 'json',
            async: false, 
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?> ('.intranet-layout-<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                $('.dashboard-layout-<?php echo $this->uniqId ?>').empty().append(data.Html).promise().done(function () {
                    Core.unblockUI('.intranet-layout-<?php echo $this->uniqId ?>');
                });
            },
            error: function() {
                Core.unblockUI('.intranet-layout-<?php echo $this->uniqId ?>');
            }
        });
    }
    
    function blockContent_<?php echo $this->uniqId ?> (mainSelector) {
        $(mainSelector).block({
            message: '<i class="icon-spinner4 spinner"></i>',
            centerX: 0,
            centerY: 0,
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                width: 16,
                top: '15px',
                left: '',
                right: '15px',
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        }); 
    }
</script>