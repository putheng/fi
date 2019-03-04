@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('geopoints/title.create')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    @include('admin.geopoints.styles')
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>@lang('geopoints/title.create')</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('app/general.home')
                </a>
            </li>
            <li><a href="#">@lang('geopoints/title.list_page')</a></li>
            <li class="active">@lang('geopoints/title.create')</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i>
                            @lang('geopoints/title.create')
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">
                            <div class="col-md-12">
                                <!--main content-->
                                {!! Form::open(['route' => 'admin.geopoints.store', 'class' => 'form-horizontal', 'files' => true]) !!}

                                <!-- CSRF Token -->
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                                @include('admin.geopoints.fields')

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

@section('footer_scripts')
    @include('admin.geopoints.scripts')
@stop
