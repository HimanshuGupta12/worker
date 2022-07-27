<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="/" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ env('PUBLIC_PATH') }}/img/logo.png" alt="" height="22">
                                </span>
                    <span class="logo-lg">
                                    <img src="{{ env('PUBLIC_PATH') }}/img/logo.png" alt="" height="40">
                                </span>
                </a>

                <a href="/" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ env('PUBLIC_PATH') }}{{ env('PUBLIC_PATH') }}/img/logo.png" alt="" height="22">
                                </span>
                    <span class="logo-lg">
                                    <img src="{{ env('PUBLIC_PATH') }}/img/logo.png" alt="" height="40">
                                </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <div style="margin-top:25px; ">
                @if(request()->routeIs('hours.index'))
                <h5>Hours report</h5>
                    @elseif(request()->routeIs('tools.index'))
                    <h5>Tool report</h5>
                        @elseif(request()->routeIs('tools.create'))
                        <h5>Add tool</h5>
                            @elseif(request()->routeIs('workers.index'))
                            <h5 style="display: inline;">Worker report</h5>
                                @elseif(request()->routeIs('workers.create'))
                                <h5>Add worker</h5>
                                @elseif(request()->routeIs('message.create'))
                                <h5>Send sms to worker</h5>
                                @elseif(request()->routeIs('sickness.holiday'))
                                <h5>Holiday/Sickness</h5>


                                    @elseif(request()->routeIs('projects.index'))
                                    <h5>Project report</h5>
                                        @elseif(request()->routeIs('projects.create'))
                                        <h5>Add project</h5>
                                        @elseif(request()->routeIs('clients.index'))
                                        <h5>Client report</h5>
                                                @elseif(request()->routeIs('clients.create'))
                                                <h5>Add client</h5>
                                                 @elseif(request()->routeIs('settings.index'))
                                                <h5>Tools settings</h5>
                                                    @elseif(request()->routeIs('setting.index'))
                                                    <h5>Company settings</h5>
                                                        @elseif(request()->routeIs('subscription.show'))
                                                        <h5>Subscription</h5>
                                                            @elseif(request()->routeIs('subscription.invoice'))
                                                            <h5>Invoices</h5>



                @endif

                

            </div>
        </div>

        <div class="d-flex">

            @if(!$disable_subscription && user()->onTrial() && !user()->checkActiveSubscription())
                @php
                    $trial_ends_at = user()->trial_ends_at;
                    $now = now();
                    if($now < $trial_ends_at) {
                        $days = $trial_ends_at->diff($now)->days;
                        $message = ($days == 0) ? "Your trial ends today." : "You have {$days}d. left for free trial.";
                    } else {
                        $message = "Your trial has ended.";
                    }
                @endphp
                <div class="d-inline-block" style="margin-top:25px; padding-right: 20px;">
                    <p>
                        {{ $message }}&nbsp;
                        <a href="{{ route('subscription.show') }}">
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light">Subscribe now</button>
                        </a>
                    </p>
                </div>
            @endif
        
            @if (Auth::check())
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry">
                        <a class="btn btn-info btn-sm">
                            <i class="mdi mdi-plus-thick d-none d-xl-inline-block"></i>
                            Add
                        </a>
                    </span>

                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item"  href="javascript:void(0);"  rel="noopener noreferrer"  id="addQuickProjectBtn" data-bs-toggle="modal" data-bs-target="#addQuickWorker"> <span key="t-profile">Add worker</span></a>
                        @if($managerDefaultAccess || in_array('projects', $managerModules))
                            @if($worker_for_hours_access)
                                <a class="dropdown-item" href="javascript:void(0);"  rel="noopener noreferrer"  id="addQuickWorkerBtn" data-bs-toggle="modal" data-bs-target="#addQuickProject"> <span key="t-my-wallet">Add project</span></a>
                            @endif
                        @endif
                    </div>
                </div>
            @endif


            <div  class="float-end d-block d-sm-none " style="padding-right: 0px;">
                @if (Auth::check())
                    <div class="dropdown d-inline-block" >
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="" key="t-henry" >
                                <a class="btn btn-info btn-sm">
                                    <i class="mdi mdi-plus-thick d-none d-xl-inline-block"></i>
                                    Add
                                </a>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item"  href="javascript:void(0);"  rel="noopener noreferrer"  id="addQuickProjectBtn" data-bs-toggle="modal" data-bs-target="#addQuickWorker"> <span key="t-profile">Add worker</span></a>
                            <a class="dropdown-item" href="javascript:void(0);"  rel="noopener noreferrer"  id="addQuickWorkerBtn" data-bs-toggle="modal" data-bs-target="#addQuickProject"> <span key="t-my-wallet">Add project</span></a>

                        </div>
                    </div>
                @endif
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ user()->company->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    @if (Auth::guest())
                        <a class="dropdown-item" href="{{ route('login') }}"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Login</span></a>
                        <a class="dropdown-item" href="{{ route('register') }}"><i class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-my-wallet">Register</span></a>
                    @endif
{{--                    <div class="dropdown-divider"></div>--}}
                    @if (Auth::check())
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                    @endif

                </div>
            </div>

        </div>
    </div>
</header>
