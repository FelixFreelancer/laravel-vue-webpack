<meta charset="utf-8"/>
<meta name="description" content="Latest updates and statistic charts">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="_token" content="{!! csrf_token() !!}"/>
<!--begin::Web font -->
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
<script>
    WebFont.load({
        google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
        active: function () {
            sessionStorage.fonts = true;
        }
    });
</script>
<!--end::Web font -->

<!--begin::Base Styles -->
<link rel="stylesheet" href="{!! asset(mix('css/admin/metronic.css')) !!}">
<link rel="stylesheet" href="{!! asset(mix('css/admin/crop.css')) !!}">
<link rel="stylesheet" href="{!! asset(mix('css/admin/croppie.css')) !!}">
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<!--end::Base Styles -->
<link rel="shortcut icon" href="{!! asset('img/favicon.png') !!}"/>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.min.css"  type="text/css">

<style>
	.select2-selection--single {
		min-height: 36px !important;
		width: 100% !important;
	}
	.select2-selection__rendered {
		padding: 5px 15px !important;
	}

	.select2-container--default,.select2-selection--multiple {
		min-height: 36px !important;
		width: 100% !important;
	}

	.select2-selection__arrow {
		top: 17px !important;
	}
	.select2-search__field{
		width: 100% !important;
	}
</style>
