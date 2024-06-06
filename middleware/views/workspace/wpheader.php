<div class="row">
    <div class="col-md-12 pl0 pr0 viewrenderRow_<?php echo $this->metaDataId ?>">
        <?php if ($this->viewrenderRow) { ?>
            <?php 
            $countRenderRow = count($this->viewrenderRow);
            $width = 100/$countRenderRow;
            $index = 1;
            foreach ($this->viewrenderRow as $renderRow) { 
                if (isset($renderRow['header-position-9'])) {
                    switch (Str::lower($renderRow['header-position-9'])) {
                        case 'progress_card': ?>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 <?php echo ($index == $countRenderRow) ? '' : 'pr0' ?>" style="width: <?php echo $width; ?>%;">
                                <div class="dashboard-stat2 bordered hoverlink-<?php echo $this->metaDataId ?>" id="<?php echo isset($renderRow['header-position-7']) ? $renderRow['header-position-7'] : '' ?>" data-criteria="<?php echo isset($renderRow['header-position-8']) ? $renderRow['header-position-8'] : '' ?>" style=" padding-bottom:31px !important;">
                                    <div class="display">
                                        <div class="number">
                                            <h3 class="font-green-sharp" style="color: <?php echo isset($renderRow['header-position-1']) ? $renderRow['header-position-1'] : '' ?> !important;">
                                                <span data-counter="counterup" data-value="7800" data-position-name="header-position-2"><?php echo isset($renderRow['header-position-2']) ? $renderRow['header-position-2'] : '' ?></span>
                                            </h3>
                                            <small data-position-name="header-position-4"><?php echo isset($renderRow['header-position-4']) ? $renderRow['header-position-4'] : '' ?></small>
                                        </div>
                                        <div class="icon">
                                            <i class="<?php echo isset($renderRow['header-position-2']) ? $renderRow['header-position-2'] : '' ?>"></i>
                                        </div>
                                    </div>
                                    <div class="progress-info">
                                        <div class="progress">
                                            <span style="width: <?php echo $renderRow['header-position-5'] ?>%; background: <?php echo isset($renderRow['header-position-1']) ? $renderRow['header-position-1'] : '' ?> !important;" class="progress-bar bg-success green-sharp">
                                                <span class="sr-only" data-position-name="header-position-5"><?php echo isset($renderRow['header-position-5']) ? $renderRow['header-position-5'] : '' ?>% progress</span>
                                            </span>
                                        </div>
                                        <div class="status">
                                            <div class="status-title" data-position-name="header-position-6"><?php echo isset($renderRow['header-position-6']) ? $renderRow['header-position-6'] : '' ?> </div>
                                            <div class="status-number" data-position-name="header-position-5"><?php echo isset($renderRow['header-position-5']) ? $renderRow['header-position-5'] : '' ?>% </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            break;
                        case 'pie_chart': ?>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 <?php echo ($index == $countRenderRow) ? '' : 'pr0' ?>" style='width: <?php echo $width; ?>%'>
                                <div class="easy-pie-chart bordered hoverlink-<?php echo $this->metaDataId ?>" id="<?php echo isset($renderRow['header-position-7']) ? $renderRow['header-position-7'] : '' ?>" data-criteria="<?php echo isset($renderRow['header-position-8']) ? $renderRow['header-position-8'] : '' ?>" style="border-radius: 4px; border:1px solid #CCC;">
                                    <div class="number transactions" data-percent="<?php echo $renderRow['header-position-5'] ?>">
                                        <span>+<?php echo $renderRow['header-position-5'] ?> </span>%
                                    </div>
                                    <a class="title" href="javascript:;"><?php echo isset($renderRow['header-position-2']) ? $renderRow['header-position-2'] : '' ?> <!--- <i class="icon-arrow-right"></i> ---></a>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    if (!jQuery().easyPieChart) {
                                        return;
                                    }

                                    $('.easy-pie-chart .number.transactions').easyPieChart({
                                        animate: 1000,
                                        size: <?php echo isset($renderRow['header-position-5']) ? $renderRow['header-position-5']: '30' ?>,
                                        lineWidth: 3,
                                        barColor: '<?php echo isset($renderRow['header-position-1']) ? $renderRow['header-position-1']: '#000' ?>'
                                    });
                                });
                            </script>
                            <?php 

                            break;
                        case 'sparkline_chart': ?>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 <?php echo ($index == $countRenderRow) ? '' : 'pr0' ?>" style='width: <?php echo $width; ?>%'>
                                <div class="sparkline-chart bordered mt0 pt15 hoverlink-<?php echo $this->metaDataId ?>" id="<?php echo isset($renderRow['header-position-7']) ? $renderRow['header-position-7'] : '' ?>" data-criteria="<?php echo isset($renderRow['header-position-8']) ? $renderRow['header-position-8'] : '' ?>" style="border-radius: 4px; border:1px solid #CCC;">
                                    <div class="number" id="sparkline_bar"></div>
                                    <a class="title" href="javascript:;">
                                        <?php echo isset($renderRow['header-position-2']) ? $renderRow['header-position-2'] : '' ?>
                                        <!--<i class="icon-arrow-right"></i>-->
                                    </a>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    if (!jQuery().sparkline) {
                                        return;
                                    }
                                    $("#sparkline_bar").sparkline(<?php echo isset($renderRow['header-position-5']) ? $renderRow['header-position-5']: '' ?>, {
                                        type: 'bar',
                                        width: '100',
                                        barWidth: 5,
                                        height: '55',
                                        barColor: '<?php echo isset($renderRow['header-position-1']) ? $renderRow['header-position-1']: '#000' ?>',
                                        negBarColor: '#e02222'
                                    });
                                });
                            </script>
                            <?php 
                            break;
                    }
                    $index++;
                }
            } ?>
        <?php } ?>
    </div>
    <div class="col-md-12">
        <div class="col-md-12 toggler-<?php echo $this->metaDataId ?>" data-toggle-status="open">
            <a class="btn btn-icon-only btn-circle btn-secondary btn-xs" href="javascript:;" title="down" ><i class="fa fa-angle-down"></i></a>
        </div>
    </div>
