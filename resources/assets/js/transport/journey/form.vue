l <template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Journey Details:  [Journey Id: {{ parseInt(last_journey_id.id) +1 }}]</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="is_contract_related">Contract Related?</label>
                            <select v-model="journey.is_contract_related" @change="fetchContracts" class="form-control input-sm" id="is_contract_related" name="is_contract_related" required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>

                        <div class="form-group" v-if="journey.is_contract_related == '1'">
                            <label for="contract_id">Contract</label>
                            <select @change="updateFields" v-model="journey.contract_id" class="form-control input-sm" id="contract_id" name="contract_id" required>
                                <option v-for="item in contracts" :value="item.id">CNTR{{ item.id }} - ({{ item.name }}) - {{ item.client.Name }}</option>
                            </select>
                        </div>

                        <div class="form-group" v-if="journey.is_contract_related == '0' || contract.allow_route_change == 'true'">
                            <label for="route_id">Route</label>
                            <select :disabled="journey.is_contract_related == '1' && contract.allow_route_change == 'false'" name="route_id" id="route_id" v-model="journey.route_id" class="form-control input-sm select2" required>
                                <option v-for="route in routes" :value="route.id">{{ route.source }} - {{ route.destination }} ({{ route.distance }} KM)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="enquiry_from">Enquiry from</label>
                            <input :disabled="journey.is_contract_related == '1'" type="text" v-model="journey.enquiry_from" class="form-control input-sm" id="enquiry_from" name="enquiry_from">
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="journey_type">Job Type</label>
                            <select :disabled="journey.is_contract_related == '1'" v-model="journey.journey_type" class="form-control input-sm" id="journey_type" name="journey_type" required>
                                <option value="Local">Local</option>
                                <option value="International">International</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="job_date">Job Date</label>
                            <input type="text" v-model="journey.job_date" class="form-control input-sm datepicker" id="job_date" name="job_date" required>
                        </div>



                        <!-- <div class="form-group">
                            <label for="driver_id">Driver</label>
                            <select name="driver_id" id="driver_id" v-model="journey.driver_id" class="form-control input-sm select2" required>
                                <option v-for="driver in drivers" :value="driver.id">{{ driver.first_name }} {{ driver.last_name }} ({{ driver.mobile_phone }})</option>
                            </select>
                        </div> -->

                    </div>



                    <div class="col-sm-3">

                        <div class="form-group">
                            <label for="job_description">Job Description</label>
                            <textarea :disabled="journey.is_contract_related == '1'" v-model="journey.job_description" name="job_description" id="job_description" rows="5" class="form-control input-sm"></textarea>
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <div v-if="journey.is_contract_related == '1'">
                            <h5><strong>Required trucks allocation: {{ contract.trucks_allocated }}</strong></h5>
                            <h5><strong>Trucks already allocated: {{ trucks_already_allocated }}</strong></h5>
                            <h5><strong>Trucks to be allocated: {{ parseInt(contract.trucks_allocated)-parseInt(trucks_already_allocated) }}</strong></h5>

                        </div>

                        <div class="form-group">
                            <label for="truck_id">Vehicle Reg. No</label>
                            <select :disabled="$route.params.id" @change="addTruck()" v-model="journey.truck_id"  class="form-control input-sm" id="truck_id" name="truck_id" required>
                                <option v-for="truck in trucks" :value="truck.id">{{ truck.plate_number }}</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="driver_id">Driver</label>
                            <select name="driver_id" id="driver_id" v-model="journey.driver_id" class="form-control input-sm select2" required>
                                <option v-for="driver in drivers" :value="driver.id">{{ driver.first_name }} {{ driver.last_name }} ({{ driver.mobile_phone }})</option>
                            </select>
                        </div>

                    </div>



