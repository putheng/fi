@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    User Profile
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/vendors/iCheck/css/all.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/user_profile.css') }}" rel="stylesheet" type="text/css"/>

@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>
            Change Password
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12 pd-top">
                        <form action="#" class="form-horizontal">
                            <div class="form-body">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">
                                        Password
                                        <span class='require'>*</span>
                                    </label>

                                    <div class="col-md-9">
                                        <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="livicon" data-name="key"
                                                                           data-size="16" data-loop="true" data-c="#000"
                                                                           data-hc="#000"></i>
                                                                    </span>
                                            <input type="password" placeholder="Password"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">
                                        Confirm Password
                                        <span class='require'>*</span>
                                    </label>

                                    <div class="col-md-9">
                                        <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="livicon" data-name="key"
                                                                           data-size="16" data-loop="true" data-c="#000"
                                                                           data-hc="#000"></i>
                                                                    </span>
                                            <input type="password" placeholder="Confirm Password"
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-actions">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn btn-primary">@lang('button.submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/jquery-mockjax/js/jquery.mockjax.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/x-editable/js/bootstrap-editable.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-magnify/bootstrap-magnify.js') }}"></script>
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
    <script src="{{ asset('assets/js/holder.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/page/user_profile.js') }}" type="text/javascript"></script>

@stop
