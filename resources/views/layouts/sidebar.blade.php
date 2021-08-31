<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link text-center">
        <img src="{{asset('img/img_logo.png')}}" alt="">
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
            <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link {{Request::routeIs('dashboard') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('sketch.index')}}"
                        class="nav-link {{Request::routeIs('sketch.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-map"></i>
                        <p>
                            Sketch
                        </p>
                    </a>
                </li>
                <li
                    class="nav-item {{Request::routeIs('payment_type.*','payment.*','order_price_distribution.*') ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{Request::routeIs('payment_type.*','payment.*','order_price_distribution.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Pendapatan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('order_price_distribution.index')}}"
                                class="nav-link {{Request::routeIs('order_price_distribution.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rekap Setoran & Tiket</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('payment.index')}}"
                                class="nav-link {{Request::routeIs('payment.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Riwayat Pembayaran</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('payment_type.index')}}"
                                class="nav-link {{Request::routeIs('payment_type.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipe Pembayaran</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('outcome.index')}}"
                        class="nav-link {{Request::routeIs('outcome*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-hand-holding-usd"></i>
                        <p>
                            Pengeluaran
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('order.index')}}" class="nav-link {{Request::routeIs('order.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Pembayaran
                        </p>
                    </a>
                </li>
                <li class="nav-header">OPERASIONAL TIKETING</li>
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
                <li class="nav-item {{Request::routeIs('routes.*','fleet_route.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('routes.*','fleet_route.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-route"></i>
                        <p>
                            Rute
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('routes.index')}}"
                                class="nav-link {{Request::routeIs('routes.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rute</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('fleet_route.index')}}"
                                class="nav-link {{Request::routeIs('fleet_route.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rute Armada</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{Request::routeIs('agency.*','user_agent.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('agency.*','user_agent.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-street-view"></i>
                        <p>
                            Agen
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('agency.index')}}"
                                class="nav-link {{Request::routeIs('agency.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Agen</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('user_agent.index')}}"
                                class="nav-link {{Request::routeIs('user_agent.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Akun Agen</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{Request::routeIs('user.*','status_penumpang.*','member.*') ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{Request::routeIs('user.*','status_penumpang.*','member.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Penumpang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('status_penumpang.index')}}"
                                class="nav-link {{Request::routeIs('status_penumpang.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Status Pembayaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('user.index')}}"
                                class="nav-link {{Request::routeIs('user.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Akun Penumpang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('member.index')}}"
                                class="nav-link {{Request::routeIs('member.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Member</p>
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
                {{-- <li class="nav-item {{ Request::is('schedule_*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{Request::is('schedule_*') ? 'active' : ''}}">
                    <i class="nav-icon fas fa-calendar"></i>
                    <p>
                        Jadwal
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('schedule_not_operate.index') }}"
                            class="nav-link {{ Request::routeIs('schedule_not_operate.*') ? 'active' : '' }}">
                            <i class="nav-icon far fa-circle"></i>
                            <p>Operasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('schedule_unavailable_booking.index') }}"
                            class="nav-link {{ Request::routeIs('schedule_unavailable_booking.*') ? 'active' : '' }}">
                            <i class="nav-icon far fa-circle"></i>
                            <p>Booking Tidak Tersedia</p>
                        </a>
                    </li>
                </ul>
                </li> --}}
                <li class="nav-item">
                    <a href="{{route('config_setting.index')}}"
                        class="nav-link {{Request::routeIs('config_setting.*') ? 'active' : ''}}">
                        <i class="fas fa-cogs"></i>
                        <p>
                            Pengaturan Global
                        </p>
                    </a>
                </li>
                <li class="nav-header">LAINNYA</li>
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
                            Kontak Bantuan
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('slider.index')}}"
                        class="nav-link {{Request::routeIs('slider.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-sliders-h"></i>
                        <p>
                            Slider Customer
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
                <li class="nav-item {{Request::routeIs('testimoni.*','review.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('testimoni.*','review.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-quote-left"></i>
                        <p>
                            Ulasan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('review.index')}}"
                                class="nav-link {{Request::routeIs('review.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Review</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('testimoni.index')}}"
                                class="nav-link {{Request::routeIs('testimoni.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Testimoni</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('faq.index')}}" class="nav-link {{Request::routeIs('faq.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-question"></i>
                        <p>
                            FAQ
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('bank_account.index')}}"
                        class="nav-link {{Request::routeIs('bank_account.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Data Bank
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('about.index')}}" class="nav-link {{Request::routeIs('about.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-info"></i>
                        <p>
                            Tentang Kami
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
                            Sosial Media
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