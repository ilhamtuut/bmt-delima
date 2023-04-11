@extends('layouts.app', ['page' => 'users', 'active' => 'referral'])
@section('css')
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Affiliasi </h4>
@endsection

@section('content')
    <div class="col-md-12">
        @include('layouts.partials.alert')
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
                            <th>#</th>
                            <th>Rekening</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th class="text-center">Status Affiliasi</th>
                            <th>Link Affiliasi</th>
                            <th>Affiliasi</th>
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
