@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('users/title.user_list_page')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>

@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>@lang('users/title.user_list_page')</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.reservations') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('app/general.home')
                </a>
            </li>
            <li class="active">@lang('users/title.user_list_page')</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left">
                        <i class="livicon" data-name="list" data-size="16"
                           data-loop="true" data-c="#fff" data-hc="white"></i>
                        @lang('users/title.user_list')
                    </h4>

                    <div class="pull-right">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-default"><span
                                    class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                    </div>
                </div>
                <br/>

                <div class="panel-body">
                    <table class="table table-bordered table-striped" id="table"
                    style="word-wrap: break-word; word-break: break-all; white-space: normal;">
                        <thead>
                        <tr class="filters">
                            <th>@lang('users/title.username')</th>
                            <th>@lang('users/title.first_name')</th>
                            <th>@lang('users/title.last_name')</th>
                            <th>@lang('users/title.email')</th>
                            <th>@lang('users/title.site')</th>
                            <th>@lang('users/title.clinic_id')</th>
                            <th>@lang('users/title.role')</th>
                            <th style="min-width: 150px">@lang('app/general.table_actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

    <script>
        $(function () {
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{!! route('admin.users.data') !!}',
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                iDisplayLength: 100,
                "columnDefs": [
                    {"width": "12%", "targets": 0},
                    {"width": "12%", "targets": 1},
                    {"width": "12%", "targets": 2},
                    {"width": "15%", "targets": 3},
                    {"width": "8%", "targets": 4},
                    {"width": "15%", "targets": 5},
                    {"width": "10%", "targets": 6},
                    {"width": "15%", "targets": 7},
                ],
                columns: [
                    {data: 'username', name: 'username'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'email', name: 'email'},
                    {data: 'site_name', name: 'site_name'},
                    {data: 'clinic_name', name: 'clinic_name'},
                    {data: 'role_name', name: 'role_name'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ],
                language: {
                    info: "@lang('app/general.pagination_info')",
                    lengthMenu: "@lang('app/general.filters_entries')",
                    search: "@lang('app/general.filters_search')",
                    paginate: {
                        first: "@lang('app/general.pagination_first')",
                        last: "@lang('app/general.pagination_last')",
                        next: "@lang('app/general.pagination_next')",
                        previous: "@lang('app/general.pagination_prev')"
                    },
                }
            });
            table.on('draw', function () {
                $('.livicon').each(function () {
                    $(this).updateLivicon();
                });
            });
        });

    </script>

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>
    <script>
        $(function () {
            $('body').on('hidden.bs.modal', '.modal', function () {
                $(this).removeData('bs.modal');
            });
        });
    </script>
@stop
