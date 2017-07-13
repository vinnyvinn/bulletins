<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Journeys In Progress</strong>

                        <div class="pull-right">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="month">Month</label>
                                </div>
                                <div class="col-sm-9">
                                    <select @change="fetchMonthData" v-model="month" name="month" id="month" class="form-control input-sm">
                                        <option v-for="item in months" :value="item">{{ item }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table nowrap datatable">
                                <thead>
                                <tr>
                                    <th>Journey #</th>
                                    <th>Contract Related</th>
                                    <th>Journey Type</th>
                                    <th>Job Date</th>
                                    <th>Ref. No.</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="journey in open_journeys">
                                    <td><router-link :to="'/journey/' + journey.id">JRNY-{{ journey.id }}</router-link></td>
                                    <td>{{ journey.is_contract_related ? 'Yes' : 'No' }}</td>
                                    <td>{{ journey.journey_type }}</td>
                                    <td>{{ date2(journey.job_date) }}</td>
                                    <td>{{ journey.ref_no }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Pending Fuel for journeys created.</strong>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table nowrap datatable">
                                <thead>
                                <tr>
                                    <th>Journey #</th>
                                    <th>Contract Related</th>
                                    <th>Journey Type</th>
                                    <th>Job Date</th>
                                    <th>Ref. No.</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="journey in not_fueled">
                                    <td><router-link :to="'/journey/' + journey.id">JRNY-{{ journey.id }}</router-link></td>
                                    <td>{{ journey.is_contract_related ? 'Yes' : 'No' }}</td>
                                    <td>{{ journey.journey_type }}</td>
                                    <td>{{ date2(journey.job_date) }}</td>
                                    <td>{{ journey.ref_no }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Journeys created &amp; pending loadings.</strong>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table nowrap datatable">
                                <thead>
                                <tr>
                                    <th>Journey #</th>
                                    <th>Contract Related</th>
                                    <th>Journey Type</th>
                                    <th>Job Date</th>
                                    <th>Ref. No.</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="journey in not_loaded">
                                    <td><router-link :to="'/journey/' + journey.id">JRNY-{{ journey.id }}</router-link></td>
                                    <td>{{ journey.is_contract_related ? 'Yes' : 'No' }}</td>
                                    <td>{{ journey.journey_type }}</td>
                                    <td>{{ date2(journey.job_date) }}</td>
                                    <td>{{ journey.ref_no }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Pending Mileage for journeys created.</strong>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table nowrap datatable">
                                <thead>
                                <tr>
                                    <th>Journey #</th>
                                    <th>Contract Related</th>
                                    <th>Journey Type</th>
                                    <th>Job Date</th>
                                    <th>Ref. No.</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="journey in not_paid">
                                    <td><router-link :to="'/journey/' + journey.id">JRNY-{{ journey.id }}</router-link></td>
                                    <td>{{ journey.is_contract_related ? 'Yes' : 'No' }}</td>
                                    <td>{{ journey.journey_type }}</td>
                                    <td>{{ date2(journey.job_date) }}</td>
                                    <td>{{ journey.ref_no }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            this.$root.isLoading = true;
            http.get('/api/dashboard?s=' + window.Laravel.station_id).then(response => {
                this.mapFetchedData(response);
            });
        },

        data() {
            return {
                open_journeys: [],
                not_loaded: [],
                not_fueled: [],
                not_paid: [],
                loading: [],
                offloading: [],
                delivery_notes: [],
                months: [],
                month: '',
            }
        },

        methods: {
            date2(value) {
                return window._date2(value);
            },
            getMonth() {
                let date = new Date();

                return (parseInt(date.getMonth()) + 1).toString() + '-' + date.getYear();
            },

            fetchMonthData() {
                this.$root.isLoading = true;
                http.get('/api/dashboard?month=' + this.month).then(response => {
                    this.mapFetchedData(response);
                });
            },

            mapFetchedData(response) {
//                $('.datatable').dataTable().fnDestroy();
                this.open_journeys = response.open_journeys;
                this.not_fueled = response.not_fueled;
                this.not_loaded = response.not_loaded;
                this.not_paid = response.not_paid;
                this.months = response.months;
                setTimeout(() => {
//                    $('.datatable').dataTable();
                    this.$root.isLoading = false;
                }, 1000);
            }
        }
    }
</script>
