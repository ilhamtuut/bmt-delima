@extends('layouts.auth')

@section('content')
    <div class="w-px-400 mx-auto pt-5 pt-lg-0">
        <div class="text-center mb-2">
            <img style="height:150px;" src="{{ asset('assets/img/logo/delima.png') }}" alt="logo">
        </div>
        <h4 class="mb-2 fw-semibold">Verifikasi Dua Langkah 💬</h4>
        <p class="text-start mb-4">
            Kami mengirimkan kode verifikasi ke ponsel Anda. Masukkan kode dari ponsel di kolom di bawah ini.
            <span class="fw-bold d-block mt-2">******1234</span>
        </p>
        <p class="mb-0 fw-semibold">Ketik kode keamanan 6 digit Anda</p>
        <form id="twoStepsForm" action="index.html" method="POST">
            <div class="mb-3">
                <div class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
                    <input type="text"
                        class="form-control auth-input w-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2"
                        maxlength="1" autofocus />
                    <input type="text"
                        class="form-control auth-input w-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2"
                        maxlength="1" />
                    <input type="text"
                        class="form-control auth-input w-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2"
                        maxlength="1" />
                    <input type="text"
                        class="form-control auth-input w-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2"
                        maxlength="1" />
                    <input type="text"
                        class="form-control auth-input w-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2"
                        maxlength="1" />
                    <input type="text"
                        class="form-control auth-input w-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2"
                        maxlength="1" />
                </div>
                <!-- Create a hidden field which is combined by 3 fields above -->
                <input type="hidden" name="otp" />
            </div>
            <button class="btn btn-primary d-grid w-100 mb-3 submit">Verifikasi akun saya</button>
            <div class="text-center">
                Tidak mendapatkan kodenya?
                <a href="javascript:void(0);" class="submit"> Kirim ulang </a>
            </div>
        </form>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/js/pages-auth-two-steps.js') }}?v={{ time() }}"></script>
@endsection
