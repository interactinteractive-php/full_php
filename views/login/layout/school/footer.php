        </div>
    </div>
</div>
<script src="<?php echo autoVersion('assets/custom/pages/scripts/login.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/common.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function(){ 
    Login.init();
    $("form#login-form #user_name").focus();
});
function new_captcha() {
    var c_currentTime = new Date();
    var c_miliseconds = c_currentTime.getTime();
    document.getElementById('captcha').src = 'api/captcha?fDownload=1&x='+c_miliseconds;
}
</script>
</body>
</html>