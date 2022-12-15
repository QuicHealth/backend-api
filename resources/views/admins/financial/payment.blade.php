@extends('layouts.dashboard')

@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Payments</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Financial Managment</a></li>
                            <li class="breadcrumb-item active">Payments</li>
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
                                    {{-- <a href="" class="btn btn-success add-btn"><i class="ri-add-line align-bottom me-1"></i> Add Donation</a> --}}
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
                                            {{-- <th scope="col" style="width: 50px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                                </div>
                                            </th> --}}

                                            <th class="sort" data-sort="name">No</th>
                                            <th class="sort" data-sort="name">Customer Name</th>
                                            <th class="sort" data-sort="name">Customer Email</th>
                                            <th class="sort" data-sort="leads_score">Appointments Details</th>
                                            <th class="sort" data-sort="leads_score">Amount</th>
                                            <th class="sort" data-sort="leads_score">Payment Status</th>
                                            <th class="sort" data-sort="leads_score">Transaction Id</th>
                                            <th class="sort" data-sort="date" scope="col">Date</th>
                                            <th class="sort" data-sort="date">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td class="id" style=""><a href="javascript:void(0);" class="fw-medium link-primary">1</a></td>
                                                <td>{{ $payment->user->fullname() }}</td>
                                                <td>{{ $payment->user->email }}</td>
                                                <td>{{ $payment->appointments_id }}</td>
                                                <td>₦{{ number_format($payment->amount)}}</td>
                                                @if ($payment->paymentStatus == "PAID")
                                                    <td class="status"><span class="badge badge-soft-success text-uppercase">Paid</span></td>
                                                @elseif ($payment->paymentStatus === 'pending')
                                                    <td class="status"><span class="badge badge-soft-danger text-uppercase">Pending</span></td>
                                                @endif
                                                <td>{{ $payment->transaction_id }}</td>
                                                <td>{{ $payment->created_at }}</td>
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
                                        {{-- <th scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="checkAll" value="option1">
                                            </div>
                                        </th> --}}
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

                    </div>
                </div>

            </div>
        </div>


    </div>
</div>

@endsection
