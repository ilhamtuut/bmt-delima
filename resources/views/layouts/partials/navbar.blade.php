<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-xxl">
        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
            <a href="{{ route('home') }}" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                    <img style="height:35px;" src="{{ asset('assets/img/logo/favicon.png') }}" alt="logo">
                </span>
                <span class="app-brand-text demo menu-text fw-bold">BMT Delima</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="mdi mdi-close align-middle"></i>
            </a>
        </div>

        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="mdi mdi-menu mdi-24px"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                        data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ Auth::user()->foto_profile_link }}" alt
                                class="w-px-40 h-auto rounded-circle" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="pages-account-settings-account.html">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ Auth::user()->foto_profile_link }}" alt
                                                class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                        <small class="text-muted">{{ Auth::user()->roles[0]->display_name }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="mdi mdi-account-outline me-2"></i>
                                <span class="align-middle">Profil Saya</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                <i class="mdi mdi-cog-outline me-2"></i>
                                <span class="align-middle">Pengaturan</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-logout me-2"></i>
                                <span class="align-middle">Keluar</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </div>
</nav>
