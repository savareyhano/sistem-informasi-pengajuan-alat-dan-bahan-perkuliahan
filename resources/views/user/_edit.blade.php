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
                        <label for="edit-name">Name</label>
                        <input type="text" name="name" id="edit-name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-email">Email</label>
                        <input type="email" name="email" id="edit-email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-username">Username</label>
                        <input type="text" name="username" id="edit-username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-password">Password</label>
                        <input type="password" name="password" id="edit-password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-role">Role</label>
                        <select class="form-control select2" name="role" id="edit-role">
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
                <button type="button" class="btn btn-primary" id="btn-edit-submit">Save</button>
            </div>
        </div>
    </div>
</div>
