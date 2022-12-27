<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title>QuicHealth - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Quichealth, Bringing HealthCare to your finger tips, at the comfort of your couch ">
    <meta name="author" content="Quichealth Team">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo/quicHealth_logo1.jpg') }}">

    <link href="{{ asset('dashboard/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('dashboard/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('dashboard/js/layout.js') }}"></script>

    <script src="{{ asset('dashboard/libs/dropzone/dropzone.css') }}"></script>

    <link href="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('dashboard/libs/filepond/filepond.min.css') }}" type="text/css" />

    <link rel="stylesheet"
        href="{{ asset('dashboard/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css') }}">

    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('dashboard/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('dashboard/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">

                        <div class="navbar-brand-box horizontal-logo">
                            <a href="{{ route('admin.home') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('logo/quicHealth_logo2.jpg') }}" alt="" height="50px">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('logo/quicHealth_logo2.jpg') }}" alt="" height="50px">
                                </span>
                            </a>

                            <a href="{{ route('admin.home') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('logo/quicHealth_logo2.jpg') }}" alt="" height="50px">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('logo/quicHealth_logo2.jpg') }}" alt="" height="50px">
                                </span>
                            </a>
                        </div>

                        <button type="button"
                            class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn shadow-none topnav-hamburger"
                            id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                        <form class="app-search d-none d-md-block">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Search..." autocomplete="off"
                                    id="search-options" value="">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                                <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                                    id="search-close-options"></span>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex align-items-center">

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button"
                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none"
                                data-toggle="fullscreen">
                                <i class='bx bx-fullscreen fs-22'></i>
                            </button>
                        </div>

                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button"
                                class="btn btn-icon light-dark-mode btn-topbar btn-ghost-secondary rounded-circle shadow-none">
                                <i class='bx bx-moon fs-22'></i>
                            </button>
                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn  shadow-none" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user"
                                        src="{{ asset('dashboard/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span
                                            class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user()->name ?? 'No name' }}</span>
                                        {{-- <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">Founder</span> --}}
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">

                                <h6 class="dropdown-header">Welcome {{ Auth::user()->name ?? 'No Name' }}!</h6>
                                {{-- <a class="dropdown-item" href="{{ route('user.profile') }}">
                                        <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i>
                                        <span class="align-middle">Profile</span>
                                    </a> --}}
                                <a class="dropdown-item" href="">
                                    <i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i>
                                    <span class="align-middle">Settings</span>
                                </a>
                                {{-- <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                        <span class="align-middle" data-key="t-logout">Logout</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="app-menu navbar-menu">

            <div class="navbar-brand-box">

                <a href="{{ route('admin.home') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('logo/quicHealth_logo2.jpg') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('logo/quicHealth_logo2.jpg') }}" alt="" height=""
                            width="60%">
                    </span>
                </a>

                <a href="{{ route('admin.home') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('logo/quicHealth_logo2.jpg') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('logo/quicHealth_logo2.jpg') }}" alt="" height=""
                            width="60%">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                    id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">
                    <div id="two-column-menu">
                    </div>
                    {{-- @if(Auth::user()->utype === 'ADM') --}}
                        <ul class="navbar-nav" id="navbar-nav">
                            <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                            <li class="nav-item">
                                <a class="nav-link menu-link {{request()->is('admin') ? 'active' : ''}}" href="{{route('admin.home')}}">
                                    <i class="mdi mdi-speedometer"></i> <span data-key="t-dashboards">Dashboards</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link collapsed" href="#sidebarhospitals" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarhospitals">
                                    <i class="ri-layout-3-line"></i> <span data-key="t-hospitals">Hospital Managment</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarhospitals">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{route('admin.verifyHospital')}}" class="nav-link {{request()->is('admin/verify-hospital') ? 'active' : ''}}" data-key="t-horizontal">Verify Hospital</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{route('admin.hospitals')}}" class="nav-link {{request()->is('admin/hospitals') ? 'active' : ''}}" data-key="t-two-column">Hospital CRUD</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/doctors') ? 'active' : ''}}" href="{{route('admin.doctors')}}">
                                                <span data-key="t-hospitals">Doctors CRUD</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link collapsed" href="#sidebarusers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarusers">
                                    <i class="bx bx-user-circle"></i> <span data-key="t-users">Users</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarusers">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/users') ? 'active' : ''}}" href="{{route('admin.users')}}">
                                                <span data-key="t-users">Users CRUD</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link collapsed" href="#sidebarmeeting" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarmeeting">
                                    <i class="bx bx-users-circle"></i> <span data-key="t-meetings">Meetings</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarmeeting">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/meeting/payment') ? 'active' : ''}}" href="{{route('admin.meeting.index')}}">
                                                <span data-key="t-meetings">All Meetings</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="collapse menu-dropdown" id="sidebarmeeting">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/meeting/payment') ? 'active' : ''}}" href="{{route('admin.financial.payment')}}">
                                                <span data-key="t-meetings">Upcoming Meetings</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="collapse menu-dropdown" id="sidebarmeeting">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/meeting/hospital/payout') ? 'active' : ''}}" href="{{route('admin.financial.hospitalpayout')}}">
                                                <span data-key="t-meetings">Pass Meeting</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link collapsed" href="#sidebarfinancial" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarfinancial">
                                    <i class="bx bx-dollar"></i> <span data-key="t-financial">Financial Managment</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarfinancial">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/financial/payment') ? 'active' : ''}}" href="{{route('admin.financial.payment')}}">
                                                <span data-key="t-financial">All Payments</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="collapse menu-dropdown" id="sidebarfinancial">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/financial/hospital/payout') ? 'active' : ''}}" href="{{route('admin.financial.hospitalpayout')}}">
                                                <span data-key="t-financial">Hospital payout request</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link collapsed" href="#sidebarmessages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarmessages">
                                    <i class="mdi mdi-message-text-outline"></i> <span data-key="t-messages">Message Managment</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarmessages">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/sendMail') ? 'active' : ''}}" href="{{route('admin.email')}}">
                                                </i> <span data-key="t-messages">Send Email</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/complains') ? 'active' : ''}}" href="{{route('admin.complains')}}">
                                                </i> <span data-key="t-messages">Complains</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/messages') ? 'active' : ''}}" href="{{route('admin.messages')}}">
                                                </i> <span data-key="t-messages">Messages</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link collapsed" href="#sidebarsettings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarsettings">
                                    <i class="mdi mdi-cog-outline"></i> <span data-key="t-settings">Settings</span>
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarsettings">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/admins') ? 'active' : ''}}" href="{{route('admin.admins')}}">
                                                </i> <span data-key="t-settings">Admin CRUD</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link menu-link {{request()->is('admin/passwordreset') ? 'active' : ''}}" href="{{route('admin.passwordReset')}}">
                                                </i> <span data-key="t-settings">Password reset</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link menu-link" href="{{ route('admin.logout') }}">
                                    <i class="mdi mdi-logout"></i> <span data-key="t-logout">{{ __('Logout') }}</span>
                                </a>
                            </li>


                            {{-- <li class="nav-item">
                                <a class="nav-link menu-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="mdi mdi-logout"></i> <span data-key="t-logout">{{ __('Logout') }}</span>
                                </a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form> --}}

                        </ul>
                    {{-- @endif --}}
                </div>

            </div>
        </div>


        <div class="vertical-overlay"></div>

        <div class="main-content">

            @yield('content')

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © QuicHealth.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Designed with ❤️ by <a href="https://anthonyokagba.me/" target="_blank">Anthony
                                    Okagba</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

    </div>

    <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="p-4">

                    <div id="layout-width"></div>

                    <div id="layout-position"></div>

                    <div id="sidebar-size"></div>

                    <div id="sidebar-view"></div>

                    <div id="sidebar-color"></div>

                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('dashboard/js/plugins.js') }}"></script>

    <script src="{{ asset('dashboard/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/jsvectormap/maps/world-merc.js') }}"></script>

    <script src="{{ asset('dashboard/libs/list.js/list.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/list.pagination.js/list.pagination.min.js') }}"></script>

    <script src="{{ asset('dashboard/libs/swiper/swiper-bundle.min.js') }}"></script>

    <script src="{{ asset('dashboard/js/pages/crm-leads.init.js') }}"></script>
    <script src="{{ asset('dashboard/js/pages/ecommerce-customer-list.init.js') }}"></script>

    <script src="{{ asset('dashboard/js/pages/crm-contact.init.js') }}"></script>

    <script src="{{ asset('dashboard/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
    <script src="{{ asset('dashboard/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <script src="{{ asset('dashboard/js/pages/mailbox.init.js') }}"></script>

    <script src="{{ asset('dashboard/libs/dropzone/dropzone-min.js') }}"></script>

    <script src="{{ asset('dashboard/libs/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('dashboard/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}">
    </script>
    <script
        src="{{ asset('dashboard/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}">
    </script>
    <script
        src="{{ asset('dashboard/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}">
    </script>
    <script src="{{ asset('dashboard/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js') }}"></script>

    <script src="{{ asset('dashboard/js/pages/form-file-upload.init.js') }}"></script>

    <script src="{{ asset('dashboard/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('dashboard/js/pages/sweetalerts.init.js') }}"></script>

    <script src="{{ asset('dashboard/js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @if (Session::has('message'))
        <script>
            toastr.success("{!! Session::get('message') !!}")
        </script>
    @elseif(Session::has('error'))
        <script>
            toastr.error("{!! Session::get('error') !!}")
        </script>
    @endif

</body>

</html>

{{--
    Hospital Managment => {
        Verify Hospital,
        Hospital CRUD,
        Doctor CRUD,
    }

    Financial Managment => {
        Hospital payout request,

    }

    Settings => {
        Password reset,
        Admin CRUD,
    }

    Message Managment => {
        Send Email,
        Complains
        Messages
    }

    User Managment => {
        User CRUD
    }
--}}
