<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="{{ url('admin/user/' . $user->unique_id . '/update') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="id-field" />

                <div class="row gy-4 mb-3">
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">First Name</label>
                            <input type="text" name="firstname" value="{{ $user->firstname }}" id="amount-field"
                                class="form-control" placeholder="First Name" required />
                            @error('firstname')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">Last Name</label>
                            <input type="text" name="lastname" value="{{ $user->lastname }}" id="amount-field"
                                class="form-control" placeholder="Last Name" required />
                            @error('lastname')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="customername-field" class="form-label">Email</label>
                    <input type="email" name="email" id="customername-field" value="{{ $user->email }}"
                        class="form-control" placeholder="Enter email" required />
                    @error('email')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Phone</label>
                    <input type="number" name="phone" id="customername-field" value="{{ $user->phone }}"
                        class="form-control" placeholder="Enter Phone" required />
                    @error('phone')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Gender</label>
                    <select class="form-control" data-trigger name="gender" id="payment-field" required>
                        <option hidden value="{{ $user->gender }}">{{ $user->gender }}</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    @error('gender')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Address</label>
                    <input type="text" name="address" id="customername-field" value="{{ $user->address }}"
                        class="form-control" placeholder="Enter Address" required />
                    @error('address')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row gy-4 mb-3">
                    <div class="col-md-6">
                        <div>
                            <label for="amount-field" class="form-label">City</label>
                            <input type="text" name="city" id="amount-field" value="{{ $user->city }}"
                                class="form-control" placeholder="City" required />
                            @error('city')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="edit-btn">Update User</button>
                </div>
            </div>
        </form>
    </div>
</div>
