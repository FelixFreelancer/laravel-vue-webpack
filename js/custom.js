// fixed header
$(function() {
	var fixHeader = 220;
	$(window).scroll(function() {
		var scroll = getCurrentScroll();
		if (scroll >= fixHeader) {
			$("body").addClass("md-top-50");
		} else {
			$("body").removeClass("md-top-50");
		}
	});

	function getCurrentScroll() {
		return window.pageYOffset || document.documentElement.scrollTop;
	}

	$(".updateprofile_picture").on("click", function(e) {
        e.preventDefault();
        var $this = $(this);
		$(".form__error").html("");
		$(".form__error").removeClass("error");
        $('.updateprofile_picture').addClass('loading');
        $('#image').ssCrop('generate', function (data) {
			if(data == ''){
				$(".form__error").html("Please select Image");
				$(".form__error").addClass("error");
			}else{
				$.ajax({
					dataType: 'json',
					method: 'post',
					url: ajaxURL + 'upload-profile-pic',
					data: {
						image: data
					},
					success: function (data) {
						$(".profile_pic").attr('src', data['data']['user']['image_name']);
						$('#image').ssCrop('resetCrop');
						$('.updateprofile_picture').removeClass('loading');
						$("#profile_pic").modal('hide');
					}
				});
			}
        });
	});

	$(".jq__read_notification").on("click", function(e) {
		var _this = $(this);
		$.ajax({
			dataType: "json",
			method: "post",
			url: ajaxURL+'read-notifications',
			data: {
				notification_id: _this.data('id')
			},
			success: function(data) {
				console.log("counter : "+data['data']['count']);
				$("#"+_this.data('id')).addClass('read');
				$(".jq__notification_count").text(data['data']['count']);
				if(data['data']['count'] != 0){
					$(".notification__count").text(data['data']['count']);
				}else{
					$(".notification__count").text('');
					$(".notification__count").removeClass('notification__count');
				}
				window.location.href = _this.data('url');
			}
		});
	});

	$(".modal").on("shown.bs.modal", function(e) {
		// $("#itemPhotosSlider").slick({
		// 	dots: false,
		// 	infinite: true,
		// 	speed: 300,
		// 	slidesToShow: 1
		// });
		$(".slider-for").slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: ".slider-nav"
		});
		$(".slider-nav").slick({
			slidesToShow: 2,
			slidesToScroll: 1,
			asNavFor: ".slider-for",
			dots: false,
			arrows: true,
			centerMode: true,
			focusOnSelect: true
		});
	});
});

var usersProfile = {
	data: {},
	el: {},
	init: function() {
		var _this = this;
		_this.bindUiActions();
		_this.sameAsCheckbox();
	},
	bindUiActions: function() {
		var _this = this;
		$("#information").change(function() {
			_this.sameAsCheckbox();
		});

		$(document).on(
			"change",
			'[name="ba_country"],[name="cd_country"],[name="ba_country_code"],[name="cd_country_code"]',
			function(e) {
				e.preventDefault();
				if ($(this).attr("name") == "ba_country") {
					$('[name="ba_country_code"]').val($(this).val());
				}
				if ($(this).attr("name") == "cd_country") {
					$('[name="cd_country_code"]').val($(this).val());
				}

				if ($(this).attr("name") == "ba_country_code") {
					$('[name="ba_country"]').val($(this).val());
				}
				if ($(this).attr("name") == "cd_country_code") {
					$('[name="cd_country"]').val($(this).val());
				}
			}
		);

		$(document).on(
			"change",
			"#email_id,#mobile_no, #phone_no, #country_code, #country",
			function(e) {
				e.preventDefault();
				$(".email_error").remove();
				$(".phone_error").remove();
				$.ajax({
					dataType: "json",
					method: "post",
					url: ajaxURL + "registration/unique",
					data: {
						cd_phone: $("#phone_no").val(),
						email: $("#email_id").val(),
						country_code: $("#country_code").val()
					},
					success: function(data) {
						$(".custom_errors").remove();
						if (!data["status"]) {
							if (data["errors"]["email"] != undefined) {
								$("#email_id").after(
									'<p class="help-block email_error error">' +
										data["errors"]["email"][0] +
										"</p>"
								);
							}
							if (data["errors"]["cd_phone"] != undefined) {
								$("#phone_no").after(
									'<p class="help-block phone_error error">' +
										data["errors"]["cd_phone"][0] +
										"</p>"
								);
							}
						}
					}
				});
			}
		);
	},
	sameAsCheckbox: function() {
		if ($("#information:checked").val() == 1) {
			$('[name="ba_address"]').val($('[name="cd_address"]').val());
			$('[name="ba_state"]').val($('[name="cd_state"]').val());
			$('[name="ba_country"]').val($('[name="cd_country"]').val());
			$('[name="ba_country_code"]').val(
				$('[name="cd_country_code"]').val()
			);
			$('[name="ba_phone"]').val($('[name="cd_phone"]').val());
			$('[name="ba_city"]').val($('[name="cd_city"]').val());
			$('[name="ba_postalcode"]').val($('[name="cd_postalcode"]').val());
			$(".billingAddressDetails").hide();
		} else {
			$('[name="ba_address"]').val("");
			$('[name="ba_state"]').val("");
			$('[name="ba_country"]').val("");
			$('[name="ba_country_code"]').val("");
			$('[name="ba_phone"]').val("");
			$('[name="ba_city"]').val("");
			$('[name="ba_postalcode"]').val("");
			$(".billingAddressDetails").show();
		}
	}
};

