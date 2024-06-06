var anketForm = function(){
    //<editor-fold defaultstate="collapsed" desc="variables">

    var initEvent = function(departmentId, departmentName, positionId, positionName, templateId, campaignKeyId, kpiTemplateId, ccanket){
        var $processHtml = $('#processHtml');

        if (ccanket !== '1') {
            $processHtml.find('input[name="param[departmentId]"]').val(departmentId).prop('readonly', true);
            $processHtml.find('input[name="param[departmentName]"]').val(departmentName).prop('readonly', true);
            $processHtml.find('input[name="param[positionId]"]').val(positionId).prop('readonly', true);
            $processHtml.find('input[name="param[positionName]"]').val(positionName).prop('readonly', true);
            $processHtml.find('input[name="param[templateId]"]').val(templateId).prop('readonly', true);
            $processHtml.find('input[name="param[campaignKeyId]"]').val(campaignKeyId).prop('readonly', true);
            $processHtml.find('input[name="param[kpiTemplateId]"]').val(kpiTemplateId);
        }
        
        var $topMenu = $('.nav.nav-scroll'),
            topMenuHeight = $topMenu.outerHeight(),
            $menuItems = $topMenu.find('a');

        $menuItems.click(function(e) {
            var href = $(this).attr('data-go-fixed-id'),
                offsetTop = $('[data-fixed-id="'+href+'"]').offset().top - topMenuHeight - 55;

            $('html, body').stop().animate({ 
                scrollTop: offsetTop
            }, 300);

            e.preventDefault();
        });

        $(document).scroll(function() { 

            var $sections = $('[data-fixed-id]');
            var pos = $(document).scrollTop() + 100;

            $sections.each(function() {

                var $this = $(this);
                var top = $this.offset().top - topMenuHeight;
                var bottom = top + $this.outerHeight();

                if (pos >= top && pos <= bottom) {

                    var id = $this.attr('data-fixed-id');

                    if (id) {  
                        $topMenu.find('.active').removeClass('active');
                        $topMenu.find('a[data-go-fixed-id="' + id + '"]').parent().addClass('active');
                    }
                }
            });
        });

        $processHtml.find('.bpMainSaveButton').click(function(e, a){
            var $this = $(this);
            var $parentForm = $('#wsForm');
            var uniqId = $parentForm.parent().attr('data-bp-uniq-id');
            
            if (window['processBeforeSave_' + uniqId]($this) && bpFormValidate($parentForm)) {
                
                PNotify.removeAll();
                
                if (typeof a === 'undefined' && $('#confirm-anket').length) {
                    
                    $('#confirm-anket').modal('show');
                    
                } else {
                    
                    $parentForm.ajaxSubmit({
                        type: 'post',
                        url: 'anket/runProcess',
                        dataType: 'json',
                        async: false,
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        beforeSubmit: function(formData, jqForm, options) {
                            formData.push({ name: 'captchaCode', value: $('#captcha_input').val() });
                        },
                        success: function(responseData) {
                            
                            $('.hranket-modal-sendbtn').prop('disabled', false).find('i').remove();
                            
                            if (responseData.status == 'error') {
                                new PNotify({
                                    title: responseData.status,
                                    text: responseData.message,
                                    type: responseData.status,
                                    sticker: false,
                                    hide: true,
                                    delay: 1000000000
                                });
                                $parentForm.find('input[name="windowSessionId"]').val(responseData.uniqId);
                            } else {
                                /*new PNotify({
                                    title: responseData.status,
                                    text: responseData.message,
                                    type: responseData.status,
                                    sticker: false
                                });*/
                                $('#confirm-anket').modal('hide');
                                
                                $processHtml.empty().append('<div class="alert alert-success">Таны анкетыг амжилттай хүлээн авлаа.</div>');
                            }

                            Core.unblockUI();
                        }
                    });
                }
            }
        });
        
        $('.hranket-modal-sendbtn').click(function(){
            $(this).prop('disabled', true).prepend('<i class="icon-spinner2 spinner"></i>');
            $processHtml.find('.bpMainSaveButton').trigger('click', [true]);
        });
        
        $('.hranket-modal-closebtn').click(function(){
            $('#confirm-anket').modal('hide');
        });
        
        $('#captcha_reload').click(function(){
            var c_currentTime = new Date();
            var c_miliseconds = c_currentTime.getTime();
            document.getElementById('captcha_img').src = 'api/captcha?fDownload=1&x='+c_miliseconds;

            $('#captcha_input').focus();
        });

    };
    //</editor-fold>
    return {
        init: function(departmentId, departmentName, positionId, positionName, templateId, campaignKeyId, kpiTemplateId, ccanket){
            initEvent(departmentId, departmentName, positionId, positionName, templateId, campaignKeyId, kpiTemplateId, ccanket);
        }
    };
}();