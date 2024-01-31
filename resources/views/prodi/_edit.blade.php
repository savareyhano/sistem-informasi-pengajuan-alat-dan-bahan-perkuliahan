<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit-label"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-edit-label">Modal Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- failed-alert -->
                <div class="alert alert-danger alert-dismissible fade show" id="failed-alert-edit" role="alert"
                     style="display: none">
                    <span id="failed-message-edit"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- /.failed-alert -->

                <form id="form-edit">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label for="edit-name">Kode Prodi</label>
                        <input type="text" name="kode_prodi" id="edit-kode-prodi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-email">Nama Prodi</label>
                        <input type="text" name="nama_prodi" id="edit-nama-prodi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-pagu">Pagu Per Mahasiswa</label>
                        <input type="number" name="pagu" id="edit-pagu" class="form-control" min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-edit-submit">Save</button>
            </div>
        </div>
    </div>
</div>
