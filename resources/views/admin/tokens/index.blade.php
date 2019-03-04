@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    @lang('tokens/title.list_page')
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
        <h1>@lang('tokens/title.list_page')
            <small>- @lang('tokens/title.page_desc')</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.reservations') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('app/general.home')
                </a>
            </li>

            <li class="active">@lang('tokens/title.list_page')
            </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left">
                        <i class="livicon" data-name="list" data-size="16" data-loop="true" data-c="#fff"
                           data-hc="white"></i>
                        @lang('tokens/title.list')
                    </h4>

                    <div class="pull-right">
                        <a href="{{ route('admin.tokens.create') }}" class="btn btn-sm btn-default"><span
                                    class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                    </div>
                </div>

                <br/>

                <div class="panel-body">
                    <table class="table table-bordered table-striped" id="table1">
                        <thead>
                        <tr class="filters">
                            <th>@lang('tokens/title.token_num')</th>
                            <th>@lang('tokens/title.title')</th>
                            <th>@lang('tokens/title.purpose_desc')</th>
                            <th style="min-width: 150px">@lang('app/general.table_actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- row-->
    </section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

    <script>
        $(function () {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{!! route('admin.tokens.data') !!}",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                iDisplayLength: 100,
                "columnDefs": [
                    {"width": "10%", "targets": 0},
                    {"width": "30%", "targets": 1},
                    {"width": "40%", "targets": 2},
                    {"width": "20%", "targets": 3}
                ],
                columns: [
                    {data: 'token_num'},
                    {data: 'title'},
                    {data: 'purpose_desc'},
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

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="delete_confirm_title"
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