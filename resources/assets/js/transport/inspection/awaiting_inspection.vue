<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Truck Awaiting Inspection</strong>

                        <router-link v-if="$root.can('view-inspection')" to="/inspected" class="pull-right">
                          <button type="button" name="button" class="btn btn-success btn-sm">INSPECTED TRUCKS</button>
                        </router-link>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table nowrap">
                                <thead>
                                <tr>
                                    <th>Truck #</th>
                                    <th>Driver</th>
                                    <th>Journey #</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="journey in journeys">
                                    <td>{{ journey.truck.plate_number }}</td>
                                    <td>{{ journey.driver.first_name }} {{ journey.driver.last_name }}</td>
                                    <td>JRNY-{{ journey.id }}</td>
                                    <td>
                                        <router-link v-if="$root.can('create-inspection')" :to="'inspection/create/'+ journey.id">
                                          <button type="button" name="button" class="btn btn-success btn-sm">INSPECT</button>
                                        </router-link>
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>Truck #</th>
                                  <th>Driver</th>
                                  <th>Journey #</th>
                                  <th>Action</th>
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
            http.get('/api/inspection/create?s=' + window.Laravel.station_id).then(response => {
                this.journeys = response.journeys;
                this.setupConfirm();
                prepareTable();
                this.$root.isLoading = false;
            });
        },

        data() {
            return {
                journeys: [],
                journeys_awaiting_inspection: []
            };
        },

        methods: {
            filterJourneys () {
                this.journeys_awaiting_inspection = this.journeys.filter( function(journey) {
                return journey.status == 'Approved';
              });
            },
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
                window._router.push({path: '/inspection/' + journey.id + '/edit'})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/inspection/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.contracts = response.contracts;
                    prepareTable();
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
