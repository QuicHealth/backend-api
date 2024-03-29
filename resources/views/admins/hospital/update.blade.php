<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title" id="exampleModalLabel">Edit Hospital</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="{{ url('admin/hospital/' . $hospital->unique_id . '/update') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="id-field" />

                <div class="mb-3">
                    <label for="customername-field" class="form-label">Hospital Name</label>
                    <input type="text" id="customername-field" name="name" value="{{ $hospital->name }}"
                        class="form-control" placeholder="Enter name" required />
                    @error('name')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="customername-field" class="form-label">Hospital Email</label>
                    <input type="email" name="email" id="customername-field" value="{{ $hospital->email }}"
                        class="form-control" placeholder="Enter email" required />
                    @error('email')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Hospital Phone</label>
                    <input type="number" name="phone" id="customername-field" value="{{ $hospital->phone }}"
                        class="form-control" placeholder="Enter Phone" required />
                    @error('phone')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Address</label>
                    <input type="text" name="address" id="customername-field" value="{{ $hospital->address }}"
                        class="form-control" placeholder="Enter Address" required />
                    @error('address')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row gy-4 mb-3">
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">City</label>
                            <input type="text" name="city" id="amount-field" value="{{ $hospital->city }}"
                                class="form-control" placeholder="City" required />
                            @error('city')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">State</label>
                            <input type="text" name="state" id="amount-field" value="{{ $hospital->state }}"
                                class="form-control" placeholder="State" required />
                            @error('state')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">Country</label>
                            <input type="text" name="country" id="amount-field" value="{{ $hospital->country }}"
                                class="form-control" placeholder="Country" readonly required />
                            @error('country')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="edit-btn">Update Hospital</button>
                </div>
            </div>
        </form>
    </div>
</div>
