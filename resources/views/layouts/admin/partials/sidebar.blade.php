<header class="main-nav">
    <div class="sidebar-user text-center">
        @if (Auth::user()->image  == null)
            <a class="setting-primary" href="{{ route('myprofile') }}"><i data-feather="settings"></i></a><img
                class="img-90 rounded-circle" src="{{asset('assets/images/dashboard/1.png')}}" alt=""/>
        @else
            <a class="setting-primary" href="{{ route('myprofile') }}"><i data-feather="settings"></i></a><img
                class="img-90 rounded-circle" src="{{asset('uploads/'. Auth::user()->image)}}" alt=""/>
        @endif

        <div class="badge-bottom"></div>@if (Auth::check())
            <a href="{{ route('myprofile') }}"><h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6></a>
            <p class="mb-0 font-roboto">{{ Auth::user()->email }}</p>
        @endif
    </div>
    <nav>
        <div class="main-navbar">
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                                                              aria-hidden="true"></i></div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title {{routeActive('index')}}" href="{{route('index')}}"><i
                                data-feather="home"></i><span>Dashboard</span></a>
                    </li>
                    @can('medchine_purchase.management')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('medicine-purchase.index')}}"
                               href="{{route('medicine-purchase.index')}}"><i
                                    data-feather="shopping-bag"></i><span>Purchase</span></a>
                        </li>
                    @endcan

                    @can('distribute-medicine.management')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('distribute-medicine.index')}}"
                               href="{{route('distribute-medicine.index')}}"><i data-feather="plus-circle"></i><span>Distribute Medicine</span></a>
                        </li>
                    @endcan
                    @can('outletStock')

                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('outlet-stock.index')}}"
                               href="{{route('outlet-stock.index')}}"><i data-feather="shopping-cart"></i><span>Outlet Stock</span></a>
                        </li>
                    @endcan

                    @can('warehouseStock')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('warehouse-stock.index')}}"
                               href="{{route('warehouse-stock.index')}}"><i data-feather="box"></i><span>Warehouse Stock</span></a>
                        </li>
                    @endcan

                    @can('warehouse-return.management')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('warehouse-return.index')}}"
                               href="{{route('warehouse-return.index')}}"><i
                                    data-feather="box"></i><span>Medicine Return</span></a>
                        </li>
                    @endcan

                    @can('customer.management')
                    @php
                        if(Auth::user()->hasrole(['Super Admin', 'Admin'])){
                             $id = 'all';
                        }else{
                            $id = Auth::user()->outlet_id;
                        }
                    @endphp

                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('get-customer',$id)}}"
                               href="{{route('get-customer',$id)}}"><i data-feather="users"></i><span>Customer Management</span></a>
                        </li>
                    @endcan

                    @can('sent_stock_request')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('stock-request.index')}}"
                               href="{{route('stock-request.index')}}"><i data-feather="box"></i><span>Sent Stock Request</span></a>
                        </li>
                    @endcan

                    @can('invoice.management')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('invoice.index')}}"
                               href="{{route('invoice.index')}}"><i data-feather="box"></i><span>Product Sale</span></a>
                        </li>
                    @endcan
                    @can('stock_request')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('warehouseRequest')}}"
                               href="{{route('warehouseRequest')}}"><i data-feather="box"></i><span>Stock Request</span></a>
                        </li>
                    @endcan

                    @can('medicine.management')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{ prefixActive('/medicine-setting') }} "
                               href="javascript:void(0)"><i data-feather="anchor"></i><span>Medicine Settings</span></a>
                            <ul class="nav-submenu menu-content"
                                style="display:{{ prefixBlock('/medicine-setting') }};">
                                <li>

                                    <a class="submenu-title {{routeActive('category.index')}}"
                                       href="{{route('category.index')}}"><i
                                            data-feather="align-justify"></i><span>Category</span></a>
                                </li>
                                <li class="">
                                    <a class="submenu-title {{routeActive('unit.index')}}"
                                       href="{{route('unit.index')}}"><i
                                            data-feather="database"></i><span>Unit</span></a>
                                </li>

                                <li class="">
                                    <a class="submenu-title {{routeActive('manufacturer.index')}}"
                                       href="{{route('manufacturer.index')}}"><i data-feather="filter"></i><span>Manufacturer</span></a>
                                </li>

                                <li class="">
                                    <a class="submenu-title {{routeActive('medicine.index')}}"
                                       href="{{route('medicine.index')}}"><i
                                            data-feather="droplet"></i><span>Medicine</span></a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('payment-method.management')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('payment-method.index')}}"
                               href="{{route('payment-method.index')}}"><i data-feather="dollar-sign"></i><span>Payment Method</span></a>
                        </li>
                    @endcan

                    @can('supplier.management')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('supplier.index')}}"
                               href="{{route('supplier.index')}}"><i data-feather="truck"></i><span>Supplier</span></a>
                        </li>
                    @endcan

                    @can('outlet.management')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('outlet.index')}}"
                               href="{{route('outlet.index')}}"><i
                                    data-feather="home"></i><span>Outlet</span></a>
                        </li>
                    @endcan

                    @can('warehouse.management')
                        <li class="dropdown">
                            <a class="nav-link menu-title {{routeActive('warehouse.index')}}"
                               href="{{route('warehouse.index')}}"><i data-feather="database"></i><span>Warehouse</span></a>
                        </li>
                    @endcan


                    @hasrole('Super Admin')


                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('/admin') }}" href="javascript:void(0)"><i
                                data-feather="anchor"></i><span>Admin Area</span></a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('/admin') }};">
                            {{-- <li>
                                <a class="submenu-title {{routeActive('datatable-AJAX')}} " href="{{ route('datatable-AJAX') }}">
                                    AJAX Data Table <span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                            <li>
                                <a class="submenu-title {{routeActive('form')}}" href="{{ route('form') }}">
                                    Collective Form<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li> --}}
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
                                <a class="submenu-title {{routeActive('permission')}}" href="{{ route('permission') }}">
                                    Permission<span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>


                            <li>
                                <a class="submenu-title {{routeActive('rolepermission')}}"
                                   href="{{ route('rolepermission') }}">
                                    Role In Permission <span class="sub-arrow"><i
                                            class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                            <li>
                                <a class="submenu-title {{routeActive('allrolepermission')}}"
                                   href="{{ route('allrolepermission') }}">
                                    All Role In Permission <span class="sub-arrow"><i
                                            class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                            <li>
                                <a class="submenu-title {{routeActive('setting')}}" href="{{ route('setting') }}">
                                    Setttings <span class="sub-arrow"><i class="fa fa-chevron-right"></i></span>
                                </a>

                            </li>
                        </ul>
                    </li>
                    @endrole
                </ul>
            </div>
        </div>
    </nav>
</header>
