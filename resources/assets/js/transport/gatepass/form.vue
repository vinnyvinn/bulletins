<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Gate Pass</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" v-model="gatepass.gatepass_date" class="form-control input-sm datepicker" id="gatepass_date" name="gatepass_date" required>
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
                        <h5><strong>Vehicle Reg.No:</strong>{{ journey.truck.plate_number }}</h5>
                        <h5><strong>Model:</strong> {{ journey.truck.model.make.name }} {{ journey.truck.model.name }}
                        </h5>
                        <div v-if="journey.truck.trailer">
                            <h5><strong>Trailer: </strong>{{ journey.truck.trailer.plate_number }}</h5>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <h5><strong>Route: RT-{{ journey.route.id}}</strong></h5>
                        <h5><strong>From:</strong> {{ journey.route.source }}</h5>
                        <h5><strong>To:</strong> {{ journey.route.destination }}</h5>
                    </div>


                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <img :src="getSource()" alt="" width="100%" height="100%"> <br>
                            </div>
                            <div class="col-sm-8">
                                <h5><strong>Name:</strong> {{ journey.driver.first_name }}  {{ journey.driver.last_name }}</h5>
                                <h5><strong>ID No:</strong> {{ journey.driver.identification_number }}</h5>
                                <h5><strong>DL number:</strong> {{ journey.driver.dl_number }}</h5>
                                <h5><strong>Mobile No:</strong> {{ journey.driver.mobile_phone }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-sm-6">
                        <h4><strong>Cargo Type</strong></h4>
                        <h5>{{ gatepass.cargo }}</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="narration">Narration</label>
                            <textarea type="text" v-model="gatepass.narration" class="form-control" id="narration"></textarea>
                        </div>
                    </div>
                </div>
                
                <hr>

                <div class="form-group pull-right">
                    <button class="btn btn-success">Save</button>
                    <router-link to="/gatepass" class="btn btn-danger">Back</router-link>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    export default {
        created() {
            if (!this.$route.params.id && !this.$route.params.unload && !this.$root.can('create-gatepass')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id && !this.$root.can('edit-gatepass')) {
                this.$router.push('/403');
                return false;
            }

            this.$root.isLoading = true;

            if (this.$route.params.id) {
                return http.get('/api/gatepass/' + this.$route.params.id).then((response) => {
                    this.journey = response.gatepass.journey;
                    this.gatepass = response.gatepass;
                    this.$root.isLoading = false;
                });
            }

            http.get('/api/gatepass/create/?id=' + this.$route.params.new + '&s=' + window.Laravel.station_id).then((response) => {
                this.journey = response.journey;
                this.gatepass.journey_id = response.journey.id;
                this.gatepass.cargo = response.journey.contract.cargo_type.name;
                this.$root.isLoading = false;
            });
        },

        mounted() {
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
            $('#gatepass_date').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '0d',
                autoclose: true,
            }).on('change', (e) => {
                this.gatepass.gatepass_date = e.target.value;
            });
        },

        data() {
            return {
                journey: {
                    contract: { cargoType: {} },
                    driver: {},
                    truck: {
                        trailer: {},
                        model: { make: {} }
                    },
                    route: {},
                },
                
                gatepass: {
                    station_id: window.Laravel.station_id,
                    journey_id: '',
                    gatepass_date: '',
                    narration: '',
                    cargo: '',
                },
            };
        },

        methods: {
            getSource() {
                if (this.journey.driver.avatar) {
                    return '/images/' + this.journey.driver.avatar;
                }
                return '/images/default_avatar.png';
            },
            store() {
                this.$root.isLoading = true;
                let request = null;

                let body = Object.assign({}, this.gatepass);

                if (this.$route.params.id) {
                    request = axios.put('/api/gatepass/' + this.$route.params.id, body)
                } else {
                    request = axios.post('/api/gatepass', body)
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.data.message], 'success');
                    window._router.push({path: '/gatepass'});
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
