<nav class="navbar navbar-default navbar-fixed-top hidden-print">
    <div class="container-fluid">
        <div class="navbar-header">
            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><router-link to="/dashboard"><i class="fa fa-tachometer"></i> Dashboard</router-link></li>
                <li><router-link to="/contracts"><i class="fa fa-file"></i> Contracts</router-link></li>
                <li><router-link to="/allocation"><i class="fa fa-truck"></i> Truck Allocations</router-link></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-check"></i> Progress <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li><router-link to="/progress/pre-loading"><i class="fa fa-truck"></i> Pre-Loading</router-link></li>
                        <li><router-link to="/progress/loading"><i class="fa fa-truck"></i> Loading</router-link></li>
                        <li><router-link to="/progress/enroute"><i class="fa fa-truck"></i> Enroute</router-link></li>
                        <li><router-link to="/progress/offloading"><i class="fa fa-truck"></i> Offloading</router-link></li>
                        <li><router-link to="/progress/in-yard"><i class="fa fa-truck"></i> In Yard</router-link></li>

                    </ul>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-lock"></i> Administration <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li><router-link to="/drivers"><i class="fa fa-users"></i> Drivers</router-link></li>
                        <li><router-link to="/routes"><i class="fa fa-road"></i> Routes</router-link></li>
                        <li><router-link to="/trailers"><i class="fa fa-flag"></i> Trailers</router-link></li>
                        <li><router-link to="/trucks"><i class="fa fa-truck"></i> Trucks</router-link></li>
                        <li><router-link to="/udfs"><i class="fa fa-truck"></i> UDFS</router-link></li>

                    </ul>
                </li>
                <!-- Authentication Links -->

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

