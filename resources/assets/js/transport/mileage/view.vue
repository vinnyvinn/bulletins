<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Mileage Allocation</strong>
            <div class="form-group pull-right">
                <router-link to="/mileage/completed" class="btn btn-danger">Back</router-link>
                <button type="button" name="button" v-if="mileage.status == 'Pending Approval'" class="btn btn-success" @click="approveMileage(mileage.id)">Approve</button>
            </div>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="journey_id">Journey Number</label>
                            <input type="text" class="form-control" disabled :value="'JRNY-' + journey.id">
                            <!--<select disabled v-model="mileage.journey_id" class="form-control input-sm" id="journey_id" name="journey_id" required>-->
                                <!--<option v-for="journey in journeys" :value="journey.id">JRNY-{{ journey.id }}</option>-->
                            <!--</select>-->
                        </div>

                        <div class="form-group">
                            <label>Trailer Make</label>
                            <h5>{{ journey.truck.trailer.model.make.id }}</h5>
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
                            <h5>{{ journey.truck.trailer.plate_number }}</h5>
                        </div>

                        <div class="form-group">
                            <label>Driver Name</label>
                            <h5>{{ journey.driver.first_name }} {{ journey.driver.last_name }}</h5>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Document Date</label>
                            <h5>{{ mileage.created_at }}</h5>
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
                            <select disabled v-model="mileage.mileage_type" class="form-control input-sm" id="mileage_type" name="mileage_type" required>
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
                            <input disabled min="0" v-model="mileage.requested_amount" type="number" class="form-control input-sm" id="requested_amount" name="requested_amount">
                        </div>
                    </div>
                    <div class="col-sm-4" v-if="$route.params.id">
                        <div class="form-group">
                            <label for="approved_amount">Approved Amount</label>
                            <input disabled min="0" v-model="mileage.approved_amount" type="number" class="form-control input-sm" id="approved_amount" name="approved_amount">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="narration">Narration</label>
                            <textarea disabled v-model="mileage.narration" name="narration" id="narration" rows="5" class="form-control input-sm"></textarea>
                        </div>
                    </div>
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
                        trailer: {
                            model: {
                                make: {}
                            }
                        },
                    },
                    route: {},
                },
                uploads: [],
                mileage: {
                    id: '',
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
            if (this.$route.params.id) {
                return this.checkState();
            }
        },

        methods: {
            getSource() {
                if(this.journey.driver.avatar){
                    return this.journey.driver.avatar;
                }

                return '/images/default_avatar.png';
            },

            checkState() {
                this.$root.isLoading = true;
                http.get('/api/mileage/' + this.$route.params.id).then((response) => {
                    this.journey = response.journey;
                    this.mileage = response.mileage;
                    this.$root.isLoading = false;
                });
            },

            formatNumber(number) {
               if (! number) {
                   return '';
               }

               return parseFloat(number).toLocaleString();
            },

            approveMileage(id) {
                http.get('/api/approve_mileage/' + id)
                    .then(response => {
                        if (response.status !== 'success') {
                            this.$root.isLoading = false;
                            alert2(this.$root, [response.message], 'danger');
                            return;
                        }

                        this.fuel = response.fuel;
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'success');
                        this.$router.push('/mileage/completed');
                    }).catch((error) => {
                        this.$root.isLoading = false;
                        alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                    });
            },
        }
    }
</script>
