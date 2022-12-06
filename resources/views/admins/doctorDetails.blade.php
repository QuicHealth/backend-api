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
                            <h3 class="text-white mb-1">{{ $doc->name }}</h3>
                            <p class="text-white-75">{{ $doc->address }}</p>
                            <div class="hstack text-white-50 gap-1">
                                <div class="me-2"><i class="ri-map-pin-user-line me-1 text-white-75 fs-16 align-middle"></i>{{ $doc->state }}, Nigeria</div>
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
                                                                <td class="text-muted">{{ $doc->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Hospital Name:</th>
                                                                <td class="text-muted">{{ $doc->hospital->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Mobile :</th>
                                                                <td class="text-muted">{{ $doc->phone }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">E-mail :</th>
                                                                <td class="text-muted">{{ $doc->email }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Gender:</th>
                                                                <td class="text-muted">{{ $doc->gender }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Address:</th>
                                                                <td class="text-muted">{{ $doc->address }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Date of Birth:</th>
                                                                <td class="text-muted">{{ $doc->dob }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="ps-0" scope="row">Joining Date:</th>
                                                                <td class="text-muted">{{ $doc->created_at }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xxl-9">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">About</h5>
                                                <p>{{ $doc->bio }}</p>
                                                <p>You always want to make sure that your fonts work well together and try to limit the number of fonts you use to three or less. Experiment and play around with the fonts that you already have in the software you’re working with reputable font websites. This may be the most commonly encountered tip I received from the designers I spoke with. They highly encourage that you use different fonts in one design, but do not over-exaggerate and go overboard.</p>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <div class="card-header border-0 align-items-center d-flex">
                                            <h4 class="card-title mb-0 flex-grow-1">Appointments with Patients</h4>
                                        </div>
                                        <div class="card" id="leadsList">
                                            <div class="card-header border-0">

                                                <div class="row g-4 align-items-center">
                                                    <div class="col-sm-3">
                                                        <div class="search-box">
                                                            <input type="text" class="form-control search" placeholder="Search for...">
                                                            <i class="ri-search-line search-icon"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div>
                                                    <div class="table-responsive table-card">
                                                        <table class="table align-middle" id="customerTable">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th scope="col" style="width: 50px;">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                                                        </div>
                                                                    </th>

                                                                    <th class="sort" data-sort="name">Patients Name</th>
                                                                    <th class="sort" data-sort="leads_score">Date</th>
                                                                    <th class="sort" data-sort="phone"scope="col">Start Time</th>
                                                                    <th class="sort" data-sort="phone"scope="col">End Time</th>
                                                                    <th class="sort" data-sort="phone"scope="col">Payment Status</th>
                                                                    <th class="sort" data-sort="phone"scope="col">Status</th>
                                                                    <th class="sort" data-sort="date" scope="col">Date Joined</th>
                                                                    <th class="sort" data-sort="date" scope="col">Action</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody class="list form-check-all">
                                                                @foreach ($doc->appointments as $docs)
                                                                    <tr>
                                                                        <th scope="row">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="checkbox" name="checkAll" value="option1">
                                                                            </div>
                                                                        </th>
                                                                        <td class="id" style="display:none;"><a href="javascript:void(0);" class="fw-medium link-primary">{{ $docs->id }}</a></td>
                                                                        <td class="name">{{ $docs->user->fullname() }}</td>
                                                                        <td class="leads_score">{{ $docs->date }}</td>
                                                                        <td class="phone">{{ $docs->start }}</td>
                                                                        <td class="phone">{{ $docs->end }}</td>
                                                                        @if ($docs->payment_status == 'pending')
                                                                            <td class="status"><span class="badge badge-soft-warning text-uppercase">Pending</span></td>
                                                                        @elseif ($docs->payment_status == 'done')
                                                                            <td class="status"><span class="badge badge-soft-success text-uppercase">Done</span></td>
                                                                        @endif
                                                                        @if ($docs->status == 'pending')
                                                                            <td class="status"><span class="badge badge-soft-warning text-uppercase">Pending</span></td>
                                                                        @elseif ($docs->status == 'done')
                                                                            <td class="status"><span class="badge badge-soft-success text-uppercase">Done</span></td>
                                                                        @endif
                                                                        <td class="date left">{{ $docs->created_at }}</td>
                                                                        <td>
                                                                            <ul class="list-inline hstack gap-2 mb-0">
                                                                                <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                                    <a href=""
                                                                                        class="text-primary d-inline-block edit-item-btn">
                                                                                        <i class="ri-pencil-fill fs-16"></i>
                                                                                    </a>
                                                                                </li>
                                                                                <li class="list-inline-item remove" data-bs-toggle="tooltip"
                                                                                    data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                                                    <a href=""
                                                                                        class="text-danger d-inline-block remove-item-btn">
                                                                                        <i class="ri-delete-bin-5-fill fs-16"></i>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        <div class="noresult" style="display: none">
                                                            <div class="text-center">
                                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                                    colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                                                </lord-icon>
                                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                                <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find any
                                                                    orders for you search.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end mt-3">
                                                        <div class="pagination-wrap hstack gap-2">
                                                            <a class="page-item pagination-prev disabled" href="#">
                                                                Previous
                                                            </a>
                                                            <ul class="pagination listjs-pagination mb-0"></ul>
                                                            <a class="page-item pagination-next" href="#">
                                                                Next
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
                                                            </div>
                                                            <div class="modal-body p-5 text-center">
                                                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                                                                <div class="mt-4 text-center">
                                                                    <h4 class="fs-semibold">You are about to delete a lead ?</h4>
                                                                    <p class="text-muted fs-14 mb-4 pt-1">Deleting your lead will remove all of your information from our database.</p>
                                                                    <div class="hstack gap-2 justify-content-center remove">
                                                                        <button class="btn btn-link link-success fw-medium text-decoration-none" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</button>
                                                                        <button class="btn btn-danger" id="delete-record">Yes, Delete It!!</button>
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
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
