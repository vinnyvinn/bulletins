<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <h4 class="text-center">WELCOME TO YOUR DASHBOARD</h4>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-awaiting"><h5>AWAITING ALLOCATION</h5></div>
                            <div class="col-sm-2" v-for="truck in awaiting">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-awaiting">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-pre-loading"><h5>PRE-LOADING</h5></div>
                            <div class="col-sm-2" v-for="truck in pre_loading">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-pre-loading">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-loading"><h5>LOADING</h5></div>
                            <div class="col-sm-2" v-for="truck in loading">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-loading">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-enroute"><h5>ENROUTE</h5></div>
                            <div class="col-sm-2" v-for="truck in enroute">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-enroute">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-offloading"><h5>OFFLOADING</h5></div>
                            <div class="col-sm-2" v-for="truck in offloading">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-offloading">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-in-yard"><h5>IN YARD</h5></div>
                            <div class="col-sm-2" v-for="truck in in_yard">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-in-yard">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-incident"><h5>INCIDENT OCCURRED</h5></div>
                            <div class="col-sm-2" v-for="truck in incident">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-incident">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-workshop"><h5>AT WORKSHOP</h5></div>
                            <div class="col-sm-2" v-for="truck in workshop">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-workshop">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            http.get('/api/truck').then(response => {
                this.prepareTrucks(response.trucks);
            });
        },

        data() {
            return {
                sharedState: window._mainState,
                awaiting: [],
                pre_loading: [],
                loading: [],
                enroute: [],
                offloading: [],
                in_yard: [],
                incident: [],
                workshop: []
            };
        },

        methods: {
            prepareTrucks(trucks) {
                trucks.forEach((truck) => {
                    if (truck.status == 'Central Truck Yard') {
                        this.workshop.push(truck);
                        return;
                    }
                    if (truck.location == 'Awaiting Allocation') {
                        this.awaiting.push(truck);
                        return;
                    }
                    if (truck.location == 'Pre-Loading') {
                        this.pre_loading.push(truck);
                        return;
                    }
                    if (truck.location == 'Loading') {
                        this.loading.push(truck);
                        return;
                    }
                    if (truck.location == 'Enroute') {
                        this.enroute.push(truck);
                        return;
                    }
                    if (truck.location == 'Offloading') {
                        this.offloading.push(truck);
                        return;
                    }
                    if (truck.location == 'In Yard') {
                        this.in_yard.push(truck);
                        return;
                    }
                    if (truck.location == 'Incident') {
                        this.incident.push(truck);
                    }
                })
            },

        }
    }
</script>
