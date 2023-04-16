@extends('layouts.app', ['page' => 'deposito', 'active' => 'my_profit'])
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Deposito /</span> Penghasilan</h4>
@endsection

@section('content')
    <div class="col-md-12">
        @include('layouts.partials.alert')
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped my-4">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th>Tanggal</th>
                            <th>TrxID</th>
                            <th>Paket</th>
                            <th>Note</th>
                            <th class="text-end">Penghasilan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $i => $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->trxid }}</td>
                                <td>{{ $value->deposito->type->name }}</td>
                                <td>Penghasilan Bulan ke-{{ $value->profit }}</td>
                                <td class="text-end">{{ number_format($value->amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data masih kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-center">Total</th>
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
