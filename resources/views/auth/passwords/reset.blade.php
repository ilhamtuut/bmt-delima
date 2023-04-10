@extends('layouts.auth')

@section('content')
    <div class="w-px-400 mx-auto pt-5 pt-lg-0">
        <div class="text-center mb-2">
            <img style="height:150px;" src="{{ asset('assets/img/logo/delima.png') }}" alt="logo">
        </div>
        <h4 class="mb-2 fw-semibold">Reset Password 🔒</h4>
        <p class="mb-4">Kata sandi baru Anda harus berbeda dari kata sandi yang digunakan sebelumnya</p>
        <form id="formAuthentication" class="mb-3" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Masukan username anda" autocomplete="username" autofocus />
                <label for="username">Username</label>
            </div>
            <div class="mb-3 form-password-toggle">
                <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                        <input type="password" id="password" class="form-control" name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <label for="password">Password Baru</label>
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
            </div>
            <div class="mb-3 form-password-toggle">
                <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <label for="password_confirmation">Konfirmasi Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                </div>
            </div>
            <button class="btn btn-primary d-grid w-100 mb-3">Set password baru</button>
            <div class="text-center">
                <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                    <i class="mdi mdi-chevron-left scaleX-n1-rtl mdi-24px"></i>
                    Kembali untuk masuk
                </a>
            </div>
        </form>
    </div>
@endsection
