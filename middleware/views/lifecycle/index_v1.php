<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body card-body-padding d-flex justify-content-center align-items-center">
                <div>
                    <img src="<?php echo $this->selectedRow['profilephoto'] ?>" onerror="onUserImgError(this);" class="img-logo img-fluid"/>
                </div>
            </div>
            <div class="container-fluid">
                <div id="members-online"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card bg-pink-400">
            <div class="card-body card-body-padding d-flex">
                <div class="flex-column">
                    <h3 class="mb0"><i class="icon-clipboard5 mr10 font-size-20"></i> <?php echo isset($this->selectedRow[$this->mainMetaDataCode]) ? $this->selectedRow[$this->mainMetaDataCode] : ''; ?></h3>
                    <h3 class="font-weight-bold mb-0"><i class="icon-target mr10 font-size-20"></i> <?php echo isset($this->selectedRow[$this->mainMetaDataName]) ? $this->selectedRow[$this->mainMetaDataName] : ''; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card bg-blue-400">
            <div class="card-body card-body-padding d-flex">
                <div class="d-flex align-items-center">
                    <h2 class="mb0"><i class="icon-enter6 mr10 font-size-20"></i> <?php echo $this->lang->line('task_count'); ?>: <?php echo isset($this->selectedRow['taskcount']) ? $this->selectedRow['taskcount'] : '0'; ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card p-0 bg-transparent">
    <div class="card-body">
        <!-- <div class="header-banner row ml0 mr0 mt-n5">
            <div class="col-md-3 col-sm-5 col-xs-12 height-100 pad-0">
                <div class="banner-img-div d-flex justify-content-center">
                    <img src="<?php echo $this->selectedRow['profilephoto'] ?>" onerror="onUserImgError(this);" class="img-logo img-fluid"/>
                </div>
            </div>
            <div class="col-md-6 col-sm-7 col-xs-12 pad-0 d-flex align-items-center text-center justify-content-center">
                <h2 class="banner-title flex-column">
                    <strong><?php echo isset($this->selectedRow[$this->mainMetaDataName]) ? $this->selectedRow[$this->mainMetaDataName] : ''; ?></strong>
                    <p class="mb0"><?php echo isset($this->selectedRow[$this->mainMetaDataCode]) ? $this->selectedRow[$this->mainMetaDataCode] : ''; ?></p>
                </h2>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12 height-100 pad-0">
                <div class="div-right row d-flex align-items-center justify-content-center">
                    <div class="col-md-4 col-sm-4 col-xs-4 first-block-l pad-0 hidden">
                        <span class="date-tt"><?php echo $this->lang->line('ot_startdate'); ?></span>
                        <span class="date-tt-val">
                            <?php
                            echo isset($this->selectedRow['startdate']) ? substr($this->selectedRow['startdate'], 0, 10) : '';
                            ?>
                        </span>
                        <div class="line-s"></div>
                        <span class="date-tt"><?php echo $this->lang->line('ot_enddate'); ?></span>
                        <span class="date-tt-val">
                            <?php
                            echo isset($this->selectedRow['enddate']) ? substr($this->selectedRow['enddate'], 0, 10) : '';
                            ?>
                        </span>
                    </div>
                    <div class="col-md-4 col-sm-3 col-xs-3 pad-0">
                        <h2 class="cnt-anket mb0"><?php echo $this->lang->line('task_count'); ?></h2>
                    </div>
                    <div class="col-md-4 col-sm-5 col-xs-5 pad-0 cnt-c d-flex align-items-center justify-content-center">
                        <p class="circle-cnt mt10 d-flex align-items-center justify-content-center">
                            <?php echo isset($this->selectedRow['taskcount']) ? $this->selectedRow['taskcount'] : '0'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="clearfix w-100"></div>
        <div class="row ml0 mr0">
            <div class="col-md-3 pad-0 lifecycle-toggler-left">
                <div class="lifecycle-div lifecycle-common-div" id="lifecycle_div_<?php echo $this->uniqId; ?>">
                    <h4 class="lifecycle-title cursorPointer lifecycle-toggler" data-toggler="collapse"><?php echo $this->lang->line('ot_process'); ?> <i class="fa fa-chevron-circle-left"></i></h4>
                    <div class="lifecycle-tree">
                        <div id="left-tree-list-<?php echo $this->uniqId; ?>" class="lifecycle-common-div lifecycle-selected-t lifecycle-dv-<?php echo $this->uniqId; ?> "></div>
                        <div id="left-tree-list-adjacent_<?php echo $this->uniqId; ?>"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 pr0 pt10 lifecycle-toggler-right">
                <div id="rightSideDv_<?php echo $this->uniqId; ?>" class="lifecycle-common-right mb20"></div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    #left-tree-list-<?php echo $this->uniqId; ?> .jstree-wholerow-ul {
        min-width: 95% !important;
    }
</style>

<script type="text/javascript">
$(function(){
    $.getStylesheet(URL_APP + 'middleware/assets/css/lifecycle/lifecycle.css');
    if (typeof lifecycle === 'undefined') {
        $.getScript(URL_APP + 'middleware/assets/js/lifecycle/lifecycleV1.js', function(){
            $.getStylesheet(URL_APP + 'middleware/assets/css/lifecycle/lifecycle.css');
            lifecycle.init('<?php echo $this->uniqId; ?>', '<?php echo $this->lifecycleId; ?>', '<?php echo $this->recordId ?>', '<?php echo $this->lifecycletaskId ?>', '<?php echo $this->treeDvId; ?>');
        });
    } else {
        lifecycle.init('<?php echo $this->uniqId; ?>', '<?php echo $this->lifecycleId; ?>', '<?php echo $this->recordId ?>', '<?php echo $this->lifecycletaskId ?>', '<?php echo $this->treeDvId; ?>');
    }
});
</script>