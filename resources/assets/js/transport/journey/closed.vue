<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Closed Journeys</strong>
                        <router-link v-if="$root.can('create-journey')" to="/journey/create" class="btn btn-primary btn-xs pull-right">
                            <i class="fa fa-plus"></i> Add New
                        </router-link>
                        <router-link to="/journey" class="btn btn-danger btn-xs pull-right">
                            <i class="fa fa-plus"></i> Open Journeys
                        </router-link>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Journey #</th>
                                        <th>Status</th>
                                        <th>Truck</th>
                                        <th>Contract</th>
                                        <th>Job Date</th>
                                        <th>Driver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="journey in journeys">
                                        <td>{{ journey.id }}</td>
                                        <td>
                                            <router-link v-if="$root.can('view-journey')" :to="'/journey/' + journey.id">JRNY-{{ journey.id }}</router-link>
                                            <span v-else>JRNY-{{ journey.id }}</span>
                                        </td>
                                        <td>
                                            <span class="label label-info" v-if="journey.status == 'Pending Approval'">Pending Approval</span>
                                            <span class="label label-success" v-if="journey.status == 'Approved'">Approved</span>
                                            <span class="label label-default" v-if="journey.status == 'Closed'">Closed</span>
                                        </td>
                                        <td>{{ journey.truck.plate_number }}</td>
                                        <td>{{ journey.contract.name }} {{ journey.contract.client.Name }}</td>
                                        <td>{{ date2(journey.job_date) }}</td>
                                        <td>{{ journey.driver ? journey.driver.first_name : 'No' }} {{ journey.driver ? journey.driver.last_name : 'Driver' }}</td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Journey #</th>
                                        <th>Status</th>
                                        <th>Truck</th>
                                        <th>Contract</th>
                                        <th>Job Date</th>
                                        <th>Driver</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
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
            http.get('/api/journey?status=Closed&s=' + window.Laravel.station_id).then(response => {
                this.journeys = response.journeys;
                this.setupConfirm();
                prepareTable(false, [], {
                    "columnDefs": [
                        {
                            "targets": [0],
                            "visible": false,
                            "searchable": false
                        },
                    ],
                    "order": [[0, "desc"]],
                    "ordering": false,
                });
                this.$root.isLoading = false;
            });
        },

        data() {
            return {
                journeys: [],

            };
        },

        methods: {
            setupConfirm() {
                $('.btn-destroy').off();
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
            },
            date2(value) {
                return window._date2(value);
            },

            edit(journey) {
                window._router.push({ path: '/journey/' + journey.id + '/edit' })
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/journey/' + id + '/?s=' + window.Laravel.station_id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.journeys = response.journeys;
                    prepareTable();
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
