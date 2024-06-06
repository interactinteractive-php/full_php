<?php echo $this->wizard; ?>

<script type="text/javascript">
$(function() {
    if ($().steps) {
        initKpiWizardForm_<?php echo $this->uid; ?>();
    } else {
        $.cachedScript('assets/core/js/plugins/forms/wizards/steps.min.js').done(function() {
            initKpiWizardForm_<?php echo $this->uid; ?>();
        });
    }
});

function initKpiWizardForm_<?php echo $this->uid; ?>() {
    $('#wizard-<?php echo $this->uid; ?>').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        transitionEffect: 'fade',
        autoFocus: true, 
        /*enableAllSteps: true,*/
        titleTemplate: '<span class="number">#index#</span> #title#', 
        labels: {
            previous: '<i class="icon-arrow-left13 mr-2"></i> ' + plang.get('prev'),
            next: plang.get('next') + ' <i class="icon-arrow-right14 ml-2"></i>',
            finish: plang.get('finish_btn') + ' <i class="icon-arrow-right14 ml-2"></i>'
        }, 
        onInit: function (event, currentIndex) { 
            $(this).attr('data-step', currentIndex);
        },
        onStepChanging: function (event, currentIndex, newIndex) {

            if (currentIndex > newIndex) {
                return true;
            }
            
            var $form = $('#wizard-<?php echo $this->uid; ?>').find('section:eq('+currentIndex+')');
            var $requiredInputs = $form.find('[required]:not(:radio)').filter(function() { 
                var $this = $(this);
                if ($this.is(':radio')) {
                    $this.closest('.radio-list').removeClass('error');
                } else {
                    $this.removeClass('error');
                    $this.parent().removeClass('error');
                }
            
                if ($this.hasClass('fileInit')) {
                    if ($this.parent().find('input[type=hidden]').length && $this.parent().find('input[type=hidden]').val()) {
                        return false;
                    } else {
                        return this.value == '';
                    }
                } else {
                    return this.value == '';
                } 
            });
            
            if ($requiredInputs.length) {
                
                PNotify.removeAll();
                new PNotify({
                    title: 'Info',
                    text: plang.get('FIN_01332'),
                    type: 'info',
                    sticker: false, 
                    addclass: pnotifyPosition
                });

                $requiredInputs.not(':hidden').eq(0).focus();
                
                console.log('Required paths =================== start');
                
                $requiredInputs.each(function() {
                    var $this = $(this);
                    
                    if ($this.hasAttr('data-path')) {
                        console.log('Path: ' + $this.attr('data-path'));
                    }
                    
                    if ($this.is(':radio')) {
                        $this.closest('.radio-list').addClass('error');
                    } else {
                        $this.addClass('error');
                        $this.parent().addClass('error');
                    }
                });
                
                console.log('Required paths =================== end');
            
                return false;
            } else {
                return true;
            }
        },
        onStepChanged: function (event, currentIndex, priorIndex) { 
            $(this).attr('data-step', currentIndex);
        },
        onFinished: function (event, currentIndex) {
            
            var $this = $(this);
            var $dialog = $this.closest('.ui-dialog');
            
            if ($dialog.length) {
                $dialog.find('.bp-btn-save').click();
            } else {
                $this.closest('form').find('.bp-btn-save').click();
            }
        }
    });
}

setTimeout(function() {

    <?php echo Mdform::$kpiFullExpressions; ?>
    
    $('#wizard-<?php echo $this->uid; ?>').removeClass('d-none');
    
}, 400);
</script>