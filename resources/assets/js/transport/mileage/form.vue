<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Mileage Allocation</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-4">
                        <fieldset class="wizag-fieldset-border">
                            <legend class="wizag-fieldset-border">Journey Details</legend>
                            <div class="form-group">
                                <label for="journey_id" class="col-sm-6">Journey Number</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" disabled :value="'JRNY-' + journey.id">
                                    <!--<select v-model="mileage.journey_id" class="form-control input-sm" id="journey_id" name="journey_id" required>-->
                                    <!--<option v-for="journey in journeys" :value="journey.id">JRNY-{{ journey.id }}</option>-->
                                    <!--</select>-->
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-6">Document Date</label>
                                <div class="col-sm-6">
                                    <h5>{{ new Date().toLocaleDateString('en-GB') }}</h5>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="mileage_type" class="col-sm-6">Mileage Type</label>
                                <div class="col-sm-6">
                                    <select :disabled="disableType" v-model="mileage.mileage_type" class="form-control input-sm" id="mileage_type" name="mileage_type" required>
                                        <option v-for="mileageType in mileageTypes" :value="mileageType.name">{{ mileageType.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-sm-4">
                        <fieldset class="wizag-fieldset-border">
                            <legend class="wizag-fieldset-border">Vehicle Details</legend>
                            <div class="form-group">
                                <label class="col-sm-6">Vehicle Number</label>
                                <div class="col-sm-6">
                                    <h5>{{ journey.truck.plate_number }}</h5>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-6">Trailer Attached</label>
                                <div class="col-sm-6">
                                    <h5>{{ journey.truck.trailer.plate_number }}</h5>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-6">Trailer Type</label>
                                <div class="col-sm-6">
                                    <h5>{{ journey.truck.trailer.type }}</h5>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="col-sm-4">
                        <fieldset class="wizag-fieldset-border">
                            <legend class="wizag-fieldset-border">Driver Details</legend>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <img :src="getSource()" class="img-responsive">
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <label class="col-sm-5">Full Name</label>
                                    <h5 class="col-sm-7">{{ journey.driver.first_name }} {{ journey.driver.last_name }}</h5>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5">License #:</label>
                                    <h5 class="col-sm-7">{{ journey.driver.dl_number }}</h5>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5">National ID:</label>
                                    <h5 class="col-sm-7">{{ journey.driver.identification_number }}</h5>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <fieldset class="wizag-fieldset-border">
                            <legend class="wizag-fieldset-border">Payment Details</legend>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Standard Mileage Amount</label>
                                </div>
                                <div class="col-sm-6">
                                    <h5><strong>{{ formatNumber(standardMileage) }}</strong></h5>
                                </div>

                                <div class="col-sm-6">
                                    <label>Amount Requested</label>
                                </div>
                                <div class="col-sm-6">
                                    <input min="1" :max="standardMileage"
                                           v-model="mileage.requested_amount" @change="validateRequestedAmount"
                                           type="number" class="form-control input-sm" id="requested_amount"
                                           name="requested_amount">
                                </div>


                                <div class="col-sm-6">
                                    <label>Top Up?</label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="checkbox" name="top_up" id="top_up" v-model="mileage.top_up">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6" v-if="mileage.top_up">
                                        <label>Top Up Amount</label>
                                    </div>
                                    <div class="col-sm-6" v-if="mileage.top_up">
                                        <input type="number" name="top_up_amount" id="top_up_amount"
                                               v-model="mileage.top_up_amount">
                                    </div>

                                    <div class="col-sm-6" v-if="mileage.top_up">
                                        <label>Top up reason</label>
                                    </div>
                                    <div class="col-sm-6" v-if="mileage.top_up">
                                        <textarea name="top_up_reason" id="top_up_reason" class="form-control input-sm" v-model="mileage.top_up_reason"></textarea>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <label>Total Request Amount</label>
                                </div>
                                <div class="col-sm-6">
                                    <h4><strong>{{ parseInt(mileage.requested_amount) + parseInt(mileage.top_up_amount)
                                        }}</strong></h4>
                                </div>

                                <div class="col-sm-6" v-if="$route.params.id">
                                    <label>Approved Amount</label>
                                </div>
                                <div class="col-sm-6" v-if="$route.params.id">
                                    <input min="0" v-model="mileage.approved_amount" type="number" class="form-control input-sm" id="approved_amount" name="approved_amount">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-6"></label>
                                <div class="col-sm-6">
                                </div>
                            </div>
                        </fieldset>

                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-10">
                                <label>Narration</label>
                                <textarea v-model="mileage.narration" name="narration" id="narration" rows="5"
                                          class="form-control input-sm"></textarea>
                            </div>
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
                disableType: false,
                journeys: [],
                journey: {
                    driver: {},
                    truck: {
                        trailer: {},
                    },
                    route: {},
                },
                uploads: [],
                mileageTypes: [
                    {
                        'slug': 'allowance_amount',
                        'name': 'Fixed Mileage',
                    },
                    {
                        'slug': 'return_mileage',
                        'name': 'Return Mileage',
                    },
                ],
                mileage: {
                    station_id: window.Laravel.station_id,
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

        computed: {
            standardMileage() {
                if (this.mileage.mileage_type == 'Fixed Mileage') return this.journey.route.allowance_amount;
                if (this.mileage.mileage_type == 'Return Mileage') return this.journey.route.return_mileage;

                let selected = this.mileageTypes.filter(type => type.name === this.mileage.mileage_type);
                if (! selected.length) return 0;

                return this.journey.route[selected[0].slug];
            }
        },

        created() {
            if (!this.$route.params.id && !this.$route.params.unload && !this.$root.can('create-mileage')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id && !this.$root.can('edit-mileage')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.approve && !this.$root.can('approve-mileage')) {
                this.$router.push('/403');
                return false;
            }

            this.$root.isLoading = true;

            if (this.$route.params.id || this.$route.params.approve) {
                let id = this.$route.params.approve ? this.$route.params.approve : this.$route.params.id;
                return http.get('/api/mileage/' + id + '/?s=' + window.Laravel.station_id).then((response) => {
                    this.disableType = response.mileage.mileage_type == 'Return Mileage';
                    this.updateMileageTypes(response.mileageTypes, response.toExclude);
                    this.journey = response.journey;
                    this.mileage = response.mileage;
                    this.mileage.top_up = this.mileage.top_up != 0;
                    this.$root.isLoading = false;
                });
            }

            http.get('/api/mileage/create?id=' + this.$route.params.new + '&s=' + window.Laravel.station_id).then((response) => {
                this.updateMileageTypes(response.mileageTypes, response.toExclude);
                this.journey = response.journey;
                this.mileage.journey_id = response.journey.id;
            }).then(() => {
                this.$root.isLoading = false;
            });
        },

        mounted() {
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },

        methods: {

            updateMileageTypes(types, toExclude) {
                let mileages = this.mileageTypes.concat(types);

                this.mileageTypes = mileages.filter(mile => {
                    return toExclude.indexOf(mile.name) === -1;
                });

                if (this.mileageTypes.length) {
                    this.mileage.mileage_type = this.mileageTypes[0].name;
                }
            },

            toggleTop_Up(){
                this.mileage.top_up = !this.mileage.top_up;
                return this.mileage.top_up_amount = 0;
            },
            validateRequestedAmount() {
                if (parseInt(this.mileage.requested_amount) > parseInt(this.standardMileage)) {
                    this.mileage.requested_amount = this.standardMileage;
                    return alert2(this.$root, ['Requested amount cannot be more than standard allowance'], 'danger');
                }
            },
            getSource() {
                if (this.journey.driver.avatar) {
                    return this.journey.driver.avatar;
                }

                return '/images/default_avatar.png';
            },

            checkState() {
                if (this.$route.params.id || this.$route.params.approve) {
                    let id = this.$route.params.approve ? this.$route.params.approve : this.$route.params.id;
                    http.get('/api/mileage/' + id + '/?s=' + window.Laravel.station_id).then((response) => {
                        this.mileage = response.mileage;
                        this.$root.isLoading = false;
                    });
                }
            },

            formatNumber(number) {
                if (!number) {
                    return '';
                }

                return parseFloat(number).toLocaleString();
            },

            store() {
                this.$root.isLoading = true;
                let request = null;
                this.mileage.standard_amount = parseInt(this.standardMileage);

                let data = mapToFormData(this.mileage, this.uploads, typeof this.$route.params.id === 'string' || typeof this.$route.params.approve === 'string');

                if (this.$route.params.id) {
                    request = http.put('/api/mileage/' + this.$route.params.id, data, true);
                } else if (this.$route.params.approve) {
                    request = http.put('/api/mileage/' + this.$route.params.approve, data, true);
                } else {
                    request = http.post('/api/mileage', data, true);
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({path: '/mileage'});
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>

<style media="screen" scoped>
    fieldset.wizag-fieldset-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow: 0px 0px 0px 0px #000;
        box-shadow: 0px 0px 0px 0px #000;
    }

    legend.wizag-fieldset-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width: auto;
        padding: 0 10px;
        border-bottom: none;
    }
</style>
