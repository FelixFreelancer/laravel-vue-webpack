//== Class Definition
var SnippetLogin = function () {

    var login = $('#m_login');

    var showErrorMsg = function (form, type, msg) {
        var alert = $('<div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
			<span></span>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        alert.animateClass('fadeIn animated');
        alert.find('span').html(msg);
    }

    //== Private Functions

    var displaySignInForm = function () {
        login.removeClass('m-login--forget-password');
        login.addClass('m-login--signin');
        login.find('.m-login__signin').animateClass('flipInX animated');
    }

    var displayForgetPasswordForm = function () {
        login.removeClass('m-login--signin');
        login.addClass('m-login--forget-password');
        login.find('.m-login__forget-password').animateClass('flipInX animated');
    }

    var handleFormSwitch = function () {
        $('#m_login_forget_password').click(function (e) {
            e.preventDefault();
            displayForgetPasswordForm();
        });

        $('#m_login_forget_password_cancel').click(function (e) {
            e.preventDefault();
            displaySignInForm();
        });
    }

    var handleSignInFormSubmit = function () {
        $('#m_login_signin_submit').click(function (e) {
            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    login: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                e.preventDefault();
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
            form.submit();
        });
    }

    var handleForgetPasswordFormSubmit = function () {
        $('#m_login_forget_password_submit').click(function (e) {

            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                }
            });

            if (!form.valid()) {
                e.preventDefault();
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
            form.submit();
        });
    }

    //== Public Functions
    return {
        // public functions
        init: function () {
            handleFormSwitch();
            handleSignInFormSubmit();
            handleForgetPasswordFormSubmit();
        }
    };
}();

//== Class Initialization
jQuery(document).ready(function () {
    SnippetLogin.init();
});