<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Vehicle Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="type">Type</label>
                                <select v-model="truck.type" class="form-control" id="type" name="type" required>
                                    <option value="Truck">Truck</option>
                                    <option value="Trailer">Trailer</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Bulldozer">Bulldozer</option>
                                    <option value="Compact">Compact</option>
                                    <option value="Crane">Crane</option>
                                    <option value="DoubleCab Pick-up">DoubleCab Pick-up</option>
                                    <option value="Excavator">Excavator</option>
                                    <option value="Fuel Tanker">Fuel Tanker</option>
                                    <option value="Lorry">Lorry</option>
                                    <option value="Mini-Bus">Mini-Bus</option>
                                    <option value="Motor Cycle">Motor Cycle</option>
                                    <option value="Motor Grader">Motor Grader</option>
                                    <option value="Motor Grader">Motor Grader</option>
                                    <option value="Other">Other</option>
                                    <option value="Pickup">Pickup</option>
                                    <option value="Roller">Roller</option>
                                    <option value="Saloon">Saloon</option>
                                    <option value="Station Wagon">Station Wagon</option>
                                    <option value="Van">Van</option>
                                    <option value="Wheel-Loader">Wheel-Loader</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sub_contracted">Sub-Contract Vehicle</label>
                                <select v-model="truck.sub_contracted" class="form-control" id="sub_contracted" name="sub_contracted" required>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="plate_number">Plate Number</label>
                                <input v-model="truck.plate_number" type="text" class="form-control text-uppercase" id="plate_number" name="plate_number" required>
                            </div>

                            <div class="form-group">
                                <label for="make">Make</label>
                                <select v-model="truck.make_id" class="form-control" id="make" name="make" required>
                                    <option v-for="make in makes" :value="make.id">{{ make.name }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="model">Model</label>
                                <select v-model="truck.model_id" class="form-control" id="model" name="model" required>
                                    <option v-for="model in models" :value="model.id">{{ model.name }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="max_load">T/Weight</label>
                                <div class="input-group">
                                    <input v-model="truck.max_load" min="0" type="number" class="form-control" id="max_load" name="max_load" describedby="max_load-addon" required>
                                    <span class="input-group-addon" id="max_load-addon">KGs</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status">Truck Status</label>
                                <select v-model="truck.status" name="status" id="status" class="form-control" required>
                                    <option value="Active">Active</option>
                                    <option value="Incident">Incident</option>
                                    <option value="Central Truck Yard">Central Truck Yard</option>
                                </select>
                            </div>

                            <div class="form-group" v-if="truck.type == 'Truck'">
                                <label for="trailer_id">Attached Trailer</label>
                                <select v-model="truck.trailer_id" name="trailer_id" id="trailer_id" class="form-control">
                                    <option value="">No Trailer</option>
                                    <option v-for="trailer in trailers" :value="trailer.id">{{ trailer.plate_number }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="driver_id">Assigned Driver</label>
                                <select v-model="truck.driver_id" name="driver_id" id="driver_id" class="form-control">
                                    <option value="">No Driver</option>
                                    <option v-for="driver in drivers" :value="driver.id">{{ driver.first_name }} {{ driver.last_name }}</option>
                                </select>
                            </div>

                            <udf module="Trucks" v-on:udfAdded="addUdfToObject" :state="truck"></udf>
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
        created() {
            this.$root.isLoading = true;

            if (this.$route.params.id) {
                return http.get('/api/truck/create?truck_id=' + this.$route.params.id)
                    .then((response) => {
                        this.makes = response.makes;
                        this.trailers = response.trailers;
                        this.drivers = response.drivers;
                        setTimeout(() => {
                            this.truck = response.truck;
                        }, 500);
                    })
                    .then(() => this.$root.isLoading = false)
                    .catch(() => this.$root.isLoading = false);
            }

            http.get('/api/truck/create').then((response) => {
                this.makes = response.makes;
                this.trailers = response.trailers;
                this.drivers = response.drivers;
            })
                .then(() => this.$root.isLoading = false)
                .catch(() => this.$root.isLoading = false);
        },

        computed: {
            models() {
                if (! this.truck.make_id) return [];

                let filtered = this.makes.filter(e => e.id == this.truck.make_id);
                if (! filtered.length) return [];

                return filtered[0].models;
            }
        },

        data() {
            return {
                trailers: [],
                drivers: [],
                makes: [],
                truck: {
                    sub_contracted: 0,
                    type: 'Truck',
                    trailer_id: '',
                    plate_number: '',
                    max_load: '',
                    make_id: null,
                    model_id: null,
                    status: 'Active',
                    location: 'Awaiting Allocation',
                    driver_id: ''
                }
            };
        },

        methods: {
            store() {
                let request = null;

                if (this.$route.params.id) {
                    console.log("hello world ---not me");
                    request = http.put('/api/truck/' + this.$route.params.id, this.truck);
                } else {
                    console.log("hello world --- me");
                    request = http.post('/api/truck/', this.truck);
                }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/trucks' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },

            addUdfToObject (slug) {
              Vue.set(this.truck,slug,'');
            }
        }
    }
</script>
