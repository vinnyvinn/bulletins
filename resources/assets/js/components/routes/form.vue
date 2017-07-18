<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Route Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store" enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="source">From</label>
                                        <input list="locations" autocomplete="off" v-model="route.source" type="text" class="form-control" id="source" name="source" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="destination">To</label>
                                        <input list="locations" autocomplete="off" v-model="route.destination" type="text" class="form-control" id="destination" name="destination" required>
                                    </div>

                                    <datalist id="locations">
                                        <option v-for="location in locations" :value="location">{{ location }}</option>
                                    </datalist>

                                    <div class="form-group">
                                        <label for="distance">Distance</label>
                                        <div class="input-group">
                                            <input v-model="route.distance" min="0" type="number" class="form-control" id="distance" name="distance" describedby="distance-addon" required>
                                            <span class="input-group-addon" id="distance-addon">KM</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label for="fuel_required">Average Fuel Required</label>
                                        <div class="input-group">
                                            <input onclick="this.select()" v-model="route.fuel_required" type="number" class="form-control" id="fuel_required" name="fuel_required" describedby="fuel-addon">
                                            <span class="input-group-addon" id="fuel-addon">Litres</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="allowance_amount">Going Mileage</label>
                                        <input onclick="this.select()" v-model="route.allowance_amount" min="0" type="number" class="form-control" id="allowance_amount" name="allowance_amount" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="return_mileage">Return Mileage</label>
                                        <input onclick="this.select()" v-model="route.return_mileage" min="0" type="number" class="form-control" id="return_mileage" name="return_mileage" required>
                                    </div>

                                    <udf module="Routes" v-on:udfAdded="addUdfToObject" :state="route"></udf>

                                </div>

                                <div v-if="mileageTypes.length">
                                    <div class="col-sm-12">
                                        <h4>Mileage Types</h4>
                                    </div>

                                    <div v-for="mileage in mileageTypes" class="col-sm-6">
                                        <div class="form-group">
                                            <label :for="mileage.slug">{{ mileage.name }}</label>
                                            <input onclick="this.select()" v-model="route[mileage.slug]" min="0" type="number" class="form-control" :id="mileage.slug" :name="mileage.slug" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button class="btn btn-success">Save</button>
                                        <router-link to="/routes" class="btn btn-danger">Back</router-link>
                                    </div>
                                </div>
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
            if (this.$route.params.id) {
                return http.get('/api/route/' + this.$route.params.id).then((response) => {
                    this.locations = response.locations;
                    this.prepareMilageTypes(response.mileageTypes, response.route);
                    this.mileageTypes = response.mileageTypes;
                });
            }
            http.get('/api/route/create').then((response) => {
                this.locations = response.locations;
                this.prepareMilageTypes(response.mileageTypes);
                this.mileageTypes = response.mileageTypes;
            });
        },

        data() {
            return {
                locations: [],
                mileageTypes: [],
                route: {
                    source: '',
                    destination: '',
                    distance: '',
                    fuel_required: 1,
                    allowance_amount: 0,
                    return_mileage: 0,
                }
            };
        },

        methods: {
            prepareMilageTypes(types, info = null) {
                info = info ? info : {
                    source: '',
                    destination: '',
                    distance: '',
                    fuel_required: 1,
                    allowance_amount: 0,
                    return_mileage: 0,
                };

                let keys = Object.keys(info);

                types.forEach(type => {
                    if (keys.indexOf(type.slug) !== -1) return;
                    info[type.slug] = 0;
                });

                this.route = info;
            },

            store(route) {
                let request = null;

                if (this.$route.params.id) {
                    request = http.put('/api/route/' + this.$route.params.id, this.route);
                } else {
                    request = http.post('/api/route', this.route);
                }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/routes' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
            addUdfToObject (slug) {
              Vue.set(this.route,slug,'');
            }
        }
    }
</script>
