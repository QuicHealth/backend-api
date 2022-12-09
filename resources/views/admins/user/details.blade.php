@extends('layouts.dashboard')

@section('content')

    <div class="page-content">
        <div class="container-fluid">
            <div class="profile-foreground position-relative mx-n4 mt-n4">
                <div class="profile-wid-bg">
                    <img src="{{ asset('dashboard/images/profile-bg.jpg')}}" alt="" class="profile-wid-img" />
                </div>
            </div>
            <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
                <div class="row g-4">
                    <div class="col-auto">
                        <div class="avatar-lg">
                            <img src="{{ asset('dashboard/images/users/avatar-1.jpg')}}" alt="user-img" class="img-thumbnail rounded-circle" />
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2">
                            <h3 class="text-white mb-1">{{ $user->firstname }} {{ $user->lastname }}</h3>
                            <p class="text-white-75">{{ $user->address }}</p>
                            <div class="hstack text-white-50 gap-1">
                                <div class="me-2"><i class="ri-map-pin-user-line me-1 text-white-75 fs-16 align-middle"></i>{{ $user->city }}, Nigeria</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <div class="d-flex">

                            <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                                {{-- <li class="nav-item">
                                    <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                        <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
                                    </a>
                                </li> --}}
                            </ul>
                            <div class="flex-shrink-0">
                                <a href="" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                            </div>
                        </div>

                        <div class="tab-content pt-4 text-muted">
                            <div class="tab-pane active" id="overview-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-xxl-3">
                                        {{-- <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-5">Complete Your Profile</h5>
                                                <div class="progress animated-progess custom-progress progress-label">
                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"><div class="label">30%</div></div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">Info</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Full Name :</th>
                                                                <td class="text-muted">{{ $user->firstname }} {{ $user->lastname }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Mobile :</th>
                                                                <td class="text-muted">{{ $user->phone }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">E-mail :</th>
                                                                <td class="text-muted">{{ $user->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Gender</th>
                                                                <td class="text-muted">{{ $user->gender }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Address:</th>
                                                                <td class="text-muted">{{ $user->address }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Date of Birth:</th>
                                                                <td class="text-muted">{{ $user->dob }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Joining Date</th>
                                                                <td class="text-muted">{{ $user->created_at }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
