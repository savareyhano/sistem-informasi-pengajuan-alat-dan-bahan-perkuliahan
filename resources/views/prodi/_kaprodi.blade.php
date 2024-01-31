<div class="modal fade" id="modal-kaprodi" tabindex="-1" role="dialog" aria-labelledby="modal-kaprodi-label"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-kaprodi-label">Modal Kaprodi/Kajur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- failed-alert -->
                <div class="alert alert-danger alert-dismissible fade show" id="failed-alert-kaprodi" role="alert"
                     style="display: none">
                    <span id="failed-message-kaprodi"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- /.failed-alert -->

                <form id="form-kaprodi">
                    <input type="hidden" name="id" id="kaprodi-id">
                    <div class="form-group">
                        <label for="kaprodi-kaprodi">User</label>
                        <select name="user" id="kaprodi-user" class="form-control select2">
                            <option value=""></option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ ucwords($user->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-kaprodi-submit">Submit</button>
            </div>
        </div>
    </div>
</div>