var login_form = {
	data: {},
	el: {
		form: "#login_form",
		message_box: "#login_message_box",
		authy_form: "#authy_code_form"
	},
	init: function() {
		var _this = this;
		_this.bindUiActions();
	},
	bindUiActions: function() {
		var _this = this;

		$('[name^="auth_code"]').keyup(function(e) {
			$(".form__error").hide();
			if (e.keyCode >= 48 && e.keyCode <= 57) {
				$(
					'[name="auth_code[' +
						(parseInt($(this).data("id")) + 1) +
						']"]'
				).focus();
			}
		});

		$(_this.el.form).validate({
			rules: {
				email: {
					required: true,
					email: true
				},
				password: {
					required: true
				}
			},
			// errorPlacement: function (error, element) {
			//     //element.siblings('.js--ss-error').html(error);
			//     element.parents('.form__group').find('.form__error').html(error).show();
			// },
			submitHandler: function(form) {
				_this.formSubmit($(form).serialize());
			}
		});
		$(_this.el.authy_form).validate({
			rules: {
				"auth_code[0]": {
					required: true
				},
				"auth_code[1]": {
					required: true
				},
				"auth_code[2]": {
					required: true
				},
				"auth_code[3]": {
					required: true
				},
				"auth_code[4]": {
					required: true
				},
				"auth_code[5]": {
					required: true
				},
				user_id: {
					required: true
				}
			},
			errorPlacement: function(error, element) {
				if (element.attr("name").indexOf("auth_code") >= 0) {
					element
						.parent()
						.parent()
						.find(".form__error")
						.html(error)
						.show();
				} else {
					error.insertBefore(element);
				}
			},
			submitHandler: function(form) {
				_this.authyFormSubmit($(form).serialize());
			}
		});
	},
	formSubmit: function(data) {
		var _this = this;
		$.ajax({
			dataType: "json",
			method: "post",
			url: ajaxURL + "login",
			data: data,
			success: function(data) {
				_this.formSuccess(data);
			},
			error: function(errors) {
				var errors = errors.responseJSON.errors;
				$(".form--error").remove();
				for (var index in errors) {
					$("#login_form")
						.find('[name="' + index + '"]')
						.after(
							'<span class="form--error">' +
								errors[index][0] +
								"</span>"
						);
				}
			}
		});
	},
	formSuccess: function(data) {
		var _this = this;
		if(data['type'] == 'otp'){
			window.location = siteURL+"users/"+data['user_id']+"/otp-verification";
		}else{
			if (data["status"]) {
				if(data['payment_required']){
						window.location.href = data['url'];
				}else{
					if (data["authy_required"]) {
						$("#login_form").hide();
						$("#authy_code_form")
						.find('[name="user_id"]')
						.val(data["user_id"]);
						$("#authy_form").show();
					} else {
						if(data['url']){
							window.location.href = data['url']+"/";
						}else{
							window.location.href = siteURL;
						}
					}
				}
			} else {
				$(_this.el.message_box).show();
				$(_this.el.message_box).html(data["message"]);
			}
		}
	},
	authyFormSubmit: function(data) {
		var _this = this;
		$.ajax({
			dataType: "json",
			method: "post",
			url: ajaxURL + "google-authentication",
			data: data,
			success: function(data) {
				_this.formSuccess(data);
			},
			error: function(errors) {
				var errors = errors.responseJSON.errors;
				$(".form--error").remove();
				for (var index in errors) {
					$("#login_form")
						.find('[name="' + index + '"]')
						.after(
							'<span class="form--error">' +
								errors[index][0] +
								"</span>"
						);
				}
			}
		});
	},
	authyFormSuccess: function(data) {
		var _this = this;
		if (data["status"]) {
			window.location.href = siteURL;
		} else {
			$(_this.el.message_box).show();
			$(_this.el.message_box).html(data["message"]);
		}
	}
};
$(function() {
	$('a[href*="#"]:not([href="#"])').click(function() {
		if (
			location.pathname.replace(/^\//, "") ==
				this.pathname.replace(/^\//, "") &&
			location.hostname == this.hostname
		) {
			var e = $(this.hash);
			if (
				((e = e.length
					? e
					: $("[name=" + this.hash.slice(1) + "]")),
				e.length)
			)
				return (
					$("html,body").animate(
						{
							scrollTop: e.offset().top
						},
						500
					),
					!1
				);
		}
	});
});
