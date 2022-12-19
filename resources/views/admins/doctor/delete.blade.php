<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-body p-5 text-center">
            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
            </lord-icon>
            <div class="mt-4 text-center">
                <h4>You are about to delete Dr. {{ $docs->name }}?</h4>
                <p class="text-muted fs-15 mb-4">Deleting the doctor will remove
                    all their information from the database.
                </p>
                <div class="hstack gap-2 justify-content-center remove">
                    <button class="btn btn-link link-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1 align-middle"></i>Close
                    </button>
                    <a href="{{ url('admin/doctor/' . $docs->unique_id . '/delete') }}" class="btn btn-danger">Yes, Delete It</a>
                </div>
            </div>
        </div>
    </div>
</div>
