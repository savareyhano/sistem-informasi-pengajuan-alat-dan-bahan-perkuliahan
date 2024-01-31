<div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="modal-status-label"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-status-label">Modal Change Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- failed-alert -->
                <div class="alert alert-danger alert-dismissible fade show" id="failed-alert-status" role="alert"
                     style="display: none">
                    <span id="failed-message-status"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- /.failed-alert -->

                <form id="form-status">
                    <input type="hidden" name="id" id="status-id">
                    <div class="form-group">
                        <label for="status-status">Status</label>
                        <select name="status" id="status-status" class="custom-select">
                            <option value="" selected disabled>Please select status</option>
                            <option value="1">Diterima</option>
                            <option value="2">Dievaluasi</option>
                            <option value="3">Ditolak</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: none">
                        <label for="status-jumlah-barang">Jumlah Barang</label>
                        <input type="number" name="jumlah_barang" id="status-jumlah-barang" class="form-control">
                    </div>
                    <div class="form-group" style="display: none">
                        <label for="status-harga-satuan">Harga Satuan</label>
                        <input type="number" name="harga_satuan" id="status-harga-satuan" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status-keterangan">Keterangan</label>
                        <textarea name="keterangan" id="status-keterangan" rows="3" class="form-control"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-status-submit">Save</button>
            </div>
        </div>
    </div>
</div>
