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
                            <div class="col-sm-2" v-for="truck in privateState.awaiting">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-awaiting">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-pre-loading"><h5>PRE-LOADING</h5></div>
                            <div class="col-sm-2" v-for="truck in privateState.pre_loading">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-pre-loading">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-loading"><h5>LOADING</h5></div>
                            <div class="col-sm-2" v-for="truck in privateState.loading">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-loading">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-enroute"><h5>ENROUTE</h5></div>
                            <div class="col-sm-2" v-for="truck in privateState.enroute">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-enroute">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-offloading"><h5>OFFLOADING</h5></div>
                            <div class="col-sm-2" v-for="truck in privateState.offloading">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-offloading">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-in-yard"><h5>IN YARD</h5></div>
                            <div class="col-sm-2" v-for="truck in privateState.in_yard">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-in-yard">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-incident"><h5>INCIDENT OCCURRED</h5></div>
                            <div class="col-sm-2" v-for="truck in privateState.incident">
                                <router-link :to="'/trucks/' + truck.id" class="text-center truck truck-incident">
                                    <i class="fa fa-4x fa-truck center-block"></i>
                                    <h5>{{ truck.plate_number }}</h5>
                                </router-link>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-12 bg-workshop"><h5>AT WORKSHOP</h5></div>
                            <div class="col-sm-2" v-for="truck in privateState.workshop">
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
            // setup the trucks
        },
        mounted() {
            this.prepareTrucks();
        },
        data() {
            return {
                sharedState: window._mainState,
                privateState: {
                    awaiting: [],
                    pre_loading: [],
                    loading: [],
                    enroute: [],
                    offloading: [],
                    in_yard: [],
                    incident: [],
                    workshop: []
                }
            };
        },

        methods: {
            prepareTrucks() {
                this.sharedState.state.trucks.forEach((truck) => {
                    if (truck.status == 'At Workshop') {
                        this.privateState.workshop.push(truck);
                        return;
                    }
                    if (truck.currentState == 'Awaiting Allocation') {
                        this.privateState.awaiting.push(truck);
                        return;
                    }
                    if (truck.currentState == 'Pre-Loading') {
                        this.privateState.pre_loading.push(truck);
                        return;
                    }
                    if (truck.currentState == 'Loading') {
                        this.privateState.loading.push(truck);
                        return;
                    }
                    if (truck.currentState == 'Enroute') {
                        this.privateState.enroute.push(truck);
                        return;
                    }
                    if (truck.currentState == 'Offloading') {
                        this.privateState.offloading.push(truck);
                        return;
                    }
                    if (truck.currentState == 'In Yard') {
                        this.privateState.in_yard.push(truck);
                        return;
                    }
                    if (truck.currentState == 'Incident') {
                        this.privateState.incident.push(truck);
                    }
                })
            },

        }
    }
</script>
