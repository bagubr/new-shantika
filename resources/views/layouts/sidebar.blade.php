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
                <li class="nav-item {{Request::routeIs('fleets.*','fleetclass.*') ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{Request::routeIs('fleets.*','fleetclass.*') ? 'active' : ''}}">
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
                <li class="nav-item">
                    <a href="{{route('routes.index')}}"
                        class="nav-link {{Request::routeIs('routes.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-route"></i>
                        <p>
                            Rute
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('agency.index')}}"
                        class="nav-link {{Request::routeIs('agency.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-street-view"></i>
                        <p>
                            Agent
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