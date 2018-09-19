$(document).ready(function () {
    $('#login_form').validate({
        rules: {
            account: {
                minlength: 2,
                required: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            account: "账号为用户名(不超30个字符)",
            password: "密码最少为6位字符",
        },
        highlight: function (element) {
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (element) {

            element.closest('.form-group').removeClass('has-error').removeClass('has-success');
        }
    });

});