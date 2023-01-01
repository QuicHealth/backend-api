<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title" id="exampleModalLabel">Approve Hospital</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="{{ url('admin/hospital/' . $hospital->unique_id . '/approve') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="id-field" />
                <div class="flex-shrink-0 avatar-md mx-auto">
                    <div class="avatar-title bg-light rounded">
                        <img src="{{ $hospital->image }}" alt="" height="50">
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <h5 class="mb-1">{{ $hospital->name }}</h5>
                    <p class="text-muted">Added {{ \Carbon\Carbon::parse($hospital->created_at)->diffForhumans() }}
                    </p>
                </div>

                <div class="mb-3">
                    <label for="customername-field" class="form-label">Hospital Name</label>
                    <input type="text" id="customername-field" name="name" value="{{ $hospital->name }}"
                        class="form-control" placeholder="Enter name" disabled />

                </div>

                <div class="mb-3">
                    <label for="customername-field" class="form-label">Hospital Email</label>
                    <input type="email" name="email" id="customername-field" value="{{ $hospital->email }}"
                        class="form-control" placeholder="Enter email" disabled />

                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Hospital Phone</label>
                    <input type="number" name="phone" id="customername-field" value="{{ $hospital->phone }}"
                        class="form-control" placeholder="Enter Phone" disabled />
                    @error('phone')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Address</label>
                    <input type="text" name="address" id="customername-field" value="{{ $hospital->address }}"
                        class="form-control" placeholder="Enter Address" disabled />

                </div>

                <div class="row gy-4 mb-3">
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">City</label>
                            <input type="text" name="city" id="amount-field" value="{{ $hospital->city }}"
                                class="form-control" placeholder="City" disabled />

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">State</label>
                            <input type="text" name="state" id="amount-field" value="{{ $hospital->state }}"
                                class="form-control" placeholder="State" disabled />

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">Country</label>
                            <input type="text" name="country" id="amount-field" value="{{ $hospital->country }}"
                                class="form-control" placeholder="Country" readonly disabled />

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="edit-btn">Approve Hospital</button>
                </div>
            </div>
        </form>
    </div>
</div>
