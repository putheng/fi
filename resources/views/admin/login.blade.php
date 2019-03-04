<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
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
                <a class="hiddenanchor" id="tologin"></a>
                <a class="hiddenanchor" id="toforgot"></a>

                <div id="wrapper">
                    <div id="login" class="animate form" style="background: white">
                        <form action="{{ route('signin') }}" autocomplete="on" method="post" role="form"
                              id="login_form">
                            <center>
                                <img src="{{ asset('assets/img/logo-med.jpg') }}" width="100%"
                                     style="margin-top: 10px; margin-bottom: 2px">
                                <h4 style="font-size: 20px; font-weight: bold">
                                    @lang('app/general.login_head')
                                </h4>

                                <h3 style="font-size: 26px">
                                    @lang('app/general.login')
                                </h3>
                            </center>
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                            <div class="form-group {{ $errors->first('username', 'has-error') }}">
                                <label style="margin-bottom:0px;" for="username" class="uname control-label"> <i
                                            class="livicon" data-name="user" data-size="16" data-loop="true"
                                            data-c="#3c8dbc" data-hc="#3c8dbc"></i>
                                    @lang('app/general.login_username')
                                </label>
                                <input id="username" name="username" placeholder="@lang('app/general.login_username')"
                                       value="{!! old('username') !!}"/>

                                <div class="col-sm-12">
                                    {!! $errors->first('username', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                <label style="margin-bottom:0px;" for="password" class="youpasswd"> <i
                                            class="livicon" data-name="key" data-size="16" data-loop="true"
                                            data-c="#3c8dbc" data-hc="#3c8dbc"></i>
                                    @lang('app/general.login_password')
                                </label>
                                <input id="password" name="password" type="password"
                                       placeholder="@lang('app/general.login_password')"/>

                                <div class="col-sm-12">
                                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="remember-me" id="remember-me" value="true"
                                           class="square-blue"/>
                                    @lang('app/general.remember_me')
                                </label>
                            </div>
                            <p class="login button">
                                <input type="submit" value="@lang('app/general.login')" class="btn btn-success"
                                       style="padding: 9px 20px;"/>
                            </p>

                            <p class="change_link">
                                <a href="#toforgot">
                                    <button type="button"
                                            class="btn btn-responsive botton-alignment btn-warning btn-sm">
                                        @lang('app/general.forgot_pwd')
                                    </button>
                                </a>
                            </p>
                        </form>
                    </div>
                    <div id="forgot" class="animate form" style="background: white">
                        <form action="{{ url('admin/forgot-password') }}" autocomplete="on" method="post" role="form"
                              id="reset_pw">
                            <h3>
                                <img src="{{ asset('assets/img/logo-med.jpg') }}"><br>@lang('app/general.forgot_pwd')
                            </h3>

                            <p>
                                @lang('app/general.forgot_pwd_title')
                            </p>

                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                            <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                <label style="margin-bottom:0px;" for="emailsignup1" class="youmai">
                                    <i class="livicon" data-name="mail" data-size="16" data-loop="true" data-c="#3c8dbc"
                                       data-hc="#3c8dbc"></i>
                                    @lang('app/general.forgot_pwd_email')
                                </label>
                                <input id="email" name="email" required type="email" placeholder="your@mail.com"
                                       value="{!! old('email') !!}"/>

                                <div class="col-sm-12">
                                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <p class="login button">
                                <input type="submit" value="@lang('app/general.forgot_pwd_reset')"
                                       class="btn btn-success"/>
                            </p>

                            <p class="change_link">
                                <a href="#tologin" class="to_register">
                                    <button type="button"
                                            class="btn btn-responsive botton-alignment btn-warning btn-sm">@lang('button.back')
                                    </button>
                                </a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{ asset('assets/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}"
        type="text/javascript"></script>
<!--livicons-->
<script src="{{ asset('assets/js/raphael-min.js') }}"></script>
<script src="{{ asset('assets/js/livicons-1.4.min.js') }}"></script>
<script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/page/login.js') }}" type="text/javascript"></script>
<!-- end of global js -->
</body>
</html>