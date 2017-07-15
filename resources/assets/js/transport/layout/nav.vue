<template>
    <div>
        <div class="top-nav">
            <h4 class="pull-left brand"><strong>{{ app }}</strong></h4>
            <router-link style="margin-right: 30px;margin-top: 3px" to="/station-selection" class="pull-right btn btn-success" v-if="showSwitch">
                Change Station
            </router-link>
        </div>

        <div class="bottom-nav">

            <router-link to="/dashboard" class="nav-items">
                <img src="/images/home.png" alt="home" class="img-responsive">
                <div class="caption">Home</div>
            </router-link>

            <router-link to="/contracts" class="nav-items" v-if="$root.can('create-contract') || $root.can('view-contract') || $root.can('edit-contract') || $root.can('approve-contract') || $root.can('delete-contract') ||
$root.can('create-contract-template') || $root.can('view-contract-template') || $root.can('edit-contract-template') || $root.can('delete-contract-template')">
                <img src="/images/contract.png" alt="contract" class="img-responsive">
                <div class="caption">Contracts</div>
            </router-link>

            <router-link to="/journey" class="nav-items" v-if="$root.can('create-journey') || $root.can('view-journey') || $root.can('edit-journey') || $root.can('approve-journey') || $root.can('delete-journey')">
                <img src="/images/journey.png" alt="journey" class="img-responsive">
                <div class="caption">Journeys</div>
            </router-link>

            <router-link to="/inspection" class="nav-items" v-if="$root.can('create-inspection') || $root.can('view-inspection') || $root.can('edit-inspection') || $root.can('approve-inspection') || $root.can('delete-inspection')">
                <img src="/images/inspection.png" alt="inspection" class="img-responsive">
                <div class="caption">Inspection</div>
            </router-link>

            <router-link to="/delivery" class="nav-items" v-if="$root.can('create-delivery') || $root.can('view-delivery') || $root.can('edit-delivery') || $root.can('approve-delivery') || $root.can('delete-delivery')">
                <img src="/images/delivery.png" alt="delivery" class="img-responsive">
                <div class="caption">Delivery Note</div>
            </router-link>

            <router-link to="/mileage" class="nav-items" v-if="$root.can('create-mileage') || $root.can('view-mileage') || $root.can('edit-mileage') || $root.can('approve-mileage') || $root.can('delete-mileage')">
                <img src="/images/millage.png" alt="millage" class="img-responsive">
                <div class="caption">Mileage</div>
            </router-link>

            <router-link to="/fuel" class="nav-items" v-if="$root.can('create-fuel') || $root.can('view-fuel') || $root.can('edit-fuel') || $root.can('approve-fuel') || $root.can('delete-fuel')">
                <img src="/images/fuel.png" alt="fuel" class="img-responsive">
                <div class="caption">Fuel Allocation</div>
            </router-link>

            <!--<router-link to="/route-card/create" class="nav-items" v-if="$root.can('create-route-card') || $root.can('view-route-card') || $root.can('edit-route-card') || $root.can('approve-route-card') || $root.can('delete-route-card')">-->
                <!--<img src="/images/card.png" alt="card" class="img-responsive">-->
                <!--<div class="caption">Route Card</div>-->
            <!--</router-link>-->

            <!--<router-link to="/reports" class="nav-items">-->
                <!--<img src="/images/report.png" alt="card" class="img-responsive">-->
                <!--<div class="caption">Reports</div>-->
            <!--</router-link>-->

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

        computed: {
            showSwitch() {
                return this.$root.user.stations.length > 1;
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
        }
    }
</script>
