<template>
    <div>
        <div class="top-nav">
            <h4 class="pull-left brand"><strong>{{ app }}</strong></h4>
        </div>
        <div class="bottom-nav">

            <router-link to="/dashboard" class="nav-items">
                <img src="/images/home.png" alt="home" class="img-responsive">
                <div class="caption">Home</div>
            </router-link>

            <router-link to="/contracts" class="nav-items">
                <img src="/images/contract.png" alt="contract" class="img-responsive">
                <div class="caption">Contracts</div>
            </router-link>

            <router-link to="/journey" class="nav-items">
                <img src="/images/journey.png" alt="journey" class="img-responsive">
                <div class="caption">Journey Creation</div>
            </router-link>

            <router-link to="/inspection" class="nav-items">
                <img src="/images/inspection.png" alt="inspection" class="img-responsive">
                <div class="caption">Truck Inspection</div>
            </router-link>

            <router-link to="/contracts" class="nav-items">
                <img src="/images/millage.png" alt="millage" class="img-responsive">
                <div class="caption">Millage Allocation</div>
            </router-link>

            <router-link to="/fuel" class="nav-items">
                <img src="/images/fuel.png" alt="fuel" class="img-responsive">
                <div class="caption">Fuel Allocation</div>
            </router-link>

            <router-link to="/contracts" class="nav-items">
                <img src="/images/card.png" alt="card" class="img-responsive">
                <div class="caption">Route Card</div>
            </router-link>

            <router-link to="/contracts" class="nav-items">
                <img src="/images/delivery.png" alt="delivery" class="img-responsive">
                <div class="caption">Delivery Note</div>
            </router-link>

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
            }
        }
    }
</script>
