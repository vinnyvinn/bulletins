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
                                <h4 class="text-center">Loading Report</h4>
                                <h5 class="text-center">From: {{ formatDateTime(report.start_date, true) }} To: {{ formatDateTime(report.end_date, true) }}</h5>
                                <hr>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th v-if="!is_grouped">Contract</th>
                                            <th v-if="!is_summary">Plate Number</th>
                                            <th :class="is_summary ? 'text-right' : ''">{{ is_summary ? 'Total Trips' : 'Loading Time' }}</th>
                                            <th class="text-right">Total Bags</th>
                                            <th class="text-right">Gross Weight</th>
                                            <th class="text-right">Tare Weight</th>
                                            <th class="text-right">Net Weight</th>
                                            <th class="text-right" v-if="!is_summary">Weighbridge Number</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="is_grouped">
                                        <template v-for="(group, index) in deliveries">
                                            <tr class="rowHead">
                                                <td :colspan="is_summary ? 7 : 2">
                                                    <strong>{{ index }} Totals</strong>
                                                </td>
                                                <td v-if="! is_summary" class="text-right">{{ is_summary ? '' : getSum(group, 'bags_loaded') }}</td>
                                                <td v-if="! is_summary" class="text-right">{{ getSum(group, 'loading_gross_weight') }}</td>
                                                <td v-if="! is_summary" class="text-right">{{ getSum(group, 'loading_tare_weight') }}</td>
                                                <td v-if="! is_summary" class="text-right">{{ getSum(group, 'loading_net_weight') }}</td>
                                                <td v-if="! is_summary" class="text-right"></td>
                                            </tr>
                                            <tr v-for="delivery in group">
                                                <td :class="is_summary ? 'text-right' : ''">{{ is_summary ? delivery.total : formatDateTime(delivery.loading_time) }}</td>
                                                <td v-if="!is_summary">{{ delivery.plate_number }}</td>
                                                <td class="text-right">{{ formatNumber(delivery.bags_loaded) }}</td>
                                                <td class="text-right">{{ formatNumber(delivery.loading_gross_weight) }}</td>
                                                <td class="text-right">{{ formatNumber(delivery.loading_tare_weight) }}</td>
                                                <td class="text-right">{{ formatNumber(delivery.loading_net_weight) }}</td>
                                                <td class="text-right" v-if="!is_summary">{{ delivery.loading_weighbridge_number }}</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tbody v-else>
                                        <tr v-for="delivery in deliveries">
                                            <td>{{ delivery.name }}</td>
                                            <td v-if="!is_summary">{{ delivery.plate_number }}</td>
                                            <td :class="is_summary ? 'text-right' : ''">{{ is_summary ? delivery.total : formatDateTime(delivery.loading_time) }}</td>
                                            <td class="text-right">{{ formatNumber(delivery.bags_loaded) }}</td>
                                            <td class="text-right">{{ formatNumber(delivery.loading_gross_weight) }}</td>
                                            <td class="text-right">{{ formatNumber(delivery.loading_tare_weight) }}</td>
                                            <td class="text-right">{{ formatNumber(delivery.loading_net_weight) }}</td>
                                            <td class="text-right" v-if="!is_summary">{{ delivery.loading_weighbridge_number }}</td>
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
                    start_date: null,
                    end_date: null,
                    group_contract: false,
                    summary: false,
                },
                is_grouped: false,
                is_summary: false,
                deliveries: [],
            };
        },
        created() {
            this.report.start_date = moment().format('YYYY-MM-DD');
            this.report.end_date = moment().format('YYYY-MM-DD');
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
                http.post('/api/reports/loading', this.report).then(response => {
                    this.is_grouped = this.report.group_contract;
                    this.is_summary = this.report.summary;
                    this.deliveries = response.deliveries;

                    return response;
                })
                    .then(() => this.$root.isLoading = false)
                    .catch(() => this.$root.isLoading = false);
            },

            formatDateTime(dateTime, noTime = false) {
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

            getSum(records, entry) {
                if (records.length < 2) return records.length[0][entry];

                return records.map(a => a[entry]).reduce((a, b) => {
                    a = parseFloat(a);
                    b = parseFloat(b);
                    a = isNaN(a) ? 0 : a;
                    b = isNaN(b) ? 0 : b;

                    return a + b;
                }).toLocaleString();
            }
        }
    }
</script>
