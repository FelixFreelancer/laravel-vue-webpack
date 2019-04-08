@extends('admin.layouts.master')

@section('contentHeader')

@stop

@section('page_title')
    Quotations
@stop

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-md-4">
                                <div class="m-input-icon m-input-icon--left">
                                    <input type="text" class="form-control m-input m-input--solid"
                                           placeholder="Search..." id="generalSearch">
                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                        <span>
                                            <i class="la la-search"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <table class="table m-table m-table--head-bg-metal  table-striped" id="dataTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>User</th>
                    <th>Total Quotation</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
@stop

@section('contentFooter')
    <script>
        $('#users_roles').selectpicker();

        var dTable = $('#dataTable').DataTable({
            ajax: {
                url: adminAjaxURL + 'datatable-quotations',
                data: function (d) {
                    d.search['value'] = $('#generalSearch').val();
                }
            },
            columns: [
                {data: 'quotation_number', name: 'quotations.quotation_number', className: 'dt-center'},
                {data: 'user_name', name: 'users.first_name', className: 'dt-center'},
                {data: 'user_name', name: 'users.last_name', className: 'dt-center', visible: false},
                {
                    data: 'user_total_price',
                    name: 'quotations.quotation_number',
                    className: 'dt-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'quotations.quotation_number',
                    className: 'dt-center',
                    orderable: false,
                    searchable: false
                },
                {data: 'action', searchable: false, orderable: false, className: 'dt-center'},
            ],
            order: [[0, 'desc']]
        });

        $('#generalSearch').on('keydown keyup blur change', $.debounce(500, function () {
            dTable.draw();
        }));

        $('#users_roles').change($.debounce(500, function () {
            dTable.draw();
        }));


        $(document).on('click', '.deleteAdmin', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this admin?')) {
                $.ajax({
                    dataType: 'json',
                    method: 'delete',
                    url: adminURL + "admins/" + $(this).data('id'),
                    success: function (data) {
                        dTable.draw(false);
                    }
                });
            }
        });
    </script>
@stop
