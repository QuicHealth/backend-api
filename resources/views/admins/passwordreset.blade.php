@extends('layouts.dashboard')

@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Password Reset</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                            <li class="breadcrumb-item active">Password Reset</li>
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
                                <label for="employeeName" class="form-label">Old Password</label>
                                <input type="text" class="form-control" name="oldPassword" id="employeeName" placeholder="Enter Old Password" value="{{ old('oldPassword') }}">
                                @error('oldPassword')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="employeeName" class="form-label">New Password</label>
                                <input type="text" class="form-control" name="newPassword" id="employeeName" placeholder="Enter New Password" value="{{ old('New Password') }}">
                                @error('newPassword')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="employeeName" class="form-label">Retype Password</label>
                                <input type="text" class="form-control" name="retypePassword" id="employeeName" placeholder="Enter retype Password" value="{{ old('retypePassword') }}">
                                @error('retypePassword')
                                    <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
