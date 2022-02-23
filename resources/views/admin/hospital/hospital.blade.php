@extends('admin.inc.app')
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="student-tabs pt-3 pb-4 pl-3">
                <ul class="d-flex nav nav-pills">
                    <li>
                        <a class="nav-link active" data-toggle="pill" href="#personal-information">
                            Hospital
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" data-toggle="pill" href="#doctors">
                            Doctors
                        </a>
                    </li>
                </ul>
            </div>
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="iq-edit-list-data">
                        <div class="tab-content">
                            @include('admin.inc.notification')
                            <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                                <div class="row mx-0 px-0">
                                    <div class="col-sm-8 mt-2 px-0">
                                        <div class="iq-card mb-0 rounded-0">
                                            <div class="iq-card-header d-flex justify-content-between">
                                                <div class="iq-header-title">
                                                    <h4 class="card-title font-weight-bold">
                                                        Edit Hospital
                                                    </h4>
                                                </div>
                                                <button data-toggle="modal" data-target="#delete" class="btn btn-danger d-block"><i class="fa fa-trash mr-0"></i></button>
                                            </div>
                                            <div class="iq-card-body">
                                                <form method="POST" action="/admin/update-hospital" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="hospital" id="" value="{{$hospital->id}}">
                                                    <div class="form-group">
                                                        <label for="fname">Name:</label>
                                                        <input type="text" class="form-control" id="fname" name="name" value="{{$hospital->name}}">
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="email">Email:</label>
                                                        <input type="email" class="form-control" id="email" name="email" value="{{$hospital->email}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="phone">Phone:</label>
                                                        <input type="text" class="form-control" id="phone" name="phone" value="{{$hospital->phone}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="add">Address:</label>
                                                        <input type="text" class="form-control" id="add" name="address" value="{{$hospital->address}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="long">Longitude:</label>
                                                        <input type="text" class="form-control" id="long" name="longitude" value="{{$hospital->longitude}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="lat">Latitude:</label>
                                                        <input type="text" class="form-control" id="lat" name="latitude" value="{{$hospital->latitude}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="city">City:</label>
                                                        <input type="text" class="form-control" id="city" name="city" value="{{$hospital->city}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="state">State:</label>
                                                        <input type="text" class="form-control" id="state" name="state" value="{{$hospital->state}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="add">Country:</label>
                                                        <input type="text" class="form-control" id="add" name="country" value="{{$hospital->country}}">
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
                                                            <h3>{{count($hospital->doctors)}}</h3>
                                                            <span>Doctors</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="doctors" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="iq-card">
                                            <div class="iq-card-body">
                                                <div class="iq-todo-page">
                                                    <div class="table-responsive mt-3">
                                                        <table id="datatableF" class="table table-striped table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    {{-- <th>S/N</th> --}}
                                                                    <th>Name</th>
                                                                    <th>Email</th>
                                                                    <th>Phone</th>
                                                                    <th>Address</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if($hospital->doctors)
                                                                    @foreach($hospital->doctors as $doc)
                                                                        <tr>
                                                                            <td>{{$doc->name}}</td>
                                                                            <td>{{$doc->email}}</td>
                                                                            <td>{{$doc->phone}}</td>
                                                                            <td>{{$doc->address}}</td>
                                                                            <td>{{$doc->created_at->format('d, M Y H:i a')}}</td>
                                                                            
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="4"><p>No doctor available</p></td>
                                                                    </tr>
                                                                @endif
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
                {{-- <div class="col-sm-6 mt-1 product-img" style="background-image: url('{{$hospital->image}}');">
                </div> --}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content pb-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Delete Hospital</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fa fa-trash text-danger" style="font-size: 90px"></i>
                    <h5 class="mt-3 mb-1">Are you sure?</h5>
                    <form action="/admin/delete-hospital" method="POST">
                        @csrf
                        <input type="hidden" name="hospital" value="{{$hospital->id}}">
                        <button type="submit" class="btn btn-danger px-5 py-2">Delete</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
