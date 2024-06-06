<div class="col-md-12">
    <form method="post" enctype="multipart/form-data">
        <?php echo $this->contentHtml; ?>
    </form>
</div>

<script type="text/javascript">
$(function() {
    <?php
    if ($this->actionType == 'read') {
    ?>
    var $form = $('form');        
    $form.find('.bp-add-one-row').parent().remove();
    $form.find('.bp-remove-row, button.red, button.green-meadow').remove();
    $form.find('input[type="text"], textarea').addClass('kpi-notfocus-readonly-input').attr('readonly', 'readonly');
    $form.find("div[data-s-path]").addClass('select2-container-disabled kpi-notfocus-readonly-input');
    $form.find('button[onclick*="dataViewSelectableGrid"], button[onclick*="chooseKpiIndicatorRowsFromBasket"]').prop('disabled', true);

    var $radioElements = $form.find("input[type='radio']");
    if ($radioElements.length) {
        $radioElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
        $radioElements.closest('.radio').addClass('disabled');
    }

    var $checkElements = $form.find("input[type='checkbox']");
    $checkElements.attr({'data-isdisabled': 'true', style: 'cursor: not-allowed', 'tabindex': '-1'});
    $checkElements.closest('.checker').addClass('disabled');
    <?php
    }
    ?>
});

function kpiIndicatorFormCommand(elem, dataStr) {
    var obj = JSON.parse(dataStr);
    var command = obj.kpiIndicatorCommand;
    
    if (command == 'save' || command == 'saveadd') {
        
        var $form = $('form');
        var $this = $form.find('input:visible:eq(0)');
        var $bpElem = $form.find('div[data-bp-uniq-id]');
        var uniqId = $bpElem.attr('data-bp-uniq-id');
        var indicatorId = $bpElem.attr('data-process-id');

        if (window['kpiIndicatorBeforeSave_' + uniqId]($this) && bpFormValidate($form)) {

            $form.ajaxSubmit({
                type: 'post',
                url: 'mdprocess/saveKpiDynamicData',
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {

                    if (data.status == 'success') {

                        window['kpiIndicatorAfterSave_' + uniqId]($this, data.status, data);
                        
                        if (command == 'saveadd') {
                            bpProcessFieldClear($form, indicatorId);
                        }
                    } 
                    
                    var parentUrl = (window.location != window.parent.location) ? document.referrer : document.location.href;
                    window.parent.postMessage(JSON.stringify(data), parentUrl);

                    Core.unblockUI();
                }
            });
        }
    }
}
</script>