@extends('layouts.app', ['page' => 'balance'])
@section('css')
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Beranda /</span> Mutasi</h4>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="list-unstyled my-1 py-1">
                    <li class="d-flex align-items-center mb-1">
                        <span class="fw-semibold mx-2">Name:</span>
                        <span>{{ $user->name }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-1">
                        <span class="fw-semibold mx-2">Username:</span>
                        <span>{{ $user->username }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-1">
                        <span class="fw-semibold mx-2">Rekening:</span>
                        <span>{{ $user->account_number }}</span>
                    </li>
                </ul>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped mb-4">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th>Tanggal</th>
                            <th>Trxid</th>
                            <th>Note</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th class="text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $saldo = 0;
                        @endphp
                        @forelse ($data as $i => $value)
                            @php
                                $saldo += $value->debit - $value->kredit;
                            @endphp
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $value->created_at }}</td>
                                <td>{{ $value->trxid }}</td>
                                <td>{{ $value->note }}</td>
                                <td>Rp{{ number_format($value->debit, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($value->kredit, 0, ',', '.') }}</td>
                                <td class="text-end">Rp{{ number_format($saldo, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data masih kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
