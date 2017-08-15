<template>
    <div class="panel panel-default">
        <div class="panel-heading no-bottom">
            <div class="row">
                <div class="col-sm-6">
                    <h4><strong>Contract Details: {{ status }}</strong></h4>
                </div>
                <div class="col-sm-6">
                    <div class="form-group pull-right" v-if="$root.can('approve-contract')">
                      <router-link :to="'/contract_setting/create/' + this.$route.params.id" class="btn btn-primary pull-right"><i class="fa fa-cog"></i> Contract Settings</router-link>
                        <button @click.prevent="approveContract" class="btn btn-success" v-if="status == 'Pending Approval'">Approve Contract</button>
                        <button @click.prevent="closeContract" class="btn btn-danger" v-if="(status == 'Pending Approval') || (status == 'Approved')">Close Contract</button>
                        <button @click.prevent="reopenContract" class="btn btn-primary" v-if="status == 'Closed'">Reopen Contract</button>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active col-sm-4 text-center">
                    <a href="#details" aria-controls="details" role="tab" data-toggle="tab">Contract Details</a>
                </li>
                <li role="presentation" class="col-sm-4 text-center">
                    <a href="#journeys" aria-controls="journeys" role="tab" data-toggle="tab">Contract Journeys</a>
                </li>
                <li role="presentation" class="col-sm-4 text-center">
                    <a href="#deliveries" aria-controls="deliveries" role="tab" data-toggle="tab">Contract Deliveries</a>
                </li>
            </ul>

        </div>

        <div class="panel-body">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="details">
                    <form action="#" role="form" @submit.prevent="store">
                        <div class="row">
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

                                <div class="form-group">
                                    <label for="job_description">Job Description</label>
                                    <textarea disabled v-model="contract.job_description" name="job_description" id="job_description" rows="5" class="form-control input-sm"></textarea>
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

                                <div class="form-group">
                                    <label>Mileage &amp; Fuel</label>
                                    <div class="checkbox">
                                        <label>
                                            <input disabled v-model="contract.ignore_delivery_note" type="checkbox"> Allow fuel and mileage before delivery note?
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Weights</label>
                                    <div class="checkbox">
                                        <label>
                                            <input disabled v-model="contract.capture_loading_weights" type="checkbox"> Weights captured at loading point?
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input disabled v-model="contract.capture_offloading_weights" type="checkbox"> Weights captured at offloading point?
                                        </label>
                                    </div>
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


                                <div class="form-group" v-if="contract.capture_loading_weights">
                                    <div class="checkbox">
                                        <label>
                                            <input disabled v-model="contract.ls_loading_weights" type="checkbox"> Loading capture from weighbridge (LS)
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" v-if="contract.capture_offloading_weights">
                                    <div class="checkbox">
                                        <label>
                                            <input disabled v-model="contract.ls_offloading_weights" type="checkbox"> Offloading capture from weighbridge (LS)
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" v-if="contract.capture_loading_weights">
                                    <div class="checkbox">
                                        <label>
                                            <input disabled v-model="contract.lh_loading_weights" type="checkbox"> Loading capture from weighbridge (LH)
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" v-if="contract.capture_offloading_weights">
                                    <div class="checkbox">
                                        <label>
                                            <input disabled v-model="contract.lh_offloading_weights" type="checkbox"> Offloading capture from weighbridge (LH)
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="route_id">Route</label>
                                    <select disabled v-model="contract.route_id" name="route_id" id="route_id" class="form-control input-sm select2" required>
                                        <option v-for="route in routes" :value="route.id">{{ route.source }} - {{ route.destination }} ({{ route.distance }} KM)</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="trucks_allocated">Trucks Allocated</label>
                                    <input disabled v-model="contract.trucks_allocated" min="0" type="number" class="form-control input-sm" id="trucks_allocated" name="trucks_allocated" required>
                                </div>

                                <div class="form-group">
                                    <label for="loading_point_id">Loading Point</label>
                                    <select disabled v-model="contract.loading_point_id" name="loading_point_id" id="loading_point_id" class="form-control input-sm select2" required>
                                        <option v-for="point in carriage_points" :value="point.id">{{ point.name }}</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="enquiry_from">Enquiry From</label>
                                    <input disabled v-model="contract.enquiry_from" type="text" class="form-control input-sm" id="enquiry_from" name="enquiry_from">
                                </div>

                                <div class="form-group">
                                    <label>Packages</label>
                                    <div class="checkbox">
                                        <label>
                                            <input disabled v-model="contract.packages_captured" type="checkbox"> Packages captured at loading?
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="contract_head">Head of Contract</label>
                                    <input disabled v-model="contract.contract_head" type="text" class="form-control input-sm" id="contract_head" name="contract_head">
                                </div>


                                <div class="form-group">
                                    <label for="estimated_days">Estimated Period</label>
                                    <div class="input-group">
                                        <input disabled v-model="contract.estimated_days" min="0" type="number" class="form-control input-sm" id="estimated_days" name="estimated_days" describedby="estimated_days-addon" required>
                                        <span class="input-group-addon" id="estimated_days-addon">Days</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="lot_number">Lot Number</label>
                                    <input disabled v-model="contract.lot_number" type="text" class="form-control input-sm" id="lot_number" name="lot_number">
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Unloading Point</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="point in contract.unloading_points">
                                        <td>{{ point.name }}</td>
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
                                    <input disabled v-model="contract.shipping_line" type="text" class="form-control input-sm" id="shipping_line" name="shipping_line">
                                </div>

                                <div class="form-group">
                                    <label for="berth_no">Berth No</label>
                                    <input disabled v-model="contract.berth_no" type="text" class="form-control input-sm" id="berth_no" name="berth_no">
                                </div>
                            </div>

                            <div class="col-sm-4">

                                <div class="form-group">
                                    <label for="vessel_name">Vessel Name</label>
                                    <input disabled v-model="contract.vessel_name" type="text" class="form-control input-sm" id="vessel_name" name="vessel_name" required>
                                </div>

                                <div class="form-group">
                                    <label for="berthing_date">Berthing Date</label>
                                    <input disabled v-model="contract.berthing_date" type="text" class="datepicker form-control input-sm" id="berthing_date" name="berthing_date" required>
                                </div>

                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="no_of_shifts">No of Shifts</label>
                                    <input disabled number v-model="contract.no_of_shifts" type="number" min="1" class="form-control input-sm" id="no_of_shifts" name="no_of_shifts" required>
                                </div>

                                <div class="form-group">
                                    <label for="vessel_arrival_date">Vessel Arrival Date</label>
                                    <input disabled v-model="contract.vessel_arrival_date" type="text" class="datepicker form-control input-sm" id="vessel_arrival_date" name="vessel_arrival_date" required>
                                </div>

                            </div>
                        </div>

                        <h4><strong>Shifts Details</strong></h4>

                        <hr>

                        <div class="row">
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
                                        <td></td>
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
                                    <select disabled v-model="contract.rate" name="rate" id="rate" class="form-control input-sm select2" required>
                                        <option value="Per Hour">Per Hour</option>
                                        <option value="Per KM">Per KM</option>
                                        <option value="Per Tonne">Per Tonne</option>
                                    </select>
                                </div>


                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="amount">Price {{ contract.rate }}</label>
                                    <input disabled v-model="contract.amount" type="number" class="form-control input-sm" id="amount" name="amount" required>
                                </div>
                            </div>


                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="stock_item_id">Billable Item</label>
                                    <select disabled v-model="contract.stock_item_id" name="stock_item_id" id="stock_item_id" class="form-control input-sm select2" required>
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
                                            <input disabled v-model="contract.subcontracted" type="checkbox">
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
                                    <input disabled v-model="contract.sub_company_name" type="text" class="form-control input-sm" id="sub_company_name" name="sub_company_name">
                                </div>
                                <div class="form-group">
                                    <label for="sub_address_1">Address Line 1</label>
                                    <input disabled v-model="contract.sub_address_1" type="text" class="form-control input-sm" id="sub_address_1" name="sub_address_1">
                                </div>

                                <div class="form-group">
                                    <label for="sub_address_2">Address Line 2</label>
                                    <input disabled v-model="contract.sub_address_2" type="text" class="form-control input-sm" id="sub_address_2" name="sub_address_2">
                                </div>

                                <div class="form-group">
                                    <label for="sub_address_3">Address Line 3</label>
                                    <input disabled v-model="contract.sub_address_3" type="text" class="form-control input-sm" id="sub_address_3" name="sub_address_3">
                                </div>

                                <div class="form-group">
                                    <label for="sub_address_4">Address Line 4</label>
                                    <input disabled v-model="contract.sub_address_4" type="text" class="form-control input-sm" id="sub_address_4" name="sub_address_4">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="sub_delivery_to">Delivery To</label>
                                    <input disabled v-model="contract.sub_delivery_to" type="text" class="form-control input-sm" id="sub_delivery_to" name="sub_delivery_to">
                                </div>
                                <div class="form-group">
                                    <label for="sub_delivery_address">Delivery Address</label>
                                    <input disabled v-model="contract.sub_delivery_address" type="text" class="form-control input-sm" id="sub_delivery_address" name="sub_delivery_address">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <show-udfs module="Contracts" :state="contract" :uploads="uploads" cols="col-sm-4"></show-udfs>
                        </div>

                        <hr>

                        <div class="form-group">
                            <router-link to="/contracts" class="btn btn-danger">Back</router-link>
                        </div>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="journeys">
                    <div class="table-responsive">
                        <table class="table no-wrap">
                            <thead>
                            <tr>
                                <th>Journey #</th>
                                <th>Status</th>
                                <th>Truck</th>
                                <th>Contract</th>
                                <th>Job Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="journey in journeys">
                                <td><router-link :to="'/journey/' + journey.id">JRNY-{{ journey.id }}</router-link></td>
                                <td>
                                    <span class="label label-info" v-if="journey.status == 'Pending Approval'">Pending Approval</span>
                                    <span class="label label-success" v-if="journey.status == 'Approved'">Approved</span>
                                    <span class="label label-default" v-if="journey.status == 'Closed'">Closed</span>

                                </td>
                                <td>{{ journey.truck.plate_number }}</td>
                                <td>CTR-{{ journey.contract_id }}</td>
                                <td>{{ date2(journey.job_date) }}</td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Journey #</th>
                                <th>Status</th>
                                <th>Truck</th>
                                <th>Contract</th>
                                <th>Job Date</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="deliveries">
                    <table class="table no-wrap">
                        <thead>
                        <tr>
                            <th>Del. Note #</th>
                            <th>Journey #</th>
                            <th>Loading NW</th>
                            <th>Loading Time</th>
                            <th>Offloading NW</th>
                            <th>Offloading Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="delivery in deliveries">
                            <td><router-link :to="'/delivery/' + delivery.id">RKS-{{ delivery.id }}</router-link></td>
                            <td><router-link :to="'/journey/' + delivery.journey_id">JRNY-{{ delivery.journey_id }}</router-link></td>
                            <td class="text-right">{{ formatNumber(delivery.loading_net_weight) }}</td>
                            <td class="text-right">{{ new Date(delivery.loading_time).toLocaleString('en-GB') }}</td>
                            <td class="text-right">{{ formatNumber(delivery.offloading_net_weight) }}</td>
                            <td class="text-right">{{ delivery.offloading_time ? new Date(delivery.offloading_time).toLocaleString('en-GB') : 'Pending' }}</td>
                        </tr>
                        </tbody>

                        <tfoot>
                        <tr>
                            <th>Del. Note #</th>
                            <th>Journey #</th>
                            <th>Loading NW</th>
                            <th>Loading Time</th>
                            <th>Offloading NW</th>
                            <th>Offloading Time</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            status: null,
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
            },
            journeys: [],
            deliveries: [],
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

    created () {
        this.$root.isLoading = true;
        http.get('/api/contract/' + this.$route.params.id).then((response) => {
            this.clients = response.clients;
            this.stockItems = response.stockItems;
            this.routes = response.routes;
            this.classifications = response.cargo_classifications;
            this.cargo_types = response.cargo_types;
            this.carriage_points = response.carriage_points;
            this.status = response.contract.status;
            this.contract = response.contract.raw;
            this.journeys = response.contract.journeys;
            this.deliveries = response.contract.deliveries;
            this.updateBooleans();
            setTimeout(() => {
                prepareTable();
            }, 500);
            this.$root.isLoading = false;
        });
    },

    methods: {
        updateBooleans() {
            let keys = [
                'capture_loading_weights', 'capture_offloading_weights', 'packages_captured', 'ls_loading_weights',
                'ls_offloading_weights', 'lh_offloading_weights', 'lh_loading_weights', 'subcontracted', 'ignore_delivery_note'
            ];

            keys.forEach(item => this.setBoolState(item));
        },

        setBoolState(key) {
            this.contract[key] = this.contract[key] == 'true';
        },

        approveContract() {
            this.processContract('approve');
        },

        closeContract() {
            this.processContract('close');
        },

        reopenContract() {
            this.processContract('reopen');
        },

        date2(value) {
            return window._date2(value);
        },

        processContract(endpoint) {
            this.$root.isLoading = true;
            http.post('/api/contract/' + this.$route.params.id + '/' + endpoint, {})
                .then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/contracts' });
                }).catch((error) => {
                this.$root.isLoading = false;
                alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
            });
        },

        formatNumber(number) {
            if (! number) {
                return '';
            }

            return parseFloat(number).toLocaleString();
        },
    },
}
</script>
