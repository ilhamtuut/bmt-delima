@extends('layouts.app', ['page' => 'balance'])
@section('css')
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Saldo</h4>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form id="form-search" action="{{ route('mutation.balance') }}" method="GET">
                    <div class="row">
                        <div class="col-lg-8"></div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search">
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
                            <th>Akun</th>
                            <th class="text-end">Saldo</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>
                                    <ul class="list-unstyled my-1 py-1">
                                        <li class="d-flex align-items-center mb-1">
                                            <span class="fw-semibold mx-2">Name:</span>
                                            <span>{{ $value->name }}</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-1">
                                            <span class="fw-semibold mx-2">Username:</span>
                                            <span>{{ $value->username }}</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-1">
                                            <span class="fw-semibold mx-2">Rekening:</span>
                                            <span>{{ $value->account_number }}</span>
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-end">Rp{{ number_format($value->saldo(), 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('mutation.history',$value->id) }}" class="badge bg-primary me-1 cursor-pointer"><i class="mdi mdi-cash-sync me-1"></i>Mutasi</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Data masih kosong</td>
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
