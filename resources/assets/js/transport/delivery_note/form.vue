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
                            <select v-model="deliveryNote.journey_id" class="form-control input-sm" id="journey_id" name="journey_id" required>
                                <option v-for="journey in journeys" :value="journey.id">JRNY-{{ journey.id }}</option>
                            </select>
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

                        <div class="form-group">
                            <label>Vehicle Number</label>
                            <h5>{{ journey.truck.plate_number }}</h5>
                        </div>

                        <div class="form-group">
                            <label>Trailer Type</label>
                            <h5>{{ journey.truck.trailer.type }}</h5>
                        </div>

                        <div class="form-group">
                            <label>Trailer Attached</label>
                            <h5>{{ journey.truck.trailer.trailer_number }}</h5>
                        </div>

                        <div class="form-group">
                            <label>Driver Name</label>
                            <h5>{{ journey.driver.first_name }} {{ journey.driver.last_name }}</h5>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Journey Date</label>
                            <h5>{{ journey.job_date }}</h5>
                        </div>

                        <div class="form-group">
                            <img :src="getSource()" class="img-responsive">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="mileage_type">Mileage Type</label>
                            <select v-model="deliveryNote.mileage_type" class="form-control input-sm" id="mileage_type" name="mileage_type" required>
                                <option value="Fixed Mileage">Fixed Mileage</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="mileage_type">Standard Mileage Amount</label>
                            <h5><strong>{{ formatNumber(journey.route.allowance_amount) }}</strong></h5>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="requested_amount">Requested Amount</label>
                            <input min="0" v-model="deliveryNote.requested_amount" type="number" class="form-control input-sm" id="requested_amount" name="requested_amount">
                        </div>
                    </div>
                    <div class="col-sm-4" v-if="$route.params.id">
                        <div class="form-group">
                            <label for="approved_amount">Approved Amount</label>
                            <input min="0" v-model="deliveryNote.approved_amount" type="number" class="form-control input-sm" id="approved_amount" name="approved_amount">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="narration">Narration</label>
                            <textarea v-model="deliveryNote.narration" name="narration" id="narration" rows="5" class="form-control input-sm"></textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button class="btn btn-success">Process</button>
                    <router-link to="/mileage" class="btn btn-danger">Back</router-link>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                journeys: [],
                uploads: [],
                deliveryNote: {
                    journey_id: '',
                    mileage_type: 'Fixed Mileage',
                    standard_amount: 0,
                    requested_amount: 0,
                    approved_amount: '',
                    balance_amount: '',
                    narration: '',
                }
            };
        },

        created() {
            http.get('/api/delivery/create').then((response) => {
                this.journeys = response.journeys;
            });
        },

        mounted() {
            this.checkState();
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },


        computed: {
            journey() {
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
            getSource() {
                if(this.journey.driver.avatar){
                    return this.journey.driver.avatar;
                }

                return '/images/default_avatar.png';
            },

            checkState() {
                if (this.$route.params.id) {
                    http.get('/api/delivery/' + this.$route.params.id).then((response) => {
                        this.mileage = response.mileage;
                    });
                }
            },

            formatNumber(number) {
               if (! number) {
                   return '';
               }

               return parseFloat(number).toLocaleString();
            },

            store() {
                this.$root.isLoading = true;
                let request = null;
                this.deliveryNote.standard_amount = parseInt(this.journey.route.allowance_amount);

                let data = mapToFormData(this.mileage, this.uploads, typeof this.$route.params.id === 'string');

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
