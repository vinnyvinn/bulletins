<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Contract Details   (Contract Id: {{ parseInt(last_contract_id.id) +1 }}*)</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="name">Contract Name</label>
                            <input v-model="contract.name" type="text" class="form-control input-sm" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="cargo_classification_id">Cargo Classification</label>
                            <select v-model="contract.cargo_classification_id" name="cargo_classification_id" id="cargo_classification_id" class="form-control input-sm select2">
                                <option v-for="classification in classifications" :value="classification.id">{{ classification.name }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="job_description">Job Description</label>
                            <textarea v-model="contract.job_description" name="job_description" id="job_description" rows="5" class="form-control input-sm"></textarea>
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="client_id">Client</label>
                            <select v-model="contract.client_id" name="client_id" id="client_id" class="form-control input-sm select2">
                                <option v-for="client in clients" :value="client.DCLink">{{ client.Name }} ({{ client.Account }})</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cargo_type_id">Cargo Type</label>
                            <select v-model="contract.cargo_type_id" name="cargo_type_id" id="cargo_type_id" class="form-control input-sm select2">
                                <option v-for="type in viableCargoTypes" :value="type.id">{{ type.name }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Weights</label>
                            <div class="checkbox">
                                <label>
                                    <input v-model="contract.capture_loading_weights" type="checkbox"> Weights captured at loading point?
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input v-model="contract.capture_offloading_weights" type="checkbox"> Weights captured at offloading point?
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="start_date">Contract Start</label>
                            <input v-model="contract.start_date" type="text" class="datepicker form-control input-sm" id="start_date" name="start_date">
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <div class="input-group">
                                <input v-model="contract.quantity" min="0" type="number" class="form-control input-sm" id="quantity" name="quantity" describedby="quantity-addon">
                                <span class="input-group-addon" id="quantity-addon">KGs</span>
                            </div>
                        </div>


                        <div class="form-group" v-if="contract.capture_loading_weights">
                            <div class="checkbox">
                                <label>
                                    <input v-model="contract.ls_loading_weights" type="checkbox"> Loading capture from weighbridge (LS)
                                </label>
                            </div>
                        </div>
                        <div class="form-group" v-if="contract.capture_offloading_weights">
                            <div class="checkbox">
                                <label>
                                    <input v-model="contract.ls_offloading_weights" type="checkbox"> Offloading capture from weighbridge (LS)
                                </label>
                            </div>
                        </div>
                        <div class="form-group" v-if="contract.capture_loading_weights">
                            <div class="checkbox">
                                <label>
                                    <input v-model="contract.lh_loading_weights" type="checkbox"> Loading capture from weighbridge (LH)
                                </label>
                            </div>
                        </div>
                        <div class="form-group" v-if="contract.capture_offloading_weights">
                            <div class="checkbox">
                                <label>
                                    <input v-model="contract.lh_offloading_weights" type="checkbox"> Offloading capture from weighbridge (LH)
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="route_id">Route</label>
                            <select v-model="contract.route_id" name="route_id" id="route_id" class="form-control input-sm select2">
                                <option v-for="route in routes" :value="route.id">{{ route.source }} - {{ route.destination }} ({{ route.distance }} KM)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="trucks_allocated">Trucks Allocated</label>
                            <input v-model="contract.trucks_allocated" min="0" type="number" class="form-control input-sm" id="trucks_allocated" name="trucks_allocated">
                        </div>

                        <div class="form-group">
                            <label for="loading_point">Loading Point</label>
                            <select v-model="contract.loading_point_id" name="loading_point_id" id="loading_point_id" class="form-control input-sm select2"x>
                                <option v-for="point in carriage_points" :value="point.id">{{ point.name }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="unloading_point">Unloading Point</label>
                            <div class="input-group">
                                <select v-model="unloadingPoint" name="unloading_point" id="unloading_point" class="form-control input-sm select2">
                                    <option v-for="point in carriage_points" :value="point.id">{{ point.name }}</option>
                                </select>
                                <span class="input-group-btn">
                                    <button @click.prevent="addUnloadingPoint" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add</button>
                                </span>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="enquiry_from">Enquiry From</label>
                            <input v-model="contract.enquiry_from" type="text" class="form-control input-sm" id="enquiry_from" name="enquiry_from">
                        </div>

                        <div class="form-group">
                            <label>Packages</label>
                            <div class="checkbox">
                                <label>
                                    <input v-model="contract.packages_captured" type="checkbox"> Packages captured at loading?
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="contract_head">Head of Contract</label>
                            <input v-model="contract.contract_head" type="text" class="form-control input-sm" id="contract_head" name="contract_head">
                        </div>


                        <div class="form-group">
                            <label for="estimated_days">Estimated Period</label>
                            <div class="input-group">
                                <input v-model="contract.estimated_days" min="0" type="number" class="form-control input-sm" id="estimated_days" name="estimated_days" describedby="estimated_days-addon">
                                <span class="input-group-addon" id="estimated_days-addon">Days</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                          <label for="lot_number">Lot Number</label>
                          <input v-model="contract.lot_number" type="text" class="form-control input-sm" id="lot_number" name="lot_number">
                      </div>
                    </div>

                    <div class="col-sm-3">
                        <table class="table table-striped">
                           <thead>
                             <tr>
                               <th>Unloading Point</th>
                               <th></th>
                             </tr>
                           </thead>
                           <tbody>
                             <tr v-for="point in contract.unloading_points">
                               <td>{{ point.name }}</td>
                               <td><button @click.prevent="removeUnloadingPoint(point)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>
                             </tr>
                           </tbody>
                        </table>
                    </div>
                </div>

                <h4><strong>Ship Details</strong></h4>

                <hr>

                <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                          <label for="shipping_line">Shipping Line</label>
                          <input v-model="contract.shipping_line" type="text" class="form-control input-sm" id="shipping_line" name="shipping_line">
                      </div>

                      <div class="form-group">
                          <label for="berth_no">Berth No</label>
                          <input v-model="contract.berth_no" type="text" class="form-control input-sm" id="berth_no" name="berth_no">
                      </div>
                    </div>

                    <div class="col-sm-4">

                      <div class="form-group">
                          <label for="vessel_name">Vessel Name</label>
                          <input v-model="contract.vessel_name" type="text" class="form-control input-sm" id="vessel_name" name="vessel_name" >
                      </div>

                      <div class="form-group">
                          <label for="berthing_date">Berthing Date</label>
                          <input v-model="contract.berthing_date" type="text" class="datepicker form-control input-sm" id="berthing_date" name="berthing_date">
                      </div>

                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                          <label for="no_of_shifts">No of Shifts</label>
                          <input number v-model="contract.no_of_shifts" type="number" min="1" class="form-control input-sm" id="no_of_shifts" name="no_of_shifts">
                      </div>

                      <div class="form-group">
                          <label for="vessel_arrival_date">Vessel Arrival Date</label>
                          <input v-model="contract.vessel_arrival_date" type="text" class="datepicker form-control input-sm" id="vessel_arrival_date" name="vessel_arrival_date">
                      </div>

                    </div>
                </div>

                <h4><strong>Shifts Details</strong></h4>

                <hr>

                <div class="row">
                    <div class="col-sm-3">
                      <div class="form-group">
                          <label for="shift_number">Shift Number</label>
                          <select v-model="shift.shift_number" name="shift_number" id="shift_number" class="form-control input-sm">
                              <option v-for="item in parseInt(contract.no_of_shifts)" :value="item">{{ item }}</option>
                          </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                          <label for="from_time">From Time</label>
                          <input v-model="shift.from_time" type="time" name="from_time" class="form-control input-sm">
                      </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="hours">No of Hours</label>
                            <select v-model="shift.hours" name="hours" id="hours" class="form-control input-sm">
                                <option v-for="item in 24" :value="item">{{ item + (item > 1 ? ' Hours' : ' Hour') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="to_time">To Time</label>
                                    <input disabled type="time" name="to_time" id="to_time" :value="toTime" class="form-control input-sm">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <br>
                                <button class="btn btn-primary" @click.prevent="addShift"><i class="fa fa-plus"></i> Add</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Shift No</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Hours</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="shift in contract.shifts">
                                  <td>{{ shift.shift_number }}</td>
                                  <td><input disabled type="time" :value="shift.from_time"></td>
                                  <td><input disabled type="time" :value="shift.to_time"></td>
                                  <td class="text-left">{{ shift.hours }}</td>
                                  <td><button class="btn btn-danger btn-xs" @click.prevent="removeShift(shift)"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h4><strong>Billing Details</strong></h4>

                <hr>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="rate">Rate</label>
                            <select v-model="contract.rate" name="rate" id="rate" class="form-control input-sm select2">
                                <option value="Per Hour">Per Hour</option>
                                <option value="Per KM">Per KM</option>
                                <option value="Per Tonne">Per Tonne</option>
                            </select>
                        </div>


                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="amount">Price {{ contract.rate }}</label>
                            <input v-model="contract.amount" type="number" class="form-control input-sm" id="amount" name="amount">
                        </div>
                    </div>


                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="stock_item_id">Billable Item</label>
                            <select v-model="contract.stock_item_id" name="stock_item_id" id="stock_item_id" class="form-control input-sm select2">
                                <option v-for="item in stockItems" :value="item.StockLink">{{ item.Description_1 }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <h4>Sub Contracts</h4>
                <hr>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Sub-Contracts</label>
                            <div class="checkbox">
                                <label>
                                    <input v-model="contract.subcontracted" type="checkbox">
                                    Check if the trucks have been subcontracted by another company.
                                    Note that the delivery note will be processed in the name of the other company.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="contract.subcontracted">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sub_company_name">Company Name</label>
                            <input v-model="contract.sub_company_name" type="text" class="form-control input-sm" id="sub_company_name" name="sub_company_name">
                        </div>
                        <div class="form-group">
                            <label for="sub_address_1">Address Line 1</label>
                            <input v-model="contract.sub_address_1" type="text" class="form-control input-sm" id="sub_address_1" name="sub_address_1">
                        </div>

                        <div class="form-group">
                            <label for="sub_address_2">Address Line 2</label>
                            <input v-model="contract.sub_address_2" type="text" class="form-control input-sm" id="sub_address_2" name="sub_address_2">
                        </div>

                        <div class="form-group">
                            <label for="sub_address_3">Address Line 3</label>
                            <input v-model="contract.sub_address_3" type="text" class="form-control input-sm" id="sub_address_3" name="sub_address_3">
                        </div>

                        <div class="form-group">
                            <label for="sub_address_4">Address Line 4</label>
                            <input v-model="contract.sub_address_4" type="text" class="form-control input-sm" id="sub_address_4" name="sub_address_4">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="sub_delivery_to">Delivery To</label>
                            <input v-model="contract.sub_delivery_to" type="text" class="form-control input-sm" id="sub_delivery_to" name="sub_delivery_to">
                        </div>
                        <div class="form-group">
                            <label for="sub_delivery_address_1">Address Line 1</label>
                            <input v-model="contract.sub_delivery_address" type="text" class="form-control input-sm" id="sub_delivery_address" name="sub_delivery_address">
                        </div>
                        <div class="form-group">
                            <label for="sub_delivery_address_2">Address Line 2</label>
                            <input v-model="contract.sub_delivery_address_2" type="text" class="form-control input-sm" id="sub_delivery_address_2" name="sub_delivery_address_2">
                        </div>
                        <div class="form-group">
                            <label for="sub_delivery_addres_3">Address Line 3</label>
                            <input v-model="contract.sub_delivery_address_3" type="text" class="form-control input-sm" id="sub_delivery_address_3" name="sub_delivery_address_3">
                        </div>
                        <div class="form-group">
                            <label for="sub_delivery_address_4">Address Line 4</label>
                            <input v-model="contract.sub_delivery_address_4" type="text" class="form-control input-sm" id="sub_delivery_address_4" name="sub_delivery_address_4">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <udf module="Contracts" :state="contract" :uploads="uploads" cols="col-sm-4"></udf>
                </div>

                <hr>

                <div class="form-group">
                    <button class="btn btn-success">Save</button>
                    <router-link to="/contracts" class="btn btn-danger">Back</router-link>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    export default {
        created() {
            http.get('/api/contract/create').then((response) => {
                this.clients = response.clients;
                this.stockItems = response.stockItems;
                this.routes = response.routes;
                this.classifications = response.cargo_classifications;
                this.cargo_types = response.cargo_types;
                this.carriage_points = response.carriage_points;
                if(response.last_contract_id){
                  this.last_contract_id = response.last_contract_id;
                }
            });
        },

        mounted() {
            this.checkState();
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },

        data() {
            return {
                classifications: [],
                cargo_types: [],
                carriage_points: [],
                unloadingPoint: null,
                shift: {
                  shift_number: null,
                  from_time: '08:00',
                  hours: 1,
                  to_time: null,
                },
                clients: [],
                routes: [],
                uploads: [],
                stockItems: [],
                contract: {
                    cargo_classification_id: null,
                    cargo_type_id: null,
                    trucks_allocated: 0,
                    job_description: '',
                    capture_loading_weights: false,
                    capture_offloading_weights: false,
                    ls_loading_weights: false,
                    ls_offloading_weights: false,
                    lh_loading_weights: false,
                    lh_offloading_weights: false,
                    loading_point_id: '',
                    unloading_points: [],
                    enquiry_from: '',
                    contract_head: '',
                    packages_captured: false,
                    estimated_days: 0,
                    lot_number: '',
                    shipping_line: '',
                    berth_no: '',
                    vessel_name: '',
                    berthing_date: null,
                    no_of_shifts: 1,
                    vessel_arrival_date: null,
                    shifts: [],
                    name: null,
                    rate: 'Per Tonne',
                    amount: null,
                    client_id: null,
                    stock_item_id: null,
                    route_id: null,
                    start_date: null,
                    quantity: null,

                    subcontracted: false,
                    sub_company_name: '',
                    sub_address_1: '',
                    sub_address_2: '',
                    sub_address_3: '',
                    sub_address_4: '',
                    sub_delivery_to: '',
                    sub_delivery_address: '',
                    sub_delivery_address_2: '',
                    sub_delivery_address_3: '',
                    sub_delivery_address_4: '',
                },
                last_contract_id: {
                  id: 0
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
        },

        methods: {
            updateBooleans() {
                let keys = [
                    'capture_loading_weights', 'capture_offloading_weights', 'packages_captured', 'ls_loading_weights',
                    'ls_offloading_weights', 'lh_offloading_weights', 'lh_loading_weights', 'subcontracted'
                ];

                keys.forEach(item => this.setBoolState(item));
            },

            setBoolState(key) {
                this.contract[key] = this.contract[key] == 'true';
            },

            addUnloadingPoint() {
                if (this.contract.unloading_points.some(point => point.id == this.unloadingPoint)) return;

                let selectedPoint = this.carriage_points.filter(point => point.id == this.unloadingPoint);

                if (selectedPoint.length < 1) return;

                if (this.contract.unloading_points.some(point => point.id == this.unloadingPoint));
                this.contract.unloading_points.push({
                    id: selectedPoint[0].id,
                    name: selectedPoint[0].name
                });
                this.unloadingPoint = 0;
            },

            removeUnloadingPoint(point) {
                this.contract.unloading_points.splice(this.contract.unloading_points.indexOf(point), 1);
            },

            addShift() {
              if (! this.shift.shift_number) return;
              if (this.contract.shifts.some(shift => shift.shift_number == this.shift.shift_number)) return;
              this.shift.to_time = this.toTime;
              this.contract.shifts.push(this.shift);
              this.shift = {
                shift_number: null,
                from_time: '08:00',
                hours: 1,
                to_time: null,
              };
            },

            removeShift(shift) {
              this.contract.shifts.splice(this.contract.shifts.indexOf(shift), 1);
            },

            checkState() {
                if (this.$route.params.id) {
                    this.$root.isLoading = true;
                    http.get('/api/contract/' + this.$route.params.id).then((response) => {
                        this.contract = response.contract.raw;
                        this.updateBooleans();
                        this.contract.start_date = this.formatDate(this.contract.start_date);
                        this.contract.vessel_arrival_date = this.contract.vessel_arrival_date ? this.formatDate(this.contract.vessel_arrival_date) : null;
                        this.contract.berthing_date = this.contract.berthing_date ? this.formatDate(this.contract.berthing_date) : null;
                        this.setupUI();
                        this.$root.isLoading = false;
                    });

                    return;
                }

                if (this.$route.params.templateId) {
                    this.$root.isLoading = true;
                    http.get('/api/contract-template/' + this.$route.params.templateId).then((response) => {
                        this.contract = response.contract.raw;
                        this.contract.name = '';
                        this.updateBooleans();
                        this.setupUI();
                        this.$root.isLoading = false;
                    });

                    return;
                }
                this.setupUI();
            },

            formatDate(date) {
                date = date.split('-');

                return date[2] + '/' + date[1] + '/' + date[0];
            },

            setupUI() {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                    todayHighlight: true,
                });

                $('#start_date').datepicker().on('changeDate', (e) => {
                    this.contract.start_date = e.date.toLocaleDateString('en-GB');
                    $('#end_date').datepicker('setStartDate', e.date);
                });

                $('#end_date').datepicker().on('changeDate', (e) => {
                    this.contract.end_date = e.date.toLocaleDateString('en-GB');
                });

                $('#berthing_date').datepicker().on('changeDate', (e) => {
                    this.contract.berthing_date = e.date.toLocaleDateString('en-GB');
                });

                $('#vessel_arrival_date').datepicker().on('changeDate', (e) => {
                    this.contract.vessel_arrival_date = e.date.toLocaleDateString('en-GB');
                });


                setTimeout(() => {
                    $('#client_id').select2().on('change', e => this.contract.client_id = e.target.value);
                    $('#route_id').select2().on('change', e => this.contract.route_id = e.target.value);
                    $('#stock_item_id').select2().on('change', e => this.contract.stock_item_id = e.target.value);
                }, 1000);
            },



            store() {
                this.$root.isLoading = true;
                let contract = JSON.parse(JSON.stringify(this.contract));
                contract.unloading_points = JSON.stringify(this.contract.unloading_points);
                contract.shifts = JSON.stringify(this.contract.shifts);

                let request = null;
                let data = mapToFormData(contract, this.uploads, typeof this.$route.params.id === 'string');

                if (this.$route.params.id) {
                    request = http.put('/api/contract/' + this.$route.params.id, data, true);
                } else {
                    request = http.post('/api/contract', data, true);
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/contracts' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },

            addUdfToObject (slug) {
              Vue.set(this.contract,slug,'');
            }
        }
    }
</script>
