@extends('layouts.app', ['page' => 'affilate', 'active' => 'komisi'])
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Beranda /</span> Komisi Affiliasi</h4>
@endsection

@section('content')
    <div class="col-md-12">
        @include('layouts.partials.alert')
        <div class="card">
            <div class="card-header">
                <form id="form-search" action="{{ route('deposito.list_affilate') }}" method="GET">
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="date" name="from_date" class="form-control" placeholder="Dari Tanggal">
                        </div>
                        <div class="col-lg-4">
                            <input type="date" name="to_date" class="form-control" placeholder="Sampai Tanggal">
                        </div>
                        <div class="col-lg-4">
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
                            <th>Dari</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Persen</th>
                            <th class="text-end">Komisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $i => $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->trxid }}</td>
                                <td>{{ $value->user->account_number }} - {{ $value->user->name }}</td>
                                <td>{{ $value->from->account_number }} - {{ $value->from->name }}</td>
                                <td class="text-end">{{ number_format($value->amount, 0, ',', '.') }}</td>
                                <td class="text-end">{{ $value->percent*100 }}%</td>
                                <td class="text-end">{{ number_format($value->bonus, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Data masih kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" class="text-center">Total</th>
                            <td class="text-end">{{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            {!! $history->appends([
                    'search' => request()->search,
                    'from_date' => request()->from_date,
                    'to_date' => request()->to_date,
                ])->render() !!}
        </div>
    </div>
@endsection
