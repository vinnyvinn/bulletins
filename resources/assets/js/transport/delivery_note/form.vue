<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Delivery Note</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="journey_id">Journey Number</label>
                            <input disabled type="text" class="form-control" id="journey_id" :value="'JRN-' + journey.id">
                            <!--<select :disabled="typeof $route.params.unload === 'string'" v-model="deliveryNote.journey_id" @change="journey" class="form-control input-sm" id="journey_id" name="journey_id" required>-->
                                <!--<option v-for="journey in journeys" :value="journey.id">JRNY-{{ journey.id }}</option>-->
                            <!--</select>-->
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Journey Date</label>
                            <h5>{{ journey.job_date }}</h5>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Route From</label>
                            <h5>{{ journey.route.source }}</h5>
                        </div>
                        <!--<div class="form-group">-->
                            <!--<label>Journey Ref No.</label>-->
                            <!--<h5>{{ journey.ref_no }}</h5>-->
                        <!--</div>-->
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Route To</label>
                            <h5>{{ journey.route.destination }}</h5>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Vehicle Number</label>
                            <h5>{{ journey.truck.plate_number }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Trailer Type</label>
                            <h5>{{ journey.truck.trailer.type }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Trailer Attached</label>
                            <h5>{{ journey.truck.trailer.plate_number }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Driver Name</label>
                            <h5>{{ journey.driver.first_name }} {{ journey.driver.last_name }}</h5>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-sm-5">
                        <h4><strong>Loading Details</strong></h4>
                        <hr>
                        <div class="form-group">
                            <label for="bags_loaded">Bags Loaded</label>
                            <input :disabled="typeof $route.params.unload === 'string'" id="bags_loaded" min="0" v-model="deliveryNote.bags_loaded" type="number" class="form-control input-sm">
                        </div>

                        <div class="form-group">
                            <label for="loading_gross_weight">Gross Weight (Kgs)</label>
                            <input :disabled="typeof $route.params.unload === 'string'" @keyup="updateNote" id="loading_gross_weight" min="0" v-model="deliveryNote.loading_gross_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="loading_tare_weight">Tare Weight (Kgs)</label>
                            <input :disabled="typeof $route.params.unload === 'string'" @keyup="updateNote" id="loading_tare_weight" min="0" v-model="deliveryNote.loading_tare_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="loading_net_weight">Net Weight (Kgs)</label>
                            <input disabled id="loading_net_weight" min="0" v-model="deliveryNote.loading_net_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="loading_weighbridge_number">Weighbridge Ticket Number</label>
                            <input :disabled="typeof $route.params.unload === 'string'" id="loading_weighbridge_number" v-model="deliveryNote.loading_weighbridge_number" type="text" class="form-control input-sm">
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <h4><strong>Offloading Details</strong></h4>
                        <hr>

                        <div class="form-group">
                            <label for="offloading_gross_weight">Gross Weight</label>
                            <input :disabled="(typeof $route.params.unload !== 'string') && (typeof $route.params.id !== 'string')" @keyup="updateNote" id="offloading_gross_weight" min="0" v-model="deliveryNote.offloading_gross_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="offloading_tare_weight">Tare Weight</label>
                            <input :disabled="(typeof $route.params.unload !== 'string') && (typeof $route.params.id !== 'string')" @keyup="updateNote" id="offloading_tare_weight" min="0" v-model="deliveryNote.offloading_tare_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="offloading_net_weight">Net Weight</label>
                            <input disabled id="offloading_net_weight" min="0" v-model="deliveryNote.offloading_net_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="offloading_weighbridge_number">Weighbridge Ticket Number</label>
                            <input :disabled="(typeof $route.params.unload !== 'string') && (typeof $route.params.id !== 'string')" id="offloading_weighbridge_number" v-model="deliveryNote.offloading_weighbridge_number" type="text" class="form-control input-sm">
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <h4><strong>Narration</strong></h4>
                        <hr>
                        <div class="form-group">
                            <label for="narration">Narration</label>
                            <textarea v-model="deliveryNote.narration" name="narration" id="narration" rows="15" class="form-control input-sm"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-success">Process</button>
                    <router-link to="/delivery" class="btn btn-danger">Back</router-link>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                journey: {
                    driver: {},
                    truck: {
                        trailer: {},
                    },
                    route: {},
                },
                journeys: [],
                uploads: [],
                deliveryNote: {
                    station_id: window.Laravel.station_id,
                    journey_id: '',
                    narration: '',
                    bags_loaded: 0,
                    loading_gross_weight: 0,
                    loading_tare_weight: 0,
                    loading_net_weight: 0,
                    loading_weighbridge_number: '',
                    offloading_gross_weight: 0,
                    offloading_tare_weight: 0,
                    offloading_net_weight: 0,
                    offloading_weighbridge_number: '',
                }
            };
        },

        created() {
            if (! this.$route.params.id && ! this.$route.params.unload &&  ! this.$root.can('create-delivery')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id && ! this.$root.can('edit-delivery')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id || this.$route.params.unload) {
                this.checkState();
                return;
            }

            http.get('/api/delivery/create?id=' + this.$route.params.journey).then((response) => {
                this.journey = response.journey;
                this.deliveryNote.journey_id = response.journey.id;
            });
        },

        mounted() {
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },


        computed: {
            journeydddd() {
                let journey = this.journeys.filter(e => e.id == this.deliveryNote.journey_id);
                if (journey.length) {
                    return journey[0];
                    this.deliveryNote.loading_weighbridge_number = 'JRNY-'+journey[0].id;
                }

                return {
                    driver: {},
                    truck: {
                        trailer: {},
                    },
                    route: {},
                };
            },
        },

        methods: {
            updateNote() {
              if(parseFloat(this.deliveryNote.loading_gross_weight) >= parseFloat(this.deliveryNote.loading_tare_weight)) {
                this.deliveryNote.loading_net_weight = parseFloat(this.deliveryNote.loading_gross_weight) - parseFloat(this.deliveryNote.loading_tare_weight);
                this.deliveryNote.offloading_net_weight = parseFloat(this.deliveryNote.offloading_gross_weight) - parseFloat(this.deliveryNote.offloading_tare_weight);
              } else {
                alert2(this.$root, ['Tare Weight cannot be more than the gross weight'], 'danger');
                this.deliveryNote.loading_tare_weight = 0;
              }

            },

            checkState() {
                this.$root.isLoading = true;
                let id = this.$route.params.unload ? this.$route.params.unload : this.$route.params.id;

                return http.get('/api/delivery/' + id).then((response) => {
                    this.deliveryNote = response.delivery;
                    this.journey = response.journey;
                    this.deliveryNote.journey_id = response.journey.id;
                    this.$root.isLoading = false;
                });
            },
            store() {
                this.$root.isLoading = true;
                let request = null;

                let data = mapToFormData(this.deliveryNote, this.uploads, typeof this.$route.params.id === 'string' || typeof this.$route.params.unload === 'string');

                if (this.$route.params.id) {
                    request = http.put('/api/delivery/' + this.$route.params.id, data, true);
                } else if (this.$route.params.unload) {
                  if(this.journey.contract.capture_offloading_weights && this.deliveryNote.offloading_net_weight == 0) {
                    alert2(this.$root, ['This delivery requires capture of offloading weight'], 'danger');
                    this.$root.isLoading = false;
                    return;
                  }
                    request = http.put('/api/delivery/' + this.$route.params.unload, data, true);
                } else {
                    request = http.post('/api/delivery', data, true);
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/delivery' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
