<?php
if (!$this->isAjax) {
?>
<div class="col-md-12">
    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="<?php echo URL; ?>rainloop/?sso&hash=<?php echo $this->ssoHash; ?>" allowfullscreen="true"></iframe>
    </div> 
</div>
<?php
} else {
?>
<div style="margin: -10px -15px 0 -15px">
    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="<?php echo URL; ?>rainloop/?sso&hash=<?php echo $this->ssoHash; ?>" allowfullscreen="true"></iframe>
    </div>
</div>    
<?php
}
?>

<script type="text/javascript">
$(function(){
    
    bpBlockMessageStart('Loading...');
        
    $('.embed-responsive-item').load(function(){
        PNotify.removeAll();
        bpBlockMessageStop();
    });
    
    <?php
    if (!$this->isAjax) {
    ?>
    $('.content-wrapper').css({'padding': 0, 'overflow': 'hidden'});
    $('.content-wrapper > .content').css('padding', 0);
    <?php
    }
    ?>
    
    webmailResize();
    
    $(window).resize(function(){
        webmailResize();
    });
});    

function webmailResize() {
    var $iframe = $('.embed-responsive-item');
    var windowHeight = $(window).height();
    var setHeight = windowHeight - $iframe.offset().top - 40;
    
    $('.embed-responsive-16by9').css('height', setHeight);
    $iframe.css('height', setHeight);
}
</script>