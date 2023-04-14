@extends('layouts.app', ['page' => 'affilate', 'active' => 'referral'])
@section('css')
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Beranda /</span> Affiliasi </h4>
@endsection

@section('content')
    <div class="col-md-12">
        @include('layouts.partials.alert')
        <div class="card">
            <div class="card-header">
                <form id="form-search" action="{{ route('users.referral') }}" method="GET">
                    <div class="row">
                        <div class="col-lg-5"></div>
                        <div class="col-lg-3">
                            <select type="text" name="status" class="form-control form-select">
                                <option value="">Status Affiliasi</option>
                                <option @if (request()->status == 1) selected @endif value="1">Aktif</option>
                                <option @if (request()->status == 2) selected @endif value="2">Tidak</option>
                            </select>
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
                            <th>#</th>
                            <th>Rekening</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th class="text-center">Status Affiliasi</th>
                            <th>Link Affiliasi</th>
                            <th>Affiliasi</th>
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
                                <td class="text-center">
                                    @if ($value->is_referral == 1)
                                        <span class="badge bg-label-success me-1">Aktif</span>
                                    @else
                                        <span class="badge bg-label-danger me-1">Tidak</span>
                                    @endif
                                </td>
                                <td>{{ $value->referral_code ? url('/referral/' . $value->referral_code) : '-' }}</td>
                                <td>{{ $value->childs()->count() }} / <a class="badge bg-primary me-1" href="{{ route('users.list_referral',$value->id) }}"><i
                                    class="mdi mdi-account-supervisor-outline me-1 mdi-14px"></i> Lihat Affiliasi</a></td>
                                <td>
                                    @if ($value->is_referral == 1)
                                        <a class="badge bg-danger me-1" href="{{ route('users.set.referral',['nonactive',$value->id]) }}"><i
                                        class="mdi mdi-update me-1 mdi-14px"></i> Unset Affiliasi</a>
                                    @else
                                        <a class="badge bg-info me-1" href="{{ route('users.set.referral', ['active', $value->id]) }}"><i
                                                class="mdi mdi-update me-1 mdi-14px"></i> Set Affiliasi</a>
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
            {!! $data->appends(['status' => request()->status, 'search' => request()->search])->render() !!}
        </div>
    </div>
@endsection
