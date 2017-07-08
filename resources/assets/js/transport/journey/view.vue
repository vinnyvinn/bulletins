<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-6">
                    <h4><strong>Journey Details: JRNY-{{ $route.params.id }} ({{ status }})</strong></h4>
                </div>
                <div class="col-sm-6">
                    <div class="form-group pull-right">
                        <button @click.prevent="approveJourney" class="btn btn-success" v-if="status == 'Pending Approval'">Approve Journey</button>
                        <!-- <button @click.prevent="closeJourney" class="btn btn-danger" v-if="(status == 'Pending Approval') || (status == 'Approved')">Close Journey</button> -->
                        <button @click.prevent="showModal = true" class="btn btn-danger" v-if="(status == 'Pending Approval') || (status == 'Approved')">Close Journey</button>
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
                                <option v-for="route in routes" :value="route.id">{{ route.source }} - {{ route.destination }} ({{ route.distance }} KM)</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="driver_id">Driver</label>
                            <h5>{{ journey_close.truck.driver.first_name }} {{ journey_close.truck.driver.last_name }} ({{ journey_close.truck.driver.mobile_phone }})</h5>
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="truck_id">Vehicle Reg. No</label>
                            <select disabled v-model="journey.truck_id" class="form-control input-sm" id="truck_id" name="truck_id" required>
                                <option v-for="truck in trucks" :value="truck.id">{{ truck.plate_number }}</option>
                            </select>
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
                                <option v-for="classification in classifications" :value="classification.id">{{ classification.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="client_id">Client</label>
                            <select disabled v-model="contract.client_id" name="client_id" id="client_id" class="form-control input-sm select2" required>
                                <option v-for="client in clients" :value="client.DCLink">{{ client.Name }} ({{ client.Account }})</option>
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
                                <option v-for="route in routes" :value="route.id">{{ route.source }} - {{ route.destination }} ({{ route.distance }} KM)</option>
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
                                    <input disabled v-model="journey.subcontracted" type="checkbox">
                                    Check if the trucks have been subcontracted by another company.
                                    Note that the delivery note will be processed in the name of the other company.
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

            <modal :showModal="showModal" :closeAction="closeModal" v-if="showModal">
              <h5 slot="header">Close Journey (JRNY-{{ $route.params.id }})</h5>
              <div slot="body" class="row">
                <div class="col-xs-6">
                  Journey: JNY-{{journey.id}}<br>
                  Contract: CNTR-{{ journey.contract_id }}<br>
                  From: {{ journey.route_source }}<br>
                  To: {{ journey.route_destination }}<br>
                  Truck: {{ journey.truck_plate_number }}<br>
                  Driver: {{ journey_close.truck.driver.first_name}} {{ journey_close.truck.driver.last_name}}<br>
                  <hr>
                  <p v-if="belowReserve"><i style="color: red;">Fuel in truck ({{journey_close.current_fuel}} ltrs) is below reserve by
                    {{ fuelDifference }} ltrs.
                    Driver to pay Ksh. {{ fuelDifference * parseInt(journey_close.fuel_price) }}</i></p>
                    <hr>
                    <p v-if="journey_close.current_km">Kms Covered: {{ parseInt(journey_close.current_km) - parseInt(journey_close.truck.current_km) }}</p><br>
                    <p v-if="journey_close.current_fuel">Fuel Used (ltrs): {{ parseInt(journey_close.truck.current_fuel) - parseInt(journey_close.current_fuel) }}</p><br>
                </div>
                <div class="col-xs-6">
                  Journey Start Km readings: {{ journey_close.truck.current_km }}<br>
                  Mileage Allocated: {{ journey_close.mileage.approved_amount }}<br>
                  Top-up Mileage: {{ journey_close.mileage.top_up_amount }}<br>
                  Fuel Allocated: {{ journey_close.truck.current_fuel }}<br>
                  Top-up fuel: {{ journey_close.fuel.top_up }}<br>
                  <div class="col-xs-12">
                    <div class="form-group">
                      <label for="current_km">Truck Current Km reading:</label>
                      <input type="number" name="current_km" class="form-control input-sm" v-model="journey_close.current_km" @change="validateKms">
                    </div>
                  </div>
                  <div class="col-xs-12">
                    <div :class="{'form-group': true, 'has-error': belowReserve}">
                      <label class="control-label" for="current_km">Truck Current fuel reading:</label>
                      <input type="number" id="current_fuel" name="current_fuel" class="form-control input-sm" v-model="journey_close.current_fuel" @change="">
                    </div>
                  </div>
                </div>
              </div>
              <div slot="footer" class="">
                <button type="button" @click.prevent="closeJourney" class="btn btn-success" name="button">Process & Close</button>
              </div>
            </modal>

        </div>
    </div>
</template>

<script>
    import Modal from 'modal-vue'
    export default {
      components: { Modal },
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

                let journey = response.journey;
                journey.current_km = '';
                journey.current_fuel = '';
                journey.fuel_reserve = 25;
                journey.fuel_price = 100;
                journey.mileage_balance = '';
                journey.below_reserve = false;

                this.journey_close = journey;

                this.journey.enquiry_from = this.journey.enquiry_from == 'null' ? '' : this.journey.enquiry_from;
                this.journey.ref_no = this.journey.ref_no == 'null' ? '' : this.journey.ref_no;
                this.journey.contract_id = this.journey.contract_id == 'null' ? '' : this.journey.contract_id;
                this.updateBooleans();
                this.status = response.journey.status;
                this.$root.isLoading = false;
            });
        },

        data() {

            return {
                showModal: false,
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
                  truck: {
                    driver: {}
                  },
                  fuel: {},
                  mileage: {},
                  current_km: '',
                  current_fuel: '',
                  fuel_reserve: 25,
                  fuel_price: 100,
                  mileage_balance: '',
                }
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
                date.setTime(date.getTime() + (parseInt(this.shift.hours) *60*60*1000));
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

              if(fuel_difference > 0){
                this.journey_close.mileage_balance = parseInt(this.journey_close.fuel_price) * fuel_difference;
              } else {
                this.journey_close.mileage_balance = 0;
              }

              return fuel_difference
            }

        },

        methods: {
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
                http.post('/api/journey/' + this.$route.params.id + '/close', this.journey_close).then((response) => {
                  alert2(this.$root, [response.message], 'success');
                  window._router.push({ path: '/journey' });
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
              if(parseInt(this.journey_close.current_km) < parseInt(this.journey_close.truck.current_km)){
                this.journey_close.current_km = 0;
                return alert2(this.$root, ['Current Km readings should be greater than previous Km reading'], 'danger');
              }
            },

        }
    }
</script>

<style media="screen" scoped>

</style>
