@extends('admin.layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Welcome Aamir</h3>
                        <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have
                            <span class="text-primary">3 unread alerts!</span>
                        </h6>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card tale-bg">
                    <div class="card-people mt-auto">
                        <img src="{{ asset('images/people.svg') }}" alt="people">
                        <div class="weather-info">
                            <div class="d-flex">
                                <div>
                                    <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>31<sup>C</sup></h2>
                                </div>
                                <div class="ml-2">
                                    <h4 class="location font-weight-normal">Bangalore</h4>
                                    <h6 class="font-weight-normal">India</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin transparent">
                <div class="row">
                    <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card card-tale">
                            <div class="card-body">
                                <p class="mb-4">Today’s Bookings</p>
                                <p class="fs-30 mb-2">4006</p>
                                <p>10.00% (30 days)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4 stretch-card transparent">
                        <div class="card card-dark-blue">
                            <div class="card-body">
                                <p class="mb-4">Total Bookings</p>
                                <p class="fs-30 mb-2">61344</p>
                                <p>22.00% (30 days)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                        <div class="card card-light-blue">
                            <div class="card-body">
                                <p class="mb-4">Number of Doctor</p>
                                <p class="fs-30 mb-2">34040</p>
                                <p>2.00% (30 days)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card transparent">
                        <div class="card card-light-danger">
                            <div class="card-body">
                                <p class="mb-4">Number of Hospitals</p>
                                <p class="fs-30 mb-2">47033</p>
                                <p>0.22% (30 days)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-4 mb-lg-0 mt-3 stretch-card transparent">
                        <div class="card card-light-blue">
                            <div class="card-body">
                                <p class="mb-4">Number of User</p>
                                <p class="fs-30 mb-2">34040</p>
                                <p>2.00% (30 days)</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title mb-0">Latest Appointments</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-borderless">
                                <thead>
                                    <tr>
                                        <th>Patients name</th>
                                        <th>Doctors name</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Search Engine Marketing</td>
                                        <td>John Paul</td>
                                        <td class="font-weight-bold">$362</td>
                                        <td>21 Sep 2018</td>
                                        <td class="font-weight-medium">
                                            <div class="badge badge-success">Completed</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Search Engine Optimization</td>
                                        <td>Peter Paul</td>
                                        <td class="font-weight-bold">$116</td>
                                        <td>13 Jun 2018</td>
                                        <td class="font-weight-medium">
                                            <div class="badge badge-success">Completed</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Display Advertising</td>
                                        <td>Ozioma Dike</td>
                                        <td class="font-weight-bold">$551</td>
                                        <td>28 Sep 2018</td>
                                        <td class="font-weight-medium">
                                            <div class="badge badge-warning">Pending</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Pay Per Click Advertising</td>
                                        <td>John Paul</td>
                                        <td class="font-weight-bold">$523</td>
                                        <td>30 Jun 2018</td>
                                        <td class="font-weight-medium">
                                            <div class="badge badge-warning">Pending</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>E-Mail Marketing</td>
                                        <td>John Paul</td>
                                        <td class="font-weight-bold">$781</td>
                                        <td>01 Nov 2018</td>
                                        <td class="font-weight-medium">
                                            <div class="badge badge-danger">Cancelled</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Referral Marketing</td>
                                        <td>John Peter</td>
                                        <td class="font-weight-bold">$283</td>
                                        <td>20 Mar 2018</td>
                                        <td class="font-weight-medium">
                                            <div class="badge badge-warning">Pending</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Social media marketing</td>
                                        <td>John Joy</td>
                                        <td class="font-weight-bold">$897</td>
                                        <td>26 Oct 2018</td>
                                        <td class="font-weight-medium">
                                            <div class="badge badge-success">Completed</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
