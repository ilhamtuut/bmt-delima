@extends('layouts.auth')

@section('content')
    <div class="w-px-400 mx-auto pt-5 pt-lg-0">
        <div class="text-center mb-2">
            <img style="height:150px;" src="{{ asset('assets/img/logo/delima.png') }}" alt="logo">
        </div>
        <h4 class="mb-2 fw-semibold">Buat akun kamu 🚀</h4>
        <p class="mb-4">Jadikan akun Anda mudah dan menyenangkan!</p>
        @include('layouts.partials.alert')
        <form id="formAuthentication" class="mb-3" action="{{ route('register') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="Masukan nama lengkap"
                    autofocus />
                <label for="name">Nama Lengkap</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukan username"
                    autofocus />
                <label for="username">Usename</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="email" name="email" placeholder="Masukan email" />
                <label for="email">Email</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control numeral-mask" maxlength="15" id="phone_number" name="phone_number"
                    placeholder="Masukan No Telp" autofocus />
                <label for="phone_number">No Telp</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="address" name="address" placeholder="Masukan alamat"
                    autofocus />
                <label for="address">Alamat</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="ktp" name="ktp" maxlength="16"
                    placeholder="Masukan No KTP" autofocus />
                <label for="ktp">No KTP</label>
            </div>
            <div class="form-floating form-floating-outline mb-3">
                <input type="file" class="form-control" id="foto_ktp" name="foto_ktp" accept="image/png, image/gif, image/jpeg" />
                <label for="foto_ktp">Foto KTP</label>
            </div>
            <div class="mb-3 form-password-toggle">
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
            <div class="mb-3 form-password-toggle">
                <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password_confirmation" />
                        <label for="password_confirmation">Konfirmasi Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
                    <label class="form-check-label" for="terms-conditions">
                        Saya setuju dengan
                        <a href="javascript:void(0);">kebijakan dan persyaratan privasi</a>
                    </label>
                </div>
            </div>
            <button class="btn btn-primary d-grid w-100">Daftar</button>
        </form>

        <p class="text-center mt-2">
            <span>Sudah memiliki akun?</span>
            <a href="{{ route('login') }}">
                <span>Masuk</span>
            </a>
        </p>
    </div>
@endsection
