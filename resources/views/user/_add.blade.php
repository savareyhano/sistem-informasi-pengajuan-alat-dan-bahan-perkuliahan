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
                        <label for="add-name">Name</label>
                        <input type="text" name="name" id="add-name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-email">Email</label>
                        <input type="email" name="email" id="add-email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-username">Username</label>
                        <input type="text" name="username" id="add-username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-password">Password</label>
                        <input type="password" name="password" id="add-password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-role">Role</label>
                        <select class="form-control select2" name="role" id="add-role">
                            <option value=""></option>
                            <option value="prodi">Prodi</option>
                            <option value="wakil_direktur">Wakil Direktur</option>
                            <option value="tata_usaha">Tata Usaha</option>
                        </select>
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
