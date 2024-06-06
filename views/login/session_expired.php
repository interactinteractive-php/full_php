<div class="col-md-12">
    <input type="text" name="username" class="se_userinfo" value=""/>
    <input type="password" name="password" class="se_userinfo" value=""/>
    <div class="row">
        <div class="col-md-2">
            <i class="fa fa-lock" style="color: #f3ac4b; font-size: 75px;"></i>
        </div>
        <div class="col-md-10" style="padding-top: 8px;">
            <h2 style="margin-top: 0;font-weight: 500;margin-bottom: 7px;">Session expired</h2>
            Та дахин нууц үгээ оруулж системд нэвтрэнэ үү
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr class="mt10"/>    
        </div>
    </div>    
    <div class="row">
        <div class="col-md-12">
            <?php
            if ($this->ldapControl) {
            ?>
            <div class="form-group">
                <?php echo $this->ldapControl; ?>
            </div>
            <?php
            }
            ?>
            <div class="form-group form-group-feedback form-group-feedback-left">
                <?php 
                echo Form::password(
                    array(
                        'name' => 'session-expired-pass', 
                        'class' => 'form-control form-control-lg readonly-white-bg', 
                        'placeholder' => 'Энд нууц үгээ оруулна уу', 
                        'required' => 'required', 
                        'autocomplete' => 'off', 
                        'readonly' => 'readonly', 
                        'onfocus' => 'this.removeAttribute(\'readonly\');'
                    )
                ); 
                ?>
                <div class="form-control-feedback form-control-feedback-lg">
                    <i class="icon-lock2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix w-100 mt20"></div>
    <div class="row">
        <div class="col-md-6 text-left">
            <button type="button" class="btn green-meadow session-expired-login"><i class="fa fa-unlock"></i> Нэвтрэх</button>
        </div>    
        <div class="col-md-6 text-right">
            <a href="logout" class="btn btn-secondary"><i class="fa fa-sign-in"></i> Нэвтрэх хуудас руу очих</a>
        </div> 
    </div>    
</div>    
<div class="clearfix w-100 mb15"></div>

<script type="text/javascript">
$(function(){
    
    setTimeout(function(){
        $('.se_userinfo').hide();
    }, 5);
    
    $('input[name="session-expired-pass"]').on('keydown', function(e){
        var keyCode = (e.keyCode ? e.keyCode : e.which);
        
        if (keyCode == 13) {
            
            PNotify.removeAll();
            var $this = $(this), thisVal = ($this.val()).trim();
            
            if (thisVal != '') {
                $('.session-expired-login').click();
            } else {
                new PNotify({
                    title: 'Warning',
                    text: 'Нууц үг оруулна уу!',
                    type: 'warning', 
                    sticker: false
                });
            }
        }
    });
    
    $('.session-expired-login').on('click', function() {
        
        PNotify.removeAll();
        var thisVal = ($('input[name="session-expired-pass"]').val()).trim();
        
        if (thisVal == '') {
            new PNotify({
                title: 'Warning',
                text: 'Нууц үг оруулна уу!',
                type: 'warning', 
                sticker: false
            });
            $('input[name="session-expired-pass"]').focus();
            return;
        }
        
        var postData = {i: timerUid, k: timerUKid, pass: thisVal};
        
        if (typeof cd97d6s8dg7sed4 !== 'undefined') {
            postData['cd97d6s8dg7sed4'] = cd97d6s8dg7sed4;
        }
        
        if ($('input[name="isLdap"]:checked').length) {
            postData['isLdap'] = 1;
        }
                
        $.ajax({
            type: 'post',
            url: 'login/sessionExpiredLogin',
            data: postData, 
            dataType: 'json', 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                Core.unblockUI();
                
                if (data.status == 'success') {
                    
                    if (typeof mgChat !== 'undefined') {
                        rtc.send({type: 'login', data: rtcLoginParams});
                    }
                    
                    $('#dialog-session-expired').dialog('close');
                    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }
            }
        });
    });
});    
</script>