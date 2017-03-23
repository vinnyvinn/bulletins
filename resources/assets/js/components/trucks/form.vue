<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Truck Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store">

                            <div class="form-group">
                                <label for="plate_number">Plate Number</label>
                                <input v-model="truck.plate_number" type="text" class="form-control text-uppercase" id="plate_number" name="plate_number" required>
                            </div>

                            <div class="form-group">
                                <label for="load_weight">Max Load Weight</label>
                                <div class="input-group">
                                    <input v-model="truck.load_weight" min="0" type="number" class="form-control" id="load_weight" name="load_weight" describedby="load_weight-addon" required>
                                    <span class="input-group-addon" id="load_weight-addon">Tonnes</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status">Truck Status</label>
                                <select v-model="truck.status" name="status" id="status" class="form-control" required>
                                    <option value="Active">Active</option>
                                    <option value="At Workshop">At Workshop</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="driver_id">Assigned Drivers</label>
                                <select v-model="truck.driver_id" name="driver_id" id="driver_id" class="form-control" required>
                                    <option v-for="driver in drivers" :value="driver.id">{{ driver.name }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-success">Save</button>
                                <router-link to="/trucks" class="btn btn-danger">Back</router-link>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.checkState();
        },

        computed: {
            drivers() {
                return this.sharedState.state.drivers;
//                if (this.$route.params.id) {
//                    return this.sharedState.state.drivers;
//                }

//                return this.sharedState.state.drivers.filter((driver) => {
//                    return driver.truck_id == null;
//                });
            }
        },

        data() {
            return {
                sharedState: window._mainState,
                truck: {
                    id: null,
                    driver_id: null,
                    plate_number: '',
                    load_weight: '',
                    status: 'Active',
                    currentState: 'Awaiting Allocation',
                    contract_id: null,
                    driver: null,
                    contract: null,
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    this.truck = this.sharedState.state.trucks[this.$route.params.id];
                    return;
                }

                this.truck.id = this.sharedState.state.trucks.length;
            },

            store() {
                if (this.$route.params.id) {
                    if (this.sharedState.state.trucks[this.$route.params.id]['status'] != 'Active') {
                        this.truck.currentState = 'Awaiting Allocation';
                    }
                    this.sharedState.state.trucks[this.$route.params.id] = this.truck;
                    window._router.push({ path: '/trucks' });
                    return;
                }
                let driver = this.sharedState.state.drivers.filter(rec => {
                    return rec.id == this.truck.driver_id;
                })[0];


                this.truck.driver = driver;

                this.sharedState.state.trucks.push(this.truck);


                driver.truck_id = this.truck.id;
                this.sharedState.state.drivers[driver.id] =  driver;

                window._router.push({ path: '/trucks' })
            }
        }
    }
</script>
