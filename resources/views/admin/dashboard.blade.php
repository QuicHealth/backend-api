@extends('admin.inc.app')
@section('dashboard')
    active
@endsection
@section('content')
    <div id="content-page" class="content-page mr-0">
        <div class="container-fluid px-0">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>User</h6>
                                <span class="iq-icon"><i class="ri-information-fill"></i></span>
                            </div>
                            <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="rounded-circle iq-card-icon iq-bg-primary mr-2"> <i
                                            class="ri-inbox-fill"></i></div>
                                </div>
                                    <h2>{{ $user }}</h2>
                                    <div class="iq-map text-primary font-size-32"><i class="ri-bar-chart-grouped-line"></i>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Hospitals</h6>
                                <span class="iq-icon"><i class="ri-information-fill"></i></span>
                            </div>
                            <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="rounded-circle iq-card-icon iq-bg-primary mr-2"> <i
                                            class="ri-inbox-fill"></i></div>
                                </div>
                                <h2>{{$hospitals}}</h2>
                                <div class="iq-map text-primary font-size-32"><i class="ri-bar-chart-grouped-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Doctors</h6>
                                <span class="iq-icon"><i class="ri-information-fill"></i></span>
                            </div>
                            <div class="iq-customer-box d-flex align-items-center justify-content-between mt-3">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="rounded-circle iq-card-icon iq-bg-primary mr-2"> <i
                                            class="ri-inbox-fill"></i></div>
                                </div>
                                <h2>{{ $doctors }}</h2>
                                
                                <div class="iq-map text-primary font-size-32"><i class="ri-bar-chart-grouped-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
