@extends('admin.inc.app')
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6 mt-1 px-0">
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
                            <form method="POST" action="/admin/update-category" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="" value="{{$hospital->id}}">
                                <div class="form-group">
                                    <label for="fname">Name:</label>
                                    <input type="text" class="form-control" id="fname" name="name" value="{{$hospital->name}}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{$hospital->email}}">
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
                                <div class="form-group">
                                    <label for="add">Address:</label>
                                    <input type="text" class="form-control" id="add" name="address" value="{{$hospital->address}}">
                                </div>
                                <button type="submit" class="btn btn-primary px-5 py-2">Update</button>
                            </form>
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
                    <h5 class="mt-3 mb-0">Are you sure?</h5>
                    <form action="/admin/delete-hospital" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$hospital->id}}">
                        <button type="submit" class="btn btn-danger px-5 py-2">Delete</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
