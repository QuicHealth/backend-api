@extends('admin.inc.app')
@section('hospitals')
    active
@endsection
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            @include('admin.inc.notification')
            <div class="row">
                <div class="col-sm-12 px-0">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between align-items-center">
                            <div class="iq-header-title">
                                <h4 class="card-title font-weight-bold">Hospitals</h4>
                            </div>
                            <div class="iq-header-title">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategory">
                                    Add Hospital
                                </button>
                            </div>
                        </div>
                        <div class="iq-card-body pt-0">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Country</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hospitals as $hospital)
                                            <tr onclick="window.location.href='/admin/hospital/{{$hospital->id}}'">
                                                <td>{{$hospital->name}}</td>
                                                <td>{{$hospital->email}}</td>
                                                <td>{{$hospital->phone}}</td>
                                                <td>{{$hospital->address}}</td>
                                                <td>{{$hospital->city}}</td>
                                                <td>{{$hospital->state}}</td>
                                                <td>{{$hospital->country}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Country</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-modal="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Add Hospital</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="/admin/add-hospital" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone:</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                </div>
                                <div class="form-group">
                                    <label for="city">City:</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                                <div class="form-group">
                                    <label for="state">State:</label>
                                    <input type="text" class="form-control" id="state" name="state">
                                </div>
                                <div class="form-group">
                                    <label for="add">Country:</label>
                                    <input type="text" class="form-control" id="add" name="country">
                                </div>
                                <div class="form-group">
                                    <label for="add">Address:</label>
                                    <input type="text" class="form-control" id="add" name="address">
                                </div>
                                <button type="submit" class="btn btn-primary px-5 py-2">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
