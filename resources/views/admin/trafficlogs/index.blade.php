@extends('admin.layouts.default')

@section('title')
    @lang('trafficlogs/title.page_title')
    @parent
@stop

{{--page level styles--}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>

@stop

{{--page content--}}
@section('content')

    <section class="content-header">
        <h1>@lang('trafficlogs/title.page_title')</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.reservations') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('app/general.home')
                </a>
            </li>
            <li class="active">@lang('trafficlogs/title.page_title')</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content paddingleft_right15">

        <form class="form-horizontal" method="post" action="{{ URL::to('admin/trafficlogs/data' ) }}" target="_blank">

            <div class="row">
                <div class="col-md-12">
                    <h2>@lang('trafficlogs/title.trafficlogs_options')</h2>
                    <hr/>
                </div>
            </div>

            <div class="row form-group">
                <div class="col-md-1" style="width: 160px">
                    <label>@lang('trafficlogs/title.date_from'):</label>
                    <input type="text" class="form-control" id="date_from" name="date_from" style="width: 140px"/>
                </div>
                <div class="col-md-2">
                    <label>@lang('trafficlogs/title.date_to'):</label>
                    <input type="text" class="form-control" id="date_to" name="date_to" style="width: 140px"/>
                </div>
            </div>

            <br/>

            <div class="row form-group">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fa fa-file-excel-o fa-1x" style="margin-right: 3px"></i>
                        @lang('trafficlogs/title.export_button')
                    </button>
                </div>
            </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>

    </section>

@stop

{{--page level script--}}
@section('footer_scripts')

    <script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>

    <script>
        $(function () {
            $("#date_from, #date_to").daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: true,
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });

            $("#date_from, #date_to").on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
            });

            $("#date_from, #date_to").on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });
    </script>
@stop

