<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Fuel Per Route Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="route_id">Route</label>
                                        <select v-model="fuelRoute.route_id" name="route_id" id="route_id" class="form-control" required>
                                            <option v-for="route in routes" :value="route.id">{{ route.source }} - {{ route.destination }} ({{ route.distance }} KMs)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="make_id">Vehicle Make</label>
                                        <select v-model="fuelRoute.make_id" name="make_id" id="make_id" class="form-control" required>
                                            <option v-for="make in makes" :value="make.id">{{ make.name }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="model_id">Vehicle Model</label>
                                        <select v-model="fuelRoute.model_id" name="model_id" id="model_id" class="form-control" required>
                                            <option v-for="model in models" :value="model.id">{{ model.name }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="fuel_required">Fuel Required</label>
                                        <div class="input-group">
                                            <input onclick="this.select()" v-model="fuelRoute.amount" min="1" type="number" class="form-control" id="fuel_required" name="fuel_required" describedby="fuel-addon" required>
                                            <span class="input-group-addon" id="fuel-addon">Litres</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-success">Save</button>
                                        <router-link to="/fuel-routes" class="btn btn-danger">Back</router-link>
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
        data() {
            return {
                routes: [],
                makes: [],
                fuelRoutes: [],
                fuelRoute: {
                    route_id: null,
                    make_id: null,
                    model_id: null,
                    amount: 0
                }
            };
        },
        computed: {
            make() {
               if (! Object.keys(this.makes).length) return { models: [] };
               if (! this.fuelRoute.make_id) return { models: [] };

               return this.makes[this.fuelRoute.make_id];
            },
            models() {
                if (! this.make.models.length) return [];

                let routeFuels = this.fuelRoutes.filter(e => e.route_id == this.fuelRoute.route_id);
                let allocatedModels = routeFuels.map(fuel => fuel.model_id);

                return this.make.models.filter(model => allocatedModels.indexOf(model.id.toString()) === -1);
            },

        },
        created() {
            http.get('/api/fuel-route/create').then((response) => {
                this.processResponse(response);
            });
        },
        methods: {
            processResponse(response) {
                this.routes = response.routes;
                this.makes = response.makes;
                this.fuelRoutes = response.fuelRoutes;
                let routeKeys = Object.keys(this.routes);
                let makesKeys = Object.keys(this.makes);
                this.fuelRoute.route_id = routeKeys.length ? this.routes[routeKeys[0]].id : null;
                this.fuelRoute.make_id = makesKeys.length ? this.makes[makesKeys[0]].id : null;
                this.setupSelect2();
            },
            setupSelect2() {
                $(document).ready(() => {
                    setTimeout(() => {
                        let route = $('#route_id');
                        let make = $('#make_id');
                        let model = $('#model_id');

                        route.select2().select2('destroy');
                        route.select2().on('change', e => this.fuelRoute.route_id = e.target.value);

                        make.select2().select2('destroy');
                        make.select2().on('change', e => {
                            this.fuelRoute.make_id = e.target.value;
                            this.fuelRoute.model_id = null;
                            model.select2().select2('destroy');
                            setTimeout(() => {
                                model.select2().on('change', e => this.fuelRoute.model_id = e.target.value);
                            }, 100);
                        });

                        model.select2().select2('destroy');
                        model.select2().on('change', e => this.fuelRoute.model_id = e.target.value);
                    }, 200)
                });
            },
            store(route) {
                this.$root.isLoading = true;
                let request = null;

                if (this.$route.params.id) {
                    request = http.put('/api/fuel-route/' + this.$route.params.id, this.fuelRoute);
                } else {
                    request = http.post('/api/fuel-route', this.fuelRoute);
                }

                request.then((response) => {
                    this.fuelRoute = {
                        route_id: null,
                        make_id: null,
                        model_id: null,
                        amount: 0
                    };
                    this.processResponse(response);
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
//                    window._router.push({ path: '/fuel-routes' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
