<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-add-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-add-label">Modal Tambah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- failed-alert -->
                <div class="alert alert-danger alert-dismissible fade show" id="failed-alert-add" role="alert"
                     style="display: none">
                    <span id="failed-message-add"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- /.failed-alert -->

                <form id="form-add">
                    <div class="form-group">
                        <label for="add-name">Kode Prodi</label>
                        <input type="text" name="kode_prodi" id="add-kode-prodi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-email">Nama Prodi</label>
                        <input type="text" name="nama_prodi" id="add-nama-prodi" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-pagu">Pagu Per Mahasiswa</label>
                        <input type="number" name="pagu" id="add-pagu" class="form-control" value="300000" min="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-submit">Save</button>
            </div>
        </div>
    </div>
</div>
