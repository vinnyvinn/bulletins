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
                                        <label for="fuel_required">Fuel Required</label>
                                        <div class="input-group">
                                            <input onclick="this.select()" v-model="route.fuel_required" min="1" type="number" class="form-control" id="fuel_required" name="fuel_required" describedby="fuel-addon" required>
                                            <span class="input-group-addon" id="fuel-addon">Litres</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="allowance_amount">Allowance Amount</label>
                                        <input onclick="this.select()" v-model="route.allowance_amount" min="0" type="number" class="form-control" id="allowance_amount" name="allowance_amount" required>
                                    </div>

                                    <udf module="Routes" v-on:udfAdded="addUdfToObject" :state="route"></udf>

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
            http.get('/api/route/create').then((response) => {
                this.locations = response.locations;
            });
        },
        mounted() {
            this.checkState();
        },
        data() {
            return {
                locations: [],
                route: {
                    source: '',
                    destination: '',
                    distance: '',
                    fuel_required: 1,
                    allowance_amount: 0
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    http.get('/api/route/' + this.$route.params.id).then((response) => {
                        this.route = response.route;
                    });
                }
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


<!--export default {-->
        <!--mounted() {-->
            <!--this.checkState();-->
        <!--},-->
        <!--data() {-->
            <!--return {-->
                <!--sharedState: window._mainState,-->
                <!--driver: {-->
                    <!--_token: window.Laravel.csrfToken,-->
                    <!--_method: 'POST',-->
                    <!--name: '',-->
                    <!--national_id: '',-->
                    <!--dl_number: '',-->
                    <!--mobile: ''-->
                <!--},-->
                <!--errors: [],-->
                <!--level: 'danger',-->
                <!--showError: false-->
            <!--};-->
        <!--},-->

        <!--methods: {-->
            <!--checkState() {-->
                <!--if (this.$route.params.id) {-->
                    <!--this.driver._method = 'PUT';-->
                    <!--http.get('/api/driver/' + this.$route.params.id).then((response) => {-->
                        <!--this.driver = response.driver;-->
                    <!--});-->

                    <!--return;-->
                <!--}-->
            <!--},-->

            <!--store() {-->
                <!--let request = null;-->

                <!--if (this.$route.params.id) {-->
                    <!--request = http.put('/api/driver/' + this.$route.params.id, this.driver);-->
                <!--} else {-->
                    <!--request = http.post('/api/driver', this.driver);-->
                <!--}-->

                <!--request.then((response) => {-->
                    <!--alert2(this.$root, [response.message], 'success');-->
                    <!--window._router.push({ path: '/drivers' });-->
                <!--}).catch((error) => {-->
                    <!--alert2(this.$root, Object.values(JSON.parse(error.message)), 'error');-->
                <!--});-->
            <!--}-->
        <!--}-->
    <!--}-->
