<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Trucks Awaiting Fueling </i></strong>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>Plate #</th>
                                    <th>Trailer</th>
                                    <th>Driver</th>
                                    <th>Current Ltrs</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="truck in vehicles">
                                    <td>{{ truck.plate_number }}</td>
                                    <td v-if="truck.trailer">{{ truck.trailer.trailer_number }}</td>
                                    <td v-if="!truck.trailer"> -- </td>
                                    <td v-if="truck.driver">{{ truck.driver.first_name }}</td>
                                    <td v-if="!truck.driver">--</td>
                                    <td>{{ truck.current_fuel}}</td>
                                    <td><button type="button" @click="fuel(truck)" name="button" class="btn btn-sm btn-success">Fuel</button></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>Plate #</th>
                                  <th>Trailer</th>
                                  <th>Driver</th>
                                  <th>Current Ltrs</th>
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
        data() {
            return {
                vehicles: [],
                contract: {
                  client: {}
                },
            };
        },

        created() {
          http.get('/api/lsfuel').then( response => {
            this.vehicles = response.vehicles;
          });
        },

        methods: {

            fuel (truck) {
              this.$router.push('/ls/fuel/create/' + truck.id + '/' + truck.contract_id);
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



            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/journey/' + id + '/?s=' + window.Laravel.station_id).then(response => {
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
