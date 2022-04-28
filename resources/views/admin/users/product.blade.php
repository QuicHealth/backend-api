@extends('admin.inc.app')
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6 mt-1 product-img" style="background-image: url('{{$product->image}}');">
                </div>
                <div class="col-sm-6 mt-1 px-0">
                    <div class="iq-card mb-0 rounded-0">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title font-weight-bold">
                                    Edit Product
                                    @if($product->status == 0)
                                        <span class="badge badge-danger font-size-12 py-0">Out of stock</span>
                                    @endif
                                </h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <form method="POST" action="/admin/edit-product">
                                @include('admin.inc.notification')
                                @csrf
                                <input type="hidden" name="product_id" id="" value="{{$product->id}}">
                                <div class="form-group">
                                    <label for="fname">Name:</label>
                                    <input type="text" class="form-control" id="fname" name="name" value="{{$product->name}}">
                                </div>
                                <div class="input-group mb-3">
                                    <label for="price" class="col-12 px-0">Price per unit:</label>
                                    <input type="text" id="price" class="form-control" value="{{$product->price}}" name="price" style="border-top-left-radius:10px;border-bottom-left-radius:10px;">
                                    <div class="input-group-append">
                                        <span class="input-group-text px-3" style="border-top-right-radius:9px;border-bottom-right-radius:9px;border:1px solid #d7dbda;background-color: #fff;">$</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name">Unit:</label>
                                    <select class="form-control" id="exampleFormControlSelect2" name="unit" style="background-color:transparent">
                                        <option value="kg" {{$product->unit == 'kg' ? 'selected' : ''}}>Kilogram</option>
                                        <option value="pd" {{$product->unit == 'pd' ? 'selected' : ''}}>Pounds</option>
                                        <option value="lit" {{$product->unit == 'lit' ? 'selected' : ''}}>Liter</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="image">Image:</label>
                                    <input type="file" name="image" class="form-control line-height-2">
                                </div>
                                <div class="form-group">
                                    <label for="name">Description:</label>
                                    <textarea class="form-control" id="name" name="description">{{$product->description}}</textarea>
                                </div>

                                <div class="checkbox mb-3">
                                    <label><input type="checkbox" name="set" {{$product->status == 0 ? 'checked':''}} > Out of stock</label>
                                </div>
                                <button type="submit" class="btn btn-primary px-5 py-2">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
