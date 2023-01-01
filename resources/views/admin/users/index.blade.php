@extends('admin.inc.app')
@section('products')
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
                                <h4 class="card-title font-weight-bold">Products</h4>
                            </div>
                            <div class="iq-header-title">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProduct">
                                    Add Product
                                </button>
                            </div>
                        </div>
                        <div class="iq-card-body pt-0">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-hover" >
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Unit of measurement</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $prod)
                                            <tr onclick="window.location.href='/admin/product/{{$prod->id}}'">
                                                <td>{{$prod->name}}</td>
                                                <td>${{$prod->price}}</td>
                                                <td>{{$prod->unit}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Unit of measurement</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-modal="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Add Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="/admin/add-product" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="input-group mb-3">
                                    <label for="price" class="col-12 px-0">Price per unit:</label>
                                    <input type="text" id="price" class="form-control" name="price" style="border-top-left-radius:10px;border-bottom-left-radius:10px;">
                                    <div class="input-group-append">
                                        <span class="input-group-text px-3" style="border-top-right-radius:9px;border-bottom-right-radius:9px;border:1px solid #d7dbda;background-color: #fff;">$</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name">Unit:</label>
                                    <select class="form-control" id="exampleFormControlSelect2" name="unit" style="background-color:transparent">
                                        <option value="kg">Kilogram</option>
                                        <option value="pd">Pounds</option>
                                        <option value="lit">Liter</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="image">Image:</label>
                                    <input type="file" name="image" class="form-control line-height-2">
                                </div>
                                <div class="form-group">
                                    <label for="name">Description:</label>
                                    <textarea class="form-control" id="name" name="description"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary px-5 py-2">Add</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
