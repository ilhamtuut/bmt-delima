@extends('layouts.app',['page'=>'deposito'])
@section('css')

@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Beranda /</span> Deposito</h4>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card mb-4">
            <h4 class="card-header">Deposito</h4>
            <!-- Account -->
            <div class="card-body">
                @include('layouts.partials.alert')
                <form id="formDeposito" method="POST" action="{{ route('deposito.save') }}">
                    @csrf
                    <div class="row mt-2 gy-4">
                        <div class="col-md-12 mt-0">
                            <div class="row">
                                @foreach ($deposito as $key => $value)
                                    <div class="col-md mb-md-0 mb-3">
                                        <div class="form-check custom-option custom-option-basic">
                                            <label class="form-check-label custom-option-content"
                                                for="deposito-{{ $value->id }}">
                                                <input name="deposito" class="form-check-input" type="radio"
                                                    value="{{ $value->id }}" id="deposito-{{ $value->id }}"
                                                    {{ $key == 0 ? 'checked' : '' }} data-percent="{{ $value->percent }}">
                                                <span class="custom-option-header">
                                                    <span class="h6 mb-0">{{ $value->name }}</span>
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
                                <select id="payment" name="payment" class="select2 form-select">
                                    <option value="">Pilih Pembayaran</option>
                                    @foreach ($payment as $value)
                                        <option value="{{ $value->id }}">{{ $value->bank_name }} - {{ $value->bank_account_name }} - {{ $value->bank_account_number }}</option>
                                    @endforeach
                                </select>
                                <label for="payment">Pembayaran</label>
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
                                <th>Jumlah</th>
                                <th>Paket</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse ($history as $i => $value)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td>{{ $value->trxid }}</td>
                                    <td>{{ number_format($value->amount,0,',','.') }}</td>
                                    <td>{{ $value->type->name }}</td>
                                    <td>{{ $value->expired_at ? $value->expired_at : '-' }}</td>
                                    <td><span class="badge bg-label-{{ $value->label }} me-1 cursor-pointer">{{ $value->status_text }}</span></td>
                                    <td>
                                        @if ($value->status == 0)
                                            <a href="{{ route('deposito.payment',$value->id) }}" class="badge bg-primary me-1 cursor-pointer"><i class="mdi mdi-cash-sync me-1"></i>Bayar</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Data masih kosong</td>
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
                payment: {
                    validators: {
                        notEmpty: {
                            message: 'Pilih pembayaran'
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
