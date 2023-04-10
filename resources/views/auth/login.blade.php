@extends('layouts.auth')

@section('content')
    <div class="w-px-400 mx-auto pt-5 pt-lg-0">
        <div class="text-center mb-2">
            <img style="height:150px;" src="{{ asset('assets/img/logo/delima.png') }}" alt="logo">
        </div>
        <h4 class="mb-2 fw-semibold">Selamat Datang di {{ config('app.name') }}! 👋</h4>
        <p class="mb-4">Silakan masuk ke akun Anda</p>
        @include('layouts.partials.alert')
        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="username" name="username"
                    placeholder="Masukan username anda" autofocus />
                <label for="username">Username</label>
            </div>
            <div class="mb-3">
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
            <div class="mb-3 d-flex justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Ingatkan saya </label>
                </div>
                <a href="{{ route('password.request') }}" class="float-end mb-1">
                    <span>Lupa Password?</span>
                </a>
            </div>
            <button class="btn btn-primary d-grid w-100">Masuk</button>
        </form>

        <p class="text-center mt-2">
            <span> Pengguna baru?</span>
            <a href="{{ route('register') }}">
                <span>Buat akun</span>
            </a>
        </p>
    </div>
@endsection
