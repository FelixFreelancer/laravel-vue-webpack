<script type="text/javascript" src="{!! asset('js/app.js') !!}"></script>
<script type="text/javascript" src="{!! asset('js/ss-crop.js') !!}"></script>
<script type="text/javascript" src="{!! asset('js/croppie.js') !!}"></script>
<script type="text/javascript" src="{!! asset('js/jquery.validate.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('js/additional-methods.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('js/custom.js') !!}"></script>
<script type="text/javascript" src="{!! asset('js/moment.js') !!}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
    });
</script>


<!-- Start Enable Tawk.to Script -->

<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5b34d18ceba8cd3125e343a2/default';;
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script> 

<!-- End Disable Tawk.to Script -->

<script src="{!! asset('js/Cookies.js') !!}"></script>
<script>
    $(document).ready(function () {

        setTimeout(function(){
          $('.cookieAcceptDiv').show();
        }, 2000);

        if (Cookies.get('gpf_cookie_accept') == 'true') {
            $('.cookieAcceptDiv').remove();
        }
        $(document).on('click', '.cookieClose', function (e) {
            e.preventDefault();
            console.log("Inside close");
            Cookies.set('gpf_cookie_accept', 'true', {expiry: 86400,path:"/"});
        });
    });
</script>
<script>

$('#image').ssCrop({
    croppie: {
        viewport: {
            width: 300,
            height: 300,
        }
    },
    result: {
        size: {
            width: 300,
            format: 'jpeg'
        }
    }
});
$(document).on('click', '.ss-crop__submit', function (e) {
    e.preventDefault();
    if ($('#image_hidden').val() != '') {
        $.ajax({
            dataType: 'json',
            method: 'post',
            url: ajaxURL + 'medias/shipment',
            data: {
                image: $('#image_hidden').val()
            },
            success: function (data) {
                if (data['status']) {
                    $('#image').ssCrop('resetCrop');
                }
            }
        });
    }
});
    function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        setInterval(function () {
            if (--timer < 0) {}
            else{
                minutes = parseInt(timer / 60, 10)
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.text(minutes + ":" + seconds);
            }
        }, 1000);
    }

    var minute = 60 * parseInt("{!!config('site.otp_disable')!!}");
    startTimer(minute, $('#otp_timer'));
</script>
