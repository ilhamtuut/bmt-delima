@extends('layouts.app',['page'=>'listwithdrawal','active'=>'listwithdrawal'])
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Beranda /</span> Penarikan</h4>
@endsection

@section('content')
    <div class="col-md-12">
        @include('layouts.partials.alert')
        <div class="card">
            <div class="card-header">
                <form id="form-search" action="{{ route('withdrawal.list') }}" method="GET">
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
                                <option @if (request()->status == 1) selected @endif value="1">Menunggu ditransfer
                                </option>
                                <option @if (request()->status == 2) selected @endif value="2">Sudah ditransfer</option>
                                <option @if (request()->status == 3) selected @endif value="3">Penarikan Gagal
                                </option>
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
                            <th>TrxID</th>
                            <th>User</th>
                            <th>Akun Bank</th>
                            <th>Status</th>
                            <th class="text-end">Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $i => $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->trxid }}</td>
                                <td>{{ $value->user->account_number }} - {{ $value->user->name }}</td>
                                <td>{{ $value->bank_name }} - {{ $value->bank_account_name }} - {{ $value->bank_account_number }}</td>
                                <td><span class="badge bg-label-{{ $value->label }} me-1">{{ $value->status_text }}</span>
                                </td>
                                <td class="text-end">{{ number_format($value->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if ($value->status == 0)
                                        <span onclick="confirmAction('{{ route('withdrawal.action',['accept',$value->id]) }}','Menerima')"
                                            class="badge bg-success cursor-pointer"><i
                                                class="mdi mdi-cash-sync me-1 mdi-14px"></i> Konfirmasi</span>
                                        <span onclick="confirmAction('{{ route('withdrawal.action',['reject',$value->id]) }}','Membatalkan')"
                                            class="badge bg-danger cursor-pointer"><i
                                                class="mdi mdi-cash-sync me-1 mdi-14px"></i> Reject</span>
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
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-center">Total</th>
                            <td class="text-end">{{ number_format($total, 0, ',', '.') }}</td>
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
@endsection

@section('js')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('customjs')
    <script>
        function confirmAction(url, msg) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: msg + " penarikan dana ini",
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
