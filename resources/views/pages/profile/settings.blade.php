@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-account-settings.css') }}?v={{ time() }}" />
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan akun /</span> Keamanan</h4>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- Change Password -->
            @include('layouts.partials.alert')
            <div class="card mb-4">
                <h5 class="card-header">Ubah Password</h5>
                <div class="card-body">
                    <form id="formAccountSettings" method="POST" action="{{ route('profile.password') }}" class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle fv-plugins-icon-container">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="password" name="current_password"
                                            id="current_password" placeholder="············">
                                        <label for="current_password">Password Saat ini</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-4 col-md-6 form-password-toggle fv-plugins-icon-container">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="password" id="new_password" name="new_password"
                                            placeholder="············">
                                        <label for="new_password">Password Baru</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <div class="mb-4 col-md-6 form-password-toggle fv-plugins-icon-container">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="password" name="confirm_password"
                                            id="confirm_password" placeholder="············">
                                        <label for="confirm_password">Konfirmasi Password Baru</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer"><i
                                            class="mdi mdi-eye-off-outline"></i></span>
                                </div>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                        <h6 class="text-body">Persyaratan Password:</h6>
                        <ul class="ps-3 mb-0">
                            <li class="mb-1">Panjang minimum 8 karakter - semakin banyak, semakin baik</li>
                            <li class="mb-1">Setidaknya satu karakter huruf kecil</li>
                            <li>Setidaknya satu angka, simbol, atau karakter spasi</li>
                        </ul>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">Simpan perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Change Password -->
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages-account-settings-security.js') }}?v={{ time() }}"></script>
@endsection
