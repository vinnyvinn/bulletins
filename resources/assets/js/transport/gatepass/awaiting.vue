<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Truck Awaiting Gate Pass</strong>

                        <router-link v-if="$root.can('view-gatepass')" to="/gatepass/completed" class="pull-right">
                          <button type="button" name="button" class="btn btn-success btn-sm">PROCESSED GATE PASSES</button>
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
                                        <router-link :to="'gatepass/create/'+ journey.id" v-if="$root.can('create-gatepass')">
                                          <button type="button" name="button" class="btn btn-success btn-sm">PROCESS</button>
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
            http.get('/api/gatepass/awaiting/?s=' + window.Laravel.station_id).then(response => {
                this.journeys = response.journeys;
                this.setupConfirm();
                prepareTable();
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
        }
    }
</script>
