@extends('admin.inc.app')
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="student-tabs pt-3 pb-4 pl-3">
                <ul class="d-flex nav nav-pills">
                    <li>
                        <a class="nav-link active" data-toggle="pill" href="#personal-information">
                            Doctor
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" data-toggle="pill" href="#appointment">
                            Appointments
                        </a>
                    </li>
                </ul>
            </div>
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="iq-edit-list-data">
                        <div class="tab-content">
                            {{-- @include('admin.inc.notification') --}}
                            <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                                <div class="row mx-0 px-0">
                                    <div class="col-sm-8 mt-2 px-0">
                                        <div class="iq-card mb-0 rounded-0">
                                            <div class="iq-card-header d-flex justify-content-between">
                                                <div class="iq-header-title">
                                                    <h4 class="card-title font-weight-bold">
                                                        Edit Doctor
                                                    </h4>
                                                </div>
                                                <button data-toggle="modal" data-target="#delete" class="btn btn-danger d-block"><i class="fa fa-trash mr-0"></i></button>
                                            </div>
                                            <div class="iq-card-body">
                                                <form method="POST" action="/admin/update-doctor" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="doctor" id="" value="{{$doctor->id}}">
                                                    <div class="form-group">
                                                        <label for="fname">Name:</label>
                                                        <input type="text" class="form-control" id="fname" name="name" value="{{$doctor->name}}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="email">Email:</label>
                                                        <input type="email" class="form-control" id="email" name="email" value="{{$doctor->email}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="state">Phone:</label>
                                                        <input type="text" class="form-control" id="state" name="phone" value="{{$doctor->phone}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="city">Hospital:</label>
                                                        <select class="form-control selectpicker" name="hospital" id="hos">
                                                            @foreach($hospitals as $hos)
                                                                <option value="{{$hos->id}}" {{$doctor->hospital->id == $hos->id ? 'selected' : ''}}>
                                                                    {{$hos->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        {{-- <input type="text" class="form-control" id="city" name="city" value="{{$doctor->hospital->name}}"> --}}
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="add">Specialty:</label>
                                                        <select class="form-control selectpicker" name="specialty" id="hos">
                                                            @foreach($specialties as $spec)
                                                                <option value="{{$spec->id}}" {{$doctor->specialties->id == $spec->id ? 'selected' : ''}}>
                                                                    {{$spec->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        {{-- <input type="text" class="form-control" id="add" name="country" value="{{$doctor->specialties->name}}"> --}}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="add">Address:</label>
                                                        <input type="text" class="form-control" id="add" name="address" value="{{$doctor->address}}">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary px-5 py-2">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 px-0 col-sm-4 mt-2">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="iq-card mx-sm-5">
                                                    <div class="iq-card-body proCount">
                                                        <div class="text-center">
                                                            <h3>0</h3>
                                                            <span>Doctors</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="appointment" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="iq-card">
                                            <div class="iq-card-body">
                                                <div class="iq-todo-page">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-sm-6 mt-1 product-img" style="background-image: url('{{$hospital->image}}');">
                </div> --}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Delete Doctor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fa fa-trash text-danger" style="font-size: 90px"></i>
                    <h5 class="mt-3 mb-1">Are you sure?</h5>
                    <form action="/admin/delete-doctor" method="POST">
                        @csrf
                        <input type="hidden" name="doctor" value="{{$doctor->id}}">
                        <button type="submit" class="btn btn-danger px-5 py-2">Delete</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
