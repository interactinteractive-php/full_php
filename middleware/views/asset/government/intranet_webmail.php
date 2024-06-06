<div id="intranet_webmail_wrap"></div>

<script type="text/javascript">
$(function(){

    $('a[href="#intranet_tab2"]').on('shown.bs.tab', function(e){
        var $webmailWrap = $('#intranet_webmail_wrap');
        
        if ($webmailWrap.children().length == 0) {
            
            $.ajax({
                type: 'post',
                url: 'webmail/getSsoHash',
                dataType: 'json',
                beforeSend:function(){
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success:function(data){
                        
                    if (data.status === 'success') {
                        
                        $webmailWrap.html('<div class="embed-responsive embed-responsive-16by9">'+
                            '<iframe class="embed-responsive-item" src="'+URL_APP+'rainloop/?sso&hash='+data.ssoHash+'" allowfullscreen="true"></iframe>'+
                        '</div>').promise().done(function(){
                            $('.embed-responsive-item').load(function(){
                                webmailResize();
                                Core.unblockUI();
                            });
                        });
                        
                    } else {
                        $webmailWrap.html(data.message);
                        Core.unblockUI();
                    }
                }
            });
        }
    });
    
    $(window).resize(function(){
        webmailResize();
    });
});

function webmailResize() {
    var $iframe = $('.embed-responsive-item');
    var windowHeight = $(window).height();
    var setHeight = windowHeight - $iframe.offset().top;
    
    $('.embed-responsive-16by9').css({'width': $(window).width(), 'height': setHeight});
    $iframe.css('height', setHeight);
}
</script>