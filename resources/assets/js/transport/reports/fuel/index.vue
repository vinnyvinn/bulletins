<template>
    <div>
        <div class="visible-print-block" id="printout"></div>
        <div class="container-fluid hidden-print">
            <div class="row">
                <div class="col-sm-3">
                    <div class="panel panel-success">
                        <div class="panel-heading">Settings</div>
                        <div class="panel-body">
                            <form @submit.prevent="generateReport">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input required type="text" v-model="report.start_date" id="start_date" name="start_date" class="input-sm form-control datepicker">
                                </div>
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input required type="text" v-model="report.end_date" id="end_date" name="end_date" class="input-sm form-control datepicker">
                                </div>
                                <div class="form-group">
                                    <label for="contract_id">Contract</label>
                                    <select v-model="report.contract_id" id="contract_id" name="contract_id" class="input-sm form-control">
                                        <option :value="null">All Contracts</option>
                                        <option v-for="contract in contracts" :key="contract.id" :value="contract.id">{{ contract.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="station_id">Station</label>
                                    <select v-model="report.station_id" id="station_id" name="station_id" class="input-sm form-control">
                                        <option :value="null">All Stations</option>
                                        <option v-for="station in stations" :key="station.id" :value="station.id">{{ station.name }}</option>
                                    </select>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input v-model="report.group_contract" type="checkbox"> Group by contract
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input v-model="report.summary" type="checkbox"> Display as summary
                                    </label>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-success btn-block">Generate</button>
                                </div>
                            </form>

                            <div v-if="(trucks.length || clients.lenght) && !is_summary">
                                <h4>Filters</h4>
                                <div class="form-group" v-if="trucks.length">
                                    <label for="station_id">Truck</label>
                                    <select v-model="selected_truck" class="input-sm form-control">
                                        <option :value="null">All Trucks</option>
                                        <option v-for="(truck, index) in trucks" :key="truck" :value="truck">{{ truck }}</option>
                                    </select>
                                </div>

                                <div class="form-group" v-if="clients.length">
                                    <label for="station_id">Client</label>
                                    <select v-model="selected_client" class="input-sm form-control">
                                        <option :value="null">All Clients</option>
                                        <option v-for="(client, index) in clients" :key="client" :value="client">{{ client }}</option>
                                    </select>
                                </div>

                                <div class="form-group" v-if="drivers.length">
                                    <label for="station_id">Driver</label>
                                    <select v-model="selected_driver" class="input-sm form-control">
                                        <option :value="null">All Drivers</option>
                                        <option v-for="(driver, index) in drivers" :key="driver" :value="driver">{{ driver }}</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Report
                            <button class="btn btn-xs btn-primary pull-right" @click.prevent="printReport">Print</button>
                        </div>
                        <div class="panel-body">
                            <div id="reportBody">
                                <h4 class="text-center">Fuel Issue Report</h4>
                                <h5 class="text-center">From: {{ formatDateTime(report.start_date, true) }} To: {{ formatDateTime(report.end_date, true) }}</h5>
                                <h5 class="text-center" v-if="selected_truck">
                                    <strong>Vehicle:</strong> {{ selected_truck }} </h5>
                                <h5 class="text-center" v-if="selected_client">
                                    <strong>Client:</strong> {{ selected_client }} </h5>
                                <h5 class="text-center" v-if="selected_driver">
                                    <strong>Driver:</strong> {{ selected_driver }} </h5>
                                <hr>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-right">Delivery number</th>
                                            <th v-if="!is_grouped">Contract</th>
                                            <th v-if="!is_summary">Plate Number</th>
                                            <th :class="is_summary ? 'text-right' : ''">{{ is_summary ? 'Fuel Vouchers' : 'Fueling Date' }}</th>
                                            <th class="text-right">Before Fueling</th>
                                            <th class="text-right">Reserve Topup</th>
                                            <th class="text-right">Top Up</th>
                                            <th class="text-right">Requested</th>
                                            <th class="text-right">Issued</th>
                                            <th class="text-right">After Fueling</th>
                                            <th class="text-right">Station</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="is_grouped">
                                        <template v-for="(group, index) in deliveries">
                                            <tr class="rowHead">
                                                <td :colspan="is_summary ? 8 : 3">
                                                    <strong>{{ index }} Totals</strong>
                                                </td>
                                                <td v-if="! is_summary" class="text-right">{{ getSum(group, 'current_fuel') }}</td>
                                                <td v-if="! is_summary" class="text-right">{{ (group.length * 25) - getSum(group, 'current_fuel', false) }}</td>
                                                <td v-if="! is_summary" class="text-right">{{ getSum(group, 'top_up_quantity') }}</td>
                                                <td v-if="! is_summary" class="text-right">{{ getSum(group, 'fuel_requested') }}</td>
                                                <td v-if="! is_summary" class="text-right">{{ getSum(group, 'fuel_issued') }}</td>
                                                <td v-if="! is_summary" class="text-right">{{ getSum(group, 'fuel_total') }}</td>
                                                <td v-if="! is_summary" class="text-right"></td>
                                            </tr>
                                            <tr v-for="delivery in group">
                                                <td>{{ delivery.index }}</td>
                                                <td :class="is_summary ? 'text-right' : ''">{{ is_summary ? delivery.total : formatDateTime(delivery.date) }}</td>
                                                <td v-if="!is_summary">{{ delivery.plate_number }}</td>
                                                <td class="text-right">{{ formatNumber(delivery.current_fuel) }}</td>
                                                <td class="text-right">{{ formatNumber(25 - parseFloat(delivery.current_fuel)) }}</td>
                                                <td class="text-right">{{ formatNumber(delivery.top_up_quantity) }}</td>
                                                <td class="text-right">{{ formatNumber(delivery.fuel_requested) }}</td>
                                                <td class="text-right">{{ formatNumber(delivery.fuel_issued) }}</td>
                                                <td class="text-right">{{ formatNumber(delivery.fuel_total) }}</td>
                                                <td class="text-right">{{ delivery.station_name }}</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tbody v-else>
                                        <tr v-for="delivery in deliveries">
                                            <td>{{ delivery.index }}</td>
                                            <td class="text-right">RKS-{{ delivery.journey_id}}</td>
                                            <td>{{ delivery.name }}</td>
                                            <td v-if="!is_summary">{{ delivery.plate_number }}</td>
                                            <td :class="is_summary ? 'text-right' : ''">{{ is_summary ? delivery.total : formatDateTime(delivery.date) }}</td>
                                            <td class="text-right">{{ formatNumber(delivery.current_fuel) }}</td>
                                            <td class="text-right">{{ formatNumber(25 - parseFloat(delivery.current_fuel)) }}</td>
                                            <td class="text-right">{{ formatNumber(delivery.top_up_quantity) }}</td>
                                            <td class="text-right">{{ formatNumber(delivery.fuel_requested) }}</td>
                                            <td class="text-right">{{ formatNumber(delivery.fuel_issued) }}</td>
                                            <td class="text-right">{{ formatNumber(delivery.fuel_total) }}</td>
                                            <td class="text-right">{{ delivery.station_name }}</td>

                                        </tr>
                                        <tr v-if="deliveries.length">
                                            <th :colspan="is_summary ? 3 : 4">
                                                <strong>Totals</strong>
                                            </th>
                                            <th class="text-right">{{ getSum(deliveries, 'current_fuel') }}</th>
                                            <td class="text-right">{{ ((deliveries.length * 25) - getSum(deliveries, 'current_fuel', false)).toLocaleString() }}</td>
                                            <th class="text-right">{{ getSum(deliveries, 'top_up_quantity') }}</th>
                                            <th class="text-right">{{ getSum(deliveries, 'fuel_requested') }}</th>
                                            <th class="text-right">{{ getSum(deliveries, 'fuel_issued') }}</th>
                                            <th class="text-right">{{ getSum(deliveries, 'fuel_total') }}</th>
                                            <th></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                report: {
                    contract_id: null,
                    station_id: null,
                    start_date: null,
                    end_date: null,
                    group_contract: false,
                    summary: false,
                },
                is_grouped: false,
                is_summary: false,
                all_deliveries: [],
                contracts: [],
                stations: [],
                selected_truck: null,
                selected_client: null,
                selected_driver: null,
            };
        },
        computed: {
            deliveries() {
                let deliveries = this.is_grouped ? Object.assign({}, this.all_deliveries) : this.all_deliveries.concat([]);

                if (!this.is_grouped) {
                    if (this.selected_truck) {
                        deliveries = deliveries.filter(del => this.selected_truck == del.plate_number);
                    }

                    if (this.selected_client) {
                        deliveries = deliveries.filter(del => this.selected_client == del.client_name);
                    }
                    if (this.selected_driver) {
                        deliveries = deliveries.filter(del => (
                            this.selected_driver == del.first_name + ' ' + del.last_name
                        ));
                    }

                    return this.mapFields(deliveries);
                }

                let keys = Object.keys(deliveries);

                for (let i = 0; i < keys.length; i++) {
                    if (this.selected_truck) {
                        deliveries[keys[i]] = deliveries[keys[i]].filter(del => this.selected_truck == del.plate_number);
                    }

                    if (this.selected_client) {
                        deliveries[keys[i]] = deliveries[keys[i]].filter(del => this.selected_client == del.client_name);
                    }

                    if (this.selected_driver) {
                        deliveries[keys[i]] = deliveries[keys[i]].filter(del => (
                            this.selected_driver == del.first_name + ' ' + del.last_name
                        ));
                    }

                    if (!deliveries[keys[i]].length) {
                        delete deliveries[keys[i]];
                    }
                }


                return this.mapFields(deliveries);
            },
            clients() {
                let clients = [];

                if (!this.is_grouped) {
                    this.all_deliveries.forEach(item => {
                        if (clients.indexOf(item.client_name) === -1) {
                            clients.push(item.client_name);
                        }
                    });
                } else {
                    let keys = Object.keys(this.all_deliveries);

                    for (let i = 0; i < keys.length; i++) {
                        this.all_deliveries[keys[i]].forEach(item => {
                            if (clients.indexOf(item.client_name) === -1) {
                                clients.push(item.client_name);
                            }
                        });
                    }
                }

                clients.sort();

                return clients;
            },
            trucks() {
                let trucks = [];

                if (!this.is_grouped) {
                    this.all_deliveries.forEach(item => {
                        if (trucks.indexOf(item.plate_number) === -1) {
                            trucks.push(item.plate_number);
                        }
                    });
                } else {
                    let keys = Object.keys(this.all_deliveries);

                    for (let i = 0; i < keys.length; i++) {
                        this.all_deliveries[keys[i]].forEach(item => {
                            if (trucks.indexOf(item.plate_number) === -1) {
                                trucks.push(item.plate_number);
                            }
                        });
                    }
                }

                trucks.sort();

                return trucks;
            },
            drivers() {
                let drivers = [];

                if (!this.is_grouped) {
                    this.all_deliveries.forEach(item => {
                        if (drivers.indexOf(item.first_name + ' ' + item.last_name) === -1) {
                            drivers.push(item.first_name + ' ' + item.last_name);
                        }
                    });
                } else {
                    let keys = Object.keys(this.all_deliveries);

                    for (let i = 0; i < keys.length; i++) {
                        this.all_deliveries[keys[i]].forEach(item => {
                            if (drivers.indexOf(item.first_name + ' ' + item.last_name) === -1) {
                                drivers.push(item.first_name + ' ' + item.last_name);
                            }
                        });
                    }
                }

                drivers.sort();

                return drivers;
            },
        },
        created() {
            this.$root.isLoading = true;
            this.report.start_date = moment().format('YYYY-MM-DD');
            this.report.end_date = moment().format('YYYY-MM-DD');
            http.get('/api/reports/init').then(response => {
                this.contracts = response.contracts;
                this.stations = response.stations;

                return response;
            })
                .catch(() => this.$root.isLoading = false)
                .then(() => this.$root.isLoading = false);
        },

        mounted() {
            $('#end_date').datepicker({
                endDate: '0d',
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayBtn: true
            }).on('change', (e) => this.report.end_date = e.target.value);

            $('#start_date').datepicker({
                endDate: '0d',
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayBtn: true
            }).on('change', (e) => {
                this.report.start_date = e.target.value;
                $('#end_date')
                    .datepicker('setDate', e.target.value)
                    .datepicker('setStartDate', e.target.value);
            });
        },
        methods: {
            generateReport() {
                this.$root.isLoading = true;
                if (this.$route.params.topup) {
                    this.report.topup = true;
                }
                http.post('/api/reports/fuel', this.report).then(response => {
                    this.selected_truck = null;
                    this.selected_client = null;
                    this.selected_driver = null;
                    this.is_grouped = this.report.group_contract;
                    this.is_summary = this.report.summary;
                    this.all_deliveries = response.deliveries;

                    return response;
                })
                    .then(() => this.$root.isLoading = false)
                    .catch(() => this.$root.isLoading = false);
            },

            mapFields(items) {
                let first = 1;

                if (!this.is_grouped) {
                    return items.map(item => {
                        item.index = first;
                        first++;

                        return item;
                    });
                }

                let keys = Object.keys(items);

                for (let i = 0; i < keys.length; i++) {
                    items[keys[i]] = items[keys[i]].map(item => {
                        item.index = first;
                        first++;

                        return item;
                    });
                }

                return items;
            },

            formatDateTime(dateTime, noTime = true) {
                return moment(dateTime).format(noTime ? "MMMM Do YYYY" : "MMMM Do YYYY, h:mm a");
            },

            formatNumber(number) {
                number = parseFloat(number);

                return isNaN(number) ? 0 : number.toLocaleString();
            },

            printReport() {
                $('#printout').html($('#reportBody').html());
                window.print();
            },

            getSum(records, entry, format = true) {
                if (records.length < 2) return records[0][entry];

                let formated = records.map(a => a[entry]).reduce((a, b) => {
                    a = parseFloat(a);
                    b = parseFloat(b);
                    a = isNaN(a) ? 0 : a;
                    b = isNaN(b) ? 0 : b;

                    return a + b;
                }, 0);

                if (!format) return formated;

                return formated.toLocaleString();
            }
        }
    }
</script>
