<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="{{asset('assets/images/dashboard/1.png')}}" alt="" />
        <div class="badge-bottom"><span class="badge badge-primary">New</span></div>
        <a href="user-profile"> <h6 class="mt-3 f-14 f-w-600">Emay Walter</h6></a>
        <p class="mb-0 font-roboto">Human Resources Department</p>
        <ul>
            <li>
                <span><span class="counter">19.8</span>k</span>
                <p>Follow</p>
            </li>
            <li>
                <span>2 year</span>
                <p>Experince</p>
            </li>
            <li>
                <span><span class="counter">95.2</span>k</span>
                <p>Follower</p>
            </li>
        </ul>
    </div>
    <nav>
        <div class="main-navbar">
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{routeActive('index')}}" href="{{route('index')}}"><i data-feather="home"></i><span>Dashboard</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title Active" href="javascript:void(0)"><i data-feather="anchor"></i><span>Starter kit</span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/') }};">
                            <li>
                                <a class="submenu-title {{routeActive('datatable-AJAX')}} " href="{{ route('datatable-AJAX') }}">
                                    AJAX Data Table <span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                            <li>
                                <a class="submenu-title {{routeActive('form')}}" href="{{ route('form') }}">
                                    Collective Form<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                            <li>
                                <a class="submenu-title {{routeActive('permission')}}" href="{{ route('permission') }}">
                                    Permission<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                            <li>
                                <a class="submenu-title {{routeActive('user')}}" href="{{ route('user') }}">
                                    User<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                            <li>
                                <a class="submenu-title {{routeActive('role')}}" href="{{ route('role') }}">
                                    Role<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                               <li>
                                <a class="submenu-title {{routeActive('rolepermission')}}" href="{{ route('rolepermission') }}">
                                    Role In Permission <span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
