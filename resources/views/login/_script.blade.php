<script>
    let alertUtil, loadingBtn, loginForm;

    function initBtnEvents() {
        $('form input').keypress(function (e) {
            if (e.which === 13) {
                $('#btn-signin').click()
            }
        })
    }

    function initAlert() {
        alertUtil = {
            showFailedAlert: (message) => {
                $('#failed-message').html(message);
                $('#failed-alert').show();
                $("html, body").animate({scrollTop: 0}, 600);
            }
        }
    }

    function initLoadingBtn() {
        loadingBtn = {
            start: () => {
                const spinner = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                $('#btn-signin').attr('disabled', true).html(spinner + ' Loading...')
            },
            stop: () => {
                $('#btn-signin').attr('disabled', false).text('Sign In')
            }
        }
    }

    function initCustomRules() {
        $.validator.addMethod('alphanumeric', function (value) {
            return /^[a-zA-Z0-9]+$/i.test(value)
        }, 'Only can alphabetic and number')
    }

    function initLoginForm() {
        const validator = $('#login-form').validate({
            // validClass: 'is-valid',
            errorClass: 'invalid-feedback',
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.insertAfter(element.next());
            },
            highlight: function (element) {
                $(element).addClass('is-invalid')
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid')
            },
            rules: {
                username: {
                    required: true,
                    alphanumeric: true
                },
                password: {
                    required: true
                },
            },
            invalidHandler: function () {
                $("html, body").animate({scrollTop: 0}, 600);
            }
        });

        $('#btn-signin').click(function () {
            const loginForm = $('#login-form');
            const isValid = loginForm.valid();
            if (isValid) {
                loadingBtn.start();
                $.post('{{ route('login.process') }}', loginForm.serialize(), 'json')
                    .done(function (response) {
                        if (response.status) {
                            window.location.href = response.redirect;
                        }
                    })
                    .fail(function (response) {
                        loadingBtn.stop();
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

                        loginForm.trigger('reset');
                        loginForm.find('input[name=username]').focus();
                        validator.resetForm()
                    })
            }
        });

        return {
            validator: validator
        }
    }

    $(document).ready(function () {
        initBtnEvents();
        initAlert();
        initLoadingBtn();
        initCustomRules();
        loginForm = initLoginForm()
    })
</script>
