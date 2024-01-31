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
                        <label for="edit-tahun-akademik">Tahun Akademik</label>
                        <input type="text" name="tahun_akademik" id="edit-tahun-akademik" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-semester">Semester</label>
                        <select name="semester" id="edit-semester" class="custom-select">
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-prodi">Prodi</label>
                        <select name="prodi[]" id="edit-prodi" class="form-control select2" multiple>
                            @foreach($programStudies as $programStudy)
                                <option value="{{ $programStudy->id }}">{{ $programStudy->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-siswa">Siswa</label>
                        <input type="text" name="siswa" id="edit-siswa" class="form-control"
                               aria-describedby="siswaHelp">
                        <small id="siswaHelp" class="form-text text-muted">siswa prodi 1, siswa prodi 2, dst</small>
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
