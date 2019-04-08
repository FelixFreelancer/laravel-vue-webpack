@extends('admin.layouts.master')

@section('contentHeader')

@stop

@section('page_title')
    Users
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
                        {{--<a href="{!! url()->route('admin.admins.create') !!}"
                           class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                            <span>
                                <i class="m-nav__link-icon flaticon-add"></i>
                                <span>
                                    New Admin
                                </span>
                            </span>
                        </a>--}}
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <table class="table m-table m-table--head-bg-metal  table-striped" id="dataTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
            <!--end: Datatable -->
        </div>
    </div>
    @include('admin.popups.users_info')
@stop

@section('contentFooter')
    <script>
        $('#users_roles').selectpicker();

        var dTable = $('#dataTable').DataTable({
            ajax: {
                url: adminAjaxURL + 'datatable-users',
                data: function (d) {
                    d.search['value'] = $('#generalSearch').val();
                }
            },
            columns: [
                {data: 'id', name: 'id', className: 'dt-center', searchable: false},
                {data: 'first_name', name: 'users.first_name', className: 'dt-center'},
                {data: 'last_name', name: 'users.last_name', className: 'dt-center'},
                {data: 'email', name: 'users.email', className: 'dt-center'},
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

        $(document).on('click', '.viewUserInfo', function (e) {
            e.preventDefault();
            $.ajax({
                dataType: 'json',
                method: 'get',
                url: adminAjaxURL + 'users/' + $(this).data('id'),
                success: function (data) {
                    var user = data['user'];
                    $('#usersInfoModal').find('#modal_title').text(user['first_name'] + ' ' + user['last_name']);
                    if (user['same_as_cd'] == 1) {
                        $('#usersInfoModal').find('#biling_details_same').show();
                        $('#usersInfoModal').find('#biling_details').hide();
                    } else {
                        $('#usersInfoModal').find('#biling_details_same').hide();
                        $('#usersInfoModal').find('#biling_details').show();
                    }
                    for (var index in user) {
                        $('#usersInfoModal').find('#' + index).text(user[index]);
                    }
                    $('#usersInfoModal').modal('show');
                }
            })
        });


        $(document).on('click', '.deleteAdmin', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    dataType: 'json',
                    method: 'delete',
                    url: adminURL + "users/" + $(this).data('id'),
                    success: function (data) {
                        dTable.draw(false);
                    }
                });
            }
        });
    </script>
@stop
