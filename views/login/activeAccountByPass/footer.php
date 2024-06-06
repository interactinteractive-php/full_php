        </div>
    </div>
</div>
<!--[if lt IE 9]>
<script src="assets/custom/addon/plugins/respond.min.js"></script>
<script src="assets/custom/addon/plugins/excanvas.min.js"></script> 
<![endif]-->
<script type="text/javascript">
jQuery(document).ready(function(){ 
    $("form#password-reset-form #p_new_password").focus();
    
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            if (regexp.constructor != RegExp) {
                regexp = new RegExp(regexp);
            } else if (regexp.global) {
                regexp.lastIndex = 0;
            }
            return this.optional(element) || regexp.test(value);
        },
        'Хамгийн багадаа 8 тэмдэгт, том жижиг үсэг, тоо болон тусгай тэмдэгт оролцсон байх'
    );
    
    $('.login-form').validate({
        rules: {
            p_new_password: {
                required: true,
                minlength: 8,
                regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{8,})'
            },
            p_new_password_confirm: {
                required: true,
                minlength: 8,
                equalTo: "#p_new_password",
                regex: '^(?=.*[a-zа-яөү])(?=.*[A-ZА-ЯӨҮ])(?=.*[0-9])(?=.*[!@#\$%\^&\*_])(?=.{8,})'
            }
        },
        messages: {
            p_new_password: {
                required: '<?php echo $this->lang->line('user_insert_password'); ?>',
                minlength: '<?php echo $this->lang->line('user_minlenght_password'); ?>'
            },
            p_new_password_confirm: {
                required: '<?php echo $this->lang->line('user_insert_password'); ?>',
                minlength: '<?php echo $this->lang->line('user_minlenght_password'); ?>',
                equalTo: '<?php echo $this->lang->line('user_equal_password'); ?>'
            }
        }
    });

    $('.login-form input').keypress(function (e) {
        if (e.which == 13) {
            if ($('.login-form').validate().form()) {
                $('.login-form').submit();
            }
            return false;
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