@extends('inc.app')
@section('create_session')
    active
@endsection
@section('sess')
    active
@endsection
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-7">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Create New Session</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <form method="post" action="/admin/session">
                                @include('inc.notification')
                                @csrf
                                <div class="form-group">
                                    <label for="email">Name:</label>
                                    <input type="text" class="form-control" id="email" name="name">
                                </div>
                                <div class="checkbox mb-3">
                                    <label><input type="checkbox" name="set"> Set as active session</label>
                                </div>
                                <button type="submit" class="btn btn-primary px-5 py-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
