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
                            <label for="job_card_id">Job Card</label>
                            <select class="" name="job_card_id" v-model="gatepass.job_card_id" class="form-control input-sm select2" required>
                              <option v-for="card in jobCards" :value="card.id">JC-{{ card.id }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <h5>
                          <strong>Vehicle Reg.No:</strong>{{ jobCard.truck.plate_number }}
                        </h5>
                        <h5>
                          <strong>Model:</strong> {{ jobCard.truck.model.make.name }} {{ jobCard.truck.model.name }}
                        </h5>
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
              jobCards: [],

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

        computed: {
          jobCard() {
            
          },
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
