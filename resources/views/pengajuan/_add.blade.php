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
                        <label for="add-tahun-akademik">Tahun Akademik</label>
                        <input type="text" name="tahun_akademik" id="add-tahun-akademik" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-semester">Semester</label>
                        <select name="semester" id="add-semester" class="custom-select">
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add-prodi">Prodi</label>
                        <select name="prodi[]" id="add-prodi" class="form-control select2" multiple>
                            @foreach($programStudies as $programStudy)
                                <option value="{{ $programStudy->id }}">{{ $programStudy->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add-siswa">Siswa</label>
                        <input type="text" name="siswa" id="add-siswa" class="form-control"
                               aria-describedby="siswaHelp">
                        <small id="siswaHelp" class="form-text text-muted">Siswa dari masing - masing prodi pisahkan dengan tanda koma (,)</small>
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
