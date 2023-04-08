@extends('layouts.auth')

@section('content')
    <div class="w-px-400 mx-auto pt-5 pt-lg-0">
        <div class="text-center mb-2">
            <img style="height:150px;" src="{{ asset('assets/img/logo/delima.png') }}" alt="logo">
        </div>
        <h4 class="mb-2">Verify your email ✉️</h4>
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif
        <p class="text-start">
            Account activation link sent to your email address: {{ Auth::user()->email }} Please follow the link inside to continue.
        </p>
        <a class="btn btn-primary w-100 my-3 submit" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Skip for now
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <p class="text-center">
            Didn't get the mail?
            <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('resend-form').submit();" class="submit"> Resend </a>
            <form class="d-none" method="POST" action="{{ route('verification.resend') }}" id="resend-form">
                @csrf
            </form>
        </p>
    </div>
@endsection
