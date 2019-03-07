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
        <h1>{{ __('questions.answers') }}</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    {{ __('app/general.home') }}
                </a>
            </li>
            <li><a href="#">{{ __('users/title.user_list_page') }}</a></li>
            <li class="active">{{ __('questions.answers') }}</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left font-sr">
                        <i class="livicon" data-name="list" data-size="16"
                           data-loop="true" data-c="#fff" data-hc="white"></i>
                            {{ $question->titleKh }}
                    </h4>

                </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">
                            <div class="col-md-12">
                                <!--main content-->

<form action="{{ route('admin.question.answer', $question) }}" method="post" accept-charset="UTF-8" class="form-horizontal">

    {{ csrf_field() }}
    @foreach($question->answers as $key => $item)
        <div class="form-group {{ $errors->has('answer.'. $key) ? ' has-error' : '' }}">
            <label for="answer{{ $key }}" class="col-md-2 control-label">
                {{ __('questions.answer') }}
            </label>

            <div class="col-md-4">
                <input class="form-control font-sr" 
                    value="{{ $item->title }}"
                    name="answer[]"
                    type="text"
                    id="answer{{ $key }}"
                    placeholder="Answer Kh" 
                >
                @if($errors->has('answer.'. $key))
                    <span class="help-block">{{ $errors->first('answer.'. $key) }}</span>
                @endif
                <br>
                <input class="form-control font-sr" 
                        value="{{ $item->titleEn }}"
                        name="answerEn[]"
                        type="text"
                        id="answerEn{{ $key }}"
                        placeholder="Answer En" 
                    >
                    @if($errors->has('answerEn.'. $key))
                        <span class="text-danger">{{ $errors->first('answerEn.'. $key) }}</span>
                    @endif
                </div>

            <div class="col-md-1">
                <input type="text" value="{{ $item->point }}" class="form-control" name="point[]" placeholder="Point">
            </div>

            <div class="col-md-2">
                <a href="{{ route('admin.question.destroy', $item) }}" class="btn btn-link">Delete</a>
            </div>
        </div>
        <hr>
    @endforeach

    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
        <label for="title" class="col-md-2 control-label">{{ __('questions.answer') }}</label>
        <div class="col-md-4">
            <input class="form-control font-sr" 
                name="title" type="text" id="title" 
                placeholder="Answer Kh" >
            @if($errors->has('title'))
                <span class="help-block">{{ $errors->first('title') }}</span>
            @endif

            <br>
            <input class="form-control font-sr" 
                    value="{{ old('titleEn') }}"
                    name="titleEn"
                    type="text"
                    id="titleEn"
                    placeholder="Answer En" 
                >
                @if($errors->has('titleEn'))
                    <span class="text-danger">{{ $errors->first('titleEn') }}</span>
                @endif
        </div>

        <div class="col-md-1">
            <input type="text" value="0" class="form-control" name="points" placeholder="Point">
        </div>
    </div>
<hr>

    <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
            <input class="btn btn-success" style="width: 160px;" type="submit" value="{{ __('questions.save') }}">
            <a href="{{ route('admin.question.index') }}" class="btn btn-default" style="width: 140px; margin-left: 10px">{{ __('questions.cancel') }}</a>
        </div>
    </div>
</form>

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
