<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>
        @section('title')
            | Yes4Me - Online Reservation App
        @show
    </title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- global js -->
    <script src="{{ asset('assets/js/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/utils.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/eModal.min.js') }}" type="text/javascript"></script>
    {{--scrolling tabs--}}
    <script src="{{ asset('assets/js/jquery.scrolling-tabs.min.js') }}"></script>
    {{--DatePicker--}}
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrap3-editable/js/bootstrap-editable.min.js') }}" type="text/javascript"></script>
    <!-- end of global js -->

    <!-- global css -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/site.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/toastr/css/toastr.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap3-editable/css/bootstrap-editable.css') }}"/>

    <!-- end of global css -->

    <style>

        /* Scrollings tabs */
        .scrtabs-tab-container .nav-tabs > li > a:hover {
            background-color: rgba(242, 102, 13, 0.5);
        }

        .scrtabs-tab-container .nav-tabs > li.active > a,
        .scrtabs-tab-container .nav-tabs > li.active > a:focus,
        .scrtabs-tab-container .nav-tabs > li.active > a:hover {
            background-color: #F2660D;
            color: #fff;
        }

        /* Controls bar */
        .switch-light {
            position: relative;
            top: -2px;
            display: inline-block;
            margin: 0;
            font-size: 16px;
        }

        .switch-light > span {
            display: inline-block;
            width: 160px;
            margin: 0;
        }

        .content-header .form-inline > * {
            margin-left: 10px;
        }

        /* Loading panel */
        .loading-panel {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 99999;
        }

        .loading-panel > div {
            position: absolute;
            top: 50%;
            left: calc(50% - 50px);
            margin: 0 auto;
        }

    </style>

    <!--page level css-->
@yield('header_styles')

<!--end of page level css-->

<body class="skin-fhi">

<header class="header non-printable">
    <a href="{{ route('admin.reservations') }}" style="margin: 0px; padding: 0px;" class="logo">
        @php
        $logoUrl = "";
        $user = Sentinel::getUser();
        if (\App\Http\Common\ORAHelper::isSingleClinicUser()) {
            $logoUrl = session("clinic_logo_url", "");
        }
        if($logoUrl == null || $logoUrl == '') {
            $logoUrl = asset('assets/img/logo.jpg');
        }
        @endphp

        <img src="{{ $logoUrl }}" style="margin: 0px; padding: 0px;" height="56" alt="logo">
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <div class="responsive_nav"></div>
            </a>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li><a href="{{ url('lang/en') }}" style="color: white; text-decoration: underline; font-size: 16px; margin-top: 10px;">@lang('lang.en')</a></li>
                <li><a href="{{ url('lang/hi') }}" style="color: white; text-decoration: underline; font-size: 16px; margin-top: 10px;">@lang('lang.hi')</a></li>
            </ul>
            <ul class="nav navbar-nav">
                {{--
                #ORG SELECTION
                @include('admin.layouts._organizations')
                --}}
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <div class="riot">
                            <i class="fa fa-user-circle-o" style="font-size:24px; margin-right: 5px;"></i>

                            <p class="user_name_max">{{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }}</p>
                            <span>
                                        <i class="caret"></i>
                                </span>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Menu Footer-->
                        <li class="user-footer pull-left">
                            <a href="{{ URL::to('admin/logout') }}">
                                <i class="livicon" data-name="sign-out" data-s="18"></i>
                                @lang('app/general.logout')
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas non-printable">
        <section class="sidebar">
            <div class="page-sidebar sidebar-nav">
                <div class="clearfix"></div>
                <!-- BEGIN SIDEBAR MENU -->
            @include('admin.layouts._left_menu')
            <!-- END SIDEBAR MENU -->
            </div>
        </section>
    </aside>

    <aside class="right-side">
        <!-- Content -->
        @yield('content')
    </aside>
    <!-- right-side -->
</div>

@if ($message = Session::get('success'))
    <script>
        $(document).ready(function () {
            toastr.options.timeOut = 1000;
            toastr.options.showDuration = 200;
            toastr.options.hideDuration = 200;
            toastr.success('{{ $message }}');
        });
    </script>
@elseif ($message = Session::get('error'))
    <script>
        $(document).ready(function () {
            toastr.options.timeOut = 3000;
            toastr.options.showDuration = 200;
            toastr.options.hideDuration = 200;
            toastr.error('{{ $message }}');
        });
    </script>
@endif

<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top non-printable" role="button"
   title="Return to top" style="display: none;"
   data-toggle="tooltip" data-placement="left">
    <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
</a>

<!-- Loading panel -->
<div class="loading-panel" style="display: none;">
    <div>
        <img src="{{ asset('assets/img/loading.gif') }}" width="70" height="70"/>
        <h3>@lang('app/general.loading')</h3>
    </div>
</div>

<script src="{{ asset('assets/vendors/toastr/js/toastr.min.js') }}"></script>

<!-- begin page level js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>
</html>
