<script type="text/javascript">
    var bp_window_<?php echo $this->methodId; ?> = $("div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']");
    var isEditMode_<?php echo $this->methodId; ?> = <?php echo (($this->isEditMode) ? 'true' : 'false'); ?>;
    pfFullExpSetFieldValue = true;
    
    Core.initBPInputType(bp_window_<?php echo $this->methodId; ?>);
    
    <?php echo $this->bpFullScriptsVarFnc; ?>    
        
    $(function(){
        
        dtlAggregateFunction_<?php echo $this->methodId; ?>(); 
        
        bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>();
        <?php echo $this->bpFullScriptsEvent; ?>       
            
        bpLoadDetailHideShowFields(bp_window_<?php echo $this->methodId; ?>); 
        showRenderSidebar(bp_window_<?php echo $this->methodId; ?>);
        
        bp_window_<?php echo $this->methodId; ?>.on('click', '.show-bpdtl-comment-btn', function() {
            var $this = $(this); $parent = $this.closest('tr');
            var sourceId = $parent.find('> td > span[data-view-path$=".id"]').text();        
            var refId = $this.data('refstructureid');

            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderEditModeBpCommentTab',
                data: {uniqId: getUniqueId(''), refStructureId: refId, sourceId: sourceId},
                beforeSend: function() {
                    Core.blockUI({
                      message: "Loading...",
                      boxed: true
                    });
                },
                success: function(data) {
                    Core.unblockUI();

                    var $dialogName2 = "dialog-bpdtl-comment";
                    if (!$($dialogName2).length) {
                      $('<div id="' + $dialogName2 + '"></div>').appendTo("body");
                    }
                    var $dialog2 = $("#" + $dialogName2);

                    $dialog2.empty().append(data);

                    $dialog2.dialog({
                      cache: false,
                      resizable: false,
                      bgiframe: true,
                      autoOpen: false,
                      title: 'Сэтгэгдэл',
                      width: 500,
                      height: "auto",
                      modal: true,
                      position: { my: "top", at: "top+100" },
                      open: function () {
                      },
                      close: function () {
                        $dialog2.empty().dialog("destroy").remove();
                      },
                      buttons: [{
                          text: plang.get("close_btn"),
                          class: "btn btn-sm blue-hoki",
                          click: function () {
                            $dialog2.dialog("close");
                          },
                        }]
                    });

                    $dialog2.dialog("open");
                },
                error: function() {
                    alert('Error');
                }
            });            
        });            
        
        Core.initCodeHighlight(bp_window_<?php echo $this->methodId; ?>);
        
        <?php
        if (Mdwebservice::$isLogViewMode) {
        ?>
        $.cachedScript('assets/core/js/plugins/forms/styling/switch.min.js').done(function() {    
            $('.form-check-input-switch-bplog_<?php echo $this->methodId; ?>').bootstrapSwitch({
                onSwitchChange: function(e, state) { 
                    if (state) {
                        bp_window_<?php echo $this->methodId; ?>.find('[data-valmode="new"]').removeClass('d-none');
                        bp_window_<?php echo $this->methodId; ?>.find('[data-valmode="old"]').addClass('d-none');
                    } else {
                        bp_window_<?php echo $this->methodId; ?>.find('[data-valmode="new"]').addClass('d-none');
                        bp_window_<?php echo $this->methodId; ?>.find('[data-valmode="old"]').removeClass('d-none');
                    }
                } 
            });
        });
        <?php
        }
        ?>
    });

    function bpFullScriptsWithoutEvent_<?php echo $this->methodId; ?>(elem, groupPath, isAddMulti, isLastRow, multiMode) {
        var element = typeof elem === 'undefined' ? 'open' : elem; 
        var groupPath = typeof groupPath === 'undefined' ? '' : groupPath; 
        var isAddMulti = typeof isAddMulti === 'undefined' ? false : isAddMulti; 
        var isLastRow = typeof isLastRow === 'undefined' ? false : isLastRow; 
        var multiMode = typeof multiMode === 'undefined' ? '' : multiMode; 
        
        <?php echo $this->bpFullScriptsWithoutEvent; ?>
    }
    function dtlAggregateFunction_<?php echo $this->methodId; ?>() {
        var $aggregate = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl:not(.bprocess-table-subdtl, [data-pager="true"]) > thead > tr > th[data-aggregate]:not([data-aggregate=""])');
        
        if ($aggregate.length) {
            
            var el = $aggregate, len = el.length, i = 0;
            
            for (i; i < len; i++) { 
                
                var $row = $(el[i]);
                var funcName = $row.attr('data-aggregate');
                var path = $row.attr('data-cell-path');
                var $gridBody = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl > .tbody > .bp-detail-row > td[data-cell-path="' + path + '"]');
                var $footCell = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl > tfoot > tr > td[data-cell-path="' + path + '"]');
                
                if (funcName === 'sum') {
                    
                    var sum = 0, cellVal;
                    $gridBody.each(function() {
                        cellVal = $(this).text();
                        if (cellVal != '') {
                            sum += pureNumber(cellVal);
                        }
                    });
                    $footCell.autoNumeric('set', sum);
                }

                if (funcName == 'avg') {
                    var avg = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl > .tbody > .bp-detail-row > td[data-cell-path="' + path + '"] > span').avg();
                    $footCell.autoNumeric('set', avg);
                }

                if (funcName == 'max') {
                    var max = bp_window_<?php echo $this->methodId; ?>.find('.bprocess-table-dtl > .tbody > .bp-detail-row > td[data-cell-path="' + path + '"] > span').max();
                    $footCell.autoNumeric('set', max);
                }

                if (funcName == 'min') {
                    var min = 0;
                    $gridBody.each(function (index) {
                        if (typeof $(this).text() != 'undefined') {
                            var cellVal = $(this).text();
                            if (cellVal != '' || Number(cellVal) > 0) {
                                cellVal = Number(cellVal);
                                if (index === 0) {
                                    min = cellVal;
                                }
                                if (min > cellVal) {
                                    min = cellVal;
                                }
                            }
                        }
                    });
                    $footCell.autoNumeric('set', min);
                }
            }
        }
    }
    
    var isSaveConfirm_<?php echo $this->methodId; ?> = false;
    
    function processBeforeSave_<?php echo $this->methodId; ?>(thisButton) {
        PNotify.removeAll();
        
        <?php echo $this->bpFullScriptsSave; ?>

        return true;
    }
    function processAfterSave_<?php echo $this->methodId; ?>(thisButton, responseStatus) {
        
        <?php echo $this->bpFullScriptsAfterSave; ?>

        return true;
    }
</script>