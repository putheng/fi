@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('questions.question_title')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

        <!--page level css -->
<!--
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
    -->

<link type="text/css" href="{{ asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css') }}"
      rel="stylesheet"/>
<link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/iCheck/css/all.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/iCheck/css/line/line.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/switchery/css/switchery.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/css/pages/formelements.css') }}" rel="stylesheet"/>

<!--end of page level css-->
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>@lang('questions.question_title')</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    @lang('app/general.home')
                </a>
            </li>
            <li><a href="#">@lang('users/title.user_list_page')</a></li>
            <li class="active">@lang('questions.question_title')</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left">
                        <i class="livicon" data-name="list" data-size="16"
                           data-loop="true" data-c="#fff" data-hc="white"></i>
                            @lang('questions.question_title')
                    </h4>

                    <div class="pull-right">
                        <a href="{{ route('admin.question.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> {{ __('questions.create') }}</a>
                    </div>
                </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">
                            <div class="col-md-7 col-md-offset-2">
                                <!--main content-->
                                <ul class="list-group">
                                    @foreach($questions as $question)
                                        <li class="list-group-item">
                                            <span class="pull-right">
                                                <a class="badge" href="{{ route('admin.question.answer', $question) }}">
                                                    {{ __('questions.answer') }}
                                                </a>
                                                <a href="{{ route('admin.question.edit', $question) }}" class="badge">{{ __('questions.edit') }}</a>
                                            </span>
                                            <span class="font-sr">{{ $question->titleKh }}</span>
                                            <br>
                                            {{ $question->titleEn }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
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
    <script src="{{ asset('assets/js/page/users.js') }}"></script>
@stop
