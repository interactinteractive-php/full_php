<div class="container login-verification">
    <div class="top-icon d-flex justify-content-center mt-1 mb-4">
        <i class="icon-lock2"></i>
    </div>
    <h3 class="font-weight-bold line-height-normal text-uppercase font-size-14">Хэрэглэгчийг баталгаажуулах</h3>
    <p class="font-size-13 text-grey">Таны нэвтрэлт өмнөх түүхээсээ өөр байгаа тул аюулгүй байдлыг хангах үүднээс, таныг мөн эсэхийг нууцлалын кодоор баталгаажуулах хэсэг!</p>
    
    <div class="wizard blue">
        <div class="steps clearfix">
            <ul role="tablist">
                <li id="vertab-step-1" class="first current">
                    <a href="javascript:;">
                        <span class="number">1</span> 
                    </a>
                </li>
                <li id="vertab-step-2" class="disabled">
                    <a href="javascript:;" class="disabled">
                        <span class="number">2</span> 
                    </a>
                </li>
                <li id="vertab-step-2" class="disabled">
                    <a href="javascript:;" class="disabled">
                        <span class="number">3</span> 
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="verification-step0">
        <div class="verification-steps">
            <?php echo $this->step1; ?>
        </div>
    </div>
</div>

<style>
    .content-wrapper {
        background: #f5f8fb url('assets/custom/img/dot.png') repeat center center;
    }
    .loginrun .bluebg {
        display: none;
    }
</style>

<script type="text/javascript">
$(function() {
    
    $('.verification-steps').on('click', '.verification-step1 a.list-group-item-action', function() {
        var $this = $(this);
        var $parent = $this.parent();
        
        $parent.find('.active').removeClass('active');
        $parent.find('.icon-checkmark3').remove();
        
        $this.addClass('active').append('<i class="icon-checkmark3 text-green ml-auto"></i>');
    });
    
    $('.verification-steps').on('click', '.verification-step1 button', function() {
        
        var $this = $(this);
        
        $this.prop('disabled', true).prepend('<i class="icon-spinner4 spinner-sm mr-1"></i> ');
        
        var $parent = $this.parent();
        var sendType = $parent.find('.active[data-type]').attr('data-type');
        
        PNotify.removeAll();
        
        $.ajax({
            type: 'post',
            url: 'login/sendVerificationCode',
            data: {sendType: sendType},
            dataType: 'json',
            success: function(data) {
                
                if (data.status == 'success') {
                    
                    var $steps = $('.verification-steps');
                    $steps.empty().append(data.step2).promise().done(function() {
                        $('#vertab-step-1').removeClass('current').addClass('done');
                        $('#vertab-step-2').removeClass('disabled').addClass('current');
                        $steps.find('input[type="text"]').focus();
                    });
                    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: 'pnotify-center'
                    });
                }
                
                $this.prop('disabled', false).find('i').remove();
            }
        });
    });
    
    $('.verification-steps').on('click', '.verification-step2 button', function() {
        var $this = $(this);
        var $parent = $this.closest('.verification-step2');
        var $input = $parent.find('input[type="text"]');
        var code = $input.val().trim();
        
        $input.removeClass('border-danger');
        
        if (code.length) {
            
            PNotify.removeAll();
            
            $.ajax({
                type: 'post',
                url: 'login/checkVerificationCode',
                data: {code: code},
                dataType: 'json',
                beforeSend: function() {
                    $this.prop('disabled', true);
                },
                success: function(data) {
                    if (data.status == 'success') {
                        
                        var $steps = $('.verification-steps');
                        var step3 = '<div class="text-center">';
                            step3 += '<h5 class="font-weight-bold">Та энэ төхөөрөмжийг хадгалах уу?</h5>';
                            step3 += '<p class="d-block mb-3 text-grey">';
                                step3 += 'Хэрэв та энэ төхөөрөмжийг хадгалбал тухайн төхөөрөмжөөс ';
                                step3 += 'дахин нэвтрэх үед баталгаажуулах код асуухгүй буюу түүнийг таны тогтмол ';
                                step3 += 'хэрэглэдэг төхөөрөмжийн бүртгэлд нэмэх болно.';
                            step3 += '</p>';
                            step3 += '<a href="javascript:;" class="btn btn-light px-4" id="no-deviceverification">Үгүй</a>';
                            step3 += '<a href="javascript:;" class="btn btn-primary bg-root-color px-4 ml-2" id="yes-deviceverification">Тийм</a>';
                        step3 += '</div>';
                        
                        $steps.empty().append(step3).promise().done(function() {
                            $('#vertab-step-2').removeClass('current').addClass('done');
                            $('#vertab-step-3').removeClass('disabled').addClass('current');
                        });

                    } else {
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false, 
                            addclass: 'pnotify-center'
                        });
                    }

                    $this.prop('disabled', false);
                }
            });
            
        } else {
            $input.addClass('border-danger');
        }
    });
    
    $('.verification-steps').on('click', '.verification-step2 .verification-step1-back', function() {
        $.ajax({
            type: 'post',
            url: 'login/verificationStep1',
            success: function(data) {
                
                $('#vertab-step-1').removeClass('done').addClass('current');
                $('#vertab-step-2').removeClass('current').addClass('disabled');
                        
                var $steps = $('.verification-steps');
                $steps.empty().append(data);
            }
        });
    });
    
    $(document.body).on('click', '#yes-deviceverification', function() {
        
        Core.blockUI({message: 'Loading...', boxed: true, zIndex: 1100});
        
        setTimeout(function() {
            window.location = 'login/validDeviceVerification/1';
        }, 300);
    });
    
    $(document.body).on('click', '#no-deviceverification', function() {
        
        Core.blockUI({message: 'Loading...', boxed: true, zIndex: 1100});
        
        setTimeout(function() {
            window.location = 'login/validDeviceVerification/0';
        }, 300);
    });
    
});
</script>