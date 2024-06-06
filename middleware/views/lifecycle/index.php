<div class="card p-0">
    <div class="card-body">
        <div class="header-banner row">
            <div class="col-md-3 col-sm-5 col-xs-12 pad-0" style="height:100% !important">
                <div class="banner-img-div">
                    <img src="assets/core/global/img/logo-lifecycle.png" class="img-logo img-fluid"/>
                </div>
            </div>
            <div class="col-md-6 col-sm-7 col-xs-12 pad-0">
                <h2 class="banner-title">
                    <?php echo isset($this->selectedRow['positionname']) ? $this->selectedRow['positionname'] : ''; ?>
                </h2>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12 pad-0" style="height:100% !important">
                <div class="div-right p-1">
                    <div class="row">
                    <div class="col-md-6">
                        <span class="date-tt"><?php echo $this->lang->line('ot_startdate'); ?></span>
                        <span class="date-tt-val">
                            <?php
                            echo isset($this->selectedRow['startdate']) ? substr($this->selectedRow['startdate'], 0, 10) : '';
                            ?>
                        </span>
                        <div class="line-s"></div>
                    </div>
                    <div class="col-md-6">
                        <span class="date-tt"><?php echo $this->lang->line('ot_enddate'); ?></span>
                        <span class="date-tt-val">
                            <?php
                            echo isset($this->selectedRow['enddate']) ? substr($this->selectedRow['enddate'], 0, 10) : '';
                            ?>
                        </span>
                        <div class="line-s"></div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="cnt-anket mt20"><?php echo $this->lang->line('ot_applicants'); ?></span>
                        </div>
                        <div class="col-md-6">
                            <p class="circle-cnt">
                                <?php echo isset($this->selectedRow['count']) ? $this->selectedRow['count'] : '0'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix w-100"></div>
        <div class="row">
            <div class="col-md-3 col-sm-12 col-xs-12 pad-0 lifecycle-toggler-left">
                <div class="lifecycle-div lifecycle-common-div" data-srcrecordid="<?php echo $this->srcRecordId; ?>" data-uniqid="<?php echo $this->uniqId; ?>" id="lifecycle_div_<?php echo $this->uniqId; ?>">
                    <h4 class="lifecycle-title cursorPointer lifecycle-toggler" data-toggler="collapse"><?php echo $this->lang->line('ot_process'); ?> <i class="fa fa-chevron-circle-left"></i></h4>
                    <div class="lifecycle-tree">
                        <div id="left-tree-list_<?php echo $this->uniqId; ?>" class="lifecycle-common-div lifecycle-selected-t"></div>
                        <div id="left-tree-list-adjacent_<?php echo $this->uniqId; ?>"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-12 col-xs-12 pr0 lifecycle-toggler-right">
                <div id="rightSideDv_<?php echo $this->uniqId; ?>" class="lifecycle-common-right"></div>
            </div>
            <div class="clearfix w-100"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function(){
    /* global lifecycle */
    $.getStylesheet(URL_APP + 'middleware/assets/css/lifecycle/lifecycle.css');
    if (typeof lifecycle === 'undefined') {
        $.getScript(URL_APP + 'middleware/assets/js/lifecycle/lifecycle.js', function(){
            $.getStylesheet(URL_APP + 'middleware/assets/css/lifecycle/lifecycle.css');
            lifecycle.init('<?php echo $this->uniqId; ?>', '<?php echo $this->srcRecordId; ?>');
        });
    } else {
        lifecycle.init('<?php echo $this->uniqId; ?>', '<?php echo $this->srcRecordId; ?>');
    }
});
</script>