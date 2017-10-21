<template>
    <div class="container">
        <div class="row hidden-print">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>ROUTE CARD GENERATION</strong>
                    </div>
                    <div class="panel-body">
                        <h3 class="text-center">{{ application_name }}</h3>
                        <h4 class="text-center text-uppercase">Route Card</h4>
                        <hr>
                        <form action="#" @submit.prevent="store">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="journey_id">Journey:
                                            <h5>
                                                <strong>{{ journey.id }}</strong>
                                            </h5>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <h5>
                                            <strong>Vehicle: {{ journey.truck_plate_number }}</strong>
                                        </h5>
                                        <h5>
                                            <strong>Trailer: {{ journey.truck.trailer ? journey.truck.trailer.plate_number : '' }}</strong>
                                        </h5>
                                    </div>
                                </div>

                                <div class="col-sm-3" v-if="journey.truck">
                                    <div class="form-group">
                                        <label>Driver</label>
                                        <h5>
                                            <strong>{{ journey.driver.first_name }} {{ journey.driver.last_name }}</strong>
                                        </h5>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Route</label>
                                        <h5 v-if="journey.route">
                                            <strong>{{ journey.route.source }} to {{ journey.route.destination }}</strong>
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- <div class="form-group">
                                            <label for="arrival_date">Truck Arrival Date</label>
                                            <input type="text" class="form-control datepicker" id="arrival_date" v-model="card.arrival_date" required>
                                        </div> -->
                                    <div class="form-group">
                                        <label for="departure_date">Truck Depature Date</label>
                                        <input type="text" class="form-control datepicker" id="departure_date" v-model="card.departure_date" required>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <!-- <div class="form-group">
                                            <label for="arrival_date">Truck Arrival Time</label>
                                            <input type="time" class="form-control" id="arrival_time" v-model="card.arrival_time" required>
                                        </div> -->
                                    <div class="form-group">
                                        <label for="departure_time">Truck Depature Time</label>
                                        <input type="time" class="form-control" id="departure_time" v-model="card.departure_time" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Process Route Card">
                                <router-link to="/rate-cards" class="btn btn-danger">Back</router-link>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="visible-print-block" v-html="printout"></div>
    </div>
</template>

<script>
    export default {
        created() {
            if (!this.$route.params.id && !this.$root.can('create-route-card')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id && !this.$root.can('edit-route-card')) {
                this.$router.push('/403');
                return false;
            }
        },

        mounted() {
            if (this.$route.params.journey) {
                this.$root.isLoading = true;
                http.get('/api/new_inspection/' + this.$route.params.journey + '?s=' + window.Laravel.station_id)
                    .then((response) => {
                        this.journey = response.journey;
                        this.card.journey_id = this.journey.id;
                        this.card.station_id = this.journey.station_id;
                        this.$root.isLoading = false;

                        return;
                    });
            }

            if (this.$route.params.id) {
                this.$root.isLoading = true;
                http.get('/api/route-card/' + this.$route.params.id + '?s=' + window.Laravel.station_id).then((response) => {
                    if (response.status !== 'success') return;
                    let card = response.card;
                    // card.arrival_time = this.formatTime(card.arrival_time);
                    card.depature_time = this.formatTime(card.depature_time);
                    this.card = card;
                    this.journey = response.card.journey;

                    this.$root.isLoading = false;
                });
            }

            $(document).ready(() => {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                    startDate: '0d',
                });

                // $('#arrival_date').datepicker().on('changeDate', (e) => {
                //     this.card.arrival_date = e.date.toLocaleDateString('en-GB');
                // }).on('change', function (e) {
                //     $('#departure_date').datepicker('setStartDate', e.target.value);
                // });
                $('#departure_date').datepicker().on('changeDate', (e) => {
                    this.card.departure_date = e.target.value;
                });
            });
        },

        data() {
            return {
                card: {
                    journey_id: null,
                    // arrival_date: null,
                    // arrival_time: null,
                    departure_date: null,
                    departure_time: null,
                },
                journey: {
                    driver: {},
                    truck: {
                        trailer: {}
                    },
                    route: {}
                },
                printout: '',
                application_name: window.Laravel.appname,
            }
        },

        methods: {
            formatTime(value) {
                console.log(value);
                let item = value.split('.');
                return item[0];
            },

            date2(value) {
                return window._date2(value);
            },

            store() {
                this.$root.isLoading = true;
                let endpoint = '/api/route-card';
                let method = 'post';
                if (this.$route.params.id) {
                    endpoint = '/api/route-card/' + this.$route.params.id;
                    method = 'put';
                }

                http[method](endpoint, this.card).then(response => {
                    this.$root.isLoading = false;
                    if (response.shouldPrint) {
                        window.open(response.printout, '_blank');
                    }
                    setTimeout(() => {
                        window._router.push({ path: '/route-cards' });
                        alert2(this.$root, [response.message], 'success');
                    }, 500);
                }).catch(() => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
