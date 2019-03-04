@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('users/title.edit')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
        <!--page level css -->
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
<!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>{{ $isMyUser ? Lang::get('users/title.user_profile') : Lang::get('users/title.edit') }}</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('app/general.home')
                </a>
            </li>
            @if($isMyUser)
                <li class="active">@lang('users/title.user_profile')</li>
            @else
                <li><a href="#">@lang('users/title.user_list_page')</a></li>
                <li class="active">@lang('users/title.edit')</li>
            @endif
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="livicon" data-name="users" data-size="16" data-c="#fff"
                                                   data-hc="#fff" data-loop="true"></i>
                            {{ $isMyUser ? Lang::get('users/title.user_profile') . ": " : Lang::get('users/title.editing') }} {!! $user->first_name!!} {!! $user->last_name!!}
                        </h3>
                    </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::model($user, ['route' => ['admin.users.update', $user], 'method' => 'post', 'class' => 'form-horizontal']) !!}

                                        <!-- CSRF Token -->
                                <input type="hidden" name="_method" value="PATCH"/>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                                @include('admin.users.fields', ['edit' => true, 'isMyUser' => $isMyUser])

                                {!! Form::close() !!}
                            </div>
                        </div>
                        <!--main content end-->
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/js/page/users.js') }}"></script>
@stop
