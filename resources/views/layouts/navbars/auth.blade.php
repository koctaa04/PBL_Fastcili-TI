<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('paper') }}/img/logo-small.png">
            </div>
        </a>
        <a href="#" class="simple-text logo-normal">
            {{ __('Creative Tim') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'dashboard') }}">
                    <i class="nc-icon nc-sun-fog-29"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'user' || $elementActive == 'profile' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="false" href="#kelolaPengguna">
                    <i class="nc-icon nc-single-02"></i>
                    <p>
                            {{ __('Kelola Pengguna') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="kelolaPengguna">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                            <a href="#">
                                <span class="sidebar-mini-icon">{{ __('L') }}</span>
                                <span class="sidebar-normal">{{ __(' Level ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                            <a href="#">
                                <span class="sidebar-mini-icon">{{ __('U') }}</span>
                                <span class="sidebar-normal">{{ __(' User ') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="{{ $elementActive == 'icons' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="true" href="#fasilitas">
                    <i class="nc-icon nc-bank"></i>
                    <p>
                            {{ __('Gedung & Fasilitas') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="fasilitas">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                            <a href="#">
                                <span class="sidebar-mini-icon">{{ __('F') }}</span>
                                <span class="sidebar-normal">{{ __(' Fasilitas ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                            <a href="#">
                                <span class="sidebar-mini-icon">{{ __('G') }}</span>
                                <span class="sidebar-normal">{{ __(' Gedung ') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="{{ $elementActive == 'laporan' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="true" href="#laporan">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>
                            {{ __('Laporan') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="laporan">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                            <a href="#">
                                <span class="sidebar-mini-icon">{{ __('L') }}</span>
                                <span class="sidebar-normal">{{ __(' Lapor Kerusakan ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                            <a href="#">
                                <span class="sidebar-mini-icon">{{ __('P') }}</span>
                                <span class="sidebar-normal">{{ __(' Prioritas Perbaikan ') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="{{ $elementActive == 'notifications' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="true" href="#teknisi">
                    <i class="nc-icon nc-bank"></i>
                    <p>
                            {{ __('Teknisi') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="teknisi">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                            <a href="#">
                                <span class="sidebar-mini-icon">{{ __('P') }}</span>
                                <span class="sidebar-normal">{{ __(' Penugasan Teknisi ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                            <a href="#">
                                <span class="sidebar-mini-icon">{{ __('R') }}</span>
                                <span class="sidebar-normal">{{ __(' Riwayat Pekerjaan ') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="active-pro mt-10 {{ $elementActive == 'tables' ? 'active' : '' }}">
                <form class="dropdown-item" action="{{ route('logout') }}" id="formLogOut" method="POST" style="display: none;">
                    @csrf
                </form>
                <a onclick="document.getElementById('formLogOut').submit();" class="bg-danger">
                    <i class="nc-icon nc-button-power text-white"></i>
                    <p class="text-white">{{ __('Log out') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>
