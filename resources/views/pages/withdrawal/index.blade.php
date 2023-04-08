@extends('layouts.app',['page'=>'withdrawal'])
@section('css')

@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Penarikan</h4>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card mb-4">
            <h4 class="card-header">Penarikan</h4>
            <!-- Account -->
            <div class="card-body">
                @include('layouts.partials.alert')
                <form id="formDeposito" method="POST" action="{{ route('withdrawal.send') }}">
                    @csrf
                    <div class="row mt-2 gy-4">
                        <div class="col-md-12 mt-0">
                            <div class="row">
                                <div class="col-md mb-md-0 mb-3">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content"
                                            for="mybank">
                                            <input name="deposito" class="form-check-input" type="radio"
                                                value="{{ Auth::user()->bank_name }}" id="mybank" checked>
                                            <span class="custom-option-header">
                                                <span class="h6 mb-0">{{ Auth::user()->bank_name }}</span>
                                            </span>
                                            <span class="custom-option-body">
                                                <small>{{ Auth::user()->bank_account_name }}</small><br>
                                                <small>{{ Auth::user()->bank_account_number }}</small>
                                            </span>
                                        </label>
                                    </div>
                                </div>
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
    @if (count($history) > 0)
        <div class="col-md-12">
            <div class="card">
                <h3 class="card-header">Riwayat</h3>
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped mb-4">
                        <thead>
                            <tr>
                                <th width="3%">#</th>
                                <th>Tanggal</th>
                                <th>TrxID</th>
                                <th>Status</th>
                                <th class="text-end">Jumlah Penarikan</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($history as $i => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td>{{ $value->trxid }}</td>
                                    <td><span class="badge bg-label-{{ $value->label }} me-1 cursor-pointer">{{ $value->status_text }}</span></td>
                                    <td class="text-end">{{ number_format($value->amount,0,',','.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data masih kosong</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
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

        var validation = FormValidation.formValidation(document.getElementById('formDeposito'), {
            fields: {
                nominal: {
                    validators: {
                        notEmpty: {
                            message: 'Masukan nominal penarikan'
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