<!--
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="ref_no">Ref No</label>
                            <input type="text" v-model="journey.ref_no" class="form-control input-sm" id="ref_no" name="ref_no">
                        </div>
                    </div> -->

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
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="loading_point_id">Unloading Point</label>
                            <select @change="changedUnloadingPoint" v-if="unloadingpoints.length > 0" v-model="journey.unloading_point"
                                    name="unloading_point" id="unloading_point"
                                    class="form-control input-sm select2" required>
                                <option v-for="point in unloadingpoints"
                                        :value="point.name">{{ point.name }}</option>
                            </select>
                            <br>
                            <input class="form-control"
                                   v-if="unloadingpoints.length === 0"
                                   type="text"
                                   placeholder="Enter Unloading Point"
                                   v-model="journey.unloading_point"
                                   :required="unloadingpoints.length >0"
                            />
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
                                    <input v-model="journey.subcontracted" type="checkbox">
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
                            <input required v-model="journey.sub_company_name" type="text" class="form-control input-sm" id="sub_company_name" name="sub_company_name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sub_address_3">Address Line 3</label>
                            <input v-model="journey.sub_address_3" type="text" class="form-control input-sm" id="sub_address_3" name="sub_address_3">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sub_address_1">Address Line 1</label>
                            <input required v-model="journey.sub_address_1" type="text" class="form-control input-sm" id="sub_address_1" name="sub_address_1">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sub_address_4">Address Line 4</label>
                            <input v-model="journey.sub_address_4" type="text" class="form-control input-sm" id="sub_address_4" name="sub_address_4">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sub_address_2">Address Line 2</label>
                            <input required v-model="journey.sub_address_2" type="text" class="form-control input-sm" id="sub_address_2" name="sub_address_2">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button class="btn btn-success">Save</button>
                    <router-link to="/journey" class="btn btn-danger">Back</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

    export default {
        created() {

            if (! this.$route.params.id && ! this.$root.can('create-journey')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id && ! this.$root.can('edit-journey')) {
                this.$router.push('/403');
                return false;
            }

            this.$root.isLoading = true;

            if (this.$route.params.id) {
                return this.checkState();
            }

            http.get('/api/journey/create').then((response) => {
                this.clients = response.clients;
                this.routes = response.routes;
                this.classifications = response.cargo_classifications;
                this.cargo_types = response.cargo_types;
                this.carriage_points = response.carriage_points;
                this.trucks = response.trucks;
                this.drivers = response.drivers;
                if(response.last_journey_id){
                  this.last_journey_id = response.last_journey_id;
                }

                return response;
            }).then(() => {
                return http.get('/api/journey/create?contracts=true').then((response) => {
                    this.contracts = response.contracts;
                    this.isContractsLoaded = true;
                    this.$root.isLoading = false;

                    return response;
                })
            }).then(() => this.setupUI())
                .catch(() => this.$root.isLoading = false);
        },

        mounted() {
          this.setupUI();

            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },

        data() {
            return {
                temp_driver: false,
                isContractsLoaded: false,
                contracts: [],
                drivers: [],
                trucks: [],
                classifications: [],
                cargo_types: [],
                carriage_points: [],
                unloadingPoint: null,
                clients: [],
                routes: [],
                uploads: [],
                stockItems: [],
                journey: {
                    station_id: window.Laravel.station_id,
                    is_contract_related: 1,
                    contract_id: '',
                    journey_type: 'Local',
                    job_date: '',
                    truck_id: '',
                    trucks: [],
                    driver_id: '',
                    ref_no: '',
                    route_id: '',
                    job_description: '',
                    enquiry_from: '',

                    subcontracted: false,
                    sub_company_name: '',
                    sub_address_1: '',
                    sub_address_2: '',
                    sub_address_3: '',
                    sub_address_4: '',
                    unloading_point:''
                },
                last_journey_id: {
                  id: 0
                },
                trucks_already_allocated: '',
                unloadingpoints:[]
            };
        },

        computed: {
            truck() {
                let truck = this.trucks.filter(e => e.id == this.journey.truck_id);
                if (truck.length) {
                    return truck[0];
                }

                return {
                    driver: {},
                };
            },
            contract() {
                let contract = this.contracts.filter(e => e.id == this.journey.contract_id);
                if (contract.length) {
                    let selectedContract = contract[0];
                    contract = JSON.parse(selectedContract.raw);
                    contract.ignore_delivery_note = selectedContract.ignore_delivery_note;
                    return contract;
                }

                return {
                    ignore_delivery_note: 0,
                    cargo_classification_id: null,
                    cargo_type_id: null,
                    name: '',
                    client_id: null,
                    loading_point_id: null,
                    quantity: null,
                    route_id: null,
                    start_date: null,
                };
            },

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
        },

        methods: {
            changedUnloadingPoint(event){
                this.journey.unloading_point = event.target.value;
            },
            addTruck() {
              setTimeout(() => {
                this.journey.trucks.push({'id': this.journey.truck_id});
                this.journey.driver_id = this.truck.driver ? this.truck.driver.id : null;
              }, 500);
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
                if (this.journey.is_contract_related == 1) {
                    $('#route_id').select2('destroy');
                    if (! this.isContractsLoaded) {
                        this.$root.isLoading = true;

                        return http.get('/api/journey/create?contracts=true').then((response) => {
                            this.contracts = response.contracts;
                            this.isContractsLoaded = true;
                            this.$root.isLoading = false;

                            return response;
                        });
                    }

                    return;
                }

                $(document).ready(() => $('#route_id').select2());
            },

            updateFields() {
                this.$root.isLoading = true;
                $('#route_id').select2('destroy');
                this.journey.unloading_point = '';

                setTimeout(() => {
                    http.get('/api/trucks_already_allocated/' + this.journey.contract_id).then((response) => {
                        this.trucks_already_allocated = response.trucks_already_allocated;
                        this.unloadingpoints = JSON.parse(response.unloading_points);
                    });
                    this.journey.route_id = this.contract.route_id;
                    this.journey.job_description = this.contract.job_description;
                    this.journey.enquiry_from = this.contract.enquiry_from == 'null' ? '' : this.contract.enquiry_from;
                    this.journey.route_id = this.contract.route_id;
                    this.$root.isLoading = false;
                    setTimeout(() => $('#route_id').select2().on('change', e =>{
                        this.journey.route_id = e.target.value
                    }), 500);
                }, 500);

            },

            checkState() {
                if (this.$route.params.id) {
                    return http.get('/api/journey/' + this.$route.params.id).then((response) => {
                        this.clients = response.clients;
                        this.routes = response.routes;
                        this.classifications = response.cargo_classifications;
                        this.cargo_types = response.cargo_types;
                        this.carriage_points = response.carriage_points;
                        this.trucks = response.trucks;
                        this.drivers = response.drivers;
                        this.contracts = response.contracts;
                        this.isContractsLoaded = true;

//                            this.contract = response.contract;
                        let journey = response.journey.raw;
                        journey.enquiry_from = journey.enquiry_from == 'null' ? '' : this.journey.enquiry_from;
                        journey.ref_no = journey.ref_no == 'null' ? '' : this.journey.ref_no;
                        journey.ref_no = journey.contract_id == 'null' ? '' : this.journey.contract_id;
                        this.journey = Object.assign({}, journey);
                        this.trucks_already_allocated = response.allocated;
                        this.updateBooleans();
                        this.status = response.journey.status;
                        this.setupUI();
                        this.$root.isLoading = false;
                    });
                }
            },

            formatDate(date) {
                date = date.split('-');

                return date[2] + '/' + date[1] + '/' + date[0];
            },

            setupUI() {
                $(document).ready(() => {
                    $('.datepicker').datepicker({
                        autoclose: true,
                        format: 'dd/mm/yyyy',
                        todayHighlight: true,
                        startDate: '0d',
                    });

                    $('#job_date').datepicker().on('changeDate', (e) => {
                        this.journey.job_date = e.date.toLocaleDateString('en-GB');
                    });

                    setTimeout(() => {
                        $('#truck_id').select2().on('change', e => {
                            this.journey.truck_id = e.target.value;
                            this.addTruck();
                            $('#driver_id').select2().val(this.truck.driver_id).trigger('change');
                        });
                        $('#driver_id').select2().on('change', e => this.journey.driver_id = e.target.value);
                        $('#route_id').select2().on('change', e => this.journey.route_id = e.target.value);
                    }, 1000);
                });
            },

            store() {
                //check if journey unloading point is set
                if(this.journey.unloading_point === ''){
                    alert2(this.$root, ["Please Enter/Select unloading point"], 'danger');
                    return;
                }

                this.$root.isLoading = true;
                let request = null;

                let truck = this.trucks.filter(e => e.id == this.journey.truck_id)[0];
                let route = this.routes.filter(e => e.id == this.journey.route_id)[0];
                // console.log(route);
                this.journey.truck_plate_number = truck.plate_number;
                this.journey.route_id = route ? route.id : this.routes[0].id;
                this.journey.route_destination = route ? route.id : this.routes[0].id;
                this.journey.ignore_delivery_note = this.contract.ignore_delivery_note;

                let data = mapToFormData(this.journey, this.uploads, typeof this.$route.params.id === 'string');

                if (this.$route.params.id) {
                    request = http.put('/api/journey/' + this.$route.params.id, data, true);
                } else {
                    request = http.post('/api/journey', data, true);
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/journey' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },


        }
    }
</script>
