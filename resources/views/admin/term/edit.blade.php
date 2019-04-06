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
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet"/>
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
                            Term & Condition
                    </h4>
                </div>
                    <div class="panel-body">
                        <!--main content-->
                        <div class="row">
                            <div class="col-md-12">
                                <!--main content-->
                                
<form action="{{ route('admin.term.edit') }}" method="post" class="form-horizontal">
    {{ csrf_field() }}
    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
        <label for="title" class="col-md-2 control-label">Title KH</label>
        <div class="col-md-4">
            <input class="form-control font-sr" value="{{ $term->title }}" name="title" type="text" id="title">
            @if($errors->has('title'))
                <span class="help-block">{{ $errors->first('title') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('title_en') ? ' has-error' : '' }}">
        <label for="title_en" class="col-md-2 control-label">Title EN</label>
        <div class="col-md-4">
            <input class="form-control font-sr" value="{{ $term->title_en }}" name="title_en" type="text" id="title_en">
            @if($errors->has('title_en'))
                <span class="help-block">{{ $errors->first('title_en') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('subtitle') ? ' has-error' : '' }}">
        <label for="subtitle" class="col-md-2 control-label">Subtitle KH</label>
        <div class="col-md-4">
            <textarea class="form-control" name="subtitle" id="subtitle">{{ $term->subtitle }}</textarea>
            @if($errors->has('subtitle'))
                <span class="help-block">{{ $errors->first('subtitle') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('subtitle_en') ? ' has-error' : '' }}">
        <label for="subtitle_en" class="col-md-2 control-label">Subtitle EN</label>
        <div class="col-md-4">
            <textarea class="form-control" name="subtitle_en" id="subtitle_en">{{ $term->subtitle_en }}</textarea>
            @if($errors->has('subtitle_en'))
                <span class="help-block">{{ $errors->first('subtitle_en') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('bradcume') ? ' has-error' : '' }}">
        <label for="bradcume" class="col-md-2 control-label">Breadcrumb KH</label>
        <div class="col-md-4">
            <input class="form-control font-sr" value="{{ $term->bradcume }}" name="bradcume" type="text" id="bradcume">
            @if($errors->has('bradcume'))
                <span class="help-block">{{ $errors->first('bradcume') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('bradcume_en') ? ' has-error' : '' }}">
        <label for="bradcume_en" class="col-md-2 control-label">Breadcrumb EN</label>
        <div class="col-md-4">
            <input class="form-control font-sr" value="{{ $term->bradcume_en }}" name="bradcume_en" type="text" id="bradcume_en">
            @if($errors->has('bradcume_en'))
                <span class="help-block">{{ $errors->first('bradcume_en') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('heading') ? ' has-error' : '' }}">
        <label for="heading" class="col-md-2 control-label">Heading KH</label>
        <div class="col-md-4">
            <input class="form-control font-sr" value="{{ $term->heading }}" name="heading" type="text" id="heading">
            @if($errors->has('heading'))
                <span class="help-block">{{ $errors->first('heading') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('heading_en') ? ' has-error' : '' }}">
        <label for="heading_en" class="col-md-2 control-label">Heading EN</label>
        <div class="col-md-4">
            <input class="form-control font-sr" value="{{ $term->heading_en }}" name="heading_en" type="text" id="heading_en">
            @if($errors->has('heading_en'))
                <span class="help-block">{{ $errors->first('heading_en') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('term') ? ' has-error' : '' }}">
        <label for="term" class="col-md-2 control-label">Term KH</label>
        <div class="col-md-4">
            <textarea class="form-control" name="term" id="term">{{ $term->term }}</textarea>
            @if($errors->has('term'))
                <span class="help-block">{{ $errors->first('term') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('term_en') ? ' has-error' : '' }}">
        <label for="term_en" class="col-md-2 control-label">Term EN</label>
        <div class="col-md-4">
            <textarea class="form-control" name="term_en" id="term_en">{{ $term->term_en }}</textarea>
            @if($errors->has('term_en'))
                <span class="help-block">{{ $errors->first('term_en') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('note') ? ' has-error' : '' }}">
        <label for="note" class="col-md-2 control-label">Note KH</label>
        <div class="col-md-4">
            <textarea class="form-control" name="note" id="note">{{ $term->note }}</textarea>
            @if($errors->has('note'))
                <span class="help-block">{{ $errors->first('note') }}</span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('note_en') ? ' has-error' : '' }}">
        <label for="note_en" class="col-md-2 control-label">Note EN</label>
        <div class="col-md-4">
            <textarea class="form-control" name="note_en" id="note_en">{{ $term->note_en }}</textarea>
            @if($errors->has('note_en'))
                <span class="help-block">{{ $errors->first('note_en') }}</span>
            @endif
        </div>
    </div>



    <div id="image-upload">
        @if($term->image)
            <div class="form-group ">
                <div class="col-md-4 col-md-offset-2">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail">
                            <img class="clinic-img" src="{{ $term->image->path() }}" alt="Logo URL">
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
            
            <input class="btn btn-success" style="width: 160px;" type="submit" value="{{ __('questions.save') }}">
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
                        console.log(data)
                        $('#image-upload').html(`
<div class="form-group">
    <div class="col-md-4 col-md-offset-2">
        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-new thumbnail">
                <img class="clinic-img" src="${data.path}" alt="Logo URL">
                <input type="hidden" name="image_id" value="${data.id}">
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
