<script>
    let table, alertUtil, formAdd, formEdit, loadingBtn;

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
    }

    function initDTEvents() {
        $('.btn_edit').on('click', function () {
            const targetId = $(this).data('id');
            $.get('{{ route('realisasi.detail.get', ['id' => $pengajuan]) }}', {id: targetId}, 'json')
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
                        url: '{{ route('realisasi.detail.delete', ['id' => $pengajuan]) }}',
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
                        $(this).attr('disabled', false).html('<i class="fas fa-trash-alt"></i>');
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
            ajax: '{{ route('realisasi.detail.datatable', ['id' => $pengajuan]) }}',
            columns: [
                {data: 'nama_barang', responsivePriority: 0},
                {data: 'image_path'},
                {data: 'pengajuan_jumlah', responsivePriority: 3},
                {data: 'jumlah', responsivePriority: 4},
                {data: 'pengajuan_harga_total', responsivePriority: 5},
                {data: 'harga_total', responsivePriority: 6},
                {data: 'realization', orderable: false, searchable: false, responsivePriority: 2},
                {data: 'keterangan', width: '150px'},
                {data: 'action', width: '120px', responsivePriority: 1, orderable: false, searchable: false},
            ]
        });

        table.on('draw', function () {
            initDTEvents()
        })
    }

    function convertToRupiah(angka) {
        var rupiah = '';
        var angkarev = angka.toString().split('').reverse().join('');
        for (var i = 0; i < angkarev.length; i++) if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
        return 'Rp ' + rupiah.split('', rupiah.length - 1).reverse().join('') + ',00';
    }

    function initCustomRule() {
        $.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        }, 'Only can alphanumeric');

        $.validator.addMethod("alphanumericspace", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
        }, 'Only can alphanumeric and space');

        $.validator.addMethod("add_required_if_new", function (value, element) {
            return !($('.add_type:checked').val() === 'new' && value === '')
        }, 'Required for new item');

        $.validator.addMethod("edit_required_if_new", function (value, element) {
            return !($('.edit_type:checked').val() === 'new' && value === '')
        }, 'Required for new item');

        $.validator.addMethod("add_required_if_existing", function (value, element) {
            return !($('.add_type:checked').val() === 'existing' && value === '')
        }, 'Required for existing item');

        $.validator.addMethod("edit_required_if_existing", function (value, element) {
            return !($('.edit_type:checked').val() === 'existing' && value === '')
        }, 'Required for existing item');
    }

    function initCreateForm() {
        $('.add_type').on('change', function () {
            if ($(this).val() == 'existing') {
                $('#add-barang-container').show().find('select').val(null).trigger('change');
                $('#add-nama-barang-container').hide().find('input').val(null);
                $('#add-gambar-container').hide().find('input').val(null);
                $('#add-jumlah-barang').val(null);
                $('#add-harga-satuan').val(null);
                $('#add-keterangan').val(null)
            } else {
                $('#add-barang-container').hide().find('select').val(null).trigger('change');
                $('#add-nama-barang-container').show().find('input').val(null);
                $('#add-gambar-container').show().find('input').val(null);
                $('#add-jumlah-barang').val(null);
                $('#add-harga-satuan').val(null);
                $('#add-keterangan').val(null)
            }
        });

        const addForm = $('#form-add');

        const validator = addForm.validate({
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            errorPlacement: function (error, element) {
                if (element.attr('type') === 'file')
                    error.insertAfter(element.closest('.input-group'));
                else if (element.hasClass('select2'))
                    error.insertAfter(element.next('span'));
                else if (element.hasClass('add_type'))
                    element.closest('.form-group').append(error);
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
                type: {
                    required: true,
                },
                barang: {
                    add_required_if_existing: true
                },
                nama_barang: {
                    add_required_if_new: true
                },
                gambar: {
                    add_required_if_new: true,
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
                    url: '{{ route('realisasi.detail.store', ['id' => $pengajuan]) }}',
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

        $('#add-barang').select2({
            ajax: {
                url: '{{ route('realisasi.detail.get_barang', ['id' => $pengajuan]) }}',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term
                    };

                    return query;
                }
            },
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'select one item'
        }).on('change', function () {
            let targetId = $(this).val();
            if (targetId) {
                $.get('{{ route('realisasi.detail.get_item', ['id' => $pengajuan]) }}', {id: $(this).val()}, 'json')
                    .done(function (response) {
                        if (response.status) {
                            $('#add-jumlah-barang').val(response.data.jumlah);
                            $('#add-harga-satuan').val(response.data.harga_satuan);
                            $('#add-keterangan').val(response.data.keterangan);
                        } else {
                            alertUtil.showFailedAlertAdd(response.message)
                        }
                    })
                    .fail(function (response) {
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
            $(this).valid()
        });

        const modalAdd = $('#modal-add');
        modalAdd.on('hide.bs.modal', function () {
            validator.resetForm();
            addForm.trigger('reset')
        });

        modalAdd.on('hidden.bs.modal', function () {
            $('#add-barang').val(null).trigger('change')
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
                    url: '{{ route('realisasi.detail.update', ['id' => $pengajuan]) }}',
                    data: editForm.serialize(),
                    dataType: 'json',
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
            $('#edit-jumlah-barang').val(detailObject.jumlah);
            $('#edit-harga-satuan').val(detailObject.harga_satuan);
            $('#edit-keterangan').val(detailObject.keterangan);
        };

        return {
            validator: validator,
            populateForm: populateForm
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
