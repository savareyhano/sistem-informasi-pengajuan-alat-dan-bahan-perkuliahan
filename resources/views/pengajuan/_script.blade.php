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
        })
    }

    function initDTEvents() {
        $('.btn_edit').on('click', function () {
            const targetId = $(this).data('id');
            $.get('{{ route('pengajuan.get') }}', {id: targetId}, 'json')
                .done(function (response) {
                    if (response.status) {
                        formEdit.populateForm(response.pengajuan);
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
                        url: '{{ route('pengajuan.delete') }}',
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
            showSuccessToast: (message) => {
                Toast.fire({icon: 'success', title: message})
            }
        }
    }

    function initDataTable() {
        table = $('#pengajuan-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            searching: false,
            order: [[5, 'desc'], [0, 'desc']],
            ajax: {
                url: '{{ route('pengajuan.datatable') }}',
                data: function (d) {
                    d.tahunAkademik = $('#filter-tahun-akademik').val();
                    d.semester = $('#filter-semester').val();
                    d.prodi = $('#filter-prodi').val();
                    d.status = $('#filter-status').val()
                }
            },
            columns: [
                {data: 'tahun_akademik', responsivePriority: 0},
                {data: 'semester', responsivePriority: 1},
                {data: 'pagu'},
                {data: 'program_studies'},
                {data: 'status'},
                {
                    data: 'created_at', responsivePriority: 2, render: function (data, type, row) {
                        if (type === 'display') {
                            date = new Date(data);
                            return date.toLocaleDateString()
                        }

                        return data
                    }
                },
                {data: 'action', width: '120px', responsivePriority: 3, orderable: false, searchable: false},
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
                if (element.hasClass('select2'))
                    error.insertAfter(element.next('span'));
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
                tahun_akademik: {
                    required: true,
                    minlength: 9,
                    maxlength: 9,
                },
                semester: {
                    required: true
                },
                'prodi[]': {
                    required: true,
                },
                siswa: {
                    required: true,
                    pattern: /^[0-9,]*$/
                }
            },
            invalidHandler: function () {
                $("#modal-add .modal-body").animate({scrollTop: 0}, 600);
            }
        });

        $('#add-prodi').select2({
            theme: 'bootstrap4',
            width: '100% !important',
            placeholder: 'Please select one or more prodi'
        }).on('change', function () {
            $(this).valid()
        });

        Inputmask({"mask": "2099/2099", "clearMaskOnLostFocus": false}).mask('#add-tahun-akademik');

        $('#btn-add-submit').click(function () {
            const isValid = addForm.valid();
            if (isValid) {
                loadingBtn.startAdd();
                $.post('{{ route('pengajuan.store') }}', addForm.serialize(), 'json')
                    .done(function (response) {
                        loadingBtn.stopAdd();
                        if (response.status) {
                            $('#modal-add').modal('hide');
                            table.ajax.reload();
                            alertUtil.showSuccessToast(response.message);
                            window.location.href = response.redirect
                        } else {
                            alertUtil.showFailedAlertAdd(response.message)
                        }
                    })
                    .fail(function (response) {
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

        addForm.trigger('reset');

        modalAdd.on('hidden.bs.modal', function () {
            $('#add-prodi').val(null).trigger('change')
        });

        return {
            validator: validator,
        }
    }

    function initEditForm() {
        const editForm = $('#form-edit');

        const validator = editForm.validate({
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            errorPlacement: function (error, element) {
                if (element.hasClass('select2'))
                    error.insertAfter(element.next('span'));
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
                tahun_akademik: {
                    required: true,
                    minlength: 9,
                    maxlength: 9,
                },
                semester: {
                    required: true
                },
                'prodi[]': {
                    required: true,
                },
                siswa: {
                    required: true,
                    pattern: /^[0-9,]*$/
                }
            },
            invalidHandler: function () {
                $("#modal-edit .modal-body").animate({scrollTop: 0}, 600);
            }
        });

        $('#edit-prodi').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Please select one or more prodi'
        }).on('change', function () {
            $(this).valid()
        });

        Inputmask({"mask": "2099/2099", "clearMaskOnLostFocus": false}).mask('#edit-tahun-akademik');

        $('#btn-edit-submit').click(function () {
            const isValid = editForm.valid();
            if (isValid) {
                loadingBtn.startEdit();
                $.ajax({
                    type: 'PUT',
                    url: '{{ route('pengajuan.update') }}',
                    data: editForm.serialize(),
                    dataType: 'json'
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

        modalEdit.on('hidden.bs.modal', function () {
            $('#edit-prodi').val(null).trigger('change')
        });

        const populateForm = pengajuaObject => {
            $('#edit-id').val(pengajuaObject.id);
            $('#edit-tahun-akademik').val(pengajuaObject.tahun_akademik);
            $('#edit-semester').val(pengajuaObject.semester);
            let programStudies = [];
            let siswa = [];
            pengajuaObject.program_studies.forEach(programStudy => {
                programStudies.push(programStudy.id);
                siswa.push(programStudy.pivot.siswa)
            });
            $('#edit-prodi').val(programStudies).trigger('change');
            $('#edit-siswa').val(siswa.join(','))
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

    function initFilter() {
        $('#filter-status, #filter-prodi, #filter-semester, #filter-tahun-akademik').change(function () {
            table.ajax.reload();
        });

        $('#filter-tahun-akademik').select2({
            theme: 'bootstrap4',
            width: '100% !important',
            placeholder: 'All Tahun Akademik'
        });

        $('#filter-semester').select2({
            theme: 'bootstrap4',
            width: '100% !important',
            placeholder: 'All Semester'
        });

        $('#filter-prodi').select2({
            theme: 'bootstrap4',
            width: '100% !important',
            placeholder: 'All Prodi'
        });

        $('#filter-status').select2({
            theme: 'bootstrap4',
            width: '100% !important',
            placeholder: 'All Status'
        });
    }

    $(document).ready(function () {
        initFilter();
        initAjaxToken();
        initBtnEvents();
        initLoadingBtn();
        initAlert();
        initDataTable();
        initCustomRule();
        formAdd = initCreateForm();
        formEdit = initEditForm()
    })
</script>
