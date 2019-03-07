<ul id="menu" class="page-sidebar-menu">

    <li {!! (0 === strpos(Request::path(), 'admin/reservation') || Request::is('admin') ? 'class="active"' : '') !!}>
        <a href="{{ URL::to('admin/reservations') }}" class="menu-link">
            <i class="livicon" data-name="calendar" data-size="20" data-c="#ecb00f" data-hc="#ecb00f"
               data-loop="true"></i>
            <span class="title">@lang('app/general.menu_reservations')</span>
        </a>
    </li>

    <li {!! (0 === strpos(Request::path(), 'admin/export') ? 'class="active"' : '') !!}>
        <a href="{{ URL::to('admin/export/index') }}" class="menu-link">
            <i class="livicon" data-name="notebook" data-size="20" data-c="#12e466" data-hc="#12e466"
               data-loop="true"></i>
            <span class="title">@lang('app/general.menu_export')</span>
        </a>
    </li>


    @if(ORAHelper::isAdminAny())

        <li {!! (0 === strpos(Request::path(), 'admin/clinics') ? 'class="active"' : '') !!}>
            <a href="{{ URL::to('admin/clinics') }}" class="menu-link">
                <i class="livicon" data-name="home" data-size="20" data-c="#f0fb98" data-hc="#f0fb98"
                   data-loop="true"></i>
                <span class="title">@lang('app/general.menu_clinics')</span>
            </a>
        </li>

        <li {!! (0 === strpos(Request::path(), 'admin/users') ||
                 0 === strpos(Request::path(), 'admin/tokens') ||
                 0 === strpos(Request::path(), 'admin/geopoints') ||
                  0 === strpos(Request::path(), 'admin/sites') ||
                  0 === strpos(Request::path(), 'admin/chains') ||
                  0 === strpos(Request::path(), 'admin/trafficlogs')  ? 'class="active"' : '') !!}>
            <a href="#" class="menu-link">
                <i class="livicon" data-name="shield" data-size="20" data-c="#ff725b" data-hc="#ff725b"
                   data-loop="true"></i>
                <span class="title">@lang('app/general.menu_administration')</span>
                <span class="fa arrow"></span>
            </a>

            <ul class="sub-menu">
                @if(ORAHelper::isSuperAdmin())

                    <li {!! (0 === strpos(Request::path(), 'admin/users') ? 'class="active" id="active"' : '') !!}>
                        <a href="{{ URL::to('admin/users') }}" class="menu-link">
                            <i class="fa fa-angle-double-right"></i>
                            <span class="title">@lang('app/general.menu_users')</span>
                        </a>
                    </li>

                @endif

                <li {!! (0 === strpos(Request::path(), 'admin/tokens') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/tokens') }}" class="menu-link">
                        <i class="fa fa-angle-double-right"></i>
                        <span class="title">@lang('app/general.menu_tokens')</span>
                    </a>
                </li>

                <li {!! (0 === strpos(Request::path(), 'admin/geopoints') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/geopoints') }}" class="menu-link">
                        <i class="fa fa-angle-double-right"></i>
                        <span class="title">@lang('app/general.menu_geopoints')</span>
                    </a>
                </li>

                <li {!! (0 === strpos(Request::path(), 'admin/sites') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/sites') }}" class="menu-link">
                        <i class="fa fa-angle-double-right"></i>
                        <span class="title">@lang('app/general.menu_sites')</span>
                    </a>
                </li>

                <li {!! (0 === strpos(Request::path(), 'admin/chains') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/chains') }}" class="menu-link">
                        <i class="fa fa-angle-double-right"></i>
                        <span class="title">@lang('app/general.menu_chains')</span>
                    </a>
                </li>

                <li {!! (0 === strpos(Request::path(), 'admin/trafficlogs') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/trafficlogs') }}" class="menu-link">
                        <i class="fa fa-angle-double-right"></i>
                        <span class="title">@lang('app/general.menu_traffic_logs')</span>
                    </a>
                </li>

                <li {!! (0 === strpos(Request::path(), 'admin/question') ? 'class="active" id="active"' : '') !!}>
                    <a href="{{ URL::to('admin/question') }}" class="menu-link">
                        <i class="fa fa-angle-double-right"></i>
                        <span class="title">@lang('questions.question_menu')</span>
                    </a>
                </li>
            </ul>
        </li>
    @else
        @if(\App\Http\Common\ORAHelper::isSingleClinicUser())
            <li {!! (0 === strpos(Request::path(), 'admin/clinics/myclinic') ? 'class="active"' : '') !!}>
                <a href="{{ URL::to('admin/clinics/myclinic') }}" class="menu-link">
                    <i class="livicon" data-name="home" data-size="20" data-c="#f0fb98" data-hc="#f0fb98"
                       data-loop="true"></i>
                    <span class="title">@lang('app/general.menu_edit_clinic')</span>
                </a>
            </li>
        @endif
        <li {!! (0 === strpos(Request::path(), 'admin/users/myuser') ? 'class="active"' : '') !!}>
            <a href="{{ URL::to('admin/users/myuser') }}" class="menu-link">
                <i class="livicon" data-name="user" data-size="20" data-c="#ff725b" data-hc="#ff725b"
                   data-loop="true"></i>
                <span class="title">@lang('app/general.menu_edit_user')</span>
            </a>
        </li>
    @endif

</ul>
