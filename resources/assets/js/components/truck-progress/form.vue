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
                                <label for="max_load">Max Load Weight</label>
                                <div class="input-group">
                                    <input v-model="truck.max_load" min="0" type="number" class="form-control" id="max_load" name="max_load" describedby="max_load-addon" required>
                                    <span class="input-group-addon" id="max_load-addon">Tonnes</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status">Truck Status</label>
                                <select v-model="truck.status" name="status" id="status" class="form-control" required>
                                    <option value="Active">Active</option>
                                    <option value="Central Truck Yard">Central Truck Yard</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="driver_id">Assigned Drivers</label>
                                <select v-model="truck.driver_id" name="driver_id" id="driver_id" class="form-control">
                                    <option value="">No Driver</option>
                                    <option v-for="driver in drivers" :value="driver.id">{{ truck.driver.first_name + ' ' + truck.driver.last_name }}</option>
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
        created() {
            http.get('/api/truck/create').then((response) => {
                this.drivers = response.drivers;
            });
        },

        mounted() {
            this.checkState();
        },

        data() {
            return {
                drivers: [],
                truck: {
                    driver_id: '',
                    plate_number: '',
                    max_load: '',
                    status: 'Active',
                    location: 'Awaiting Allocation',
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    http.get('/api/truck/' + this.$route.params.id).then((response) => {
                        this.truck = response.truck;
                    });
                }
            },

            store() {
                let request = null;

                if (this.$route.params.id) {
                    request = http.put('/api/truck/' + this.$route.params.id, this.truck);
                } else {
                    request = http.post('/api/truck', this.truck);
                }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/trucks' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
