<script>
    let alertUtil, loadingBtn, formUpdatePassword;

    function initBtnEvents() {
        $('#form-update-password input').keypress(function (e) {
            if (e.which === 13) {
                $('#btn-update-password').click()
            }
        });
    }

    function initLoadingBtn() {
        loadingBtn = {
            startUpdatePassword: () => {
                const spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                $('#btn-update-password').attr('disabled', true).html(spinner + ' Loading...')
            },
            stopUpdatePassword: () => {
                $('#btn-update-password').attr('disabled', false).text('Save')
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
            showSuccessToast: (message) => {
                Toast.fire({icon: 'success', title: message})
            }
        }
    }

    function initCustomRule() {
        $.validator.addMethod("alphanumericspace", function (value, element) {
            return this.optional(element) || /^[a-zA-Z0-9\s]+$/.test(value);
        }, 'Only can alphabetic and space');
    }

    function initUpdatePasswordForm() {
        const updatePasswordForm = $('#form-update-password');

        const validator = updatePasswordForm.validate({
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            highlight: function (element) {
                $(element).addClass('is-invalid')
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid')
            },
            rules: {
                new_password: {
                    required: true,
                    alphanumericspace: true,
                    minlength: 8,
                    maxlength: 32
                },
                old_password: {
                    required: true
                },
                new_password_confirmation: {
                    required: true,
                    equalTo: '#new-password'
                }
            },
            invalidHandler: function () {
                $("body #form-update-password").animate({scrollTop: 0}, 600);
            }
        });

        $('#btn-update-password').click(function () {
            $.post('{{ route('option.updatePassword') }}', updatePasswordForm.serialize(), 'json')
                .done(function (response) {
                    loadingBtn.stopUpdatePassword();
                    if (response.status) {
                        validator.resetForm();
                        updatePasswordForm.trigger('reset');
                        alertUtil.showSuccessToast(response.message)
                    } else {
                        alertUtil.showFailedAlert(response.message)
                    }
                })
                .fail(function (response) {
                    loadingBtn.stopUpdatePassword();
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
        initBtnEvents();
        initLoadingBtn();
        initAlert();
        initCustomRule();
        initAjaxToken();
        formUpdatePassword = initUpdatePasswordForm()
    })
</script>
