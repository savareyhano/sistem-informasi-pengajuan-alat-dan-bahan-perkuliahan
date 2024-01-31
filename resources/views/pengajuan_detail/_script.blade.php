<script>
    let table, alertUtil, formAdd, formEdit, formStatus, loadingBtn;

    function initBtnEvents() {
        $('#form-add input, #form-add select').keypress(function (e) {
            if (e.which === 13) {
                $('#btn-add-submit').click()
            }
        });

        $('#form-edit input, #form-edit select').keypress(function (e) {
            if (e.which === 13) {
                $('#btn-edit-submit').click()
            }
        });

        $('#form-status input, #form-edit select').keypress(function (e) {
            if (e.which === 13) {
                $('#btn-status-submit').click()
            }
        })
    }

    function initDTEvents() {
        $('.btn_edit').on('click', function () {
            const targetId = $(this).data('id');
            $.get('{{ route('pengajuan.detail.get', ['id' => $pengajuan]) }}', {id: targetId}, 'json')
                .done(function (response) {
                    if (response.status) {
                        formEdit.populateForm(response.data);
                        $('#modal-edit').modal('show');
                    } else {
                        alertUtil.showFailedAlert(response.message)
                    }
                })
                .fail(function (response) {
                    let errorMessage;
                    if (response.responseJSON.hasOwnProperty('errors')) {
                        if ((typeof response.responseJSON.errors === 'object' || typeof response.responseJSON.errors === 'function')) {
                            errorMessage = Object.values(response.responseJSON.errors).map(error => {
                                return error.join("<br>")
                            }).join("<br>");
                            alertUtil.showFailedAlert(errorMessage)
                        } else if (typeof response.responseJSON.errors === 'string') {
                            alertUtil.showFailedAlert(response.responseJSON.errors)
                        }
                    } else if (response.responseJSON.hasOwnProperty('message')) {
                        alertUtil.showFailedAlert(response.responseJSON.message);
                    } else {
                        alertUtil.showFailedAlert('Gagal menghubungkan ke server, silahkan coba kembali')
                    }
                });
        });

        $('.btn_delete').on('click', function () {
            Swal.fire({
                title: 'Apa Anda Yakin?',
                text: 'Anda tidak dapat mengembalikan data ini',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
            }).then(result => {
                if (result.value) {
                    const spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                    $(this).attr('disabled', true).html(spinner);

                    const targetId = $(this).data('id');
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route('pengajuan.detail.delete', ['id' => $pengajuan]) }}',
                        data: {id: targetId},
                        dataType: 'json'
                    }).done(function (response) {
                        if (response.status) {
                            table.ajax.reload();
                            alertUtil.showSuccessToast(response.message)
                        } else {
                            alertUtil.showFailedAlert(response.message)
                        }
                        $(this).attr('disabled', false).html('<i class="fas fa-trash-alt"></i>')
                    }).fail(function (response) {
                        let errorMessage;
                        if (response.responseJSON.hasOwnProperty('errors')) {
                            if ((typeof response.responseJSON.errors === 'object' || typeof response.responseJSON.errors === 'function')) {
                                errorMessage = Object.values(response.responseJSON.errors).map(error => {
                                    return error.join("<br>")
                                }).join("<br>");
                                alertUtil.showFailedAlert(errorMessage)
                            } else if (typeof response.responseJSON.errors === 'string') {
                                alertUtil.showFailedAlert(response.responseJSON.errors)
                            }
                        } else if (response.responseJSON.hasOwnProperty('message')) {
                            alertUtil.showFailedAlert(response.responseJSON.message);
                        } else {
                            alertUtil.showFailedAlert('Gagal menghubungkan ke server, silahkan coba kembali')
                        }
                        $(this).attr('disabled', false).html('<i class="fas fa-trash-alt"></i>')
                    });
                }
            })
        });

        $('.btn_change').on('click', function () {
            const targetId = $(this).data('id');
            $('#status-id').val(targetId);
            $('#modal-status').modal('show')
        })
    }

    function initLoadingBtn() {
        loadingBtn = {
            startAdd: () => {
                const spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                $('#btn-add-submit').attr('disabled', true).html(spinner + ' Loading...')
            },
            stopAdd: () => {
                $('#btn-add-submit').attr('disabled', false).text('Save')
            },
            startEdit: () => {
                const spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                $('#btn-edit-submit').attr('disabled', true).html(spinner + ' Loading...')
            },
            stopEdit: () => {
                $('#btn-edit-submit').attr('disabled', false).text('Save')
            },
            startStatus: () => {
                const spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                $('#btn-status-submit').attr('disabled', true).html(spinner + ' Loading...')
            },
            stopStatus: () => {
                $('#btn-status-submit').attr('disabled', false).text('Save')
            },
        }
    }

    function initAlert() {
        alertUtil = {
            showFailedAlert: (message) => {
                $('#failed-message').html(message);
                $('#failed-alert').show();
                $("html, body").animate({scrollTop: 0}, 600);
            },
            showFailedAlertAdd: (message) => {
                $('#failed-message-add').html(message);
                $('#failed-alert-add').show();
                $("#modal-add .modal-body").animate({scrollTop: 0}, 600);
            },
            showFailedAlertEdit: (message) => {
                $('#failed-message-edit').html(message);
                $('#failed-alert-edit').show();
                $("#modal-edit .modal-body").animate({scrollTop: 0}, 600);
            },
            showFailedAlertStatus: (message) => {
                $('#failed-message-status').html(message);
                $('#failed-alert-status').show();
                $("#modal-status .modal-body").animate({scrollTop: 0}, 600);
            },
            showSuccessToast: (message) => {
                Toast.fire({icon: 'success', title: message})
            }
        }
    }

    function initDataTable() {
        table = $('#detail-pengajuan-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            order: [[5, 'desc'], [0, 'desc']],
            ajax: '{{ route('pengajuan.detail.datatable', ['id' => $pengajuan]) }}',
            columns: [
                {data: 'nama_barang', responsivePriority: 0},
                {data: 'image_path'},
                {data: 'jumlah'},
                {data: 'harga_satuan'},
                {data: 'harga_total'},
                {data: 'keterangan', width: '150px'},
                {
                    data: 'created_at', responsivePriority: 1, render: function (data, type, row) {
                        if (type === 'display') {
                            date = new Date(data);
                            return date.toLocaleDateString()
                        }

                        return data
                    }
                },
                {data: 'action', width: '120px', responsivePriority: 2, orderable: false, searchable: false},
            ]
        });

        table.on('draw', function () {
            initDTEvents()
        })
    }

    function initCustomRule() {
        $.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        }, 'Only can alphanumeric');

        $.validator.addMethod("alphanumericspace", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
        }, 'Only can alphanumeric and space');
    }

    function initCreateForm() {
        const addForm = $('#form-add');

        const validator = addForm.validate({
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            errorPlacement: function (error, element) {
                if (element.attr('type') === 'file')
                    error.insertAfter(element.closest('.input-group'));
                else
                    error.insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid')
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid')
            },
            rules: {
                nama_barang: {
                    required: true,
                },
                gambar: {
                    required: true,
                    accept: 'image/*'
                },
                jumlah_barang: {
                    required: true,
                    digits: true,
                    min: 1,
                },
                harga_satuan: {
                    required: true,
                    digits: true,
                    min: 1
                },
            },
            invalidHandler: function () {
                $("#modal-add .modal-body").animate({scrollTop: 0}, 600);
            }
        });

        $('#btn-add-submit').click(function () {
            const isValid = addForm.valid();
            if (isValid) {
                loadingBtn.startAdd();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pengajuan.detail.store', ['id' => $pengajuan]) }}',
                    data: new FormData(addForm[0]),
                    dataType: 'json',
                    processData: false,
                    contentType: false
                }).done(function (response) {
                    loadingBtn.stopAdd();
                    if (response.status) {
                        $('#modal-add').modal('hide');
                        table.ajax.reload();
                        alertUtil.showSuccessToast(response.message)
                    } else {
                        alertUtil.showFailedAlertAdd(response.message)
                    }
                }).fail(function (response) {
                    loadingBtn.stopAdd();
                    let errorMessage;
                    if (response.responseJSON.hasOwnProperty('errors')) {
                        if ((typeof response.responseJSON.errors === 'object' || typeof response.responseJSON.errors === 'function')) {
                            errorMessage = Object.values(response.responseJSON.errors).map(error => {
                                return error.join("<br>")
                            }).join("<br>");
                            alertUtil.showFailedAlertAdd(errorMessage)
                        } else if (typeof response.responseJSON.errors === 'string') {
                            alertUtil.showFailedAlertAdd(response.responseJSON.errors)
                        }
                    } else if (response.responseJSON.hasOwnProperty('message')) {
                        alertUtil.showFailedAlertAdd(response.responseJSON.message);
                    } else {
                        alertUtil.showFailedAlertAdd('Gagal menghubungkan ke server, silahkan coba kembali')
                    }
                });
            }
        });

        const modalAdd = $('#modal-add');
        modalAdd.on('hide.bs.modal', function () {
            validator.resetForm();
            addForm.trigger('reset')
        });

        return {
            validator: validator
        }
    }

    function initEditForm() {
        const editForm = $('#form-edit');

        const validator = editForm.validate({
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            errorPlacement: function (error, element) {
                if (element.attr('type') === 'file')
                    error.insertAfter(element.closest('.input-group'));
                else
                    error.insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid')
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid')
            },
            rules: {
                id: {
                    required: true,
                    digits: true
                },
                nama_barang: {
                    required: true,
                },
                gambar: {
                    accept: 'image/*'
                },
                jumlah_barang: {
                    required: true,
                    digits: true,
                    min: 1,
                },
                harga_satuan: {
                    required: true,
                    digits: true,
                    min: 1
                },
            },
            invalidHandler: function () {
                $("#modal-edit .modal-body").animate({scrollTop: 0}, 600);
            }
        });

        $('#btn-edit-submit').click(function () {
            const isValid = editForm.valid();
            if (isValid) {
                loadingBtn.startEdit();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pengajuan.detail.update', ['id' => $pengajuan]) }}',
                    data: new FormData(editForm[0]),
                    dataType: 'json',
                    processData: false,
                    contentType: false
                }).done(function (response) {
                    loadingBtn.stopEdit();
                    if (response.status) {
                        $('#modal-edit').modal('hide');
                        table.ajax.reload();
                        alertUtil.showSuccessToast(response.message)
                    } else {
                        alertUtil.showFailedAlertEdit(response.message)
                    }
                }).fail(function (response) {
                    loadingBtn.stopEdit();
                    let errorMessage;
                    if (response.responseJSON.hasOwnProperty('errors')) {
                        if ((typeof response.responseJSON.errors === 'object' || typeof response.responseJSON.errors === 'function')) {
                            errorMessage = Object.values(response.responseJSON.errors).map(error => {
                                return error.join("<br>")
                            }).join("<br>");
                            alertUtil.showFailedAlertEdit(errorMessage)
                        } else if (typeof response.responseJSON.errors === 'string') {
                            alertUtil.showFailedAlertEdit(response.responseJSON.errors)
                        }
                    } else if (response.responseJSON.hasOwnProperty('message')) {
                        alertUtil.showFailedAlertEdit(response.responseJSON.message);
                    } else {
                        alertUtil.showFailedAlertEdit('Gagal menghubungkan ke server, silahkan coba kembali')
                    }
                });
            }
        });

        const modalEdit = $('#modal-edit');
        modalEdit.on('hide.bs.modal', function () {
            validator.resetForm();
            editForm.trigger('reset')
        });

        const populateForm = detailObject => {
            $('#edit-id').val(detailObject.id);
            $('#edit-nama-barang').val(detailObject.nama_barang);
            $('#edit-jumlah-barang').val(detailObject.jumlah);
            $('#edit-harga-satuan').val(detailObject.harga_satuan);
            $('#edit-keterangan').val(detailObject.keterangan);
        };

        return {
            validator: validator,
            populateForm: populateForm
        }
    }

    function initStatusForm() {
        const statusForm = $('#form-status');

        const validator = statusForm.validate({
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            highlight: function (element) {
                $(element).addClass('is-invalid')
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid')
            },
            rules: {
                id: {
                    required: true,
                    digits: true
                },
                status: {
                    required: true,
                    digits: true,
                },
                jumlah_barang: {
                    required: true,
                    digits: true
                },
                harga_satuan: {
                    required: true,
                    digits: true
                },
                invalidHandler: function () {
                    $("#modal-edit .modal-body").animate({scrollTop: 0}, 600);
                }
            }
        });

        $('#status-status').on('change', function () {
            $(this).valid();

            if ($(this).val() == 2) {
                const targetId = $('#status-id').val();
                $.get('{{ route('pengajuan.detail.get', ['id' => $pengajuan]) }}', {id: targetId}, 'json')
                    .done(function (response) {
                        if (response.status) {
                            $('#status-harga-satuan').val(response.data.harga_satuan).closest('.form-group').show();
                            $('#status-jumlah-barang').val(response.data.jumlah).closest('.form-group').show();
                            $('#status-keterangan').val(response.data.keterangan).closest('.form-group').show();
                        } else {
                            alertUtil.showFailedAlertStatus(response.message)
                        }
                    })
                    .fail(function (response) {
                        let errorMessage;
                        if (response.responseJSON.hasOwnProperty('errors')) {
                            if ((typeof response.responseJSON.errors === 'object' || typeof response.responseJSON.errors === 'function')) {
                                errorMessage = Object.values(response.responseJSON.errors).map(error => {
                                    return error.join("<br>")
                                }).join("<br>");
                                alertUtil.showFailedAlertStatus(errorMessage)
                            } else if (typeof response.responseJSON.errors === 'string') {
                                alertUtil.showFailedAlertStatus(response.responseJSON.errors)
                            }
                        } else if (response.responseJSON.hasOwnProperty('message')) {
                            alertUtil.showFailedAlertStatus(response.responseJSON.message);
                        } else {
                            alertUtil.showFailedAlertStatus('Gagal menghubungkan ke server, silahkan coba kembali')
                        }
                    });
            } else {
                const targetId = $('#status-id').val();
                $.get('{{ route('pengajuan.detail.get', ['id' => $pengajuan]) }}', {id: targetId}, 'json')
                    .done(function (response) {
                        if (response.status) {
                            $('#status-keterangan').val(response.data.keterangan).closest('.form-group').show();
                        } else {
                            alertUtil.showFailedAlertStatus(response.message)
                        }
                    })
                $('#status-harga-satuan').val(null).closest('.form-group').hide();
                $('#status-jumlah-barang').val(null).closest('.form-group').hide();
            }
        });

        $('#btn-status-submit').click(function () {
            const isValid = statusForm.valid();
            if (isValid) {
                loadingBtn.startStatus();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('pengajuan.detail.update_negotiation', ['id' => $pengajuan]) }}',
                    data: statusForm.serialize(),
                    dataType: 'json',
                }).done(function (response) {
                    loadingBtn.stopStatus();
                    if (response.status) {
                        $('#modal-status').modal('hide');
                        table.ajax.reload();
                        alertUtil.showSuccessToast(response.message)
                    } else {
                        alertUtil.showFailedAlertStatus(response.message)
                    }
                }).fail(function (response) {
                    loadingBtn.stopStatus();
                    let errorMessage;
                    if (response.responseJSON.hasOwnProperty('errors')) {
                        if ((typeof response.responseJSON.errors === 'object' || typeof response.responseJSON.errors === 'function')) {
                            errorMessage = Object.values(response.responseJSON.errors).map(error => {
                                return error.join("<br>")
                            }).join("<br>");
                            alertUtil.showFailedAlertStatus(errorMessage)
                        } else if (typeof response.responseJSON.errors === 'string') {
                            alertUtil.showFailedAlertStatus(response.responseJSON.errors)
                        }
                    } else if (response.responseJSON.hasOwnProperty('message')) {
                        alertUtil.showFailedAlertStatus(response.responseJSON.message);
                    } else {
                        alertUtil.showFailedAlertStatus('Gagal menghubungkan ke server, silahkan coba kembali')
                    }
                });
            }
        });

        const modalStatus = $('#modal-status');
        modalStatus.on('hide.bs.modal', function () {
            validator.resetForm();
            statusForm.trigger('reset')
        });

        modalStatus.on('hidden.bs.modal', function () {
            $('#status-status').val(null).trigger('change')
        });

        return {
            validator: validator
        }
    }

    function initAjaxToken() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    }

    $(document).ready(function () {
        initAjaxToken();
        initBtnEvents();
        initLoadingBtn();
        initAlert();
        initDataTable();
        initCustomRule();
        formAdd = initCreateForm();
        formEdit = initEditForm();
        formKaprodi = initStatusForm();

        @if(session()->has('status'))
            toast = {!! json_encode(session()->get('status')) !!};

        if (toast.status) {
            alertUtil.showSuccessToast(toast.message)
        } else {
            alertUtil.showFailedAlert(toast.message)
        }
        @endif
    })
</script>
