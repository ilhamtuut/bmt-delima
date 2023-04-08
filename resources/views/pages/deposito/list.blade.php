@extends('layouts.app', ['page' => 'deposito', 'active' => 'listdeposito'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Deposito /</span> List Deposito</h4>
@endsection

@section('content')
    <div class="col-md-12">
        @include('layouts.partials.alert')
        <div class="card">
            <div class="card-header">
                <form id="form-search" action="{{ route('deposito.list') }}" method="GET">
                    <div class="row">
                        <div class="col-lg-3">
                            <input type="date" name="from_date" class="form-control" placeholder="Dari Tanggal">
                        </div>
                        <div class="col-lg-3">
                            <input type="date" name="to_date" class="form-control" placeholder="Sampai Tanggal">
                        </div>
                        <div class="col-lg-3">
                            <select type="text" name="status" class="form-control form-select">
                                <option value="">Pilih</option>
                                <option @if (request()->status == 1) selected @endif value="1">Menunggu Pembayaran
                                </option>
                                <option @if (request()->status == 2) selected @endif value="2">Aktif</option>
                                <option @if (request()->status == 3) selected @endif value="3">Menunggu Konfirmasi
                                </option>
                                <option @if (request()->status == 4) selected @endif value="4">Batal</option>
                                <option @if (request()->status == 5) selected @endif value="5">Selesai</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search"
                                    aria-label="Search" aria-describedby="search">
                                <span class="input-group-text bg-primary text-warning cursor-pointer btn-search"
                                    id="search"><i class="mdi mdi-magnify"></i></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped mb-4">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>TrxID</th>
                            <th>Paket</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Estimasi Perolehan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $i => $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->user->account_number }} - {{ $value->user->name }}</td>
                                <td>{{ $value->trxid }}</td>
                                <td>{{ $value->type->name }}</td>
                                <td>{{ $value->expired_at ? $value->expired_at : '-' }}</td>
                                <td><span class="badge bg-label-{{ $value->label }} me-1">{{ $value->status_text }}</span>
                                </td>
                                <td class="text-end">{{ number_format($value->amount, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($value->amount * $value->type->percent, 0, ',', '.') }}</td>
                                <td>
                                    @if ($value->status == 2)
                                        <span onclick="confirmAction('{{ route('deposito.action_deposito',['accept',$value->id]) }}','Menerima')"
                                            class="badge bg-success cursor-pointer"><i
                                                class="mdi mdi-cash-sync me-1 mdi-14px"></i> Konfirmasi</span>
                                        <span onclick="confirmAction('{{ route('deposito.action_deposito',['reject',$value->id]) }}','Membatalkan')"
                                            class="badge bg-danger cursor-pointer"><i
                                                class="mdi mdi-cash-sync me-1 mdi-14px"></i> Reject</span>
                                    @endif
                                    @if ($value->payment)
                                    <span class="badge bg-info cursor-pointer showModal"
                                        data-amount="{{ $value->payment ? $value->payment->amount : '' }}"
                                        data-bank_code="{{ $value->payment ? $value->payment->bank_code : '' }}"
                                        data-bank_name="{{ $value->payment ? $value->payment->bank_name : '' }}"
                                        data-bank_account_name="{{ $value->payment ? $value->payment->bank_account_name : '' }}"
                                        data-bank_account_number="{{ $value->payment ? $value->payment->bank_account_number : '' }}"
                                        data-link="{{ $value->payment ? $value->payment->link : '' }}"><i
                                            class="mdi mdi-information-outline me-1 mdi-14px"></i> Detail Pembayaran</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Data masih kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" class="text-center">Total</th>
                            <td class="text-end">{{ number_format($total, 0, ',', '.') }}</td>
                            <td class="text-end">{{ number_format($profit, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            {!! $history->appends([
                    'status' => request()->status,
                    'search' => request()->search,
                    'from_date' => request()->from_date,
                    'to_date' => request()->to_date,
                ])->render() !!}
        </div>
    </div>

    <div class="modal fade" id="addNewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body p-md-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-3">
                        <h4 class="pb-1"> Informasi Pembayaran</h4>
                    </div>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-bank-outline mdi-24px"></i><span class="fw-semibold mx-2">Nama Bank:</span>
                            <span id="info-bank_name"></span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-bank-outline mdi-24px"></i><span class="fw-semibold mx-2">Nama Akun:</span>
                            <span id="info-bank_account_name"></span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-bank-outline mdi-24px"></i><span class="fw-semibold mx-2">Nomor Rekening:</span>
                            <span id="info-bank_account_number"></span>
                        </li>
                        <div class="d-none" id="show-info">
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-check mdi-24px"></i><span class="fw-semibold mx-2">Jumlah Pembayaran:</span>
                                <span id="info-amount"></span>
                            </li>
                            <li class="align-items-center mb-3">
                                <i class="mdi mdi-image-area-close mdi-24px"></i><span class="fw-semibold mx-2">Bukti Pembayaran:</span>
                                <p class="mt-2">
                                    <a href="#" id="link-image" target="_blank">
                                        <img src="" alt="" id="info-image" style="width: 50%;">
                                    </a>
                                </p>
                            </li>
                        </div>
                    </ul>
                    <div class="col-12 text-end">
                        <button type="reset" class="btn btn-outline-secondary btn-reset" data-bs-dismiss="modal"
                            aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $('.showModal').click(function(e) {
            $('#info-bank_name').html($(this).data('bank_name'));
            $('#info-bank_account_name').html($(this).data('bank_account_name'));
            $('#info-bank_account_number').html($(this).data('bank_account_number'));
            $('#show-info').addClass('d-none');
            if($(this).data('amount') > 0){
                $('#show-info').removeClass('d-none');
                $('#info-amount').html('Rp'+formatNumber($(this).data('amount')));
                $('#info-image').attr('src',$(this).data('link'));
                $('#link-image').attr('href',$(this).data('link'));
            }
            new bootstrap.Modal(document.getElementById('addNewModal')).show();
        })

        function confirmAction(url, msg) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: msg + " pembayaran deposito ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Accept',
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-label-secondary waves-effect'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    fetch(url)
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        Swal.showValidationMessage('Request failed:' + error);
                    });
                }
            });
        }
    </script>
@endsection
