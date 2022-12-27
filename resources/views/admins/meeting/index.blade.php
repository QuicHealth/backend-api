@extends('layouts.dashboard')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Admins</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                <li class="breadcrumb-item active">Admins</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card" id="leadsList">
                        <div class="card-header border-0">

                            <div class="row g-4 align-items-center">
                                <div class="col-sm-3">
                                    <div class="search-box">
                                        <input type="text" class="form-control search" placeholder="Search for...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <div class="col-sm-auto ms-auto">
                                    <div class="hstack gap-2">
                                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                            id="create-btn" data-bs-target="#showModal">
                                            <i class="ri-add-line align-bottom me-1"></i>Add Admin
                                        </button>
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

                                                <th class="sort" data-sort="name">Patient's Name</th>
                                                <th class="sort" data-sort="name">Doctor's Name</th>
                                                <th class="sort" data-sort="leads_score">Date</th>
                                                <th class="sort" data-sort="phone"scope="col">Start Time</th>
                                                <th class="sort" data-sort="phone"scope="col">End Time</th>
                                                <th class="sort" data-sort="phone"scope="col">Payment Status</th>
                                                <th class="sort" data-sort="phone"scope="col">Status</th>
                                                <th class="sort" data-sort="date" scope="col">Date Created</th>
                                                <th class="sort" data-sort="date" scope="col">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @foreach ($appointments as $appoint)
                                                <tr>
                                                    <th scope="row">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="checkAll" value="option1">
                                                        </div>
                                                    </th>
                                                    <td class="id" style="display:none;">
                                                        <a href="javascript:void(0);" class="fw-medium link-primary">{{ $appoint->id }}</a>
                                                    </td>
                                                    <td class="name">{{ $appoint->user->fullname() }}</td>
                                                    <td class="name">{{ $appoint->doctor->name }}</td>
                                                    <td class="leads_score">{{ $appoint->date }}</td>
                                                    <td class="phone">{{ $appoint->start }}</td>
                                                    <td class="phone">{{ $appoint->end }}</td>
                                                    @if ($appoint->payment_status == 'pending')
                                                        <td class="status"><span
                                                            class="badge badge-soft-warning text-uppercase">Pending</span>
                                                        </td>
                                                    @elseif ($appoint->payment_status == 'done')
                                                        <td class="status"><span
                                                            class="badge badge-soft-success text-uppercase">Done</span>
                                                        </td>
                                                    @endif
                                                    @if ($appoint->status == 'pending')
                                                        <td class="status"><span
                                                            class="badge badge-soft-warning text-uppercase">Pending</span>
                                                        </td>
                                                    @elseif ($appoint->status == 'done')
                                                        <td class="status"><span
                                                            class="badge badge-soft-success text-uppercase">Done</span>
                                                        </td>
                                                    @endif
                                                    <td class="date left">{{ \Carbon\Carbon::parse($appoint->created_at)->toFormattedDateString() }}
                                                    </td>
                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                                <a href="" class="text-primary d-inline-block edit-item-btn">
                                                                    <i class="ri-pencil-fill fs-16"></i>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item remove" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                                <a href="" class="text-danger d-inline-block remove-item-btn">
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
                                            <p class="text-muted mb-0">We've searched more than 150+
                                                Orders We did not find any
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
                                                <h4 class="fs-semibold">You are about to delete a lead
                                                    ?</h4>
                                                <p class="text-muted fs-14 mb-4 pt-1">Deleting your
                                                    lead will remove all of your information from our
                                                    database.</p>
                                                <div class="hstack gap-2 justify-content-center remove">
                                                    <button
                                                        class="btn btn-link link-success fw-medium text-decoration-none"
                                                        data-bs-dismiss="modal"><i
                                                            class="ri-close-line me-1 align-middle"></i>
                                                        Close</button>
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
