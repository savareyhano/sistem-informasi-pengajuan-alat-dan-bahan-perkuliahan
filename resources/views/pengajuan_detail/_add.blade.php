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

                <form id="form-add" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="add-nama-barang">Nama Barang</label>
                        <input type="text" name="nama_barang" id="add-nama-barang" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-gambar">Gambar</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="upload-gambar">Upload</span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="add-gambar"
                                       aria-describedby="upload-gambar" name="gambar" accept="image/jpeg, image/png">
                                <label class="custom-file-label" for="add-gambar">Choose file</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add-jumlah-barang">Jumlah Barang</label>
                        <input type="number" name="jumlah_barang" id="add-jumlah-barang" class="form-control" min="1">
                    </div>
                    <div class="form-group">
                        <label for="add-harga-satuan">Harga Satuan</label>
                        <input type="number" name="harga_satuan" id="add-harga-satuan" class="form-control" min="1">
                    </div>
                    <div class="form-group">
                        <label for="add-keterangan">Keterangan</label>
                        <textarea name="keterangan" id="add-keterangan" rows="3" class="form-control"></textarea>
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
