var Login = function () {

    var handleLogin = function() {
        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                username: {
                    required: true
                },
                password: {
                    required: true
                },
                security_code: {
                    required: true
                },
                remember: {
                    required: false
                }
            },
            messages: {
                username: {
                    required: "Username is required."
                },
                password: {
                    required: "Password is required."
                }, 
                security_code: {
                    required: "Captcha is required."
                }
            },
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.login-form')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function (error, element) {
                if (typeof element.attr('name') != 'undefined' && element.attr('name') == 'security_code') {
                    error.insertAfter(element);
                } else {
                    error.insertAfter(element.closest('.input-icon'));
                }
            },
            submitHandler: function (form) {
                $('button[type="submit"]').prop('disabled', true).prepend('<i class="icon-spinner4 spinner-sm mr-1"></i>');
                setLoginInfoInput();
                form.submit();
            }
        });

        $('.login-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.login-form').validate().form()) {
                    $('.login-form').submit();
                }
                return false;
            }
        });
    }; 
    
    var setLoginInfoInput = function() {
        var response = $.ajax({
            type: 'post',
            url: 'api/loginInfo', 
            data: {uname: $('input[name="user_name"]').val(), upass: $('input[name="pass_word"]').val()},
            dataType: 'text',
            async: false
        });
        
        if (response.status == 200) {
            $('.login-form').append('<input type="hidden" name="loginInfo" value="'+response.responseText+'">');
            $('input[name="user_name"], input[name="pass_word"]').removeAttr('name');
        }
    };
    
    var handlePasswordReset = function() {
        $('.login-form').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            invalidHandler: function (event, validator) { //display error alert on form submit   
                $('.alert-danger', $('.login-form')).show();
            },
            highlight: function (element) { // hightlight error inputs
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') == 'security_code') {
                    error.insertAfter(element);
                } else {
                    error.insertAfter(element.closest('.input-icon'));
                }
            },
            submitHandler: function (form) {
                $('button[type="submit"]').prop('disabled', true).prepend('<i class="icon-spinner4 spinner-sm mr-1"></i>');
                form.submit();
            }
        });

        $('.login-form input').keypress(function (e) {
            if (e.which == 13) {
                if ($('.login-form').validate().form()) {
                    $('.login-form').submit();
                }
                return false;
            }
        });
    }
    
    return {
        init: function () {	
            handleLogin();
        }, 
        initPasswordReset: function () {	
            handlePasswordReset();
        }
    };
}();