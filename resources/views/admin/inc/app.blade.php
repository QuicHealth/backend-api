<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title></title>
      <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/typography.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
      <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
{{--      <link rel="stylesheet" href="{{asset('assets/css/select2.min.css')}}">--}}
       <script src="https://cdn.tiny.cloud/1/oaana24ym6wz4ha898gs8nvwv6ltp28mm24wdq5evznrnr34/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
      <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
       <script type="text/javascript">
           tinymce.init({
               selector: '#desp'
           });
           tinymce.init({
               selector: '#descp'
           });
       </script>
   </head>
   <body class="sidebar-main-active right-column-fixed header-top-bgcolor">
        <div class="wrapper">
         <!-- Sidebar  -->
            <div class="iq-sidebar">
                <div class="iq-sidebar-logo d-flex justify-content-between pl-0">
                    <a href="/admin" >
{{--                        <img src="{{asset('assets/images/sm.jpg')}}" class="img-fluid" alt="">--}}
                    </a>
                    <div class="iq-menu-bt-sidebar">
                        <div class="iq-menu-bt align-self-center">
                            <div class="wrapper-menu">
                                <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                                <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="sidebar-scrollbar">
                    <nav class="iq-sidebar-menu">
                        <ul id="iq-sidebar-toggle" class="iq-menu">
                            <li class="@yield('dashboard')">
                                <a href="/admin" class="iq-waves-effect"><i class="ri-home-4-line"></i><span>Dashboard</span></a>
                            </li>
                            <li class="@yield('users')">
                                <a href="/admin/users" class="iq-waves-effect"><i class="fa fa-user"></i><span>Users</span></a>
                            </li>
                            <li class="@yield('hospitals')">
                                <a href="/admin/hospitals" class="iq-waves-effect"><i class="fa fa-hospital-o"></i><span>Hospital</span></a>
                            </li>
                            <li class="@yield('doctors')">
                                <a href="/admin/doctors" class="iq-waves-effect"><i class="fa fa-stethoscope"></i><span>Doctors</span></a>
                            </li>
                            {{-- <li class="@yield('products')">
                                <a href="/admin/products" class="iq-waves-effect"><i class="las la-gift"></i><span>Products</span></a>
                            </li> --}}
                            <li>
                                <a href="javascript:void()" class="iq-waves-effect" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="las la-power-off"></i>  <span>Logout</span>

                                </a>
                                <form action="/admin/logout" method="post" id="logout-form" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </nav>
                    <div class="p-3"></div>
                </div>
            </div>
            @include('admin.inc.navbar')
            {{-- @include('admin.inc.notification') --}}
            @yield('content')

        </div>
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/js/popper.min.js')}}"></script>
        <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/js/jquery.counterup.min.js')}}"></script>
        <script src="{{asset('assets/js/wow.min.js')}}"></script>
        <script src="{{asset('assets/js/slick.min.js')}}"></script>
{{--        <script src="{{asset('assets/js/select2.min.js')}}"></script>--}}
        <script src="{{asset('assets/js/owl.carousel.min.js')}}"></script>
        <script src="{{asset('assets/js/jquery.magnific-popup.min.js')}}"></script>
        <script src="{{asset('assets/js/smooth-scrollbar.js')}}"></script>
        <script src="{{asset('assets/js/lottie.js')}}"></script>
        <script src="{{asset('assets/js/core.js')}}"></script>
        <script src="{{asset('assets/js/animated.js')}}"></script>
        <script src="{{asset('assets/js/chart-custom.js')}}"></script>
        <script src="{{asset('assets/js/custom.js')}}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
        <script src="{{asset('assets/js/main.js')}}"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
      @yield('script')

   </body>
</html>
