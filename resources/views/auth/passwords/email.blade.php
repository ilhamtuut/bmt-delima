@extends('layouts.auth')

@section('content')
    <div class="w-px-400 mx-auto">
        <div class="text-center mb-2">
            <img style="height:150px;" src="{{ asset('assets/img/logo/delima.png') }}" alt="logo">
        </div>
        <h4 class="mb-2 fw-semibold">Lupa Password? 🔒</h4>
        <p class="mb-4">Masukkan email Anda dan kami akan mengirimkan instruksi untuk mengatur ulang kata sandi Anda</p>
        @include('layouts.partials.alert')
        <form id="formAuthentication" class="mb-3" action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-floating form-floating-outline mb-3">
                <input type="text" class="form-control" id="email" name="email" placeholder="Masukan email anda"
                    autofocus />
                <label for="email">Email</label>
            </div>
            <button class="btn btn-primary d-grid w-100">Kirim Link Reset</button>
        </form>
        <div class="text-center">
            <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                <i class="mdi mdi-chevron-left scaleX-n1-rtl mdi-24px"></i>
                Kembali untuk masuk
            </a>
        </div>
    </div>
@endsection
