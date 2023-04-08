@extends('layouts.app', ['page' => 'transfer'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Transfer Dana</h4>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card mb-4">
            <h4 class="card-header">Transfer Dana</h4>
            <!-- Account -->
            <div class="card-body">
                @include('layouts.partials.alert')
                <form id="formDeposito" method="POST" action="{{ route('transfer.send') }}">
                    @csrf
                    <div class="row mt-2 gy-4">
                        <div class="col-md-12 mt-0">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="rekening" name="rekening" maxlength="11" />
                                <label for="rekening">Rekening Tujuan</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control numbering-dot" id="name" readonly />
                                <label for="name">Nama Penerima</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control numbering-dot" id="nominal" name="nominal" />
                                <label for="nominal">Nominal (Rp)</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('customjs')
    <script>
        window.Helpers.initCustomOptionCheck();

        var select2 = $('.select2');
        if (select2.length) {
            select2.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>');
                $this.select2({
                    dropdownParent: $this.parent()
                });
            });
        }

        $('#rekening').change(function(e) {
            is_valid(this.value);
        });

        var validation = FormValidation.formValidation(document.getElementById('formDeposito'), {
            fields: {
                rekening: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan nomor rekening'
                        }
                    }
                },
                nominal: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan nominal transfer'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    // Use this for enabling/changing valid/invalid class
                    // eleInvalidClass: '',
                    eleValidClass: '',
                    rowSelector: '.col-md-12'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            }
        });

        validation.on('core.form.valid', function() {
            $.blockUI({
                message: null
            });
        });

        $('.btn-reset').click(function(e) {
            validation.resetForm();
        });

        function is_valid(account) {
            $('#name').val('');
            $.blockUI({
                message: null
            });
            $.ajax({
                url: "{{ route('transfer.valid_account') }}",
                type: 'GET',
                data: {
                    account: account
                },
                success: function(res) {
                    $.unblockUI();
                    if (res.success) {
                        $('#name').val(res.data.name);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500,
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect waves-light'
                            },
                            buttonsStyling: false
                        });
                    }
                }
            });
        }
    </script>
@endsection
