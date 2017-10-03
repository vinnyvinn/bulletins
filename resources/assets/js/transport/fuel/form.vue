<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Fuel Allocation</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" v-model="fuel.date" class="form-control input-sm datepicker" id="date" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="journey_id">Journey</label>
                            <input type="text" class="form-control" disabled :value="'JRNY-' + journey.id">
                            <!--<select class="" name="journey_id" v-model="fuel.journey_id" class="form-control input-sm select2" required @change="selectJourney">-->
                            <!--<option v-for="journey in journeys" :value="journey.id">JRNY-{{ journey.id }}</option>-->
                            <!--</select>-->
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h5>
                            <strong>Vehicle Reg.No:</strong>{{ journey.truck.plate_number }}</h5>
                        <h5>
                            <strong>Model:</strong> {{ journey.truck.model.name }}
                        </h5>
                        <div v-if="journey.truck.trailer">
                            <h5>
                                <strong>Trailer: </strong>{{ journey.truck.trailer.plate_number }}</h5>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <h5>
                            <strong>Route: RT-{{ journey.route.id}}</strong>
                        </h5>
                        <h5>
                            <strong>From:</strong> {{ journey.route.source }}</h5>
                        <h5>
                            <strong>To:</strong> {{ journey.route.destination }}</h5>
                    </div>

                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <img :src="getSource()" alt="" width="100%" height="100%"> <br>
                            </div>
                            <div class="col-sm-8">
                                <h5>
                                    <strong>Name:</strong> {{ journey.driver.first_name }} {{ journey.driver.last_name }}</h5>
                                <h5>
                                    <strong>ID No:</strong> {{ journey.driver.identification_number }}</h5>
                                <h5>
                                    <strong>DL number:</strong> {{ journey.driver.dl_number }}</h5>
                                <h5>
                                    <strong>Mobile No:</strong> {{ journey.driver.mobile_phone }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="current_fuel">Current Fuel (Litres)</label>
                            <input type="number" id="current_fuel" class="form-control input-sm" v-model="fuel.current_fuel" @keyup="calculateTotal">
                            <p v-if="below_reserve">
                                Current fuel below reserve. Driver to pay for {{ this.deficit }} litre(s).
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Standard Quantity for this Route (Litres)</label><br>
                            <h4>{{ fuelAmount }}</h4>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="fuel_issued">Requested Quantity (Litres)</label>
                            <input disabled type="number" id="fuel_requested" class="form-control input-sm" v-model="fuel.fuel_requested">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="fuel_issued">Fuel Issued (Litres)</label>
                            <input type="number" min=0 :max="fuel.fuel_requested" id="fuel_issued" class="form-control input-sm" v-model="fuel.fuel_issued" @keyup="calculateTotal">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="tank">Tank to fill</label>
                            <input type="text" name="tank" id="tank" class="form-control input-sm" v-model="fuel.tank">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pump">Pump</label>
                            <input type="text" name="pump" id="pump" class="form-control input-sm" v-model="fuel.pump">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="fuel_total">Total Fuel in Tank(Litres)</label>
                            <input disabled type="text" id="fuel_total" class="form-control input-sm" v-model="fuel.fuel_total">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="narration">Description</label>
                            <textarea id="narration" class="form-control input-sm" v-model="fuel.narration"></textarea>
                        </div>
                    </div>
                    <hr>

                    <div class="col-sm-3">
                        <label for="top_up">Top Up?</label>
                        <input type="checkbox" name="top_up" id="top_up" v-model="fuel.top_up">
                    </div>
                    <div v-if="fuel.top_up">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="top_up_quantity">Top Up quantity:</label>
                                <input @keyup="calculateTotal" type="number" class="form-control" id="top_up_quantity" v-model="fuel.top_up_quantity">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="top_up_reason">Top up reason</label>
                            <textarea name="narration" id="top_up_reason" class="form-control input-sm" v-model="fuel.top_up_reason"></textarea>
                        </div>
                    </div>
                </div>
                <br>
                <strong>Mileage Readings</strong>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="current_km">Current KM</label>
                            <input step="0.1" type="number" :min="minimumKm" id="current_km" class="form-control input-sm" v-model="fuel.current_km" @change="calculateKms">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="previous_km">Previous KM</label>
                            <input disabled type="number" id="previous_km" class="form-control input-sm" v-model="journey.truck.current_km">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="previous_fuel">Previous Fuel</label>
                            <input disabled type="number" id="previous_fuel" class="form-control input-sm" v-model="journey.truck.current_fuel">
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <h5>
                            <strong>KM Covered:</strong> {{ isNaN(km_covered) ? '' : km_covered }}</h5>
                        <h5>
                            <strong>Fuel Used:</strong> {{ isNaN(fuel_used) ? '' : fuel_used }}</h5>
                        <h5>
                            <strong>KM/Ltr:</strong> {{ isNaN(km_per_litre) ? '' : km_per_litre.toFixed(2) }}</h5>
                    </div>

                </div>

                <div class="form-group pull-right">
                    <button class="btn btn-success" :disabled="! can_save">Save</button>
                    <router-link to="/fuel" class="btn btn-danger">Back</router-link>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                fuel_reserve: 25,
                journey: {
                    driver: {},
                    truck: {
                        trailer: {},
                        model: { make: {} }
                    },
                    route: {},
                },
                fuelRoute: {},
                journeys: [],
                fuelRoutes: [],
                km_covered: 0,
                fuel_used: 0,
                km_per_litre: 0,
                fuel: {
                    station_id: window.Laravel.station_id,
                    journey_id: '',
                    date: '',
                    current_fuel: 0,
                    fuel_requested: 0,
                    fuel_issued: 0,
                    fuel_total: 0,
                    narration: '',
                    previous_km: 0,
                    previous_fuel: 0,
                    current_km: 0,
                    status: 'Awaiting Approval',
                    tank: '',
                    pump: '',
                    top_up: false,
                    top_up_reason: '',
                    top_up_quantity: 0,
                },
                can_save: true,
                below_reserve: false,
                deficit: ''
            };
        },
        created() {
            if (!this.$route.params.id && !this.$route.params.unload && !this.$root.can('create-fuel')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id && !this.$root.can('edit-fuel')) {
                this.$router.push('/403');
                return false;
            }

            this.$root.isLoading = true;
            this.can_save = true;

            if (this.$route.params.id) {
                return http.get('/api/fuel/' + this.$route.params.id).then((response) => {
                    this.journey = response.fuel.journey;
                    this.fuelRoute = response.fuelRoute ? response.fuelRoute : { amount: 0 };
                    this.fuel = response.fuel;
                    this.fuel.top_up = this.fuel.top_up != '0';
                    this.setupUI();
                    //                    this.calculateKms();
                    this.$root.isLoading = false;
                });
            }

            http.get('/api/fuel/create/?id=' + this.$route.params.new + '&s=' + window.Laravel.station_id).then((response) => {
                this.journey = response.journey;
                this.fuelRoute = response.fuelRoute ? response.fuelRoute : { amount: 0 };
                this.fuel_requested = this.fuelRoute.amount;
                this.fuel.journey_id = response.journey.id;
                this.setupUI();
            }).then(() => {
                //                this.calculateKms();
                this.$root.isLoading = false;
            });
        },

        mounted() {
            $('input[type="number"]').on('focus', function() {
                this.select();
            });
        },


        computed: {
            minimumKm() {
                return this.fuel.previous_km;
            },

            fuelAmount() {
                if (!this.fuelRoute.amount) return 0;

                return parseInt(this.fuelRoute.amount);
            },
        },

        methods: {
            setupUI() {
                $(document).ready(() => {
                    $('.datepicker').datepicker({
                        autoclose: true,
                        format: 'dd/mm/yyyy',
                        todayHighlight: true,
                        startDate: '0d',
                    });

                    $('#date').datepicker().on('changeDate', (e) => {
                        this.fuel.date = e.date.toLocaleDateString('en-GB');
                    });
                });
            },

            calculateTotal() {
                let reserve = parseInt(this.fuel_reserve);
                let current_fuel = parseInt(this.fuel.current_fuel);
                let route_fuel_required = parseInt(this.fuelAmount);
                let topup = this.fuel.top_up ? parseInt(this.fuel.top_up_quantity) : 0;

                reserve = isNaN(reserve) ? 0 : reserve;
                current_fuel = isNaN(current_fuel) ? 0 : current_fuel;
                route_fuel_required = isNaN(route_fuel_required) ? 0 : route_fuel_required;
                topup = isNaN(topup) ? 0 : topup;

                if (parseInt(this.fuel.current_fuel) < reserve) {
                    this.deficit = reserve - current_fuel;
                    this.below_reserve = true;
                } else {
                    this.below_reserve = false;
                }

                this.fuel.fuel_requested = (route_fuel_required + reserve + topup) - current_fuel;

                return this.fuel.fuel_total = parseInt(this.fuel.fuel_issued) + parseInt(this.fuel.current_fuel);
            },

            calculateKms() {
                if (parseInt(this.fuel.current_km) < parseInt(this.journey.truck.current_km)) {
                    this.can_save = true;
                    this.fuel.current_km = 0;
                    alert2(this.$root, ['Current Km readings should be greater than previous Km reading'], 'danger');
                    return false;
                }
                this.fuel_used = parseInt(this.journey.truck.current_fuel) - parseInt(this.fuel.current_fuel);
                this.km_covered = parseInt(this.fuel.current_km) - parseInt(this.journey.truck.current_km);

                if (parseInt(this.fuel.current_km) > 0 && parseInt(this.fuel.current_km) > parseInt(this.journey.truck.current_km)) {
                    this.can_save = true;
                }

                this.km_per_litre = parseInt(this.km_covered) / parseInt(this.fuel_used);

                return true;
            },

            getSource() {
                if (this.journey.driver.avatar) {
                    return '/images/' + this.journey.driver.avatar;
                }
                return '/images/default_avatar.png';
            },
            store() {
                if (!this.calculateKms()) return;
                this.$root.isLoading = true;
                let request = null;

                let body = Object.assign({}, this.fuel);

                if (this.$route.params.id) {
                    request = axios.put('/api/fuel/' + this.$route.params.id, body)
                } else {
                    request = axios.post('/api/fuel', body)
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.data.message], 'success');
                    window._router.push({ path: '/fuel' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
