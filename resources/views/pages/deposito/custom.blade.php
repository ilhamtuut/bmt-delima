@extends('layouts.app', ['page' => 'deposito', 'active' => 'customdeposito'])
@section('css')
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Deposito /</span> Buat Custom Deposito</h4>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card mb-4">
            <h4 class="card-header">Buat Custom Deposito</h4>
            <!-- Account -->
            <div class="card-body">
                @include('layouts.partials.alert')
                <form id="formDeposito" method="POST" action="{{ route('deposito.createCustom') }}">
                    @csrf
                    <div class="row mt-2 gy-4">
                        <div class="col-md-12 mt-0">
                            <div class="form-floating form-floating-outline">
                                <select id="account_number" name="account_number" class="select2 form-select">
                                    <option value="">Pilih Nomor Rekening</option>
                                    @foreach ($users as $value)
                                        <option value="{{ $value->id }}" data-name="{{ $value->name }}">{{ $value->account_number }}</option>
                                    @endforeach
                                </select>
                                <label for="account_number">Nomor Rekening</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="name" readonly />
                                <label for="name">Nama Lengkap</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control numbering" type="text" id="contract" name="contract" />
                                <label for="contract">Jangka Waktu (Bulan)</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control numbering" type="text" id="percent" name="percent" />
                                <label for="percent">Percent</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control numbering-dot" id="nominal" name="nominal" />
                                <label for="nominal">Nominal (Rp)</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="estimasi" readonly />
                                <label for="estimasi">Estimasi Perolehan (Rp)</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Daftar</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('js')
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

        $('#account_number').change(function(e) {
            var name = $(this).find("option:selected").data('name');
            $('#name').val(name);
        });

        $('#percent').keyup(function (e) {
            var amount = $('#nominal').val().replace(/[.]/g, '');
            var percent = $(this).val()/100;
            var estimasi = amount * percent;
            $('#estimasi').val(formatNumber(estimasi));
        });

        $('#nominal').keyup(function (e) {
            var amount = this.value.replace(/[.]/g, '');
            var percent = $("#percent").val()/100;
            var estimasi = amount * percent;
            $('#estimasi').val(formatNumber(estimasi));
        });

        $("input[name='deposito']").change(function(e){
            var amount = $('#nominal').val().replace(/[.]/g, '');
            var percent = $("#percent").val()/100;
            var estimasi = amount * percent;
            $('#estimasi').val(formatNumber(estimasi));
        });

        var validation = FormValidation.formValidation(document.getElementById('formDeposito'), {
            fields: {
                account_number: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan nomor rekening'
                        }
                    }
                },
                contract: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan jangka waktu'
                        }
                    }
                },
                percent: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan percent'
                        }
                    }
                },
                nominal: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan nominal deposito'
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
            $.blockUI({ message: null });
        });

        $('.btn-reset').click(function(e) {
            validation.resetForm();
        });
    </script>
@endsection
