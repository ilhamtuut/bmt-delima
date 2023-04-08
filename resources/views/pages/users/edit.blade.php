@extends('layouts.app', ['page' => 'users', 'active' => 'add-user'])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-account-settings.css') }}?v={{ time() }}" />
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Edit User</h4>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card mb-4">
            <h4 class="card-header">Edit User</h4>
            <!-- Account -->
            <div class="card-body">
                @include('layouts.partials.alert')
                <form id="formEditUser" method="POST" action="{{ route('users.update',$user->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-2 gy-4">
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="name" name="name" value="{{ $user->name }}" autofocus />
                                <label for="name">Nama Lengkap</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" name="username" value="{{ $user->username }}" id="username" />
                                <label for="username">Username</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" id="email" value="{{ $user->email }}" name="email" />
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control numeral-mask" value="{{ $user->phone_number }}" id="phone_number" maxlength="15" name="phone_number" />
                                <label for="phone_number">No Telp</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" class="form-control numeral-mask" value="{{ $user->ktp }}" id="ktp" maxlength="16" name="ktp" />
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
                                <input type="text" class="form-control" id="address" value="{{ $user->address }}" name="address" />
                                <label for="address">Address</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="bank_name" name="bank_name" class="select2 form-select">
                                    <option value="">Select Bank</option>
                                    @foreach ($banks as $item)
                                        <option value="{{ $item->name }}" @if ($item->name == $user->bank_name)
                                            selected
                                        @endif data-code="{{ $item->code }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <label for="bank_name">Bank</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control d-none" type="text" id="bank_code" value="{{ $user->bank_code }}" name="bank_code" />
                                <input class="form-control" type="text" id="account_name" value="{{ $user->bank_account_name }}" name="account_name" />
                                <label for="account_name">Nama Akun</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control numeral-mask" type="text" id="account_number" value="{{ $user->bank_account_number }}" name="account_number" />
                                <label for="account_number">Nomor Rekening</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <select id="role" name="role" class="select2 form-select">
                                    <option value="">Select</option>
                                    @foreach ($roles as $role)
                                        @if (Auth::user()->hasRole('admin'))
                                            @if ($role->id == 3)
                                                <option value="{{ $role->id }}"
                                                    @foreach ($user->roles as $r)
                                                @if ($role->id == $r->id)
                                                    selected
                                                @endif @endforeach>
                                                    {{ $role->display_name }}
                                                </option>
                                            @endif
                                        @else
                                            <option value="{{ $role->id }}"
                                                @foreach ($user->roles as $r)
                                            @if ($role->id == $r->id)
                                                selected
                                            @endif @endforeach>
                                                {{ $role->display_name }}
                                            </option>
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
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
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
            console.log(code);
            $('#bank_code').val(code);
        })
    </script>
@endsection
