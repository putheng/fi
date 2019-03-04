<!DOCTYPE html>
<html>

<head>
    <title>@lang('auth/form.forgot_password_page_title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- global level css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <!-- end of global level css -->
    <!-- page level css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/login.css') }}"/>
    <link href="{{ asset('assets/vendors/iCheck/css/square/blue.css') }}" rel="stylesheet"/>
    <!-- end of page level css -->

</head>

<body>

<div class="container">
    <div class="row vertical-offset-100">
        <div class="col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div id="container_demo">
                <div id="wrapper">
                    <div id="login" class="animate form">
                        <form method="post" action="">
                            <h3 class="black_bg">
                                <img src="{{ asset('assets/img/logo.jpg') }}">
                                <br>Log In</h3>

                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                            <!-- New Password -->
                            <div class="form-group{{ $errors->first('password', ' has-error') }}">
                                <label style="margin-bottom:0px;" for="password" class="youpasswd">
                                    @lang('auth/form.newpassword')
                                </label>
                                <input type="password" name="password" id="password" value="{{ old('password') }}"
                                       required/>
                                {{ $errors->first('password', '<span class="help-block">:message</span>') }}
                            </div>

                            <!-- Password Confirm -->
                            <div class="form-group{{ $errors->first('password_confirm', ' has-error') }}">
                                <label style="margin-bottom:0px;" for="password_confirm" class="youpasswd">
                                    @lang('auth/form.confirmpassword')
                                </label>
                                <input type="password" name="password_confirm" id="password_confirm"
                                       value="{{ old('password_confirm') }}" required/>
                                {{ $errors->first('password_confirm', '<span class="help-block">:message</span>') }}
                            </div>

                            <!-- Form actions -->
                            <div class="form-group">
                                <a class="btn" href="{{ route('admin.dashboard') }}">@lang('button.cancel')</a>
                                <button type="submit" class="btn btn-info">@lang('button.submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--<div class="container">
    <div class="row vertical-offset-100">
        <div class=" col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3  col-md-5 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">@lang('auth/form.forgot_password_title')</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="" class="form-horizontal">
                        <!-- CSRF Token -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <!-- New Password -->
                        <div class="form-group{{ $errors->first('password', ' has-error') }} col-sm-12">
                            <label for="password">@lang('auth/form.newpassword')</label>
                            <input type="password" name="password" id="password" value="{{ old('password') }}"
                                   class="form-control"/>
                            {{ $errors->first('password', '<span class="help-block">:message</span>') }}
                        </div>

                        <!-- Password Confirm -->
                        <div class="form-group{{ $errors->first('password_confirm', ' has-error') }} col-sm-12">
                            <label class="control-label" for="password_confirm">@lang('auth/form.confirmpassword')</label>
                            <input type="password" name="password_confirm" id="password_confirm"
                                   value="{{ old('password_confirm') }}" class="form-control"/>
                            {{ $errors->first('password_confirm', '<span class="help-block">:message</span>') }}
                        </div>

                        <!-- Form actions -->
                        <div class="form-group">
                            <div class="col-sm-12">
                                <a class="btn" href="{{ route('admin.dashboard') }}">@lang('button.cancel')</a>
                                <button type="submit" class="btn btn-info">@lang('button.submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
--}}

<!-- global js -->
<script src="{{ asset('assets/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- end of global js -->

</body>
</html>