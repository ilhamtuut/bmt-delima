@extends('layouts.app', ['page' => 'users', 'active' => 'add-user'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-account-settings.css') }}?v={{ time() }}" />
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Buat Akun</h4>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card mb-4">
            <h4 class="card-header">Buat Akun</h4>
            <!-- Account -->
            <div class="card-body">
                @include('layouts.partials.alert')
                <form id="formCreateUser" method="POST" action="{{ route('users.create') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-2 gy-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="name" name="name" autofocus />
                                <label for="name">Nama Depan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="last_name" name="last_name" autofocus />
                                <label for="name">Nama Belakang</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" name="username" id="username" />
                                <label for="username">Username</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="email" name="email" />
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control numeral-mask" id="phone_number" maxlength="15" name="phone_number" />
                                <label for="phone_number">No Telp</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control numeral-mask" id="ktp" maxlength="16" name="ktp" />
                                <label for="ktp">No KTP</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="file" id="foto_ktp" name="foto_ktp" />
                                <label for="foto_ktp">Foto KTP</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control" id="address" name="address" />
                                <label for="address">Alamat</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="bank_name" name="bank_name" class="select2 form-select">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($banks as $value)
                                        <option value="{{ $value->name }}" data-code="{{ $value->code }}">{{ $value->name }}</option>
                                    @endforeach
                                </select>
                                <label for="bank_name">Bank</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control d-none" type="text" id="bank_code" name="bank_code" />
                                <input class="form-control" type="text" id="account_name" name="account_name" />
                                <label for="account_name">Nama Akun</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control numeral-mask" type="text" id="account_number" name="account_number" />
                                <label for="account_number">Nomor Rekening</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="role" name="role" class="select2 form-select">
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $role)
                                        @if (Auth::user()->hasRole('admin'))
                                            @if ($role->id == 3)
                                                <option value="{{$role->id}}">{{$role->display_name}}</option>
                                            @endif
                                        @else
                                            <option value="{{$role->id}}">{{$role->display_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label for="role">Role</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password" class="form-control" name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password" />
                                        <label for="password">Password</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Buat Akun</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages-account-settings-account.js') }}?v={{ time() }}"></script>
@endsection

@section('customjs')
    <script>
        $('#bank_name').change(function (e) {
            var code = $(this).find("option:selected").data('code');
            $('#bank_code').val(code);
        })
    </script>
@endsection
