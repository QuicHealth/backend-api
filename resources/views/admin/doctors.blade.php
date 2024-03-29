@extends('admin.inc.app')
@section('doctors')
    active
@endsection
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            {{-- @include('admin.inc.notification') --}}
            <div class="row">
                <div class="col-sm-12 px-0">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between align-items-center">
                            <div class="iq-header-title">
                                <h4 class="card-title font-weight-bold">Doctors</h4>
                            </div>
                            <div class="iq-header-title">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategory">
                                    Add Doctor
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
                                            <th>Hospital</th>
                                            <th>Specialty</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doctors as $doctors)
                                            <tr onclick="window.location.href='/admin/doctor/{{$doctors->id}}'">
                                                <td>{{$doctors->name}}</td>
                                                <td>{{$doctors->email}}</td>
                                                <td>{{$doctors->hospital->name}}</td>
                                                <td>{{$doctors->specialties->name}}</td>
                                                <td>{{$doctors->phone}}</td>
                                                <td>{{$doctors->address}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Hospital</th>
                                            <th>Specialty</th>
                                            <th>Phone</th>
                                            <th>Address</th>
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
                            <form method="post" action="/admin/add-doctor" enctype="multipart/form-data">
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
                                    <label for="hos" class="d-block">Hospital:</label>
                                    <select class="form-control selectpicker" name="hospital" id="hos">
                                       @foreach($hospitals as $hospital)
                                           <option value="{{$hospital->id}}">
                                               {{$hospital->name}}
                                           </option>
                                       @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="hos" class="d-block">Specialty:</label>
                                    <select class="form-control selectpicker" name="specialty" id="hos">
                                       @foreach($specialties as $spec)
                                           <option value="{{$spec->id}}">
                                               {{$spec->name}}
                                           </option>
                                       @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="add">Phone:</label>
                                    <input type="text" class="form-control" id="add" name="phone">
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
