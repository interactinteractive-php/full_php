<script type="text/javascript">
var URL_FN = URL,
    URL_APP = '<?php echo URL; ?>',
    ENVIRONMENT = '<?php echo ENVIRONMENT; ?>',
    sysLangCode = '<?php echo Lang::getCode(); ?>',
    decimal_fixed_num = 6, 
    round_scale = 2, 
    vr_top_menu = <?php echo (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) ? 'true' : 'false'; ?>, 
    dv_no_resizable = <?php echo (defined('DV_NO_RESIZABLE') ? DV_NO_RESIZABLE : "false"); ?>, 
    isAlwaysConfirmCloseTab = <?php echo Config::getFromCache('CONFIG_ALWAYS_CONFIRM_CT') ? 'true' : 'false'; ?>, 
    isAppMultiTab = <?php echo Config::getFromCacheDefault('CONFIG_MULTI_TAB', null, 0); ?>,
    CONFIG_URL = '<?php echo (defined('CONFIG_URL') ? CONFIG_URL : ''); ?>', 
    isAlwaysNewTab = <?php echo Config::getFromCacheDefault('CONFIG_ALWAYS_NEWTAB', null, 0); ?>,
    checkModifiedCatch = '<?php echo Config::getFromCache('CONFIG_CHECK_MODIFIED_CATCH'); ?>', 
    isTestServer = <?php echo Config::getFromCache('IS_TEST_SERVER') ? 'true' : 'false'; ?>,
    isCloseOnEscape = <?php echo Config::getFromCache('CONFIG_IS_CLOSE_ON_ESCAPE') ? 'true' : 'false'; ?>,
    pnotifyPosition = '<?php echo Config::getFromCache('CONFIG_PNOTIFY_POSITION'); ?>', 
    isDeleteActionBeforeReload = <?php echo Config::getFromCache('CONFIG_IS_DELETEACTION_BEFORERELOAD') ? 'true' : 'false'; ?>, 
    is_pfd = <?php echo Config::getFromCache('IS_DEV') ? 'true' : 'false'; ?>, 
    usePushNotification = <?php echo Config::getFromCacheDefault('usePushNotification', null, 0); ?>, 
    menuCountInterval = <?php echo Config::getFromCacheDefault('menuCountInterval', null, 0); ?>, 
    uid = '<?php echo Ue::sessionUserId(); ?>',
    isTouchEnabled = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0), 
    gmapApiKey = '<?php echo Config::getFromCache('googleMapApiKey'); ?>'; 
    <?php 
    if($accountMask = Config::get('CONFIG_ACCOUNT_CODE_MASK', 'departmentId='.Ue::sessionUserKeyDepartmentId())) echo 'var accountCodeMask = \''.$accountMask.'\';'; 
    if($storeKeeperKeyCodeMask = Config::getFromCache('CONFIG_STORE_KEEPER_KEY_CODE_MASK')) echo 'var storeKeeperKeyCodeMask = \''.$storeKeeperKeyCodeMask.'\';'; 
    if(Session::isCheck(SESSION_PREFIX . 'isUrlAuthenticate')) echo 'var isUrlAuth = 1;'; 
    if($sysTabLimitCount = Config::getFromCache('sysTabLimitCount')) echo 'var sysTabLimitCount = '.$sysTabLimitCount.';'; 
    if($bpSaveFptLog = Config::getFromCache('bpSaveFptLog')) echo 'var saveFptLog = \''.$bpSaveFptLog.'\';'; 
    ?> 
</script>