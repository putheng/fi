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
                        <a href="{{ route('admin.question.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Create</a>
                    </div>
                </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">
                            <div class="col-md-12">
                                <!--main content-->
                                
<form action="{{ route('admin.question.create') }}" method="post" accept-charset="UTF-8" class="form-horizontal">
    {{ csrf_field() }}
    <div class="form-group {{ $errors->has('titleKh') ? ' has-error' : '' }}">
        <label for="titleKh" class="col-md-2 control-label">{{ __('questions.title_kn') }}</label>
        <div class="col-md-4">
            <input class="form-control font-sr" value="{{ old('titleKh') }}" name="titleKh" type="text" id="titleKh">
            @if($errors->has('titleKh'))
                <span class="help-block font-sr">{{ $errors->first('titleKh') }}</span>
            @endif
        </div>

        <div class="col-md-2">
            <input class="form-control" value="{{ old('subtitle') }}" name="subtitle" type="text" id="subtitle" placeholder="Sub title">
            @if($errors->has('subtitle'))
                <span class="help-block">{{ $errors->first('subtitle') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('titleEn') ? ' has-error' : '' }}">
        <label for="titleEn" class="col-md-2 control-label">{{ __('questions.title_en') }}</label>
        <div class="col-md-4">
            <input class="form-control" value="{{ old('titleEn') }}" name="titleEn" type="text" id="titleEn">
            @if($errors->has('titleEn'))
                <span class="help-block">{{ $errors->first('titleEn') }}</span>
            @endif
        </div>

        <div class="col-md-2">
            <input class="form-control" value="{{ old('subtitleEn') }}" name="subtitleEn" type="text" id="subtitleEn" placeholder="Sub title">
            @if($errors->has('subtitleEn'))
                <span class="help-block">{{ $errors->first('subtitleEn') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('header') ? ' has-error' : '' }}">
        <label for="header" class="col-md-2 control-label">
            {{ __('questions.header_kh') }}
        </label>
        <div class="col-md-4">
            <input class="form-control" value="{{ old('header') }}" name="header" type="text" id="header">
            @if($errors->has('header'))
                <span class="help-block">{{ $errors->first('header') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('header_en') ? ' has-error' : '' }}">
        <label for="header_en" class="col-md-2 control-label">
            {{ __('questions.header') }}
        </label>
        <div class="col-md-4">
            <input class="form-control" value="{{ old('header_en') }}" name="header_en" type="text" id="header_en">
            @if($errors->has('header_en'))
                <span class="help-block">{{ $errors->first('header_en') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="titleEn" class="col-md-2 control-label">
            {{ __('questions.can_be') }}
        </label>
        <div class="col-md-4">
            <select class="form-control" name="type">
                <option value="1">{{ __('questions.one_answer') }}</option>
                <option value="2">{{ __('questions.multiple_answer') }}</option>
            </select>
        </div>
    </div>

    <div id="image-upload">

    </div>
    <div class="form-group">
        <label for="titleEn" class="col-md-2 control-label">
            {{ __('questions.photo') }}
        </label>
        <div class="col-md-10">
            <input type="file" id="file" name="file" accept="image/*" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-offset-2 col-md-10">
            <input class="btn btn-success" style="width: 160px;" type="submit" value="Save">
            <a href="{{ route('admin.question.index') }}" class="btn btn-default" style="width: 140px; margin-left: 10px">{{ __('page.close') }}</a>
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

           <script type="text/javascript">
                $(document).ready(function (e) {
                    $('#file').on('change', function () {
                        var file_data = $('#file').prop('files')[0];
                        var _token = $("input[name='_token']").val();

                        var form_data = new FormData();
                        form_data.append('file', file_data);
                        form_data.append('_token', _token);

                        $.ajax({
                            url: '{{ route('admin.question.upload') }}', // point to server-side controller method
                            dataType: 'text', // what to expect back from the server
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            type: 'post',
                            success: function (response) {
                                var data = JSON.parse(response);

                                $('#image-upload').html(`
<div class="form-group">
    <div class="col-md-4 col-md-offset-2">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail">
                <img class="clinic-img" src="${data.path}" alt="Logo URL">
                <input type="hidden" name="image" value="${data.id}">
            </div>
        </div>
    </div>
</div>
                                `);
                            },
                            error: function (response) {
                                $('#msg').html(response); // display error response from the server
                            }
                        });
                    });
                });
            </script>
@stop
