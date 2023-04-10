@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}?v={{ time() }}" />
@endsection
@section('title')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Beranda /</span> Profil Saya</h4>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @include('layouts.partials.alert')
        </div>
        <div class="col-12">
            <div class="card mb-4">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top">
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto text-center">
                        <img src="{{ Auth::user()->foto_profile_link }}" alt="user image"
                        class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                        <span class="d-block h-auto ms-0 ms-sm-4 mt-1 badge bg-primary cursor-pointer" id="btn-upload">Upload foto</span>
                        <form id="upload-profile" action="{{ route('profile.upload.foto') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input name="foto" type="file" class="form-control d-none" accept="image/png, image/gif, image/jpeg" id="foto_profile" name="foto_profile" />
                        </form>
                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4>{{ Auth::user()->name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item">
                                        <i class="mdi mdi-calendar-blank-outline me-1 mdi-20px"></i><span
                                            class="fw-semibold"> Bergabung
                                            {{ date('d F Y', strtotime(Auth::user()->created_at)) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6">
            <!-- About User -->
            <div class="card mb-4">
                <div class="card-body">
                    <small class="card-text text-uppercase text-muted">Tentang</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-link mdi-24px"></i><span class="fw-semibold mx-2">Rekening:</span>
                            <span>{{ Auth::user()->account_number }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-account-outline mdi-24px"></i><span class="fw-semibold mx-2">Nama Lengkap:</span>
                            <span>{{ Auth::user()->name }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-account-check-outline mdi-24px"></i><span class="fw-semibold mx-2">Username:</span>
                            <span>{{ Auth::user()->username }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-check mdi-24px"></i><span class="fw-semibold mx-2">Status:</span>
                            <span>Active</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-flag-outline mdi-24px"></i><span class="fw-semibold mx-2">No KTP:</span>
                            <span>{{ Auth::user()->ktp }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-map-marker-outline mdi-24px"></i><span class="fw-semibold mx-2">Alamat:</span>
                            <span>{{ Auth::user()->address }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!--/ About User -->
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6">
            <!-- About User -->
            <div class="card mb-4">
                <div class="card-body">
                    <small class="card-text text-uppercase text-muted">Kontak</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-phone-outline mdi-24px"></i><span class="fw-semibold mx-2">No Telp:</span>
                            <span>{{ Auth::user()->phone_number }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="mdi mdi-email-outline mdi-24px"></i><span class="fw-semibold mx-2">Email:</span>
                            <span>{{ Auth::user()->email }}</span>
                        </li>
                    </ul>
                    @if (Auth::user()->bank_name)
                        <small class="card-text text-uppercase text-muted">Akun Bank</small>
                        <ul class="list-unstyled my-3 py-1">
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-bank mdi-24px"></i><span class="fw-semibold mx-2">Nama Bank:</span>
                                <span>{{ Auth::user()->bank_name }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-account-outline mdi-24px"></i><span class="fw-semibold mx-2">Nama Pelimik:</span>
                                <span>{{ Auth::user()->bank_account_name }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="mdi mdi-link mdi-24px"></i><span class="fw-semibold mx-2">Nomor Rekening:</span>
                                <span>{{ Auth::user()->bank_account_number }}</span>
                            </li>
                        </ul>
                    @else
                        <a href="{{ route('profile.bank') }}" class="btn btn-primary waves-effect waves-light">
                            <i class="mdi mdi-bank-outline me-1"></i>Add Bank Account
                        </a>
                    @endif
                </div>
            </div>
            <!--/ About User -->
        </div>
    </div>
@endsection

@section('customjs')
    <script>
        $('#btn-upload').click(function(e){
            $('#foto_profile').click();
        });

        $('#foto_profile').change(function(e){
            $('#upload-profile').submit();
        });
    </script>
@endsection
