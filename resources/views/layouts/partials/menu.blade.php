<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <!-- Dashboards -->
            <li class="menu-item {{ isset($page) && $page == 'home' ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                    <div data-i18n="Beranda">Beranda</div>
                </a>
            </li>
            @role(['member'])
            <li class="menu-item {{ isset($page) && $page == 'deposito' ? 'active' : '' }}">
                <a href="{{ route('deposito.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-credit-card-outline"></i>
                    <div data-i18n="Deposito">Deposito</div>
                </a>
            </li>
            <li class="menu-item {{ isset($page) && $page == 'withdrawal' ? 'active' : '' }}">
                <a href="{{ route('withdrawal.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-layers-outline"></i>
                    <div data-i18n="Penarikan">Penarikan</div>
                </a>
            </li>
            <li class="menu-item {{ isset($page) && $page == 'transfer' ? 'active' : '' }}">
                <a href="{{ route('transfer.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-currency-usd"></i>
                    <div data-i18n="Transfer Dana">Transfer Dana</div>
                </a>
            </li>
            <li class="menu-item {{ isset($page) && $page == 'mutation' ? 'active' : '' }}">
                <a href="{{ route('mutation.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
                    <div data-i18n="Mutasi">Mutasi</div>
                </a>
            </li>
            @endrole

            @role(['admin','super_admin'])
            <!-- Users -->
            <li class="menu-item {{ isset($page) && $page == 'users' ? 'active' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-account-supervisor-outline"></i>
                    <div data-i18n="Users">Users</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ isset($active) && $active == 'add-user' ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                            <div data-i18n="Buat Akun">Buat Akun</div>
                        </a>
                    </li>
                    @foreach (App\Models\Role::all() as $role)
                        @if(Auth::user()->hasRole('admin') && $role->name == 'member')
                            <li class="menu-item {{ isset($active) && $active == $role->name ? 'active' : '' }}">
                                <a href="{{ route('users.list',$role->name) }}" class="menu-link">
                                    <i class="menu-icon tf-icons mdi mdi-format-list-bulleted"></i>
                                    <div data-i18n="List {{$role->display_name}}">List {{$role->display_name}}</div>
                                </a>
                            </li>
                        @elseif(Auth::user()->hasRole('super_admin'))
                            <li class="menu-item {{ isset($active) && $active == $role->name ? 'active' : '' }}">
                                <a href="{{ route('users.list',$role->name) }}" class="menu-link">
                                    <i class="menu-icon tf-icons mdi mdi-format-list-bulleted"></i>
                                    <div data-i18n="List {{$role->display_name}}">List {{$role->display_name}}</div>
                                </a>
                            </li>
                        @endif
                    @endforeach
                    {{-- <li class="menu-item {{ isset($active) && $active == 'referral' ? 'active' : '' }}">
                        <a href="{{ route('users.referral') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-share-all-outline"></i>
                            <div data-i18n="Affiliasi">Affiliasi</div>
                        </a>
                    </li> --}}
                </ul>
            </li>

            <!-- Affiliasi -->
            <li class="menu-item {{ isset($page) && $page == 'affilate' ? 'active' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-share-all-outline"></i>
                    <div data-i18n="Affiliasi">Affiliasi</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ isset($active) && $active == 'referral' ? 'active' : '' }}">
                        <a href="{{ route('users.referral') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>
                            <div data-i18n="Affiliasi">Affiliasi</div>
                        </a>
                    </li>
                    <li class="menu-item {{ isset($active) && $active == 'komisi' ? 'active' : '' }}">
                        <a href="{{ route('deposito.list_affilate') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-credit-card-outline"></i>
                            <div data-i18n="Komisi Affiliasi">Komisi Affiliasi</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Settings -->
            <li class="menu-item {{ isset($page) && $page == 'settings' ? 'active' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-file-document-outline"></i>
                    <div data-i18n="Pengaturan">Pengaturan</div>
                </a>
                <ul class="menu-sub">
                    @role(['super_admin'])
                        <li class="menu-item {{ isset($active) && $active == 'sbank' ? 'active' : '' }}">
                            <a href="{{ route('setting.bank') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-bank"></i>
                                <div data-i18n="Bank">Bank</div>
                            </a>
                        </li>
                        <li class="menu-item {{ isset($active) && $active == 'bank_account' ? 'active' : '' }}">
                            <a href="{{ route('setting.bank_account') }}" class="menu-link">
                                <i class="menu-icon tf-icons mdi mdi-bank-plus"></i>
                                <div data-i18n="Akun Bank Deposito">Akun Bank Deposito</div>
                            </a>
                        </li>
                    @endrole
                    <li class="menu-item {{ isset($active) && $active == 'sdeposito' ? 'active' : '' }}">
                        <a href="{{ route('setting.deposito') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-credit-card-outline"></i>
                            <div data-i18n="Deposito">Deposito</div>
                        </a>
                    </li>
                    <li class="menu-item {{ isset($active) && $active == 'swithdrawal' ? 'active' : '' }}">
                        <a href="{{ route('setting.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-currency-usd"></i>
                            <div data-i18n="Penarikan">Penarikan</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Components -->
            <li class="menu-item {{ isset($page) && $page == 'deposito' ? 'active' : '' }}">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons mdi mdi-layers-outline"></i>
                    <div data-i18n="Deposito">Deposito</div>
                </a>
                <ul class="menu-sub">
                    <!-- Cards -->
                    <li class="menu-item {{ isset($active) && $active == 'adddeposito' ? 'active' : '' }}">
                        <a href="{{ route('deposito.add') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-credit-card-outline"></i>
                            <div data-i18n="Buka Deposito">Buka Deposito</div>
                        </a>
                    </li>
                    <li class="menu-item {{ isset($active) && $active == 'listdeposito' ? 'active' : '' }}">
                        <a href="{{ route('deposito.list') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-google-circles-extended"></i>
                            <div data-i18n="List Deposito">List Deposito</div>
                        </a>
                    </li>
                    <li class="menu-item {{ isset($active) && $active == 'profit' ? 'active' : '' }}">
                        <a href="{{ route('deposito.list_profit') }}" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-google-circles-extended"></i>
                            <div data-i18n="List Penghasilan">List Penghasilan</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Forms -->
            <li class="menu-item {{ isset($page) && $page == 'listwithdrawal' ? 'active' : '' }}">
                <a href="{{ route('withdrawal.list') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-checkbox-marked-outline"></i>
                    <div data-i18n="Penarikan">Penarikan</div>
                </a>
            </li>
            <li class="menu-item {{ isset($page) && $page == 'balance' ? 'active' : '' }}">
                <a href="{{ route('mutation.balance') }}" class="menu-link">
                    <i class="menu-icon tf-icons mdi mdi-wallet-outline"></i>
                    <div data-i18n="Saldo">Saldo</div>
                </a>
            </li>
            @endrole
        </ul>
    </div>
</aside>
