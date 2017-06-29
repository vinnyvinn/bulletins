<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Mileage Allocation</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="journey_id">Journey Number</label>
                            <select v-model="mileage.journey_id" class="form-control input-sm" id="journey_id" name="journey_id" required>
                                <option v-for="journey in journeys" :value="journey.id">JRNY-{{ journey.id }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Trailer Make</label>
                            <h5>{{ journey.truck.trailer.make }}</h5>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Vehicle Number</label>
                            <h5>{{ journey.truck.plate_number }}</h5>
                        </div>

                        <div class="form-group">
                            <label>Trailer Type</label>
                            <h5>{{ journey.truck.trailer.type }}</h5>
                        </div>
                    </div>

                    <div class="col-sm-3">
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
                            <label>Document Date</label>
                            <h5>{{ new Date().toLocaleDateString('en-GB') }}</h5>
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
                            <select v-model="mileage.mileage_type" class="form-control input-sm" id="mileage_type" name="mileage_type" required>
                                <option value="Fixed Mileage">Fixed Mileage</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="mileage_type">Standard Mileage Amount</label>
                            <h5><strong>{{ formatNumber(journey.route.allowance_amount) }}</strong></h5>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="requested_amount">Requested Amount</label>
                            <input min="0" v-model="mileage.requested_amount" @change="validateRequestedAmount" type="number" class="form-control input-sm" id="requested_amount" name="requested_amount">
                        </div>
                    </div>
                    <div class="col-sm-3" v-if="$route.params.id">
                        <div class="form-group">
                            <label for="approved_amount">Approved Amount</label>
                            <input min="0" v-model="mileage.approved_amount" type="number" class="form-control input-sm" id="approved_amount" name="approved_amount">
                        </div>
                    </div>
                    <div class="col-sm-3">
                      <label for="top_up">Top Up?</label>
                      <input type="checkbox" name="top_up" id="top_up" v-model="mileage.top_up" @change="!mileage.top_up">
                      <div class="form-group" v-if="mileage.top_up">
                        <label for="top_up_amount">Top Up Amount:</label>
                        <input type="number" name="top_up_amount" id="top_up_amount" v-model="mileage.top_up_amount">
                        <label for="top_up_reason">Top up reason</label>
                        <textarea name="narration" id="top_up_reason" class="form-control input-sm" v-model="mileage.top_up_reason"></textarea>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <label for="total_amount_given">Total Amount: {{ parseInt(mileage.requested_amount) + parseInt(mileage.top_up_amount)}}</label>

                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="narration">Narration</label>
                            <textarea v-model="mileage.narration" name="narration" id="narration" rows="5" class="form-control input-sm"></textarea>
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
                mileage: {
                    journey_id: '',
                    mileage_type: 'Fixed Mileage',
                    standard_amount: 0,
                    requested_amount: 0,
                    approved_amount: '',
                    balance_amount: '',
                    narration: '',
                    top_up: false,
                    top_up_amount: 0,
                    top_up_reason: ''
                }
            };
        },

        created() {
            http.get('/api/mileage/create').then((response) => {
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
                let journey = this.journeys.filter(e => e.id == this.mileage.journey_id);
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

          toggleTop_Up(){
            this.mileage.top_up = !this.mileage.top_up;
            return this.mileage.top_up_amount = 0;
          },
            validateRequestedAmount() {
              if(parseInt(this.mileage.requested_amount) > parseInt(this.journey.route.allowance_amount)){
                this.mileage.requested_amount = 0;
                return alert2(this.$root, ['Requested amount cannot be more than standard allowance'], 'danger');
              }
            },
            getSource() {
                if(this.journey.driver.avatar){
                    return this.journey.driver.avatar;
                }

                return '/images/default_avatar.png';
            },

            checkState() {
                if (this.$route.params.id) {
                    http.get('/api/mileage/' + this.$route.params.id).then((response) => {
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
                this.mileage.standard_amount = parseInt(this.journey.route.allowance_amount);

                let data = mapToFormData(this.mileage, this.uploads, typeof this.$route.params.id === 'string');

                if (this.$route.params.id) {
                    request = http.put('/api/mileage/' + this.$route.params.id, data, true);
                } else {
                    request = http.post('/api/mileage', data, true);
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/mileage' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
