            </div>
        </div>
    </div>
<script src="<?php echo autoVersion('assets/custom/pages/scripts/login.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/common.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){ 
    Login.init();
    $('form#login-form #user_name').focus();
    <?php
    if ($this->isLoginSaveUsername) {
    ?>
    var sU = localStorage.getItem('_pf_u');  
    if (sU != '' && sU != null && /^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{4})$/.test(sU)) {
        $('form#login-form #user_name').val(decodeURIComponent(escape(atob(sU))));
        $('form#login-form input[name=pass_word]').focus();
        $('form#login-form input[name=isSaveUsername]').prop('checked', true);
    }
    <?php
    }
    ?>
});
function new_captcha() {
    var c_currentTime = new Date();
    var c_miliseconds = c_currentTime.getTime();
    document.getElementById('captcha').src = 'api/captcha?x='+c_miliseconds;
}
</script>
</body>
</html>