@extends('layouts.app', ['page' => 'users', 'active' => $role])
@section('css')
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> List
        {{ ucwords(str_replace('_', ' ', $role)) }}</h4>
@endsection

@section('content')
    <div class="col-md-12">
        @include('layouts.partials.alert')
        <div class="card">
            <div class="card-header">
                <form id="form-search" action="{{ route('users.list', $role) }}" method="GET">
                    <div class="row">
                        <div class="col-lg-6"></div>
                        <div class="col-lg-2">
                            <select type="text" name="status" class="form-control form-select">
                                <option value="">Pilih</option>
                                <option @if(request()->status == 1) selected @endif value="1">Aktif</option>
                                <option @if(request()->status == 2) selected @endif value="2">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search">
                                <span class="input-group-text bg-primary text-warning cursor-pointer btn-search" id="search"><i class="mdi mdi-magnify"></i></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped mb-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Rekening</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>No Telp</th>
                            <th>Alamat</th>
                            <th>No Ktp</th>
                            <th>Akun Bank</th>
                            <th>Status</th>
                            <th>Tgl Join</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($data as $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $value->account_number }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->username }}</td>
                                <td>{{ $value->email }}</td>
                                <td>{{ $value->phone_number }}</td>
                                <td>{{ $value->address }}</td>
                                <td>{{ $value->ktp }}</td>
                                <td>@if($value->bank_name) {{ $value->bank_name }} / {{ $value->bank_account_name }} / {{ $value->bank_account_number }} @endif</td>
                                <td>
                                    @if ($value->status == 1)
                                        <span class="badge bg-label-success me-1">Aktif</span>
                                    @else
                                        <span class="badge bg-label-danger me-1">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $value->created_at }}</td>
                                <td>
                                    <a class="badge bg-info me-1" href="{{ route('users.edit',$value->id) }}"><i
                                        class="mdi mdi-pencil-outline me-1 mdi-14px"></i> Edit</a>
                                    <a class="badge bg-{{ $value->status == 1 ? 'danger' : 'success' }} me-1" href="{{ route('users.block',$value->id) }}"><i
                                        class="mdi mdi-cancel me-1 mdi-14px"></i> {{ $value->status == 1 ? 'Block' : 'UnBlock' }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">Data masih kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {!! $data->appends(['status'=>request()->status,'search'=>request()->search])->render() !!}
        </div>
    </div>
@endsection
