<script type="text/javascript">
$.ajaxSetup({
    global: false,
    type: 'post', 
    data: {nult: 1}
});
function bpResultConsole(status, message, result) {
    
    if (typeof result !== 'undefined' && result) {
        var jsonObj = {status: status, text: message, result: result};
        var jsonLog = JSON.stringify(jsonObj);
    } else {
        var jsonLog = '{"status":"'+status+'","text":"'+message+'"}';
    }
    
    console.log('#bpResult: ' + jsonLog);
    $('#bp-result-element').val(jsonLog);
}

<?php
if ($this->isTestCase == false) {
    echo "bpResultConsole('error', 'No testcase data!');";
} else {
?>
function runSaveTestCaseForm() {
    var $bp = $('div[data-meta-type="process"]'), 
        uniqId = $bp.attr('data-bp-uniq-id'), 
        $saveBtn = $('.bpMainSaveButton'), 
        elem = $saveBtn[0], 
        $parentForm = $bp.find('form'), 
        runMode = (typeof runMode !== 'undefined') ? runMode : '', 
        $changeInputs = $bp.find('input[data-path]:not([type=hidden]):not([data-path*="."]), select[data-path]:not([data-path*="."]), input.popupInit[data-path]:not([data-path*="."])');

    Core.blockUI({message: 'Loading...', boxed: true});
    
    $saveBtn.attr({'disabled': 'disabled'}).prepend('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
    
    setTimeout(function() {
        
        if ($changeInputs.length) {
            $changeInputs.each(function() {
                $(this).trigger('change');
            });
        }

        if (window['processBeforeSave_' + uniqId](elem)) {

            if (bpFormValidate($parentForm)) {

                $parentForm.ajaxSubmit({
                    type: 'post',
                    url: 'mdprocess/runTestCaseProcess',
                    dataType: 'json',
                    async: false,
                    beforeSubmit: function(formData, jqForm, options) {
                        formData.push({ name: 'nult', value: 1 });
                        <?php
                        if (isset($this->_runTest)) {
                        ?>
                        formData.push({ name: '_runTest', value: '<?php echo $this->_runTest; ?>' });
                        <?php
                        }
                        ?>
                    },
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(responseData) {
                        
                        if (responseData.hasOwnProperty('result')) {
                            bpResultConsole(responseData.status, responseData.message, responseData.result);
                        } else {
                            bpResultConsole(responseData.status, responseData.message);
                        }
                    
                        if (responseData.status == 'success') {
                            $parentForm.find('input[name="windowSessionId"]').val(responseData.uniqId);
                        }
                        
                        PNotify.removeAll();
                        new PNotify({
                            title: responseData.status,
                            text: responseData.message,
                            type: responseData.status,
                            addclass: 'pnotify-center',
                            sticker: false
                        }); 

                        bpIgnoreGroupRemove($parentForm);

                        Core.unblockUI();
                    },
                    error: function() { alert('Error'); }
                });

            } else {
                bpIgnoreGroupRemove($parentForm);
            }

        } else {
            bpIgnoreGroupRemove($parentForm);
        }

        Core.unblockUI();
        $saveBtn.removeAttr('disabled').find('i:eq(0)').remove();

    }, 100);
}
<?php
}
?>
</script>

<div class="col-md-12">
    <?php
    if ($this->isTestCase) {
    ?>
    <div style="width: 100%;display: flex;align-items: center;margin: 6px 0;padding: 0 0 5px 0;border-bottom: 1px #eee solid;vertical-align: middle;">
        <span class="text-uppercase">
            <?php echo $this->row['META_DATA_CODE'].' - '.$this->row['META_DATA_NAME']; ?>
        </span>
        <div class="ml-auto">
            <button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton" onclick="runSaveTestCaseForm();"><i class="icon-checkmark-circle2"></i> Хадгалах</button>
        </div>
    </div>
    <?php
        echo $this->contentHtml;
    } else {
        echo html_tag('div', array('class' => 'alert alert-info'), 'No testcase data!');
    }
    ?>
</div>

<textarea class="bp-result-element" id="bp-result-element" style="display: none"></textarea>