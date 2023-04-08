@extends('layouts.app', ['page' => 'mutation'])

@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Mutasi</h4>
@endsection

@section('content')
    <div class="col-lg-12 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h4 class="mb-2">Saldo</h4>
                </div>
            </div>
            <div class="card-body d-flex justify-content-between flex-wrap gap-3">
                <div class="d-flex gap-3">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded">
                            <i class="mdi mdi-wallet-outline mdi-24px"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <h4 class="mb-0">Rp{{ number_format($saldo, 0, ',', '.') }}</h4>
                        <small class="text-muted">Saldo saat ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form id="form-search" action="{{ route('mutation.index') }}" method="GET">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="date" name="from_date" class="form-control" placeholder="Dari Tanggal">
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <input type="date" name="to_date" class="form-control" placeholder="Sampai Tanggal">
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
                            <th>Note</th>
                            <th>Tipe</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->trxid }}</td>
                                <td>{{ $value->note }}</td>
                                <td>{{ $value->type }}</td>
                                <td class="text-end">{{ number_format($value->amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data masih kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! $data->appends([
                    'from_date' => request()->from_date,
                    'to_date' => request()->to_date,
                ])->render() !!}
        </div>
    </div>
@endsection
