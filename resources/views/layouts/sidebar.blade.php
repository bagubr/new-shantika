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
                @unlessrole('restaurant')
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
                <li class="nav-header">Restoran</li>
                @hasanyrole('superadmin')
                <li class="nav-item">
                    <a href="{{route('restaurant.index')}}"
                        class="nav-link {{Request::routeIs('restaurant.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-utensils"></i>
                        <p>
                            Restoran
                        </p>
                    </a>
                </li>
                @endrole
                <li class="nav-item">
                    <a href="{{route('r.history_restaurant')}}"
                        class="nav-link {{Request::routeIs('r.history_restaurant') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>
                            Riwayat Kupon Restoran
                        </p>
                    </a>
                </li>
                @endunlessrole
                @hasanyrole('superadmin|ticketing|owner')
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
                <li class="nav-item {{Request::routeIs('routes.*','fleet_route.*', 'fleet_route_prices.*', 'route_setting.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('routes.*','fleet_route.*', 'fleet_route_prices.*', 'route_setting.*') ? 'active' : ''}}">
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
                        @foreach (\App\Models\Area::get() as $area)
                        @if(Auth::user()->area_id && Auth::user()->area_id == $area->id || Auth::user()->area_id == null)
                        <li class="nav-item">
                            <a href="{{route('route_setting.index', ['area_id' => $area->id])}}"
                                class="nav-link @if (Request::routeIs('route_setting*') && $area->id == request()->area_id)
                                active
                                @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rute {{$area->name}}</p>
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item {{Request::routeIs('fleet_route_prices.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('fleet_route_prices.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Jadwal
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach (\App\Models\Area::get() as $area)
                        @if(Auth::user()->area_id && Auth::user()->area_id == $area->id || Auth::user()->area_id == null)
                        <li class="nav-item">
                            <a href="{{route('fleet_route_prices.index', ['area_id' => $area->id])}}"
                                class="nav-link @if (Request::routeIs('fleet_route_prices*') && $area->id == request()->area_id)
                                    active
                                @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Jadwal {{$area->name}}
                                </p>
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </li>
                <li class=" nav-item {{Request::routeIs('agency.*','user_agent.*') ? 'menu-open' : ''}}">
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
                <li class="nav-item {{Request::routeIs('agency_route.*', 'agency_fleet.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('agency_route.*', 'agency_fleet.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Agen Setting
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach (\App\Models\Area::get() as $area)
                        @if(Auth::user()->area_id && Auth::user()->area_id == $area->id || Auth::user()->area_id == null)
                        <li class="nav-item">
                            <a href="{{route('agency_route.index', ['area_id' => $area->id])}}"
                                class="nav-link @if (Request::routeIs('agency_route*') && $area->id == request()->area_id)
                                active
                                @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rute {{$area->name}}</p>
                            </a>
                        </li>
                        @endif
                        @endforeach
                        @foreach (\App\Models\Area::get() as $area)
                        @if(Auth::user()->area_id && Auth::user()->area_id == $area->id || Auth::user()->area_id == null)
                        <li class="nav-item">
                            <a href="{{route('agency_fleet.index', ['area_id' => $area->id])}}"
                                class="nav-link @if (Request::routeIs('agency_fleet*') && $area->id == request()->area_id) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Armada {{$area->name}}</p>
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item {{Request::routeIs('membership_histories.*', 'member.*', 'promo.*') ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{Request::routeIs('membership_histories.*', 'member.*', 'promo.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Membership dan Promo
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('member.index')}}"
                                class="nav-link {{Request::routeIs('member.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Membership</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('membership_histories.index')}}"
                                class="nav-link {{Request::routeIs('membership_histories.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Membership History</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('promo.index')}}"
                                class="nav-link {{Request::routeIs('promo.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Promo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('souvenir.index')}}"
                                class="nav-link {{Request::routeIs('souvenir.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Daftar Souvenir</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('souvenir_redeem.index')}}"
                                class="nav-link {{Request::routeIs('souvenir_redeem.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Souvenir Redeem</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="nav-item {{Request::routeIs('user.*','status_penumpang.*','souvenir.*','souvenir_redeem.*') ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{Request::routeIs('user.*','status_penumpang.*','souvenir.*','souvenir_redeem.*') ? 'active' : ''}}">
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
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('time_change_route.index')}}"
                        class="nav-link {{Request::routeIs('time_change_route.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>
                            Perubahan Waktu
                        </p>
                    </a>
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
                @endrole
                @role('restaurant')
                <li class="nav-header">Restoran</li>
                <li class="nav-item">
                    <a href="{{route('restaurant.show_restaurant_detail')}}"
                        class="nav-link {{Request::routeIs('restaurant.show_restaurant_detail') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-utensils"></i>
                        <p>
                            Restoran Detail
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('restaurant_barcode.index')}}"
                        class="nav-link {{Request::routeIs('restaurant_barcode.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-barcode"></i>
                        <p>
                            Scan Barcode Resto
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('restaurant.history_restaurant_detail')}}"
                        class="nav-link {{Request::routeIs('restaurant.history_restaurant_detail') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>
                            Riwayat Kupon Restoran
                        </p>
                    </a>
                </li>
                @endrole
                <li class="nav-header">LAINNYA</li>
                @role('superadmin')
                <li class="nav-item {{Request::routeIs('admin.*','role.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('admin.*','role.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            Admin
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('admin.index')}}"
                                class="nav-link {{Request::routeIs('admin.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Admin</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('config_setting.index')}}"
                        class="nav-link {{Request::routeIs('config_setting.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Pengaturan Global</p>
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
                    <a href="{{route('broadcast_message.index')}}"
                        class="nav-link {{Request::routeIs('broadcast_message.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            Broadcast Message
                        </p>
                    </a>
                </li>
                @endrole
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
