        </div>
    </div>
</div>
<!--[if lt IE 9]>
<script src="assets/custom/addon/plugins/respond.min.js"></script>
<script src="assets/custom/addon/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<?php echo autoVersion('assets/custom/addon/admin/pages/scripts/login-soft.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function(){ 
    Login.initPasswordReset();
    $("form#password-reset-form #p_email").focus();
    
    $('input[name="p_type"]').on('change', function(){
        var pType = $('input[name="p_type"]:checked').val(), 
            $field = $('#p_email'), 
            $parent = $field.closest('.input-icon');
        
        if (pType == 'phoneNumber') {
            $field.removeClass('email').attr('placeholder', '<?php echo $this->lang->line('Утасны дугаар'); ?>');
            $parent.find('i').removeClass('icon-envelope').addClass('icon-mobile');
        } else {
            $field.addClass('email').attr('placeholder', '<?php echo $this->lang->line('email_address'); ?>');
            $parent.find('i').removeClass('icon-mobile').addClass('icon-envelope');
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