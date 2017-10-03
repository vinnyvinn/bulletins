<template>
    <div>
        <div class="panel panel-default hidden-print">
            <div class="panel-heading hidden-print">
                <strong>Fuel Allocation</strong>

                <div class="form-group pull-right hidden-print">
                    <!-- <button type="button" name="button" v-if="fuel.status == 'Awaiting Approval'" class="btn btn-success" @click="approveFuel(fuel.id)">Approve</button> -->
                    <!-- <button type="button" name="button" v-else class="btn btn-warn btn-warning" @click="approveFuel(fuel.id)">Cancel Approval</button> -->
                    <button type="button" class="btn btn-success" @click="printFuelVoucher" :disabled="disablePrint">
                        <i class="fa fa-print fa-fw"></i> Print</button>
                    <router-link to="/fuel" class="btn btn-danger">Back</router-link>
                </div>
            </div>

            <div class="panel-body pm80">
                <div class="row">
                    <div class="col-xs-6">
                        <h3>
                            <img style='display:block' src="/images/logo.jpg" alt="Sanghani">
                            <strong>FUEL VOUCHER</strong>
                        </h3>
                    </div>
                    <div class="col-xs-6">
                        <div class="text-right">
                            <h3>{{ config.name }}</h3>
                            <h5>{{ config.telephone }}</h5>
                            <h5>{{ config.email }}</h5>
                            <h5>{{ config.location }}</h5>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher No: </strong> FUEL-{{ fuel.id }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Truck No: </strong>
                        <span class="text-uppercase">{{ fuel.journey.truck.plate_number }}</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Date to be issued: </strong> {{ fuel.date }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Journey No: </strong> JRN-{{ fuel.journey.id }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Route: </strong>
                        <span class="text-uppercase">{{ fuel.journey.route.source }}</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>To: </strong>
                        <span class="text-uppercase">{{ fuel.journey.route.destination }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Driver: </strong> {{ fuel.journey.driver ? fuel.journey.driver.first_name : '' }} {{ fuel.journey.driver ? fuel.journey.driver.last_name : '' }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Distance: </strong>
                        <span class="text-uppercase">{{ fuel.journey.distance }} KMs</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Fuel Qty: </strong>
                        <span class="text-uppercase">{{ fuel.fuel_issued }} Litres</span>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <strong>Supervisors Comment</strong>
                        <br>
                        <p>{{ fuel.narration }}</p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher Approved By:</strong>
                        <br>
                        <span v-if="fuel.user">
                            {{ fuel.user.first_name }} {{ fuel.user.last_name }}
                        </span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Voucher Processed By: </strong>
                        <br> {{ fuel.user.first_name }} {{ fuel.user.last_name }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Recipient Sign: </strong>
                        <br> .....................................................................
                    </div>
                </div>

                <hr>
                <br>

                <div v-for="mileage in mileages">
                    <div class="col-xs-6">
                        <h3>
                            <img style='display:block' src="/images/logo.jpg" alt="Sanghani">
                            <strong>MILEAGE</strong>
                            <span>
                                <small>TOP UP</small>
                            </span>
                        </h3>
                    </div>
                    <div class="col-xs-6">
                        <div class="text-right">
                            <h3>{{ config.name }}</h3>
                            <h5>{{ config.telephone }}</h5>
                            <h5>{{ config.email }}</h5>
                            <h5>{{ config.location }}</h5>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <strong>Voucher No: </strong> MLG-{{ mileage.id }}
                        </div>
                        <div class="col-xs-4">
                            <strong>Truck No: </strong>
                            <span class="text-uppercase">{{ fuel.journey.truck.plate_number }}</span>
                        </div>
                        <div class="col-xs-4">
                            <strong>Date to be issued: </strong> {{ fuel.date }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <strong>Journey No: </strong> JRN-{{ mileage.journey_id }}
                        </div>
                        <div class="col-xs-4">
                            <strong>Mileage Type: </strong>
                            <span class="text-uppercase">{{ mileage.mileage_type }}</span>
                        </div>
                        <div class="col-xs-4">
                            &nbsp;
                        </div>
                    </div>

                    <div class="row" v-if="parseInt(mileage.top_up)">
                        <div class="col-xs-4">
                            <strong>Top Up Amount: </strong> {{ mileage.top_up_amount }}
                        </div>
                        <div class="col-xs-4">
                            <strong>Top Up Reason: </strong> {{ mileage.top_up_reason }}
                        </div>
                        <div class="col-xs-4">
                            &nbsp;
                        </div>
                    </div>

                    <div class="row text-left">
                        <div class="col-xs-12">
                            <h4>
                                <strong>Amount Approved: </strong> {{ isNaN(parseFloat(mileage.approved_amount)) ? '' : parseFloat(mileage.approved_amount).toLocaleString() }}</h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-12">
                            <strong>Supervisors Comment</strong>
                            <br>
                            <p>{{ mileage.narration }}</p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-4">
                            <strong>Voucher Approved By:</strong>
                            <br>
                            <span v-if="mileage.user">
                                {{ mileage.user.first_name }} {{ fuel.user.last_name }}
                            </span>
                        </div>
                        <div class="col-xs-4">
                            <strong>Voucher Processed By: </strong>
                            <br>
                            <span v-if="mileage.user">
                                {{ mileage.user.first_name }} {{ fuel.user.last_name }}
                            </span>
                        </div>
                        <div class="col-xs-4">
                            <strong>Recipient Sign: </strong>
                            <br> .....................................................................
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pm50 visible-print">
            <div class="row">
                <div class="col-xs-6">
                    <h3>
                        <img style='display:block' src="/images/logo.jpg" alt="Sanghani">
                        <strong>FUEL VOUCHER</strong>
                    </h3>
                </div>
                <div class="col-xs-6">
                    <div class="text-right">
                        <h3>{{ config.name }}</h3>
                        <h5>{{ config.telephone }}</h5>
                        <h5>{{ config.email }}</h5>
                        <h5>{{ config.location }}</h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <strong>Voucher No: </strong> FUEL-{{ fuel.id }}
                </div>
                <div class="col-xs-4">
                    <strong>Truck No: </strong>
                    <span class="text-uppercase">{{ fuel.journey.truck.plate_number }}</span>
                </div>
                <div class="col-xs-4">
                    <strong>Date to be issued: </strong> {{ fuel.date }}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <strong>Journey No: </strong> JRN-{{ fuel.journey.id }}
                </div>
                <div class="col-xs-4">
                    <strong>Route: </strong>
                    <span class="text-uppercase">{{ fuel.journey.route.source }}</span>
                </div>
                <div class="col-xs-4">
                    <strong>To: </strong>
                    <span class="text-uppercase">{{ fuel.journey.route.destination }}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <strong>Driver: </strong> {{ fuel.journey.driver ? fuel.journey.driver.first_name : '' }} {{ fuel.journey.driver ? fuel.journey.driver.last_name : '' }}
                </div>
                <div class="col-xs-4">
                    <strong>Distance: </strong>
                    <span class="text-uppercase">{{ fuel.journey.distance }} KMs</span>
                </div>
                <div class="col-xs-4">
                    <strong>Fuel Qty: </strong>
                    <span class="text-uppercase">{{ fuel.fuel_issued }} Litres</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <strong>Supervisors Comment</strong>
                    <p>{{ fuel.narration }}</p>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-4">
                    <strong>Voucher Approved By:</strong>
                    <br>
                    <span v-if="fuel.user">
                        {{ fuel.user.first_name }} {{ fuel.user.last_name }}
                    </span>
                </div>
                <div class="col-xs-4">
                    <strong>Voucher Processed By: </strong>
                    <br> {{ fuel.user.first_name }} {{ fuel.user.last_name }}
                </div>
                <div class="col-xs-4">
                    <strong>Recipient Sign: </strong>
                    <br> .....................................................................
                </div>
            </div>
            <hr>
            <br>

            <div class="row" v-for="(mileage, index) in mileages">
                <div v-if="index % 2 == 1" class="page-break"></div>
                <div class="col-xs-6">
                    <h3>
                        <img style='display:block' src="/images/logo.jpg" alt="Sanghani">
                        <strong>MILEAGE</strong>
                        <span>
                            <small>TOP UP</small>
                        </span>
                    </h3>
                </div>
                <div class="col-xs-6">
                    <div class="text-right">
                        <h3>{{ config.name }}</h3>
                        <h5>{{ config.telephone }}</h5>
                        <h5>{{ config.email }}</h5>
                        <h5>{{ config.location }}</h5>
                    </div>
                </div>
                <div class="col-xs-12">
                    <hr>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher No: </strong> MLG-{{ mileage.id }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Truck No: </strong>
                        <span class="text-uppercase">{{ fuel.journey.truck.plate_number }}</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Date to be issued: </strong> {{ fuel.date }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Journey No: </strong> JRN-{{ mileage.journey_id }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Mileage Type: </strong>
                        <span class="text-uppercase">{{ mileage.mileage_type }}</span>
                    </div>
                    <div class="col-xs-4">
                    </div>
                </div>

                <div class="row" v-if="parseInt(mileage.top_up)">
                    <div class="col-xs-4">
                        <strong>Top Up Amount: </strong> {{ mileage.top_up_amount }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Top Up Reason: </strong> {{ mileage.top_up_reason }}
                    </div>
                    <div class="col-xs-4">
                        &nbsp;
                    </div>
                </div>

                <div class="row text-left">
                    <div class="col-xs-12">
                        <h5>
                            <strong>Amount Approved: </strong> {{ parseFloat(mileage.approved_amount).toLocaleString() }}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <strong>Supervisors Comment</strong>
                        <p>{{ mileage.narration }}</p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher Approved By:</strong>
                        <br>
                        <span v-if="mileage.user">
                            {{ mileage.user.first_name }} {{ fuel.user.last_name }}
                        </span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Voucher Processed By: </strong>
                        <br>
                        <span v-if="mileage.user">
                            {{ mileage.user.first_name }} {{ fuel.user.last_name }}
                        </span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Recipient Sign: </strong>
                        <br> .....................................................................
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                config: {},
                km_covered: 0,
                fuel_used: 0,
                km_per_litre: 0,
                fuel: {
                    journey: {
                        driver: {},
                        route: {},
                        truck: {},
                    },
                    user: {},
                    fuel_required: '',
                    current_fuel: '',
                    fuel_requested: '',
                    fuel_issued: '',
                    fuel_total: '',
                    narration: '',
                    tank: '',
                    pump: '',
                    top_up: '',
                    top_up_reason: '',
                    top_up_quantity: 0,
                },
                delivery_note: '',
                mileages: [],
            };
        },
        created() {
            if (!this.$root.can('view-fuel')) {
                this.$router.push('/403');
                return false;
            }
            this.$root.isLoading = true;
            http.get('/api/fuel/' + this.$route.params.id).then((response) => {
                this.fuel = response.fuel;
                this.delivery_note = response.delivery_note;
                this.mileages = response.mileages;
                this.config = response.config;
                this.$root.isLoading = false;
            });
        },
        computed: {
            disablePrint() {
                let isApproved = true;
                this.mileages.forEach(mileage => {
                    if (mileage.status != "Approved") {
                        isApproved = false;
                    }
                });
                if (this.fuel.status == "Approved" && isApproved) {
                    return false;
                }

                return true;
            },

            minimumKm() {
                return this.fuel.previous_km;
            }
        },

        methods: {
            calculateTotal() {
                return this.fuel.fuel_total = parseFloat(fuel.fuel_issued) + parseFloat(fuel.top_up_quantity) + parseInt(this.fuel.current_fuel);
            },

            calculateKms() {
                if (this.fuel.current_km < this.fuel.previous_km) {
                    return alert2(this.$root, ['Current Km readings should be greater than previous Km reading'], 'danger');
                }
                this.fuel_used = parseInt(this.fuel.previous_fuel) - parseInt(this.fuel.current_fuel);
                this.km_covered = parseInt(this.fuel.current_km) - parseInt(this.fuel.previous_km);

                return this.km_per_litre = parseInt(this.km_covered) / parseInt(this.fuel_used);
            },
            getSource() {
                if (this.fuel.journey.driver.avatar) {
                    return '/images/' + this.journey.driver.avatar;
                }
                return '/images/default_avatar.png';
            },
            printFuelVoucher() {
                window.print();
            },
            approveFuel(id) {
                http.get('/api/approve/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }

                    this.fuel = response.fuel;

                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
