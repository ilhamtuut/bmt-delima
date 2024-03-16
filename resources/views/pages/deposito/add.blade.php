@extends('layouts.app', ['page' => 'deposito', 'active' => 'adddeposito'])
@section('css')
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Deposito /</span> Buat Deposito</h4>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card mb-4">
            <h4 class="card-header">Buat Deposito</h4>
            <!-- Account -->
            <div class="card-body">
                @include('layouts.partials.alert')
                <form id="formDeposito" method="POST" action="{{ route('deposito.create') }}">
                    @csrf
                    <div class="row mt-2 gy-4">
                        <div class="col-md-12 mt-0">
                            <div class="form-floating form-floating-outline">
                                <select id="account_number" name="account_number" class="select2 form-select">
                                    <option value="">Pilih Nomor Rekening</option>
                                    @foreach ($users as $value)
                                        <option value="{{ $value->id }}" data-name="{{ $value->name.' '.$value->last_name }}">{{ $value->account_number.' - '.$value->username.' - '.$value->name.' '.$value->last_name }}</option>
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
                            <div class="row">
                                @foreach ($deposito as $key => $value)
                                    <div class="col-md mb-md-0 mb-3">
                                        <div class="form-check custom-option custom-option-basic" style="min-height: 5.378125rem;">
                                            <label class="form-check-label custom-option-content"
                                                for="deposito-{{ $value->id }}">
                                                <input name="deposito" class="form-check-input" type="radio"
                                                    value="{{ $value->id }}" id="deposito-{{ $value->id }}"
                                                    {{ $key == 0 ? 'checked' : '' }} data-percent="{{ $value->percent }}">
                                                <span class="custom-option-header">
                                                    <span class="h6 mb-0">{{ $value->name }}</span>
                                                    @if ($value->id == 3)
                                                        <span style="font-size: large; font-weight:700;" class="text-warning">VIP</span>
                                                    @endif
                                                </span>
                                                <span class="custom-option-body">
                                                    <small class="text-primary">Minimal Rp{{ number_format($value->minimal,0,',','.') }}</small>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
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

        $('#nominal').keyup(function (e) {
            var amount = this.value.replace(/[.]/g, '');
            var percent = $("input[name='deposito']:checked").data('percent');
            var estimasi = amount * percent;
            $('#estimasi').val(formatNumber(estimasi));
        });

        $("input[name='deposito']").change(function(e){
            var amount = $('#nominal').val().replace(/[.]/g, '');
            var percent = $("input[name='deposito']:checked").data('percent');
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
                deposito: {
                    validators: {
                        notEmpty: {
                            message: 'Pilih paket deposito'
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
