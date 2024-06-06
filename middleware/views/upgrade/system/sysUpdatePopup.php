<form id="sysupdate-form" autocomplete="off" method="post">
    <div class="form-group form-group-feedback form-group-feedback-left">
        <input autocomplete="off" name="hidden" type="text" style="display:none;">
        <input type="password" class="form-control form-control-lg sysupdate-password" placeholder="Нууц үг оруулна уу" id="sysupdate-pass" autocomplete="off" name="ps">
        <div class="form-control-feedback form-control-feedback-lg">
            <i class="icon-key"></i>
        </div>
    </div>
</form>
<div id="sysupdate-next"></div>

<script type="text/javascript">
$(function() {
    
    $('#sysupdate-pass').disableAutofill();
        
    $('#sysupdate-pass').on('keydown', function(e) {
        
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code == 13) {
            var $this = $(this);
            
            $.ajax({
                type: 'post',
                url: 'mdupgrade/sysUpdateAccessByPass',
                data: {ps: $this.val()},
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(data) {
                    PNotify.removeAll();
                    
                    if (data.status == 'success') {
                        
                        $this.closest('form').hide();
                        $('#sysupdate-next').append(data.html);
                        
                        Core.initUniform($('#sysupdate-next'));
                        
                    } else {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                    }
                
                    Core.unblockUI();
                }
            });
        }
    });
    
    $(document.body).on('click', '#sysupdate-confirm-button', function(){
        $('#sysupdate-codenames').show();
    });
    
    $(document.body).on('click', 'input[name="codeNames[]"]', function(){
        var $this = $(this);
        var checkVal = $this.val();
        var codeNames = $('input[name="codeNames[]"]:checked').map(function(){
            return this.value; 
        }).get().join(',');
        
        if (codeNames != '') {
            $('#sysupdate-start-button').show();
        } else {
            $('#sysupdate-start-button').hide();
        }
        
        if (checkVal == 'frontend-full' && $this.is(':checked')) {
            var $uncheck = $('input[value="frontend-assets"], input[value="frontend-helper"], input[value="frontend-libs"], input[value="frontend-middleware"]');
            $uncheck.prop('checked', false);
            $.uniform.update($uncheck);
        }
        
        if ((checkVal == 'frontend-assets' || checkVal == 'frontend-helper' || checkVal == 'frontend-libs' || checkVal == 'frontend-middleware') && $this.is(':checked')) {
            var $uncheck = $('input[value="frontend-full"]');
            $uncheck.prop('checked', false);
            $.uniform.update($uncheck);
        }
    });
    
    $(document.body).on('click', '#sysupdate-start-button', function(){
        
        var codeNames = $('input[name="codeNames[]"]:checked').map(function(){
            return this.value; 
        }).get().join(',');
        
        $.ajax({
            type: 'post',
            url: 'mdupgrade/sysUpdate',
            data: {codeNames: codeNames},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                Core.unblockUI();
            }
        });
        
    });
    
}); 
</script>    