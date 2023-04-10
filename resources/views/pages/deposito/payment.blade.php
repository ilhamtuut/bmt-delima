@extends('layouts.app', ['page' => 'deposito', 'active' => 'psyment'])
@section('css')
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Deposito /</span> Pembayaran</h4>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-6 col-lg-6 col-md-6">
            @include('layouts.partials.alert')
            <div class="card">
                <div class="card-body text-center">
                    <div class="mx-auto mb-4">
                        <img src="{{ asset('assets//img/icons/unicons/wallet-info.png') }}" alt="Avatar Image" class="rounded-circle w-px-100">
                    </div>
                    <h5 class="mb-1 card-title">{{ $deposito->type->name }}</h5>
                    <p class="text-primary mb-3">{{ $deposito->trxid }}</p>
                    <div class="d-flex align-items-center justify-content-around mb-4">
                        <div>
                            <h4 class="mb-3">Rp{{ number_format($deposito->amount + $deposito->code,0,',','.') }}</h4>
                            <p class="mb-2"><span class="badge bg-label-{{ $deposito->label }} me-1 cursor-pointer">{{ $deposito->status_text }}</span></p>
                            <p class="mb-1"><b>Akun Pembayaran</b></p>
                            <p class="mb-1">{{ $deposito->payment->bank_name }}</p>
                            <p class="mb-1">{{ $deposito->payment->bank_account_name }}</p>
                            <p class="mb-1">{{ $deposito->payment->bank_account_number }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        @if ($deposito->status == 0)
                            <a href="javascript:;"
                            class="btn btn-primary d-flex align-items-center me-3 waves-effect waves-light showModal"><i
                            class="mdi mdi-credit-card-check-outline me-1"></i>Konfirmasi Pembayaran</a>
                        @else
                            <a href="{{ route('deposito.index') }}"
                            class="btn btn-primary d-flex align-items-center me-3 waves-effect waves-light"><i
                            class="mdi mdi-history me-1"></i>Deposito</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-md-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2 pb-1"> Konfirmasi Pembayaran</h3>
                    </div>
                    <form id="addNewForm" class="row g-4" action="{{ route('deposito.confirm_payment',$deposito->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control numbering-dot" type="text" id="nominal" name="nominal" autofocus />
                                <label for="nominal">Jumlah Pembayaran</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="file" name="file" id="file" accept="image/png, image/gif, image/jpeg" />
                                <label for="file">Bukti Pembayaran</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Kirim</button>
                            <button type="reset" class="btn btn-outline-secondary btn-reset" data-bs-dismiss="modal"
                                aria-label="Close">
                                Keluar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $('.showModal').click(function(e) {
            new bootstrap.Modal(document.getElementById('addNewModal')).show();
        })

        var validation = FormValidation.formValidation(document.getElementById('addNewForm'), {
            fields: {
                nominal: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan jumlah pembayaran'
                        }
                    }
                },
                file: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan bukti pembayran'
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
                    rowSelector: '.col-12'
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
