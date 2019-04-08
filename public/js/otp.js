var otp = {
    el: {
        callExceptionError: '.jq__call_exception',
        generalErrorMessageClass: '.error_msg',
        changeMobilePopup: '#changeMobileNumber',
        mobileConfirmationPopup: '#mobile_confirmation',
    },
    init: function () {
        var _this = this;
        _this.bindUiActions();
    },
    bindUiActions: function () {
        var _this = this;

        setTimeout(function () {
            $('.already__desc').html('');
            $('.already__desc').removeClass('success');
            $('.otpViaCall').html('Haven’t received the SMS yet? You can <a class="confirmMobile" href="#nogo" title="Our system will call your phone with the one time passcode">Try the voice option</a> but please confirm that we have <a href="#nogo" data-toggle="modal" data-target="#changeMobileNumber">the right phone number</a> on file');
        }, otpExpiresInSeconds);

        setTimeout(function () {
			$('.already__desc').html('');
			$('.already__desc').removeClass('success');
            $('.otpViaCall').html('Didn’t received the call? Unfortunately, we couldn’t verify your phone number automatically, please <a href="javascript:;" class="js_contact_us">contact a support representative here</a>');
        }, callExpiresInSeconds);

        $(document).on('click', '.sendOtpViaCall', function (e) {
            e.preventDefault();
            $(_this.el.callExceptionError).html("");
            $.ajax({
                dataType: 'json',
                method: 'post',
                url: ajaxURL + "users/" + user_id + '/otp-call',
                success: function (data) {
                    if (data['status']) {
                        $('.otpViaCall').html('We are calling your registered phone number with your seven digit code. Please input the code below.');
                        $(_this.el.mobileConfirmationPopup).modal("hide");
                    } else {
                        $(_this.el.callExceptionError).html("Sommething went wrong.Please try again later.");
                        $(_this.el.callExceptionError).addClass("error");
                    }
                }
            });
        });

        $(document).on('click', '.js__change_number', function (e) {
            e.preventDefault();
            $(_this.el.callExceptionError).html("");
            $(_this.el.callExceptionError).removeClass("error");
            $(_this.el.mobileConfirmationPopup).modal("hide");
            setTimeout(function () {
                $(_this.el.changeMobilePopup).modal("show");
            }, 1000);
        });

        $(document).on('submit', '#update_number', function (e) {
            e.preventDefault();
            $(_this.el.generalErrorMessageClass).html('');
            $(_this.el.generalErrorMessageClass).removeClass("error");

            $.ajax({
                dataType: 'json',
                method: 'post',
                url: ajaxURL + "update-number",
                data: $("#update_number").serialize(),
                success: function (data) {
                    if (data['status']) {
                        $("#jq__cd_phone").val('');
                        $(_this.el.changeMobilePopup).modal("hide");
                        setTimeout(function () {
                            $(".js__mobile_number").html("+" + data['user']['cd_country_code'] + data['user']['cd_phone']);
                            $(_this.el.mobileConfirmationPopup).modal("show");
                        }, 1000);
                    } else {
                        $.each(data['errors'], function (i, val) {
                            $("." + i + "_error").html(val);
                            $("." + i + "_error").addClass("error");
                        });
                    }
                }
            });
        });

        $(document).on('click', '.confirmMobile', function (e) {
            e.preventDefault();
            $(".js__mobile_number").html("+" + code + mobile_number);
            $(_this.el.mobileConfirmationPopup).modal("show");
        });

        $('[name^="otp"]').keyup(function (e) {
            $('.form__error').hide();
            if (e.keyCode >= 48 && e.keyCode <= 57) {
                $('[name="otp[' + (parseInt($(this).data('id')) + 1) + ']"]').focus();
            }
        });

        $(_this.el.mobileConfirmationPopup).on("hidden.bs.modal", function () {
            $(_this.el.generalErrorMessageClass).html('');
            $(_this.el.generalErrorMessageClass).removeClass("error");
            $(_this.el.callExceptionError).html('');
            $(_this.el.callExceptionError).removeClass("error");
        });

        $(_this.el.changeMobilePopup).on("hidden.bs.modal", function () {
            $("#jq__cd_phone").val('');
        });
    }
};
