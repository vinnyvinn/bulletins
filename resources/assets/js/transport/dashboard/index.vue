<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">

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
