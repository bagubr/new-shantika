<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link text-center">
        <span class="brand-text font-weight-light">New Shantika</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link {{Request::routeIs('dashboard') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dasboard
                        </p>
                    </a>
                </li>
                <li class="nav-item {{Request::routeIs('fleets.*','fleetclass.*','layouts.*') ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{Request::routeIs('fleets.*','fleetclass.*','layouts.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-bus"></i>
                        <p>
                            Armada
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('fleets.index')}}"
                                class="nav-link {{Request::routeIs('fleets.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Armada</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('fleetclass.index')}}"
                                class="nav-link {{Request::routeIs('fleetclass.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kelas Armada</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('layouts.index')}}"
                                class="nav-link {{Request::routeIs('layouts.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Layout Armada</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{Request::routeIs('area.*','province.*','city.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('area.*','province.*','city.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-map-marker"></i>
                        <p>
                            Wilayah
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('area.index')}}"
                                class="nav-link {{Request::routeIs('area.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('province.index')}}"
                                class="nav-link {{Request::routeIs('province.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Provinsi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('city.index')}}"
                                class="nav-link {{Request::routeIs('city.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kota</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('order.index')}}" class="nav-link {{Request::routeIs('order.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Pemesanan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('routes.index')}}"
                        class="nav-link {{Request::routeIs('routes.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-route"></i>
                        <p>
                            Rute
                        </p>
                    </a>
                </li>
                <li class="nav-item {{Request::routeIs('agency.*','user_agent.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('agency.*','user_agent.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-street-view"></i>
                        <p>
                            Agent
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('agency.index')}}"
                                class="nav-link {{Request::routeIs('agency.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Agent</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('user_agent.index')}}"
                                class="nav-link {{Request::routeIs('user_agent.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Akun Agent</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('time_classification.index')}}"
                        class="nav-link {{Request::routeIs('time_classification.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>
                            Waktu
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('information.index')}}"
                        class="nav-link {{Request::routeIs('information.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-info"></i>
                        <p>
                            Informasi
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('facility.index')}}"
                        class="nav-link {{Request::routeIs('facility.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-sign"></i>
                        <p>
                            Fasilitas
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('article.index')}}"
                        class="nav-link {{Request::routeIs('article.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>
                            Artikel
                        </p>
                    </a>
                </li>
                <li class="nav-item {{Request::routeIs('payment_type.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('payment_type.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Pembayaran
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('payment_type.index')}}"
                                class="nav-link {{Request::routeIs('payment_type.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipe Pembayaran</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Request::is('schedule_*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{Request::is('schedule_*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Jadwal
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('schedule_not_operate.index') }}" class="nav-link {{ Request::routeIs('schedule_not_operate.*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Operasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('schedule_unavailable_booking.index') }}" class="nav-link {{ Request::routeIs('schedule_unavailable_booking.*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>Booking Tidak Tersedia</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">USER</li>
                <li class="nav-item">
                    <a href="{{route('user.index')}}" class="nav-link {{Request::routeIs('user.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('customer_menu.index')}}"
                        class="nav-link {{Request::routeIs('customer_menu.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>
                            Menu Customer
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('chat.index')}}" class="nav-link {{Request::routeIs('chat.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-comment"></i>
                        <p>
                            Chat
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('slider.index')}}"
                        class="nav-link {{Request::routeIs('slider.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-sliders-h"></i>
                        <p>
                            Slider
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('testimoni.index')}}"
                        class="nav-link {{Request::routeIs('testimoni.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-quote-left"></i>
                        <p>
                            Testimoni
                        </p>
                    </a>
                </li>
                <li class="nav-header">LAINNYA</li>
                <li class="nav-item">
                    <a href="{{route('faq.index')}}" class="nav-link {{Request::routeIs('faq.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-question"></i>
                        <p>
                            FAQ
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('about.index')}}" class="nav-link {{Request::routeIs('about.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-info"></i>
                        <p>
                            Tentang Kita
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('terms_condition.index')}}"
                        class="nav-link {{Request::routeIs('terms_condition.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-balance-scale"></i>
                        <p>
                            Syarat dan Ketentuan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('social_media.index')}}"
                        class="nav-link {{Request::routeIs('social_media.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-hashtag"></i>
                        <p>
                            Social Media
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('privacy_policy.index')}}"
                        class="nav-link {{Request::routeIs('privacy_policy.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            Kebijakan Privasi
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Keluar
                        </p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>