<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header bg-light p-3">
            <h5 class="modal-title">Add Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
        </div>
        <form action="{{ url('admin/admin/add') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">

                <div class="mb-3">
                    <label for="customername-field" class="form-label">Name</label>
                    <input type="text" name="name" id="customername-field" class="form-control" placeholder="Enter Name" required />
                    @error('name')
                        <p class="text-danger">{{$message}}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="customername-field" class="form-label">Email</label>
                    <input type="email" name="email" id="customername-field" class="form-control" placeholder="Enter Email" required />
                    @error('email')
                        <p class="text-danger">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="customername-field" class="form-label">Position</label>
                    <select class="form-control" data-trigger name="position" id="payment-field" required>
                        <option value="">Select Position</option>
                        <option value="">Male</option>
                        <option value="">Female</option>
                    </select>
                    @error('gender')
                        <p class="text-danger">{{$message}}</p>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="add-btn">Save Admin</button>
                </div>
            </div>
        </form>
    </div>
</div>
