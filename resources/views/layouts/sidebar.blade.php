<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link">
        <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="New Shantika" class="brand-image img-circle elevation-3"
            style="opacity: .8">
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
                            Fleet
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('fleets.index')}}"
                                class="nav-link {{Request::routeIs('fleets.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fleet</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('fleetclass.index')}}"
                                class="nav-link {{Request::routeIs('fleetclass.*') ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fleet Class</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{route('information.index')}}"
                        class="nav-link {{Request::routeIs('information.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-info"></i>
                        <p>
                            Information
                        </p>
                    </a>
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
                    <a href="{{route('privacy_policy.index')}}"
                        class="nav-link {{Request::routeIs('privacy_policy.*') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            Privacy Policy
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" class="nav-link">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>
                            Logout
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