        </div>
    </div>
</div>
<!--[if lt IE 9]>
<script src="assets/custom/addon/plugins/respond.min.js"></script>
<script src="assets/custom/addon/plugins/excanvas.min.js"></script> 
<![endif]-->
<script type="text/javascript">
jQuery(document).ready(function(){ 
    $('.clouduser-signup-btn').on('click', function() {
        var $this = $(this), $form = $('#wsForm'), uniqId = $form.find('[data-bp-uniq-id]').attr('data-bp-uniq-id');
        
        $this.prop('disabled', true).prepend('<i class="icon-spinner4 spinner-sm mr-1"></i>');

        if (bpFormValidate($form) && window['processBeforeSave_' + uniqId]($this)) {
            
            $form.ajaxSubmit({
                type: 'post',
                url: 'login/cloudUserSignupSave',
                dataType: 'json',
                beforeSubmit: function(formData, jqForm, options) {
                    var urlToken = window.location.search.substring(1);
                    urlToken = urlToken.replace('token=', '');
                    formData.push({ name: 'token', value: urlToken });
                }, 
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {

                    PNotify.removeAll();
                    
                    if (data.status == 'success') {
                        
                        $form.empty().append('<div class="alert bg-success text-white alert-styled-left">'+data.message+'</div>');
                        $this.remove();
            
                    } else {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false, 
                            addclass: pnotifyPosition
                        });
                        $this.removeAttr('disabled').find('i').remove();
                    }
                    
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