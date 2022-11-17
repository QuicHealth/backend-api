  <!DOCTYPE html>
  <html lang="en">

  <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Quichealth Admin</title>
      <!-- plugins:css -->
      <link rel="stylesheet" href="{{ asset('asset/ti-icons/css/themify-icons.css') }} ">
      <link rel="stylesheet" href="{{ asset('asset/feather/feather.css') }} ">
      <link rel="stylesheet" href="{{ asset('asset/css/vendor.bundle.base.css') }} ">
      <!-- endinject -->
      <!-- Plugin css for this page -->
      <link rel="stylesheet" href="{{ asset('asset/datatables.net-bs4/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href=" {{ asset('asset/ti-icons/css/themify-icons.css') }} ">
      <link rel="stylesheet" type="text/css" href="{{ asset('js/select.dataTables.min.css') }} ">
      <!-- End plugin css for this page -->
      <!-- inject:css -->
      <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
      <!-- endinject -->
      <link rel="shortcut icon" href="{{ asset('logo/quicHealth_logo1.jpg') }}" />
  </head>

  <body>
      <div class="container-scroller">
          @include('admin.inc.header')
          <div class="container-fluid page-body-wrapper">
              <!-- partial:partials/_settings-panel.html -->
              {{-- <div class="theme-setting-wrapper">
                  <div id="settings-trigger"><i class="ti-settings"></i></div>
                  <div id="theme-settings" class="settings-panel">
                      <i class="settings-close ti-close"></i>
                      <p class="settings-heading">SIDEBAR SKINS</p>
                      <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                          <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
                      </div>
                      <div class="sidebar-bg-options" id="sidebar-dark-theme">
                          <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
                      </div>
                      <p class="settings-heading mt-2">HEADER SKINS</p>
                      <div class="color-tiles mx-0 px-4">
                          <div class="tiles success"></div>
                          <div class="tiles warning"></div>
                          <div class="tiles danger"></div>
                          <div class="tiles info"></div>
                          <div class="tiles dark"></div>
                          <div class="tiles default"></div>
                      </div>
                  </div>
              </div> --}}

              <!-- partial -->
              <!-- partial:partials/_sidebar.html -->

              @include('admin.inc.sidebar')
              <!-- partial -->
              <div class="main-panel">
                  @yield('content')
                  <!-- content-wrapper ends -->
                  @include('admin.inc.footer')
              </div>
              <!-- main-panel ends -->
          </div>
          <!-- page-body-wrapper ends -->
      </div>
      <!-- container-scroller -->

      <!-- plugins:js -->
      <script src="{{ asset('asset/js/vendor.bundle.base.js') }} "></script>
      <!-- endinject -->
      <!-- Plugin js for this page -->
      <script src="{{ asset('asset/chart.js/Chart.min.js') }} "></script>
      <script src="{{ asset('asset/datatables.net/jquery.dataTables.js') }}"></script>
      <script src="{{ asset('asset/datatables.net-bs4/dataTables.bootstrap4.js') }} "></script>
      <script src="{{ asset('js/dataTables.select.min.js') }}  "></script>

      <!-- End plugin js for this page -->
      <!-- inject:js -->
      <script src="{{ asset('js/off-canvas.js') }}"></script>
      <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
      <script src="{{ asset('js/template.js') }}"></script>
      <script src="{{ asset('js/settings.js') }}"></script>
      <script src="{{ asset('js/todolist.js') }}"></script>
      <!-- endinject -->
      <!-- Custom js for this page-->
      <script src="{{ asset('js/dashboard.js') }}"></script>
      <script src="{{ asset('js/Chart.roundedBarCharts.js') }}"></script>
      <!-- End custom js for this page-->
  </body>

  </html>
