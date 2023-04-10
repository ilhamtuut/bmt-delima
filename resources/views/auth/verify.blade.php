@extends('layouts.auth')

@section('content')
    <div class="w-px-400 mx-auto pt-5 pt-lg-0">
        <div class="text-center mb-2">
            <img style="height:150px;" src="{{ asset('assets/img/logo/delima.png') }}" alt="logo">
        </div>
        <h4 class="mb-2">Verifikasi akun Anda ✉️</h4>
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                Link verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        @endif
        <p class="text-start">
            Link aktivasi akun dikirim ke alamat email Anda: {{ Auth::user()->email }} Ikuti link di dalam untuk melanjutkan.
        </p>
        <a class="btn btn-primary w-100 my-3 submit" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Lewati untuk saat ini
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <p class="text-center">
            Tidak menerima email?
            <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('resend-form').submit();" class="submit"> Kirim ulang </a>
            <form class="d-none" method="POST" action="{{ route('verification.resend') }}" id="resend-form">
                @csrf
            </form>
        </p>
    </div>
@endsection
