<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title" id="exampleModalLabel">Edit Doctor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="{{ url('admin/doctor/' . $docs->unique_id . '/update') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="id-field" />

                <div class="mb-3">
                    <label for="customername-field" class="form-label">Doctor Name</label>
                    <input type="text" id="customername-field" name="name" value="{{ $docs->name }}"
                        class="form-control" placeholder="Enter name" required />
                    @error('name')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="customername-field" class="form-label">Doctor Email</label>
                    <input type="email" name="email" id="customername-field" value="{{ $docs->email }}"
                        class="form-control" placeholder="Enter email" required />
                    @error('email')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Doctor Phone</label>
                    <input type="number" name="phone" id="customername-field" value="{{ $docs->phone }}"
                        class="form-control" placeholder="Enter Phone" required />
                    @error('phone')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Hospital</label>
                    <select class="form-control" data-trigger name="hospital" id="payment-field">
                        <option hidden value="{{ $docs->hospital->id }}">{{ $docs->hospital->name }}</option>
                        @foreach ($hos as $hospital)
                            <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                        @endforeach
                    </select>
                    @error('hospital')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Address</label>
                    <input type="text" name="address" id="customername-field" value="{{ $docs->address }}"
                        class="form-control" placeholder="Enter Address" required />
                    @error('address')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row gy-4 mb-3">
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">City</label>
                            <input type="text" name="city" id="amount-field" value="{{ $docs->city }}"
                                class="form-control" placeholder="City" required />
                            @error('city')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">State</label>
                            <input type="text" name="state" id="amount-field" value="{{ $docs->state }}"
                                class="form-control" placeholder="State" required />
                            @error('state')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="edit-btn">Update Doctor</button>
                </div>
            </div>
        </form>
    </div>
</div>
