<div class="vertical-menu">
    <div data-simplebar="init" class="h-100">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: -17px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 0px;">

                            <!--- Sidemenu -->
                            <div id="sidebar-menu" class="mm-active">
                                <!-- Left Menu Start -->
                                <ul class="metismenu list-unstyled mm-show" id="side-menu">
                                    <li class="menu-title" key="t-menu">Menu</li>
                                    <!--<li>-->
                                    <!--    <a href="route('hours.hoursreport')">-->
                                    <!--        <i class="bx bx-timer"></i>-->
                                    <!--        <span key="t-ecommerce">Hours</span>-->
                                    <!--    </a>-->
                                    <!--</li>-->
<!--                                <li>
                                        <a href="" class="">
                                            <i class="bx bx-stats"></i>
                                            <span key="t-ecommerce">Dashboard</span>
                                        </a>
                                    </li>-->
                                    @if($managerDefaultAccess || in_array('hours', $managerModules))
                                        @if($worker_for_hours_access)
                                            <li>
                                                <a href="{{ route('hours.index')}}" class="">
                                                    <i class="bx bx-alarm"></i>
                                                    <span key="t-ecommerce">Hours report</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                    @if($managerDefaultAccess || in_array('tools', $managerModules))
                                        @if($worker_for_tools_access)
                                            <li>
                                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                    <i class="bx bx-wrench"></i>
                                                    <span key="t-ecommerce">Tools</span>
                                                </a>
                                                <ul class="sub-menu" aria-expanded="false">
                                                    <li><a href="{{ route('tools.index') }}">List of tools</a></li>
                                                    <li><a href="{{ route('tools.create') }}">Add tool</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                    @endif
                                    @if($managerDefaultAccess || in_array('workers', $managerModules))
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                <i class="bx bx-group"></i>
                                                <span key="t-ecommerce">Workers</span>
                                            </a>
                                            <ul class="sub-menu" aria-expanded="false">
                                                <li><a href="{{ route('workers.index') }}">List of workers</a></li>
                                                <li><a href="{{ route('workers.create') }}">Add worker</a></li>
                                                <li><a href="{{ route('message.create') }}">Send sms to worker</a></li>
                                                @if($worker_for_hours_access)
                                                    <li><a href="{{ route('sickness.holiday') }}">Holiday/Sickness</a></li>
                                                @endif
                                            </ul>
                                        </li>
                                    @endif
                                    @if($managerDefaultAccess || in_array('projects', $managerModules))
                                        @if($worker_for_hours_access)
                                            <li>
                                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                    <i class="bx bx-briefcase-alt-2"></i>
                                                    <span key="t-ecommerce">Projects</span>
                                                </a>
                                                <ul class="sub-menu" aria-expanded="false">
                                                    <li><a href="{{ route('projects.index') }}">List of projects</a></li>
                                                    <li><a href="{{ route('projects.create') }}">Add project</a></li>
                                                </ul>
                                            </li>
                                        @endif
                                    @endif
                                    @if($managerDefaultAccess || in_array('clients', $managerModules))
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                <i class="bx bx-group"></i>
                                                <span key="t-ecommerce">Clients</span>
                                            </a>
                                            <ul class="sub-menu" aria-expanded="false">
                                                <li><a href="{{ route('clients.index') }}">List of clients</a></li>
                                                <li><a href="{{ route('clients.create') }}">Add client</a></li>
                                            </ul>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                                            <i class="bx bx bx-cog"></i>
                                            <span key="t-ecommerce">Settings</span>
                                        </a>
                                        <ul class="sub-menu" aria-expanded="false">
                                            <!-- <li><a href="{{ route('inventorization.edit') }}">Inventorization settings</a></li> -->
                                            <!-- <li><a href="{{ route('tool-categories.index') }}">Tool categories</a></li>
                                            <li><a href="{{ route('storages.index') }}">Storages</a></li>
                                            <li><a href="{{ route('setting.index') }}">Company settings</a></li> -->
                                            <li><a id="tool-settings" href="{{ route('settings.index') }}">Tools settings</a></li>
                                            <li><a href="{{ route('setting.index') }}">Company settings</a></li>
                                            <li><a href="{{ route('settings.hours') }}">Hours settings</a></li>
                                            @if(!$disable_subscription)
                                                <li>
                                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                                        <span key="t-ecommerce">Subscription</span>
                                                    </a>
                                                    <ul class="sub-menu" aria-expanded="false">
                                                        <li><a href="{{ route('subscription.show') }}">Plans</a></li>
                                                        <li><a href="{{ route('subscription.invoice') }}">Invoices</a></li>
                                                    </ul>
                                                </li>
                                            @endif
                                        </ul>
                                    </li>

                                </ul>
                            </div>
                            <!-- Sidebar -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 1306px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
            <div class="simplebar-scrollbar"
                 style="height: 345px; transform: translate3d(0px, 327px, 0px); display: block;"></div>
            </div>
        </div>
    </div>

{{--<nav class="navbar navbar-default">--}}
{{--    <div class="container-fluid">--}}
{{--        <div class="navbar-header">--}}
{{--            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">--}}
{{--                <span class="sr-only">Toggle navigation</span>--}}
{{--                <span class="icon-bar"></span>--}}
{{--                <span class="icon-bar"></span>--}}
{{--                <span class="icon-bar"></span>--}}
{{--            </button>--}}
{{--            <a class="navbar-brand" href="/">Owner</a>--}}
{{--        </div>--}}

{{--        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">--}}
{{--            <ul class="nav navbar-nav">--}}
{{--                <li @if (Route::is('workers.index')) class="active" @endif><a href="{{ route('workers.index') }}">Workers</a></li>--}}
{{--                <li @if (Route::is('tools.index')) class="active" @endif><a href="{{ route('tools.index') }}">Tools</a></li>--}}
{{--                <li @if (Route::is('tool-categories.index')) class="active" @endif><a href="{{ route('tool-categories.index') }}">Tool categories</a></li>--}}
{{--                <li @if (Route::is('storages.index')) class="active" @endif><a href="{{ route('storages.index') }}">Storages</a></li>--}}
{{--                <li @if (Route::is('inventorization.edit')) class="active" @endif><a href="{{ route('inventorization.edit') }}">Inventorization settings</a></li>--}}
{{--            </ul>--}}
{{--            <ul class="nav navbar-nav navbar-right">--}}
{{--                <li><a href="{{ route('scan', ['redirect' => route('tools.create')]) }}">Scan</a></li>--}}
{{--                <li class="dropdown">--}}
{{--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ user()->email }} <span class="caret"></span></a>--}}
{{--                    <ul class="dropdown-menu">--}}
{{--                        @if (Auth::check())--}}
{{--                            <li><a href="{{ route('logout') }}">Logout</a></li>--}}
{{--                        @else--}}
{{--                            <li><a href="{{ route('login') }}">Login</a></li>--}}
{{--                            <li><a href="{{ route('register') }}">Register</a></li>--}}
{{--                        @endif--}}
{{--                    </ul>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </div><!-- /.navbar-collapse -->--}}
{{--    </div><!-- /.container-fluid -->--}}
{{--</nav>--}}
