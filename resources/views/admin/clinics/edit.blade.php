@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('clinics/title.edit')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    @include('admin.clinics.styles')
@stop

{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>{{ $isMyClinic ? Lang::get('clinics/title.my_clinic') : Lang::get('clinics/title.edit') }}</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('app/general.home')
                </a>
            </li>
            @if($isMyClinic)
                <li class="active">@lang('clinics/title.my_clinic')</li>
            @else
                <li><a href="#">@lang('clinics/title.list_page')</a></li>
                <li class="active">@lang('clinics/title.edit')</li>
            @endif
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="form-group" style="position: absolute; top: 5px; right: 30px;">
                            <input class="btn btn-sm btn-success" style="width: 160px;" type="submit" value="Save"
                                   onclick="$('form').submit();">
                            <a href="{!! route('admin.clinics.index') !!}" class="btn btn-sm btn-default"
                               style="width: 140px; margin-left: 10px">Cancel</a>
                        </div>
                        <h3 class="panel-title"><i class="livicon" data-name="home" data-size="16" data-c="#fff"
                                                   data-hc="#fff" data-loop="true"></i>
                            {{ $isMyClinic ? Lang::get('clinics/title.my_clinic_editing') : Lang::get('clinics/title.editing') }} {!! $clinic->code !!} - {!! $clinic->name !!}
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">
                            {!! Form::model($clinic, ['id' => 'frmMain', 'route' => ['admin.clinics.update', $clinic], 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) !!}

                                    <!-- CSRF Token -->
                            <input type="hidden" name="_method" value="PATCH"/>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                            <div class="col-md-7">
                                @include('admin.clinics.fields', ['edit' => true, 'isMyClinic' => $isMyClinic])
                            </div>

                            <div class="col-md-5">
                                @include('admin.clinics.holidays', ['edit' => true])
                                @include('admin.clinics.work_times', ['edit' => true])
                                @include('admin.clinics.services', ['edit' => true])
                                </div>

                            {!! Form::close() !!}
                        </div>
                        <!--main content end-->
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

@section('footer_scripts')
    @include('admin.clinics.scripts')
@stop
