@extends('layouts.app',['page'=>'home'])

@section('content')
    @role('member')
        <div class="row gy-4 mb-4">
            <div class="col-md-12 col-lg-8">
                <div class="card" style="min-height: 245px; padding:15px;">
                    <div class="d-flex align-items-end row">
                        <div class="col-md-6 order-2 order-md-1">
                            <div class="card-body">
                                <h4 class="card-title pb-xl-2">Welcome <strong> {{ Auth::user()->name }}!</strong>🎉</h4>
                                <p>Investasikan dana kamu sekarang, untuk menghasilkan tambahan pemasukan Anda.</p>
                                <a href="{{ route('deposito.index') }}" class="btn btn-primary waves-effect waves-light">
                                    <i class="menu-icon tf-icons mdi mdi-credit-card-outline"></i> Deposito Now</a>
                            </div>
                        </div>
                        <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                            <div class="card-body pb-0 px-0 px-md-4 ps-0">
                                <img src="{{ asset('assets/img/illustrations/illustration-john-light.png') }}" height="180"
                                    alt="View Profile" data-app-light-img="illustrations/illustration-john-light.png"
                                    data-app-dark-img="illustrations/illustration-john-dark.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Marketing & Sales-->
            <div class="col-md-12 col-lg-4">
                <div class="swiper-container swiper-container-horizontal swiper swiper-sales" id="swiper-marketing-sales">
                    <div class="swiper-wrapper">
                        @foreach ($types as $value)
                            <div class="swiper-slide pb-3" style="min-height: 245px;">
                                <h5 class="mb-2">{{ $value->name }}</h5>
                                <div class="d-flex align-items-center mt-3">
                                    <img src="{{ asset('assets//img/icons/unicons/wallet-info.png') }}"
                                        alt="Marketing and sales" width="84" class="rounded" />
                                    <div class="d-flex flex-column w-100 ms-4">
                                        <div class="row d-flex flex-wrap justify-content-between">
                                            <div class="col-12">
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-flex mb-2 pb-1 align-items-center">
                                                        <small class="mb-0 me-2 text-truncate">Jangka Waktu</small>
                                                        <small class="mb-0 me-2 sales-text-bg bg-label-secondary">{{ $value->contract }} bulan</small>
                                                    </li>
                                                    <li class="d-flex mb-2 pb-1 align-items-center">
                                                        <small class="mb-0 me-2 text-truncate">Profit</small>
                                                        <small class="mb-0 me-2 sales-text-bg bg-label-secondary">{{ $value->percent*100 }}%</small>
                                                    </li>
                                                    <li class="d-flex align-items-center">
                                                        <small class="mb-0 me-2 text-truncate">Minimal Deposito</small>
                                                        <small class="mb-0 me-2 sales-text-bg bg-label-secondary">RP{{ number_format($value->minimal,0,',','.') }}</small>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 pt-1">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="location.href = '{{ route('deposito.index') }}'">Apply</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <!--/ Marketing & Sales-->
        </div>
        <div class="row gy-4 mb-4">
            <!-- Overview-->
            <div class="col-lg-12">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4 class="mb-2">Overview</h4>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-wrap gap-3">
                        <div class="d-flex gap-3">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-form-select mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">RP{{ number_format($deposito,0,',','.') }}</h4>
                                <small class="text-muted">Deposito</small>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-info rounded">
                                    <i class="mdi mdi-trending-up mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">RP{{ number_format($withdraw,0,',','.') }}</h4>
                                <small class="text-muted">Penarikan</small>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-warning rounded">
                                    <i class="mdi mdi-poll mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">RP{{ number_format($profit,0,',','.') }}</h4>
                                <small class="text-muted">Profit</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Sales Overview-->
        </div>
    @endrole
    @role(['admin','super_admin'])
        <div class="row gy-4 mb-4">
            <!-- Overview-->
            <div class="col-lg-12">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4 class="mb-2">Overview</h4>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-between flex-wrap gap-4">
                        <div class="d-flex gap-4">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="mdi mdi-account-outline mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">{{ number_format($users,0,',','.') }}</h4>
                                <small class="text-muted">Users</small>
                            </div>
                        </div>
                        <div class="d-flex gap-4">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-success rounded">
                                    <i class="mdi mdi-form-select mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">RP{{ number_format($deposito,0,',','.') }}</h4>
                                <small class="text-muted">Deposito</small>
                            </div>
                        </div>
                        <div class="d-flex gap-4">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-warning rounded">
                                    <i class="mdi mdi-poll mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">RP{{ number_format($profit,0,',','.') }}</h4>
                                <small class="text-muted">Profit</small>
                            </div>
                        </div>
                        <div class="d-flex gap-4">
                            <div class="avatar">
                                <div class="avatar-initial bg-label-info rounded">
                                    <i class="mdi mdi-trending-up mdi-24px"></i>
                                </div>
                            </div>
                            <div class="card-info">
                                <h4 class="mb-0">RP{{ number_format($withdraw,0,',','.') }}</h4>
                                <small class="text-muted">Penarikan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Sales Overview-->
        </div>
    @endrole
@endsection
@section('js')
    <script src="{{ asset('assets/js/dashboards-ecommerce.js') }}"></script>
@endsection
