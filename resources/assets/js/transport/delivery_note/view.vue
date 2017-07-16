<template>
    <div>
        <div class="panel panel-default hidden-print">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6">
                        <h4><strong>Delivery Note</strong></h4>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group pull-right">
                            <button @click.prevent="printNote" class="btn btn-success">Print</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <form action="#" role="form" @submit.prevent="store">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="journey_id">Journey Number</label>
                                <input disabled type="text" class="form-control" id="journey_id" :value="'JRN-' + journey.id">
                                <!--<select disabled v-model="deliveryNote.journey_id" class="form-control input-sm" id="journey_id" name="journey_id" required>-->
                                    <!--<option v-for="journey in journeys" :value="journey.id">JRNY-{{ journey.id }}</option>-->
                                <!--</select>-->
                            </div>

                            <div class="form-group">
                                <label>Route From</label>
                                <h5>{{ journey.route.source }}</h5>
                            </div>


                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Journey Date</label>
                                <h5>{{ journey.job_date }}</h5>
                            </div>

                            <div class="form-group">
                                <label>Route To</label>
                                <h5>{{ journey.route.destination }}</h5>
                            </div>

                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Journey Ref No.</label>
                                <h5>{{ journey.ref_no }}</h5>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Journey Date</label>
                                <h5>{{ journey.job_date }}</h5>
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
                                <input disabled id="bags_loaded" min="0" v-model="deliveryNote.bags_loaded" type="number" class="form-control input-sm">
                            </div>

                            <div class="form-group">
                                <label for="loading_gross_weight">Gross Weight</label>
                                <input disabled @keyup="updateNote" id="loading_gross_weight" min="0" v-model="deliveryNote.loading_gross_weight" type="number" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="loading_tare_weight">Tare Weight</label>
                                <input disabled @keyup="updateNote" id="loading_tare_weight" min="0" v-model="deliveryNote.loading_tare_weight" type="number" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="loading_net_weight">Net Weight</label>
                                <input disabled id="loading_net_weight" min="0" v-model="deliveryNote.loading_net_weight" type="number" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="loading_weighbridge_number">Weighbridge Ticket Number</label>
                                <input disabled id="loading_weighbridge_number" v-model="deliveryNote.loading_weighbridge_number" type="text" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <h4><strong>Offloading Details</strong></h4>
                            <hr>

                            <div class="form-group">
                                <label for="offloading_gross_weight">Gross Weight</label>
                                <input disabled @keyup="updateNote" id="offloading_gross_weight" min="0" v-model="deliveryNote.offloading_gross_weight" type="number" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="offloading_tare_weight">Tare Weight</label>
                                <input disabled @keyup="updateNote" id="offloading_tare_weight" min="0" v-model="deliveryNote.offloading_tare_weight" type="number" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="offloading_net_weight">Net Weight</label>
                                <input disabled id="offloading_net_weight" min="0" v-model="deliveryNote.offloading_net_weight" type="number" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="offloading_weighbridge_number">Weighbridge Ticket Number</label>
                                <input disabled id="offloading_weighbridge_number" v-model="deliveryNote.offloading_weighbridge_number" type="text" class="form-control input-sm">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <h4><strong>Narration</strong></h4>
                            <hr>
                            <div class="form-group">
                                <label for="narration">Narration</label>
                                <textarea disabled v-model="deliveryNote.narration" name="narration" id="narration" rows="15" class="form-control input-sm"></textarea>
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
        <div class="visible-print-block" id="printable" v-html="printout"></div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                journeys: [],
                journey: { route: {}, driver: {}, truck: { trailer: {} } },
                uploads: [],
                printout: '',
                deliveryNote: {
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
            if (this.$route.params.id) {
                this.checkState();
                return;
            }

            http.get('/api/delivery/create').then((response) => {
                this.journey = response.journey;
            });
        },

        mounted() {
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },


        computed: {
            journeyff() {
                let journey = this.journeys.filter(e => e.id == this.deliveryNote.journey_id);
                if (journey.length) {
                    return journey[0];
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
            printNote() {
                http.get('/api/delivery/print/' + this.$route.params.id).then(response => {
                    this.printout = response.printout;
                    setTimeout(() => {
                        if (response.shouldPrint) {
                            window.print();
                        }
                    }, 500);
                });
            },
            updateNote() {
                this.deliveryNote.loading_net_weight = parseFloat(this.deliveryNote.loading_gross_weight) - parseFloat(this.deliveryNote.loading_tare_weight);
                this.deliveryNote.offloading_net_weight = parseFloat(this.deliveryNote.offloading_gross_weight) - parseFloat(this.deliveryNote.offloading_tare_weight);
            },

            checkState() {
                return http.get('/api/delivery/' + this.$route.params.id).then((response) => {
                    this.journey = response.journey;
                    this.deliveryNote = response.delivery;
                });
            },
            store() {
                this.$root.isLoading = true;
                let request = null;

                let data = mapToFormData(this.deliveryNote, this.uploads, typeof this.$route.params.id === 'string');

                if (this.$route.params.id) {
                    request = http.put('/api/delivery/' + this.$route.params.id, data, true);
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
