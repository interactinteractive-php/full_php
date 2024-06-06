            </div>
        </div>
    </div>
    <footer>    
        <div class="footer-bottom">
            <ul class="footer">
                <li><a href="https://www.facebook.com/ParliamentofMongolia" target="_blank" rel="noopener noreferrer"><img src="assets/custom/gov/img/facebook.svg" width="30"/></a></li>
                <li><a href="https://twitter.com/ParliamentMN" target="_blank" rel="noopener noreferrer"><img src="assets/custom/gov/img/twitter.svg" width="30"/></a></li>
                <li><a href="https://www.instagram.com/parliament.mn/" target="_blank" rel="noopener noreferrer"><img src="assets/custom/gov/img/instagram.svg" width="30"/></a></li>
                <li><a href="https://www.youtube.com/c/ParliamentofMongolia/videos" target="_blank" rel="noopener noreferrer"><img src="assets/custom/gov/img/youtube.svg" width="30"/></a></li>
                <li><?php echo Config::getFromCacheDefault('login_footer_text', null, 'МОНГОЛ УЛСЫН ИХ ХУРЛЫН ТАМГЫН ГАЗАР © 2021.'); ?></li>
            </ul>
        </div>
</footer>
<script src="<?php echo autoVersion('assets/custom/pages/scripts/login.js'); ?>" type="text/javascript"></script>
<script src="<?php echo autoVersion('assets/core/js/main/common.js'); ?>" type="text/javascript"></script>
<?php if (Config::getFromCache('USE_SNOW')) { ?>
    <script src="assets/core/js/main/snow-it.js" type="text/javascript"></script>
<?php } ?>

<script type="text/javascript">
jQuery(document).ready(function(){ 
    Login.init();
    <?php if (Config::getFromCache('USE_SNOW')) { ?>
        $.fn.snowit({ maxSize: 40, total: 50 });
    <?php } ?>
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