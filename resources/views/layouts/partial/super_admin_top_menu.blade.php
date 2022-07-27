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
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    
                    @if (Auth::guard('super_admin')->check())
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                    @endif

                </div>
            </div>

        </div>
    </div>
</header>
