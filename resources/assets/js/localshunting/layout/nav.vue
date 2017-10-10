<template>
    <div class="hidden-print">
        <div class="top-nav">
            <h4 class="pull-left brand">
                <strong>{{ app }}</strong>
            </h4>
            <router-link style="margin-right: 30px;margin-top: 3px" to="/ls/contract-selection" class="pull-right btn btn-success">
                Change Contract
            </router-link>
            <router-link style="margin-right: 30px;margin-top: 3px" to="/station-selection" class="pull-right btn btn-success" v-if="showSwitch">
                Change Station
            </router-link>
        </div>

        <div class="bottom-nav">

            <router-link to="/ls/dashboard" class="nav-items">
                <img src="/images/home.png" alt="home" class="img-responsive">
                <div class="caption">Home</div>
            </router-link>

            <router-link to="/contracts" class="nav-items" v-if="$root.can('create-contract') || $root.can('view-contract') || $root.can('edit-contract') || $root.can('approve-contract') || $root.can('delete-contract') ||
                    $root.can('create-contract-template') || $root.can('view-contract-template') || $root.can('edit-contract-template') || $root.can('delete-contract-template')">
                <img src="/images/contract.png" alt="contract" class="img-responsive">
                <div class="caption">Contracts</div>
            </router-link>

            <router-link to="/ls/trucks-allocation" class="nav-items" v-if="$root.can('ls-create-allocation') || $root.can('ls-view-allocation') || $root.can('ls-edit-allocation') || $root.can('ls-delete-allocation')">
                <img src="/images/journey.png" alt="journey" class="img-responsive">
                <div class="caption">Allocation</div>
            </router-link>

            <router-link to="/ls/fuel" class="nav-items" v-if="$root.can('ls-create-fuel') || $root.can('ls-view-fuel') || $root.can('ls-edit-fuel') || $root.can('ls-approve-fuel') || $root.can('ls-delete-fuel')">
                <img src="/images/fuel.png" alt="fuel" class="img-responsive">
                <div class="caption">Fuel Allocation</div>
            </router-link>

            <router-link to="/ls/gatepass" class="nav-items" v-if="$root.can('ls-create-gatepass') || $root.can('ls-view-gatepass') || $root.can('ls-edit-gatepass') || $root.can('ls-approve-gatepass') || $root.can('ls-delete-gatepass')">
                <img src="/images/gatepass.png" alt="inspection" class="img-responsive">
                <div class="caption">GatePass Inwards</div>
            </router-link>

            <router-link to="/ls/delivery" class="nav-items" v-if="$root.can('ls-create-delivery') || $root.can('ls-view-delivery') || $root.can('ls-edit-delivery') || $root.can('ls-approve-delivery') || $root.can('ls-delete-delivery')">
                <img src="/images/delivery.png" alt="delivery" class="img-responsive">
                <div class="caption">Delivery Note</div>
            </router-link>

            <router-link to="/ls/mileage" class="nav-items" v-if="$root.can('ls-create-mileage') || $root.can('ls-view-mileage') || $root.can('ls-edit-mileage') || $root.can('ls-approve-mileage') || $root.can('ls-delete-mileage')">
                <img src="/images/millage.png" alt="millage" class="img-responsive">
                <div class="caption">Mileage</div>
            </router-link>

            <router-link to="/ls/reports" v-if="$root.can('reports')" class="nav-items">
                <img src="/images/report.png" alt="fuel" class="img-responsive">
                <div class="caption">Reports</div>
            </router-link>

            <!-- <router-link to="/route-card/create" class="nav-items" v-if="$root.can('create-route-card') || $root.can('view-route-card') || $root.can('edit-route-card') || $root.can('approve-route-card') || $root.can('delete-route-card')">
                                    <img src="/images/card.png" alt="card" class="img-responsive">
                                    <div class="caption">Route Card</div>
                                </router-link> -->

            <!-- <router-link to="/reports" class="nav-items">
                                    <img src="/images/report.png" alt="card" class="img-responsive">
                                    <div class="caption">Reports</div>
                                </router-link> -->

            <a href="/" class="nav-items">
                <img src="/images/switch.png" alt="logout" class="img-responsive">
                <div class="caption">Long Haul</div>
            </a>

            <div @click.prevent="logout" class="nav-items">
                <img src="/images/logout.png" alt="logout" class="img-responsive">
                <div class="caption">Logout</div>
            </div>
        </div>
    </div>
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
            },

            showSwitch() {
                return this.$root.user.stations.length > 1;
            }
        }
    }
</script>
