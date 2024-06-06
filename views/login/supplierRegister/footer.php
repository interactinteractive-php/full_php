        </div>
    </div>
</div>
<!--[if lt IE 9]>
<script src="assets/custom/addon/plugins/respond.min.js"></script>
<script src="assets/custom/addon/plugins/excanvas.min.js"></script> 
<![endif]-->
<script type="text/javascript">
jQuery(document).ready(function(){ 
    $('.supplier-register-btn').on('click', function() {
        var $this = $(this), 
            $form = $('#supplier-register-form'), 
            uniqId = $form.find('[data-bp-uniq-id]').attr('data-bp-uniq-id');
        
        $this.prop('disabled', true).prepend('<i class="icon-spinner4 spinner-sm mr-1"></i>');

        if (bpFormValidate($form) && window['kpiIndicatorBeforeSave_' + uniqId]($this)) {

            $form.ajaxSubmit({
                type: 'post',
                url: 'login/supplierRegisterSave',
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {

                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: pnotifyPosition
                    });
                    
                    $this.removeAttr('disabled').find('i').remove();
                    Core.unblockUI();
                }
            });
        } else {
            $this.removeAttr('disabled').find('i').remove();
        }
    });
});
function new_captcha() {
    var c_currentTime = new Date();
    var c_miliseconds = c_currentTime.getTime();
    document.getElementById('captcha').src = 'api/captcha?fDownload=1&x='+c_miliseconds;
    
    $('#security_code').focus();
}
</script>
</body>
</html>