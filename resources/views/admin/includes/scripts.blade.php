<script src="{!! asset(mix('js/admin/metronic.js')) !!}"></script>
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
    });
    $.fn.dataTableExt.oStdClasses.sPageButton = "btn m-btn--square  btn-default";
    $.fn.dataTableExt.oStdClasses.sLength = "js--lengthDatatableTable";
    $.extend($.fn.dataTable.defaults, {
        sPaginationType: "full_numbers",
        processing: true,
        serverSide: true,
        searching: false,
        "language": {
            "lengthMenu": "_MENU_",
            "info": "Displaying _START_ - _END_ of _TOTAL_ records"
        },
        "fnInitComplete": function (oSettings, json) {
            $(".js--lengthDatatableTable select").addClass('form-control', 'm-input');
            $(".js--lengthDatatableTable select").removeClass('dataTables_length', 'select2');
        },
    });
</script>

<script>
    $(document).on('click', '.markAllAsRead', function (e) {
        e.preventDefault();
        var $this = $(this);
        $.ajax({
            dataType: 'json',
            method: 'get',
            url: adminAjaxURL + 'notifications/read',
            success: function (data) {

            }
        })
    });
</script>

@if(session()->has('message') && session()->has('class'))
    <script>
        $(".alert").delay(4000).slideUp(200, function () {
            $(this).alert('close');
        });
    </script>
@endif
