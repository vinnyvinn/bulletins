<template>
    <div>
        <div class="visible-print-block">
            <div class="pm50 visible-print" v-if="submitted">
                <div class="row">
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
                            <span class="text-uppercase">{{ mileage.journey.truck.plate_number }}</span>
                        </div>
                        <div class="col-xs-4">
                            <strong>Date to be issued: </strong> {{ mapDate(mileage.created_at) }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4">
                            <strong>Journey No: </strong> JRN-{{ mileage.journey_id }}
                        </div>
                        <div class="col-xs-4">
                            <strong>Mileage Type: </strong>
                            <span class="text-uppercase">{{ mileage.mileage_current_type }}</span>
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
                                {{ mileage.user.first_name }} {{ mileage.user.last_name }}
                            </span>
                        </div>
                        <div class="col-xs-4">
                            <strong>Voucher Processed By: </strong>
                            <br>
                            <span v-if="mileage.user">
                                {{ mileage.user.first_name }} {{ mileage.user.last_name }}
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
        <div class="panel panel-default hidden-print" v-if="! showModal">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-6">
                        <h4>
                            <strong>Journey Details: JRNY-{{ $route.params.id }} ({{ status }})</strong>
                        </h4>
                        <div v-if="journey.opener">
                            <strong>Created By: </strong> {{ journey.opener.first_name }} {{ journey.opener.last_name }}
                        </div>
                        <div v-if="journey.closer">
                            <strong>Closed By: </strong> {{ journey.closer.first_name }} {{ journey.closer.last_name }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group pull-right" v-if="$root.can('approve-journey')">
                            <button @click.prevent="approveJourney" class="btn btn-success" v-if="status == 'Pending Approval'">Approve Journey
                            </button>
                            <!-- <button @click.prevent="closeJourney" class="btn btn-danger" v-if="(status == 'Pending Approval') || (status == 'Approved')">Close Journey</button> -->
                            <button @click.prevent="showModal = true" class="btn btn-danger" v-if="(status == 'Approved') && journey_close.mileage && journey_delivery_status != 'Loaded'">Close Journey
                            </button>
                            <!-- <button @click.prevent="reopenJourney" class="btn btn-primary" v-if="status == 'Closed'">Reopen Journey</button> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <form action="#" role="form" @submit.prevent="store">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="is_contract_related">Contract Related?</label>
                                <select disabled v-model="journey.is_contract_related" @change="fetchContracts" class="form-control input-sm" id="is_contract_related" name="is_contract_related" required>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>

                            <div class="form-group" v-if="journey.is_contract_related == '1'">
                                <label for="contract_id">Contract</label>
                                <input disabled id="contract_id" type="text" class="form-control input-sm" :value="'CNTR-' + contract.id">
                            </div>

                            <div class="form-group">
                                <label for="job_description">Job Description</label>
                                <textarea disabled v-model="journey.job_description" name="job_description" id="job_description" rows="5" class="form-control input-sm"></textarea>
                            </div>

                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="journey_type">Job Type</label>
                                <select disabled v-model="journey.journey_type" class="form-control input-sm" id="journey_type" name="journey_type" required>
                                    <option value="Local">Local</option>
                                    <option value="International">International</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="route_id">Route</label>
                                <select disabled name="route_id" id="route_id" v-model="journey.route_id" class="form-control input-sm select2" required>
                                    <option v-for="route in routes" :value="route.id">{{ route.source }} - {{ route.destination }} ({{ route.distance }} KM)
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="driver_id">Driver</label>
                                <h5>{{ journey_close.driver.first_name }} {{ journey_close.driver.last_name }} ({{ journey_close.driver.mobile_phone }})</h5>
                            </div>

                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="truck_id">Vehicle Reg. No</label>
                                <input disabled class="form-control" v-model="journey.truck_plate_number">
                                <!-- <select hidden disabled v-model="journey.truck_id" class="form-control input-sm" id="truck_id" name="truck_id" required>
                                                    <option v-for="truck in trucks" :value="truck.id">{{ truck.plate_number }}</option>
                                                </select> -->
                            </div>

                            <div class="form-group">
                                <label for="enquiry_from">Enquiry from</label>
                                <input disabled type="text" v-model="journey.enquiry_from" class="form-control input-sm datepicker" id="enquiry_from" name="enquiry_from">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="job_date">Job Date</label>
                                <input disabled type="text" v-model="journey.job_date" class="form-control input-sm datepicker" id="job_date" name="job_date" required>
                            </div>

                            <!--<div class="form-group">-->
                            <!--<label for="ref_no">Ref No</label>-->
                            <!--<input disabled type="text" v-model="journey.ref_no" class="form-control input-sm" id="ref_no" name="ref_no">-->
                            <!--</div>-->
                        </div>

                    </div>

                    <hr>

                    <div class="row" v-if="journey.is_contract_related == '1'">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name">Contract Name</label>
                                <input disabled v-model="contract.name" type="text" class="form-control input-sm" id="name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="cargo_classification_id">Cargo Classification</label>
                                <select disabled v-model="contract.cargo_classification_id" name="cargo_classification_id" id="cargo_classification_id" class="form-control input-sm select2" required>
                                    <option v-for="classification in classifications" :value="classification.id">
                                        {{ classification.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <select disabled v-model="contract.client_id" name="client_id" id="client_id" class="form-control input-sm select2" required>
                                    <option v-for="client in clients" :value="client.DCLink">{{ client.Name }} ({{ client.Account }})
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="cargo_type_id">Cargo Type</label>
                                <select disabled v-model="contract.cargo_type_id" name="cargo_type_id" id="cargo_type_id" class="form-control input-sm select2" required>
                                    <option v-for="type in viableCargoTypes" :value="type.id">{{ type.name }}</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="start_date">Contract Start</label>
                                <input disabled v-model="contract.start_date" type="text" class="datepicker form-control input-sm" id="start_date" name="start_date" required>
                            </div>

                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <div class="input-group">
                                    <input disabled v-model="contract.quantity" min="0" type="number" class="form-control input-sm" id="quantity" name="quantity" describedby="quantity-addon" required>
                                    <span class="input-group-addon" id="quantity-addon">KGs</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="route_id">Route</label>
                                <select disabled v-model="contract.route_id" class="form-control input-sm select2" required>
                                    <option v-for="route in routes" :value="route.id">{{ route.source }} - {{ route.destination }} ({{ route.distance }} KM)
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="loading_point_id">Loading Point</label>
                                <select disabled v-model="contract.loading_point_id" name="loading_point_id" id="loading_point_id" class="form-control input-sm select2" required>
                                    <option v-for="point in carriage_points" :value="point.id">{{ point.name }}</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <hr>

                    <div class="row" v-if="journey.is_contract_related == '0'">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Sub-Contracts</label>
                                <div class="checkbox">
                                    <label>
                                        <input disabled v-model="journey.subcontracted" type="checkbox"> Check if the trucks have been subcontracted by another company. Note that the delivery note will be processed in the name of the other company.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-if="journey.subcontracted && journey.is_contract_related == '0'">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sub_company_name">Company Name</label>
                                <input disabled required v-model="journey.sub_company_name" type="text" class="form-control input-sm" id="sub_company_name" name="sub_company_name">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sub_address_3">Address Line 3</label>
                                <input disabled v-model="journey.sub_address_3" type="text" class="form-control input-sm" id="sub_address_3" name="sub_address_3">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sub_address_1">Address Line 1</label>
                                <input disabled required v-model="journey.sub_address_1" type="text" class="form-control input-sm" id="sub_address_1" name="sub_address_1">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sub_address_4">Address Line 4</label>
                                <input disabled v-model="journey.sub_address_4" type="text" class="form-control input-sm" id="sub_address_4" name="sub_address_4">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sub_address_2">Address Line 2</label>
                                <input disabled required v-model="journey.sub_address_2" type="text" class="form-control input-sm" id="sub_address_2" name="sub_address_2">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <router-link to="/journey" class="btn btn-danger">Back</router-link>
                    </div>
                </form>
            </div>
        </div>
        <div class="hidden-print" v-if="showModal">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Return Mileage Allocation</strong>
                </div>

                <div class="panel-body">
                    <form action="#" role="form" @submit.prevent="closeJourney">
                        <div class="row">
                            <div class="col-sm-4">
                                <fieldset class="wizag-fieldset-border">
                                    <legend class="wizag-fieldset-border">Journey Details</legend>
                                    <div class="form-group">
                                        <label for="journey_id" class="col-sm-6">Journey Number</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" disabled :value="'JRNY-' + journey_close.id">
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
                                            <select disabled v-model="mileage.mileage_type" class="form-control input-sm" id="mileage_type" name="mileage_type" required>
                                                <option value="Return Mileage">Return Mileage</option>
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
                                            <h5>{{ journey_close.truck.plate_number }}</h5>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-6">Trailer Attached</label>
                                        <div class="col-sm-6">
                                            <h5>{{ journey_close.truck.trailer.plate_number }}</h5>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-6">Trailer Type</label>
                                        <div class="col-sm-6">
                                            <h5>{{ journey_close.truck.trailer.type }}</h5>
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
                                            <label class="col-sm-6">Full Name</label>
                                            <h5 class="col-sm-6">{{ journey_close.driver.first_name }} {{ journey_close.driver.last_name }}</h5>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6">License #:</label>
                                            <h5 class="col-sm-6">{{ journey_close.driver.dl_number }}</h5>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6">National ID:</label>
                                            <h5 class="col-sm-6">{{ journey_close.driver.identification_number }}</h5>
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
                                            <div class="form-group">
                                                <label for="current_km">Truck Current Km reading:</label>
                                                <input min="0" type="number" name="current_km" class="form-control input-sm" v-model="journey_close.current_km" @change="validateKms" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div :class="{'form-group': true, 'has-error': belowReserve}">
                                                <label class="control-label" for="current_km">Truck Current fuel reading:</label>
                                                <input min="0" type="number" id="current_fuel" name="current_fuel" class="form-control input-sm" v-model="journey_close.current_fuel" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <p v-if="belowReserve">
                                                <i style="color: red;">
                                                    Fuel in truck ({{journey_close.current_fuel}} ltrs) is below reserve by {{ fuelDifference }} ltrs. Driver to pay Ksh. {{ fuelDifference * parseInt(journey_close.fuel_price) }}
                                                </i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Standard Mileage Amount</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <h5 class="text-right">
                                                <strong>{{ formatNumber(journey_close.route.return_mileage) }}</strong>
                                            </h5>
                                        </div>

                                        <div class="col-sm-6">
                                            <label>Total Return Mileage Amount</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <h4 class="text-right">
                                                <strong>{{ parseInt(mileage.requested_amount).toLocaleString() }}</strong>
                                            </h4>
                                        </div>

                                        <div class="col-sm-6" v-if="$route.params.id">
                                            <label>Approved Amount</label>
                                        </div>
                                        <div class="col-sm-6" v-if="$route.params.id">
                                            <input min="0" :max="mileage.requested_amount" v-model="mileage.approved_amount" type="number" class="form-control input-sm" id="approved_amount" name="approved_amount">
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="col-sm-10">
                                        <label>Narration</label>
                                        <textarea v-model="mileage.narration" name="narration" id="narration" rows="5" class="form-control input-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <button class="btn btn-success">Process</button>
                            <button class="btn btn-danger" @click.prevent="showModal = false">Back</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            this.$root.isLoading = true;
            http.get('/api/journey/' + this.$route.params.id).then((response) => {
                this.clients = response.clients;
                this.routes = response.routes;
                this.classifications = response.cargo_classifications;
                this.cargo_types = response.cargo_types;
                this.carriage_points = response.carriage_points;
                this.trucks = response.trucks;
                this.drivers = response.drivers;
                this.contract = response.contract;

                this.journey = response.journey.raw;
                this.journey.opener = response.journey.opener;
                this.journey.closer = response.journey.closer;

                if (response.journey.delivery) {
                    this.journey_delivery_status = response.journey.delivery.status;
                }


                let journey = response.journey;
                journey.current_km = '';
                journey.current_fuel = '';
                journey.fuel_reserve = 25;
                journey.fuel_price = 100;
                journey.mileage_balance = '';
                journey.below_reserve = false;
                this.mileage.requested_amount = journey.route.return_mileage;

                this.journey_close = journey;

                this.journey.enquiry_from = this.journey.enquiry_from == 'null' ? '' : this.journey.enquiry_from;
                this.journey.ref_no = this.journey.ref_no == 'null' ? '' : this.journey.ref_no;
                this.journey.contract_id = this.journey.contract_id == 'null' ? '' : this.journey.contract_id;
                this.updateBooleans();
                this.status = response.journey.status;
                this.$root.isLoading = false;
            });
        },
        mounted() {
            $(document).ready(function() {
                $('input[type=number]').on('focus', function() {
                    this.select();
                });
            });
        },
        data() {
            return {
                showModal: false,
                submitted: false,
                config: {},
                mileage: {
                    station_id: window.Laravel.station_id,
                    journey_id: '',
                    mileage_type: 'Return Mileage',
                    standard_amount: 0,
                    requested_amount: 0,
                    approved_amount: '',
                    balance_amount: '',
                    narration: '',
                    top_up: false,
                    top_up_amount: 0,
                    top_up_reason: ''
                },
                drivers: [],
                trucks: [],
                status: '',
                contract: {},
                classifications: [],
                cargo_types: [],
                carriage_points: [],
                unloadingPoint: null,

                clients: [],
                routes: [],
                uploads: [],
                stockItems: [],
                journey: {
                    is_contract_related: 0,
                    contract_id: null,
                    journey_type: 'Local',
                    job_date: null,
                    truck_id: null,
                    driver_id: null,
                    ref_no: '',
                    route_id: null,
                    job_description: '',
                    enquiry_from: '',
                    truck: {
                        driver: {}
                    },
                    subcontracted: false,
                    sub_company_name: '',
                    sub_address_1: '',
                    sub_address_2: '',
                    sub_address_3: '',
                    sub_address_4: '',
                },
                journey_close: {
                    route: {},
                    driver: {},
                    truck: { trailer: {} },
                    fuel: {},
                    mileage: {},
                    current_km: '',
                    current_fuel: '',
                    fuel_reserve: 25,
                    fuel_price: 100,
                    mileage_balance: '',
                },
                journey_delivery_status: ''
            };
        },

        computed: {
            viableCargoTypes() {
                return this.cargo_types.filter(e => e.cargo_classification_id == this.contract.cargo_classification_id);
            },

            toTime() {
                let date = new Date();
                let timeSplit = this.shift.from_time.split(':');

                date.setHours(timeSplit[0], timeSplit[1]);
                date.setTime(date.getTime() + (parseInt(this.shift.hours) * 60 * 60 * 1000));
                let minutes = date.getMinutes().toString();
                let hours = date.getHours().toString();

                hours = hours.length == 1 ? ('0' + hours) : hours;
                minutes = minutes.length == 1 ? ('0' + minutes) : minutes;

                return hours + ':' + minutes;
            },
            belowReserve() {
                return this.fuelDifference > 0;
            },
            fuelDifference() {
                let current = parseInt(this.journey_close.current_fuel);

                if (isNaN(current)) return 0;

                if (current < 0) {
                    this.journey_close.current_fuel = current * -1;
                }

                let fuel_difference = parseInt(this.journey_close.fuel_reserve) - current;

                if (fuel_difference > 0) {
                    this.journey_close.mileage_balance = parseInt(this.journey_close.fuel_price) * fuel_difference;
                } else {
                    this.journey_close.mileage_balance = 0;
                }

                return fuel_difference
            },

            mileageAmount() {
                let defaultAmount = parseInt(this.journey_close.route.return_mileage);
                if (!this.fuelDifference) return defaultAmount;

                let finalAmount = defaultAmount - (parseInt(this.journey_close.fuel_price) * this.fuelDifference);

                if (finalAmount < 1) return 0;

                return finalAmount > defaultAmount ? defaultAmount : finalAmount;
            },

        },

        watch: {
            mileageAmount(value) {
                this.mileage.requested_amount = value;
            }
        },

        methods: {
            getSource() {
                if (this.journey_close.driver.avatar) {
                    return this.journey_close.driver.avatar;
                }

                return '/images/default_avatar.png';
            },

            validateRequestedAmount() {
                if (parseInt(this.mileage.requested_amount) > parseInt(this.journey_close.route.return_mileage)) {
                    this.mileage.requested_amount = 0;
                    return alert2(this.$root, ['Requested amount cannot be more than standard return allowance'], 'danger');
                }
            },

            formatNumber(number) {
                if (!number) {
                    return '';
                }

                return parseFloat(number).toLocaleString();
            },

            mapDate(date) {
                return moment(date).format('DD-MM-Y');
            },

            closeModal() {
                this.showModal = false;
            },
            updateBooleans() {
                let keys = [
                    'subcontracted',
                ];

                keys.forEach(item => this.setBoolState(item));
            },

            setBoolState(key) {
                this.journey[key] = this.journey[key] == 'true';
            },

            fetchContracts() {
                this.$root.isLoading = true;
                http.get('/api/journey/create?contracts=true').then((response) => {
                    this.contracts = response.contracts;
                    this.$root.isLoading = false;
                });
            },

            updateFields() {
                $('#route_id').select2('destroy');
                setTimeout(() => {
                    this.journey.route_id = this.contract.route_id;
                    this.journey.job_description = this.contract.job_description;
                    this.journey.enquiry_from = this.contract.enquiry_from == 'null' ? '' : this.contract.enquiry_from;
                    $('#route_id').select2().on('change', e => this.journey.route_id = e.target.value);
                }, 1000);
            },

            checkState() {
                if (this.$route.params.id) {
                    http.get('/api/journey/' + this.$route.params.id).then((response) => {
                        this.contract = response.contract.raw;
                        this.contract.start_date = this.formatDate(this.contract.start_date);
                        this.contract.end_date = this.formatDate(this.contract.end_date);
                        this.setupUI();
                    });

                    return;
                }
            },

            formatDate(date) {
                date = date.split('-');

                return date[2] + '/' + date[1] + '/' + date[0];
            },

            approveJourney() {
                this.processJourney('approve');
            },

            closeJourney() {
                this.$root.isLoading = true;
                this.mileage.standard_amount = this.journey_close.route.return_mileage;
                this.journey_close.returnMileage = this.mileage;
                http.post('/api/journey/' + this.$route.params.id + '/close', this.journey_close).then((response) => {
                    this.mileage = response.mileage;
                    this.mileage.mileage_current_type = response.mileage.mileage_type;
                    this.config = response.config;
                    this.submitted = true;
                    setTimeout(() => {
                        window.print();
                        this.submitted = false;
                        alert2(this.$root, [response.message], 'success');
                        
                        window._router.push({ path: '/journey' });
                    }, 200);
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
                this.$root.isLoading = false;
            },

            reopenJourney() {
                this.processJourney('reopen');
            },

            processJourney(endpoint) {
                this.$root.isLoading = true;
                http.post('/api/journey/' + this.$route.params.id + '/' + endpoint, {})
                    .then((response) => {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'success');
                        window._router.push({ path: '/journey' });
                    }).catch((error) => {
                        this.$root.isLoading = false;
                        alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                    });
            },
            validateKms() {
                if (parseInt(this.journey_close.current_km) < parseInt(this.journey_close.truck.current_km)) {
                    this.journey_close.current_km = 0;
                    return alert2(this.$root, ['Current Km readings should be greater than previous Km reading'], 'danger');
                }
            },
        }
    }
</script>

<style media="screen" scoped>

</style>
