<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview">
                <a href="{{ url('/dashboard') }}">
                    <i class="fa fa-home"></i> <span>Home</span>
                </a>
            </li>
            <li class="treeview">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i>
                    <span>Masters</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="dropdown-header">GENERAL</li>
                    <li><a href="{{ route('workshop.stations.index') }}"><i class="fa fa-list"></i> Stations</a></li>
                    <li><a href="{{ url('/trucks') }}"><i class="fa fa-list"></i> Vehicles</a></li>
                    <li><a href="{{ url('/routes') }}"><i class="fa fa-list"></i> Routes</a></li>
                    {{--<li><a href="{{ route('workshop.mileage.index') }}"><i class="fa fa-list"></i> Mileage Types</a></li>--}}
                    <li><a href="{{ url('/fuel-routes') }}"><i class="fa fa-list"></i> Fuel Per Model</a></li>

                    <li class="dropdown-header">MAN POWER</li>
                    <li><a href="{{ url('/drivers') }}"><i class="fa fa-list"></i> Drivers</a></li>
{{--                    <li><a href="{{ route('super.employee.index', ['t' => 'drivers']) }}"><i class="fa fa-list"></i> Drivers</a></li>--}}
                    {{--<li><a href="{{ route('super.employee.index', ['t' => 'workshop']) }}"><i class="fa fa-list"></i> Workshop Employee</a></li>--}}
                    <li><a href="{{ route('workshop.users.index') }}"><i class="fa fa-list"></i> System Users</a></li>
                    <li class="dropdown-header">MAKES &amp; MODElS</li>
                    <li><a href="{{ route('workshop.make.index') }}"><i class="fa fa-list"></i> Makes</a></li>
                    <li><a href="{{ route('workshop.model.index') }}"><i class="fa fa-list"></i> Models</a></li>

                    <li class="dropdown-header">CONTRACTS</li>
                    <li><a href="{{ route('workshop.cargo-classification.index') }}"><i class="fa fa-list"></i> Cargo Classifications</a></li>
                    <li><a href="{{ route('workshop.cargo-type.index') }}"><i class="fa fa-list"></i> Cargo Types</a></li>
                    <li><a href="{{ route('workshop.carriage-point.index') }}"><i class="fa fa-list"></i> Loading & Offloading Points</a></li>

                    <li class="dropdown-header">WORKSHOP</li>
                    <li><a href="{{ route('workshop.inspection.index') }}"><i class="fa fa-list"></i> Inspection Checklist</a></li>
                    <li><a href="{{ route('workshop.job-type.index') }}"><i class="fa fa-list"></i> Job Types</a></li>
                    <li><a href="{{ route('workshop.job-operation.index') }}"><i class="fa fa-list"></i> Job Operations</a></li>
                    <li><a href="{{ route('workshop.job-task.index') }}"><i class="fa fa-list"></i> Operation Tasks</a></li>
                </ul>
            </li>

            <li class="treeview {{ Request::is('administrator/products')? 'active' :''  }}">
                <a href="{{ route('all_products') }}">
                    <i class="fa fa-wrench"></i>
                    <span>Spares</span>
                </a>
            </li>

            {{--<li class="treeview {{ Request::is('administrator/brands*')? 'active' :''  }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-leaf"></i>--}}
                    {{--<span>Models</span>--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ Request::is('administrator/brands')? 'active' :''  }}">--}}
                        {{--<a href="{{ route('all_brands') }}"><i class="fa fa-list"></i> Models </a>--}}
                    {{--</li>--}}
                    {{--<li class="{{ Request::is('administrator/brands/create')? 'active' :''  }}">--}}
                        {{--<a href="{{ route('create_brand') }}"><i class="fa  fa-plus-square"></i> Create Model </a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            {{----}}


            {{--<li class="treeview {{ Request::is('administrator/categories*')? 'active' :''  }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-folder-o"></i>--}}
                    {{--<span>Categories</span>--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ Request::is('administrator/categories')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('all_categories') }}"><i class="fa fa-bars"></i> Categories </a></li>--}}
                    {{--<li class="{{ Request::is('administrator/categories/create')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('create_category') }}"><i class="fa fa-plus-circle"></i> Create Category--}}
                        {{--</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}


            {{--<li class="treeview {{ Request::is('administrator/shop/sales*')? 'active' :''  }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-sellsy"></i>--}}
                    {{--<span>Sales</span>--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ Request::is('administrator/shop/sales')? 'active' :''  }}">--}}
                        {{--<a href="{{ route('admin_all_sales') }}"><i class="fa fa-list-alt"></i> <span>All Sales </span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li class="{{ Request::is('administrator/shop/sales/create')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('admin_new_sales') }}"><i class="fa fa-plus-square-o"></i>New Sales </a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}


            {{--<li class="treeview {{ Request::is('admin/repair-product*')? 'active' :''  }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-wrench"></i>--}}
                    {{--<span>Repair Product</span>--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ Request::is('admin/repair-product')? 'active' :''  }}">--}}
                        {{--<a href="{{ route('all_repair_product') }}"><i class="fa fa-list-alt"></i> All repair product--}}
                        {{--</a>--}}
                    {{--</li>--}}


                    {{--<li class="{{ Request::is('admin/repair-product/completed')? 'active' :''  }}">--}}
                        {{--<a href="{{ route('completed_repair_product') }}"><i class="fa fa-list-alt"></i> Completed--}}
                            {{--repair </a>--}}
                    {{--</li>--}}

                    {{--<li class="{{ Request::is('admin/repair-product/create')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('new_repair_product') }}"><i class="fa fa-plus-square-o"></i>Add new </a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}


            {{--<li class="treeview {{ Request::is('administrator/stock*')? 'active' :''  }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-bar-chart"></i>--}}
                    {{--<span>Stocks</span>--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ Request::is('administrator/stock')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('stock_index') }}"><i class="fa fa-list"></i> Stocks </a></li>--}}
                    {{--<li class="{{ Request::is('administrator/stock/create')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('create_stock') }}"><i class="fa fa-plus-square"></i>Add Stocks </a></li>--}}
                    {{--<li class="{{ Request::is('administrator/stock/transfer-to-stock')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('transfer_stock') }}"><i class="fa fa-share-alt"></i>Transfer to Shop</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}


            {{--<li class="treeview {{ Request::is('administrator/shop*')? 'active' :''  }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-shopping-cart"></i>--}}
                    {{--<span>Shops</span>--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ Request::is('administrator/shop')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('all_shops') }}"><i class="fa fa-list"></i> Shops </a></li>--}}
                    {{--<li class="{{ Request::is('administrator/shop/create')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('add_shop_admin') }}"><i class="fa fa-plus-square"></i> Add Shops </a>--}}
                    {{--</li>--}}

                {{--</ul>--}}
            {{--</li>--}}


            {{--<li class="treeview {{ Request::is('administrator/agents*')? 'active' :''  }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-users"></i>--}}
                    {{--<span>Staffs</span>--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ Request::is('administrator/agents')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('all_agents') }}"><i class="fa fa-list"></i> All Staff </a></li>--}}
                    {{--<li class="{{ Request::is('administrator/agents/create')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('admin_agent_create') }}"><i class="fa fa-plus-square-o"></i>Add Staff--}}
                        {{--</a></li>--}}

                {{--</ul>--}}
            {{--</li>--}}


            {{--<li class="treeview {{ Request::is('administrator/messages*')? 'active' :''  }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-envelope-o"></i>--}}
                    {{--<span>Messages</span>--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ Request::is('administrator/messages')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('admin_inbox') }}"><i class="fa fa-inbox"></i> Inbox</a></li>--}}
                    {{--<li class="{{ Request::is('administrator/messages/sent')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('admin_sent_message') }}"><i class="fa fa-send-o"></i> Sent</a></li>--}}
                    {{--<li class="{{ Request::is('administrator/messages/compose')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('admin_message_compose') }}"><i class="fa fa-pencil"></i> Compose </a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            {{--<li class="treeview {{ Request::is('administrator/account*')? 'active' :''  }}">--}}
                {{--<a href="#">--}}
                    {{--<i class="fa fa-user"></i>--}}
                    {{--<span>Account</span>--}}
                    {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                    {{--<li class="{{ Request::is('administrator/account/change-password')? 'active' :''  }}"><a--}}
                                {{--href="{{ route('change_password') }}"><i class="fa fa-circle-o"></i> Change Password</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}


            <li class="treeview {{ Request::is('administrator/settings*')? 'active' :''  }}">
                <a href="#">
                    <i class="fa fa-gears"></i>
                    <span>Settings</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('administrator/settings')? 'active' :''  }}">
                        <a href="{{ route('common_settings') }}">
                            <i class="fa fa-circle-o"></i> Common Settings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('workshop.integrations.index') }}">
                            <i class="fa fa-circle-o"></i> Integrations
                        </a>
                    </li>

                </ul>
            </li>


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
