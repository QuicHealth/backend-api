<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
      <!-- Typography CSS -->
      <link rel="stylesheet" href="{{asset('assets/css/typography.css')}}">
      <!-- Style CSS -->
      <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
      <!-- Responsive CSS -->
      {{-- <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}"> --}}
      <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">

   </head>
   <body>
        <section class="sign-in-page">
            <div class="container bg-white p-0 rounded">
                <div class="row no-gutters mt-3">
                    <div class="col-sm-12 align-self-center">
                        <div class="sign-in-from px-3">
                            <h4 class="my-3 text-center">Admin login</h4>
                            <form class="py-2 px-3 pb-5" method="POST" action="/admin/login">
                                @include('admin.inc.notification')
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Username</label>
                                    <input type="text" class="form-control mb-0" id="exampleInputEmail1" name="username" value="admin" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" class="form-control mb-0" id="exampleInputPassword1" value="123456" name="password" placeholder="Password">
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary px-5 py-2 w-100">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Sign in END -->
      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script src="{{asset('assets/js/jquery.min.js')}}"></script>
   </body>
</html>
