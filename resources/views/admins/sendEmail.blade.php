@extends('layouts.dashboard')

@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Send Email</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Message Managment</a></li>
                            <li class="breadcrumb-item active">Send Email</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="leadsList">
                    <div class="card-header border-0">

                        <form action="" method="" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="employeeName" class="form-label">Emails</label>
                                <input type="text" class="form-control" name="name" id="employeeName" placeholder="Enter Email" value="{{ old('email') }}">
                                @error('name')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="employeeUrl" class="form-label">Message</label>
                                <textarea name="" class="form-control" id="" cols="30" rows="10"></textarea>
                                @error('message')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Send Email</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
