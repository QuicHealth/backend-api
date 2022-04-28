<!-- TOP Nav Bar -->
<div class="iq-top-navbar mr-0">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0" id="chanav">
            <ul class="navbar-list ml-xl-4 ml-2">
                <li>
                    <a href="{{env('APP_FRONTEND').'/portal/student/dashboard'}}" class="" style="">
{{--                        <img src="{{asset('assets/images/stal.jpg')}}" class="img-fluid" width="200px" style="width: 200px; height: 100%;">--}}
                    </a>
                </li>
            </ul>

            <div class="iq-menu-bt align-self-center justify-content-start">
                <div class="wrapper-menu">
                    <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                    <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                </div>
            </div>


            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav navbar-list">
                    <li>
                        <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center bg-primary rounded">
                            <img src="{{asset('assets/images/user/icon.png')}}" class="img-fluid rounded mr-3">
                            <div class="caption">
                                <h6 class="mb-0 line-height text-white">Admin</h6>
                                <span class="font-size-12 text-white">Available</span>
                            </div>
                        </a>
                        <div class="iq-sub-dropdown iq-user-dropdown">
                            <div class="iq-card shadow-none m-0">
                                <div class="iq-card-body p-0 ">
                                    <div class="bg-primary p-3">
                                        <h5 class="mb-0 text-white line-height">Hello Admin</h5>
                                        <span class="text-white font-size-12">Available</span>
                                    </div>
                                    <div class="d-inline-block w-100 text-center p-3">
                                        <form action="/admin/logout" method="post">
                                            @csrf
                                            <button class="bg-primary iq-sign-btn" type="submit"> Sign out<i class="ri-login-box-line ml-2"></i> </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>


    </div>
</div>
<!-- TOP Nav Bar END -->
