@extends('layouts.dashboard')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Hospital Details</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Hospital Managment</a></li>
                                <li class="breadcrumb-item active">Hospital Details</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body p-4">
                            <div>
                                <div class="flex-shrink-0 avatar-md mx-auto">
                                    <div class="avatar-title bg-light rounded">
                                        <img src="assets/images/companies/img-2.png" alt="" height="50">
                                    </div>
                                </div>
                                <div class="mt-4 text-center">
                                    <h5 class="mb-1">{{ $hos->name }}</h5>
                                    <p class="text-muted">Since
                                        {{ \Carbon\Carbon::parse($hos->created_at)->toFormattedDateString() }}</p>
                                </div>
                                <div class="table-responsive align-content-center">
                                    <table class="table mb-0 table-borderless">
                                        <tbody>
                                            <tr>
                                                <th><span class="fw-medium">Hospital Name:</span></th>
                                                <td>
                                                    <h3>
                                                        <span class="badge bg-primary text-lg">{{ $hos->name }}</span>
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><span class="fw-medium">Email:</span></th>
                                                <td>
                                                    <h3>
                                                        <span class="badge bg-secondary text-lg">{{ $hos->email }}</span>
                                                    </h3>

                                                </td>
                                            </tr>
                                            <tr>
                                                <th><span class="fw-medium">No. of Doctors:</span></th>
                                                <td>
                                                    <h3>
                                                        <span
                                                            class="badge bg-info text-dark">{{ $hos->doctors->count() }}</span>
                                                    </h3>

                                                </td>
                                            </tr>
                                            <tr>
                                                <th><span class="fw-medium">Contact No.:</span></th>
                                                <td>
                                                    <h3>
                                                        <span class="badge bg-secondary text-white">{{ $hos->phone }}
                                                        </span>
                                                    </h3>

                                                </td>
                                            </tr>
                                            <tr>
                                                <th><span class="fw-medium">Verification:</span></th>
                                                <td>
                                                    @if ($hos->verified == true)
                                                        <h3> <span class="badge bg-success">verified</span> </h3>
                                                    @else
                                                        <h3> <span class="badge bg-warning text-dark">not verified</span>
                                                        </h3>
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <th><span class="fw-medium">Location:</span></th>
                                                <td>{{ $hos->address }}</td>
                                            </tr>

                                            @if (!empty($hos->settings))
                                                <tr>
                                                    <th><span class="fw-medium">Bank Account:</span></th>.
                                                    <td>{{ $hos->settings->bank }}</td>
                                                </tr>
                                                <tr>
                                                    <th><span class="fw-medium">Account No.:</span></th>
                                                    <td>{{ $hos->settings->acc_no }}</td>
                                                </tr>
                                                <tr>
                                                    <th><span class="fw-medium">Account Name:</span></th>
                                                    <td>{{ $hos->settings->acc_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th><span class="fw-medium">Amount:</span></th>
                                                    <td>₦{{ number_format($hos->settings->amount) }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th><span class="fw-medium">Joining Date:</span></th>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($hos->created_at)->toFormattedDateString() }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card-header border-0 align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Doctors</h4>
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
                                                        <input class="form-check-input" type="checkbox" id="checkAll"
                                                            value="option">
                                                    </div>
                                                </th>

                                                <th class="sort" data-sort="name">Name</th>
                                                <th class="sort" data-sort="leads_score">Email</th>
                                                <th class="sort" data-sort="phone"scope="col">Phone</th>
                                                <th class="sort" data-sort="phone"scope="col">Gender</th>
                                                <th class="sort" data-sort="date" scope="col">Date Joined</th>
                                                <th class="sort" data-sort="date" scope="col">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @foreach ($hos->doctors as $docs)
                                                <tr>
                                                    <th scope="row">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="checkAll"
                                                                value="option1">
                                                        </div>
                                                    </th>
                                                    <td class="id" style="display:none;"><a href="javascript:void(0);"
                                                            class="fw-medium link-primary">#VZ2101</a></td>
                                                    <td class="name">{{ $docs->name }}</td>
                                                    <td class="leads_score">{{ $docs->email }}</td>
                                                    <td class="phone">{{ $docs->phone }}</td>
                                                    <td class="phone">{{ $docs->gender }}</td>
                                                    <td class="date left">
                                                        {{ \Carbon\Carbon::parse($docs->created_at)->toFormattedDateString() }}
                                                    </td>
                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                                data-bs-trigger="hover" data-bs-placement="top"
                                                                title="Edit">
                                                                <a href="{{ url('admin/doctor/' . $docs->unique_id) }}"
                                                                    class="text-primary d-inline-block ">
                                                                    <i class="ri-eye-fill fs-16"></i>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item remove" data-bs-toggle="tooltip"
                                                                data-bs-trigger="hover" data-bs-placement="top"
                                                                title="Remove">
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
                                            <p class="text-muted mb-0">We've searched more than 150+ Orders We did not find
                                                any
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

                            <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1"
                                aria-labelledby="deleteRecordLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close" id="btn-close"></button>
                                        </div>
                                        <div class="modal-body p-5 text-center">
                                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                                            </lord-icon>
                                            <div class="mt-4 text-center">
                                                <h4 class="fs-semibold">You are about to delete a lead ?</h4>
                                                <p class="text-muted fs-14 mb-4 pt-1">Deleting your lead will remove all of
                                                    your information from our database.</p>
                                                <div class="hstack gap-2 justify-content-center remove">
                                                    <button
                                                        class="btn btn-link link-success fw-medium text-decoration-none"
                                                        data-bs-dismiss="modal"><i
                                                            class="ri-close-line me-1 align-middle"></i> Close</button>
                                                    <button class="btn btn-danger" id="delete-record">Yes, Delete
                                                        It!!</button>
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
