@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    @lang('clinics/title.list_page')
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
        <h1>@lang('clinics/title.list_page')
            <small>- @lang('clinics/title.page_desc')</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.reservations') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('app/general.home')
                </a>
            </li>

            <li class="active">@lang('clinics/title.list_page')
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
                        @lang('clinics/title.list')
                    </h4>

                    <div class="pull-right">
                        <a href="{{ route('admin.clinics.create') }}" class="btn btn-sm btn-default"><span
                                    class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                    </div>
                </div>

                <br/>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                            <tr>
                                <th style="width: 40%">
                                    @lang('clinics/title.clinic')
                                </th>
                                <th style="width: 10%">
                                    @lang('clinics/title.site_id')
                                </th>
                                <th style="width: 10%">
                                    @lang('clinics/title.res_future')
                                </th>
                                <th style="width: 10%">
                                    @lang('clinics/title.res_past')
                                </th>
                                <th style="width: 10%">
                                    @lang('clinics/title.res_completed')
                                </th>
                                <th style="width: 5%">
                                    @lang('clinics/title.is_enabled')
                                </th>
                                <th style="min-width: 110px">
                                    @lang('clinics/title.view_res_title')
                                </th>
                                <th style="min-width: 110px">
                                    @lang('clinics/title.export_title')
                                </th>
                                <th style="min-width: 110px">
                                    @lang('clinics/title.edit_clinic_title')
                                </th>
                                <th style="min-width: 90px">
                                    @lang('button.delete')
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clinics as $clinic)
                                <tr>
                                    <td>
                                        {{ $clinic->name }}
                                    </td>
                                    <td>
                                        {{ $clinic->site_name }}
                                    </td>
                                    <td>
                                        {{ $clinic->future_res }}
                                    </td>
                                    <td>
                                        {{ $clinic->old_res_total }}
                                    </td>
                                    <td>
                                        {{ $clinic->old_res_completed }}
                                    </td>
                                    <td align="center">
                                        @if($clinic->is_enabled)
                                            <img src='{{  asset('assets/img/chk_true32.png') }}' height='16' >
                                        @endif
                                    </td>
                                    <td>
                                        @if($clinic->id == 0)
                                            <span>&nbsp;</span>
                                        @else
                                            <A href="{{ URL::to('admin/reservations/' . $clinic->id ) }}"
                                               class="btn btn-default btn-xs blue-stripe action-btn">
                                                <i class="fa fa-bars" style="margin-right: 4px"></i>
                                                @lang('clinics/title.view_res')
                                            </A>
                                        @endif
                                    </td>
                                    <td>
                                        <A href="{{ URL::to('admin/export/index/' . $clinic->id ) }}"
                                           class="btn btn-default btn-xs green-stripe action-btn">
                                            <i class="fa fa-share" style="margin-right: 4px"></i>
                                            @lang('clinics/title.export')
                                        </A>
                                    </td>
                                    <td>
                                        @if($clinic->id == 0)
                                            <span>&nbsp;</span>
                                        @else
                                            <A href="{{ route('admin.clinics.edit',  $clinic->id )}}"
                                               class="btn btn-default btn-xs orange-stripe action-btn">
                                                <i class="fa fa-edit" style="margin-right: 4px"></i>
                                                @lang('clinics/title.edit_clinic')
                                            </A>
                                        @endif
                                    </td>
                                    <td>
                                        @if($clinic->id == 0)
                                            <span>&nbsp;</span>
                                        @else
                                            <A href="{{ route('admin.clinics.confirm-delete',  $clinic->id )}}"
                                               data-toggle="modal" data-target="#delete_confirm" class="btn btn-default btn-xs red-stripe action-btn">
                                                <i class="fa fa-edit" style="margin-right: 4px"></i>
                                                @lang('app/general.delete')
                                            </A>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')

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