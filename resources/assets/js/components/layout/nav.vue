<template>
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
                <router-link class="navbar-brand" to="/">
                    {{ app }}
                </router-link>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li v-if="can('view-dashboard')"><router-link to="/dashboard"><i class="fa fa-tachometer"></i> Dashboard</router-link></li>
                    <li v-if="can('view-contracts')"><router-link to="/contracts"><i class="fa fa-file"></i> Contracts</router-link></li>
                    <li v-if="can('view-allocation')"><router-link to="/allocation"><i class="fa fa-truck"></i> Truck Allocations</router-link></li>
                    <li v-if="can('view-progress')" class="dropdown">
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





                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-check"></i> Workshop <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><router-link to="/job-card"> Job Cards</router-link></li>
                        </ul>
                    </li>






                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown" v-if="can('view-administration')">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-lock"></i> Administration <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li v-if="can('view-drivers')"><router-link to="/drivers"><i class="fa fa-users"></i> Drivers</router-link></li>
                            <li v-if="can('view-routes')"><router-link to="/routes"><i class="fa fa-road"></i> Routes</router-link></li>
                            <li v-if="can('view-trailers')"><router-link to="/trailers"><i class="fa fa-flag"></i> Trailers</router-link></li>
                            <li v-if="can('view-trucks')"><router-link to="/trucks"><i class="fa fa-truck"></i> Trucks</router-link></li>
                            <li v-if="can('view-trucks')"><a href="/administrator"><i class="fa fa-truck"></i> Administration</a></li>
                            <!--<li class="dropdown-header">User Accounts</li>-->
                            <li class="dropdown-header">System Settings</li>
                            <li v-if="can('view-trucks')"><router-link to="/udfs"><i class="fa fa-truck"></i> User Defined Fields </router-link></li>

                            <!--<li v-if="can('view-users')"><router-link to="/users"><i class="fa fa-group"></i> Users</router-link></li>-->

                        </ul>
                    </li>
                    <!-- Authentication Links -->

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ $root.user.first_name }} {{ $root.user.last_name }}<span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a @click.prevent="logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</template>

<script>
    export default {
        created() {
            this.$root.user = JSON.parse(localStorage.getItem('fewuia32rfwe'));
        },
        data() {
            return {}
        },

        props: {
            app: {
                required: true,
                default: ''
            }
        },

        methods: {
            logout() {
                localStorage.removeItem('foeiwafwfuwe');
                localStorage.removeItem('fewuia32rfwe');
                http.post('/logout', {}).then(() => window.location = '/login');
            },

            can(permission) {
                return window.can(permission)
            }
        }
    }
</script>