</div>
<style type="text/css">
    .hoverlink-<?php echo $this->metaDataId ?>:hover {
        background: rgba(222, 229, 249, 0.49);
        cursor: pointer;
        
    }
    .hoverlink-<?php echo $this->metaDataId ?>, .toggler-<?php echo $this->metaDataId ?> {
        margin-bottom: 5px !important;
    }
    .toggler-<?php echo $this->metaDataId ?> {
        margin: 0;
        padding: 0;
        width: 12px;
        height: 12px;
        min-width: 12px;
        max-width: 12px;
        float: right;
        top: 23px;
        right: 18px;
        z-index: 9999;
    }
    .toggler-<?php echo $this->metaDataId ?> > a {
        height: 24px;
        width: 25px;
        background: inherit;
        border: 1px solid #FFF;
    }
    .toggler-<?php echo $this->metaDataId ?> > a:hover {
        background: inherit;
    }
    
    .toggler-<?php echo $this->metaDataId ?> > a > i {
        color: #FFF;
        margin-top: 0;
    }
</style>
<script type="text/javascript">
    $(function () {
        $('.hoverlink-<?php echo $this->metaDataId ?>').click(function () {
            var $dialogName = 'dialog-workspace';
            if (!$(".ws-hidden-params-two").length) {
                $('.ws-hidden-params').append('<div class="ws-hidden-params-two"></div>');
            }
                        
            var _this<?php echo $this->metaDataId ?> = $(this);
            var _datacriteria<?php echo $this->metaDataId ?> = _this<?php echo $this->metaDataId ?>.attr('data-criteria');
            var _dataid<?php echo $this->metaDataId ?> = _this<?php echo $this->metaDataId ?>.attr('id');
            var _criteria<?php echo $this->metaDataId ?> = _datacriteria<?php echo $this->metaDataId ?>.split('&');
            
            $('.ws-hidden-params-two').empty();
            var _html = '';
            for (var index_<?php echo $this->metaDataId ?> = 0; index_<?php echo $this->metaDataId ?> <  _criteria<?php echo $this->metaDataId ?>.length; index_<?php echo $this->metaDataId ?>++) {
                var criteriasplit_<?php echo $this->metaDataId ?> = _criteria<?php echo $this->metaDataId ?>[index_<?php echo $this->metaDataId ?>].split('=');
                _html += '<input type="hidden" name="'+ criteriasplit_<?php echo $this->metaDataId ?>[0] +'" value="'+ criteriasplit_<?php echo $this->metaDataId ?>[1] +'" />';
                
            }
            $('.ws-hidden-params-two').html(_html);
            workspace_id.find(".workspace-menu").find("a[data-menu-id='"+ _dataid<?php echo $this->metaDataId ?> +"']").trigger("click");
        });
        
        $('.toggler-<?php echo $this->metaDataId ?>').on('click', function () {
            $('.viewrenderRow_<?php echo $this->metaDataId ?>').toggle();
            var _this = $(this);
            if (_this.attr('data-toggle-status') == 'open') {
                _this.attr('data-toggle-status', 'close');
                _this.html('<a class="btn btn-icon-only btn-circle btn-secondary btn-xs" href="javascript:;" title="up"><i class="fa fa-angle-up"></i></a>');
            } else {
                _this.attr('data-toggle-status', 'open');
                _this.html('<a class="btn btn-icon-only btn-circle btn-secondary btn-xs" href="javascript:;" title="down"><i class="fa fa-angle-down"></i></a>');
            }
        });
    });
    
</script>